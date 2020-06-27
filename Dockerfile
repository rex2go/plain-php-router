FROM php:7.2-fpm
RUN docker-php-ext-install -j$(nproc) mysqli
RUN docker-php-ext-install -j$(nproc) pdo 
RUN docker-php-ext-install -j$(nproc) pdo_mysql

RUN apt update && apt install -y git && apt install -y unzip