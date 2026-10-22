# syntax=docker/dockerfile:1

# Base: Red Hat UBI 8 php-74. Its php:7.4 is a maintained RHEL 8 module stream
# that still receives security backports; the Docker Hub php:7.4 images are
# end-of-life and frozen. Pinned by digest (Dependabot bumps it).
FROM registry.access.redhat.com/ubi8/php-74@sha256:9848ef69a0c5fcafa839a31e36cd19cde9db36f1a647dbea8df3a6226f291a25

# Laravel serves from public/; the base builds DocumentRoot as
# /opt/app-root/src${DOCUMENTROOT}. COMPOSER_ARGS is appended to the assemble
# step's composer install.
ENV DOCUMENTROOT=/public \
    COMPOSER_VERSION=2 \
    COMPOSER_ARGS="--no-dev"

COPY .docker/php-app.ini /etc/php.d/zz-app.ini

# s2i assemble installs Composer dependencies and sets group-0 permissions so
# the app can run under an arbitrary UID.
COPY --chown=1001:0 . /tmp/src
RUN /usr/libexec/s2i/assemble

# storage/ is a mounted volume at runtime; create the public symlink statically.
RUN ln -sfn ../storage/app/public /opt/app-root/src/public/storage

# The base's Apache listens on 8080 as a non-root user.
EXPOSE 8080
CMD ["/usr/libexec/s2i/run"]
