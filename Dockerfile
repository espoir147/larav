FROM php:8.2-cli
RUN apt-get update -y && apt-get install -y libpq-dev libzip-dev unzip curl \
    && docker-php-ext-install pdo pdo_pgsql zip
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /app
COPY . .
RUN composer install --optimize-autoloader --no-dev --ignore-platform-req=ext-pdo_mysql
RUN chmod -R 777 storage bootstrap/cache
RUN php artisan storage:link
CMD php artisan config:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
