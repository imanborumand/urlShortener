FROM php:8.1-fpm

WORKDIR /var/www/server

USER root

RUN pecl install mongodb
RUN pecl install  -o -f  redis

RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    libpng-dev \
    libjpeg* \
    libfreetype6-dev \
    unzip \
    supervisor\
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd\
    && docker-php-ext-install pdo pdo_mysql mysqli zip\
    && docker-php-ext-install bcmath\
    && rm -rf /tmp/pear \
    && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini\
    && echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini


# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN mkdir -p /var/www/server/vendor && chown -R www:www /var/www/server

COPY ./app /var/www/server

# Set working directory
WORKDIR /var/www/server

RUN chown -R www:www /var/www/server


CMD php-fpm

