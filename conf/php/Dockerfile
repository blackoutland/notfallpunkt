FROM php:fpm-bullseye

# Install dependencies
RUN apt-get update && apt-get install -y git zlib1g-dev libjpeg-dev libpng-dev libonig-dev libcurl4-openssl-dev libicu-dev libsqlite3-dev libzip-dev

RUN docker-php-ext-install pdo_mysql gd mbstring curl fileinfo iconv intl zip

RUN apt-get install -y unzip libz-dev libmemcached-dev

RUN pecl install memcached && docker-php-ext-enable memcached

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

EXPOSE 9000

COPY docker-entrypoint.sh /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]

CMD ["php-fpm"]
