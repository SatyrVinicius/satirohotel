FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev unzip git wget fontconfig libxrender1 libxext6 libx11-6 libxtst6 libxi6 libfreetype6 libfontconfig1 fonts-dejavu-core \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip \
    && (apt-get install -y wkhtmltopdf || true)

RUN a2enmod rewrite

COPY hotel/ /var/www/html/
WORKDIR /var/www/html/

EXPOSE 80
