FROM php:8.1-fpm-alpine

WORKDIR /var/www/html

COPY . .
ADD ./docker/php.ini /usr/local/etc/php/php.ini
# RUN chmod -R 777 .
RUN chown -R www-data:www-data /var/www
RUN curl -sS https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o /usr/local/bin/install-php-extensions && chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions pdo pdo_mysql common mysql xml xmlrpc curl gd imagick cli dev imap mbstring soap zip intl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
