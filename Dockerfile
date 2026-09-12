FROM php:8.3-cli

RUN apt-get update \
    && apt-get install -y unzip \
    && docker-php-ext-install pdo_mysql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --no-interaction --prefer-dist

COPY . .

EXPOSE 10000
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000} -t public"]
