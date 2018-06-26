FROM php:7.2-apache

LABEL AUTHOR="Zoltan Szepesi"

COPY . /server
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN apt-get update && \
    apt-get install -y libxml2-dev && \
    docker-php-ext-install pdo_mysql

RUN chown -R www-data:www-data /server \
    && a2enmod rewrite

RUN cd /server && cp .env.production .env
RUN cd /server && php artisan key:generate
