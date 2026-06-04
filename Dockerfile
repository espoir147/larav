FROM php:8.2-cli

# Installation des paquets système et extensions PHP pour PostgreSQL
RUN apt-get update -y && apt-get install -y libpq-dev libzip-dev unzip curl \
    && docker-php-ext-install pdo pdo_pgsql zip

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définition du dossier de travail
WORKDIR /app
COPY . .

# Installation des dépendances Laravel
RUN composer install --optimize-autoloader --no-dev

# Permissions nécessaires pour Laravel
RUN chmod -R 777 storage bootstrap/cache

# Lancement de l'application sur le port dynamique de Render
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
