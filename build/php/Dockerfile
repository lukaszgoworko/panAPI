FROM php:7.1.5-fpm-alpine

RUN apk update && apk add postgresql-dev \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql

WORKDIR /var/www/html
