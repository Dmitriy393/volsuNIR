
FROM php:8.4-fpm


RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql


COPY . /var/www/html


WORKDIR /var/www/html

EXPOSE 80
