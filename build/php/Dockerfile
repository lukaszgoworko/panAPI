FROM php:7-fpm
RUN apt-get update && apt-get install -y \
  && apt-get install -y libpq-dev \
  && docker-php-ext-install pdo pdo_pgsql \
  && apt-get clean

WORKDIR /var/www/html
