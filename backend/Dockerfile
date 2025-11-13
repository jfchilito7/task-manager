# Usamos PHP 8.2
FROM php:8.2-cli

# Instalamos dependencias del sistema y extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite

# Instalamos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecemos el directorio de trabajo
WORKDIR /var/www

# El comando por defecto al iniciar el contenedor
CMD bash -c "composer install && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"

# Exponemos el puerto 8000
EXPOSE 8000
