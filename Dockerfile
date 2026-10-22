# syntax=docker/dockerfile:1

# Verseghy website backend - production image.
#
# Base: Red Hat UBI 8 "php-74" source-to-image (s2i) builder.
#
# Why not docker.io/php:7.4-fpm any more: that upstream tag is frozen at the
# November 2022 7.4.33 build and will never receive the PHP2Shell engine fix
# (CVE-2026-109618 / CVE-2026-109619). The php:7.4 module in UBI 8 is a Red Hat
# "Full Life" application stream that is still maintained, and this base ships
# php-fpm-7.4.33-8.module+el8.10.0+25117+30337cca (RHSA-2026:87080), which fixes
# both PHP2Shell CVEs plus ten other 2025/2026 CVEs.
#
# We deliberately do NOT apply the published interim php.ini mitigations
# (session.upload_progress.enabled=0, memory_limit=-1, reduced post_max_size).
# Those were stopgaps for hosts that could not patch the engine. This image runs
# a patched engine, so the fix stands on its own.
#
# The base bundles Apache httpd in front of php-fpm (over a unix socket), runs
# as an unprivileged user, and listens on 8080. Its s2i "assemble" script
# installs Composer dependencies; its "run" script templates php.ini from
# environment variables and execs httpd in the foreground.
#
# Pinned by digest for reproducibility; Dependabot's docker ecosystem bumps it
# as Red Hat rebuilds the tag on each advisory.
FROM registry.access.redhat.com/ubi8/php-74@sha256:9848ef69a0c5fcafa839a31e36cd19cde9db36f1a647dbea8df3a6226f291a25

# Laravel serves from public/. The base builds DocumentRoot as
# /opt/app-root/src${DOCUMENTROOT}; public/.htaccess supplies the front-
# controller rewrites and the base already sets AllowOverride All on that tree.
# COMPOSER_ARGS is appended to the assemble step's "composer install
# --no-interaction --no-ansi --optimize-autoloader".
ENV DOCUMENTROOT=/public \
    COMPOSER_VERSION=2 \
    COMPOSER_ARGS="--no-dev"

# Functional upload limits (see .docker/php-app.ini). Loaded last from the
# engine's ini scan dir. COPY writes as root into /etc/php.d regardless of the
# image's runtime user, so php can read it.
COPY .docker/php-app.ini /etc/php.d/zz-app.ini

# Hand the application source to the s2i assembler. It moves the tree into
# /opt/app-root/src, installs Composer dependencies (--no-dev), runs the
# package:discover script, and normalizes group permissions so the app runs
# under an arbitrary UID with GID 0.
COPY --chown=1001:0 . /tmp/src
RUN /usr/libexec/s2i/assemble

# public/storage -> storage/app/public. Created statically (no artisan boot
# needed at build time). At runtime storage/ is the mounted PVC that already
# holds app/public, framework/ and logs/.
RUN ln -sfn ../storage/app/public /opt/app-root/src/public/storage

# httpd in the base listens on 8080 as a non-root user.
EXPOSE 8080
CMD ["/usr/libexec/s2i/run"]
