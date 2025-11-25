FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev libicu-dev \
    && docker-php-ext-install pdo pdo_mysql intl

WORKDIR /var/www

# Copia o binário do composer de uma imagem oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Comando padrão do container (depois que o Laravel existir)
CMD php artisan serve --host=0.0.0.0 --port=8000
