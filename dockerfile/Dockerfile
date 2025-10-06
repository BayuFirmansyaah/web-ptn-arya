FROM php:8.2-fpm-alpine

# Install dependencies
RUN apk add --no-cache \
    bash git curl libpng-dev libjpeg-turbo-dev libwebp-dev libxpm-dev libzip-dev oniguruma-dev zip unzip composer npm nodejs \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-xpm \
    && docker-php-ext-install gd pdo pdo_mysql mbstring zip bcmath sockets opcache

WORKDIR /var/www

# Copy composer files untuk caching layer
COPY ../web/composer.json ../web/composer.lock ./

RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Copy seluruh Laravel project
COPY ../web /var/www

# Set permission
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
