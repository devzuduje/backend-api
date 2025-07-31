FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_pgsql
COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
RUN composer install --no-dev
EXPOSE 80
