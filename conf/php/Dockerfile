FROM php:fpm-bullseye

# Install system dependencies
RUN apt-get update && apt-get install -y git zlib1g-dev libjpeg-dev libpng-dev libonig-dev libcurl4-openssl-dev libicu-dev libsqlite3-dev libzip-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql gd mbstring curl fileinfo iconv intl zip

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

EXPOSE 9000

#CMD ["php", "runner.php"]
#ENTRYPOINT ["tail", "-f", "/dev/null"]

