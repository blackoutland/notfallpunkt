FROM php:fpm-bullseye

# Install dependencies
RUN apt-get update && apt-get install -y git zlib1g-dev libjpeg-dev libpng-dev libonig-dev libcurl4-openssl-dev libicu-dev libsqlite3-dev libzip-dev

# Split into several separate runs so our container build doesn't abort on Raspi Zero because of RAM issues
#RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install gd
#RUN docker-php-ext-install mbstring
RUN docker-php-ext-install curl
#RUN docker-php-ext-install fileinfo
#RUN docker-php-ext-install iconv
RUN docker-php-ext-install intl
RUN docker-php-ext-install zip

RUN apt-get install -y unzip libz-dev libmemcached-dev

RUN pecl install memcached
RUN docker-php-ext-enable memcached

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

EXPOSE 9000

COPY docker-entrypoint.sh /entrypoint.sh
RUN chmod 744 /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]

CMD ["php-fpm"]
