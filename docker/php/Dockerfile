FROM php:7.3-fpm-alpine
WORKDIR /var/www/html

# Start command
COPY ./start.sh ./docker/php/start.sh
CMD ./docker/php/start.sh

# Requirements
RUN apk update
RUN apk add bash autoconf g++ make openssl postgresql-dev

# PHP OPCache Extension
RUN docker-php-ext-install opcache

# PHP bcmath Extension
RUN docker-php-ext-install bcmath

# PHP Postgres Extension
# RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_pgsql

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Config files
COPY php-fpm.conf /usr/local/etc/php-fpm.conf
COPY www.conf /usr/local/etc/php-fpm.d/www.conf