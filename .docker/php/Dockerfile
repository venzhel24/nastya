FROM php:8.3-fpm

# Установка необходимых библиотек и PHP-расширений
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev zip unzip git curl \
    libxml2-dev libcurl4-openssl-dev libonig-dev \
    libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev \
    libicu-dev libexif-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo pdo_pgsql zip mbstring curl soap dom opcache gd intl exif bcmath \
    && docker-php-ext-enable pdo_pgsql opcache bcmath

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Копирование файла конфигурации PHP
ADD ./php.ini /usr/local/etc/php/php.ini

# Настройка пользователя и группы
ARG USER_ID=1000
ARG GROUP_ID=1000

RUN usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data || true

# Создание рабочей директории
RUN mkdir -p /var/www/html && chown -R www-data:www-data /var/www/html

USER www-data

WORKDIR /var/www/html
