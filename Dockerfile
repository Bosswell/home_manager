FROM php:7.4-apache

RUN apt-get update && apt-get install -y git libzip-dev unzip \
    && apt-get install --no-install-recommends -y tzdata \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo_mysql \
    && pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    && a2enmod rewrite headers

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY . /var/www/html

WORKDIR /var/www/html