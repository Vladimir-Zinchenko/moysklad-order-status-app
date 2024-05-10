FROM php:7.4-apache

ENV APACHE_DOCUMENT_ROOT /app/public_html

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf
RUN sed -ri -e '/Options -Indexes/d' /etc/apache2/conf-available/docker-php.conf
RUN sed -ri -e 's!AllowOverride All!Require all granted!g' /etc/apache2/conf-available/docker-php.conf

RUN pecl install xdebug-3.1.5 \
    && apt update \
    && apt install libzip-dev mc -y \
    && docker-php-ext-enable xdebug \
    && a2enmod rewrite \
    && a2enmod headers \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*
