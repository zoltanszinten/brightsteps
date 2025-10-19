# PHP-FPM for Laravel 12
FROM php:8.3-fpm-alpine

# system deps
RUN apk add --no-cache \
      icu-dev libzip-dev oniguruma-dev zlib-dev curl git bash tzdata shadow \
      mariadb-client nano

# php extensions
RUN docker-php-ext-configure intl \
 && docker-php-ext-install -j$(nproc) intl pdo_mysql mbstring zip bcmath opcache pcntl

# Composer
ENV COMPOSER_HOME=/tmp/composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

# Speed up artisan by enabling opcache for CLI
RUN echo "opcache.enable_cli=1" > /usr/local/etc/php/conf.d/docker-oc-enable-cli.ini

# www-data owns the app so container writes storage/bootstrap/cache
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
