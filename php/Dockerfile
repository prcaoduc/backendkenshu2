FROM php:7.3-fpm

RUN apt-get update
RUN docker-php-ext-install pdo_mysql mysqli mbstring

WORKDIR /home/sites