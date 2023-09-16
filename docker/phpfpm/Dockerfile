# -*- coding: utf-8 -*-
FROM php:8.2-fpm

RUN apt-get update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

WORKDIR /var/www/html
EXPOSE 9000