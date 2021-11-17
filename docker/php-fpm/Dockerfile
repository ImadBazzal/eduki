FROM php:8.0-fpm

RUN docker-php-ext-install mysqli pdo pdo_mysql opcache

RUN pecl install xdebug apcu redis

RUN docker-php-ext-enable xdebug apcu redis

RUN apt-get -y update \
	&& apt-get install -y libicu-dev zip \
	&& docker-php-ext-configure intl \
	&& docker-php-ext-install intl

COPY --from=composer:2.1.9 /usr/bin/composer /usr/bin/composer

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /data/application