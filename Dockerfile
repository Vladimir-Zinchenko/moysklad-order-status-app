FROM php:7.4-apache

ENV APACHE_DOCUMENT_ROOT "/app/public_html"
ENV PHP_IDE_CONFIG "serverName=app"

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf
RUN sed -i '/AllowOverride All/a Require all granted' /etc/apache2/conf-available/docker-php.conf

RUN pecl install xdebug-3.1.5 \
    && apt update \
    && apt install libzip-dev mc -y \
    && docker-php-ext-enable xdebug \
    && a2enmod rewrite \
    && a2enmod headers \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

RUN echo "session.save_path=/tmp" >> /usr/local/etc/php/conf.d/docker-session.ini
RUN echo "session.auto_start=On" >> /usr/local/etc/php/conf.d/docker-session.ini
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.start_with_request = yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_host=docker.for.mac.localhost" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
