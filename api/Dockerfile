FROM php:8.1-apache

RUN docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite headers

# Copy Apache VirtualHost config
COPY apache/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN echo "ServerName api.lab.local" >> /etc/apache2/apache2.conf

WORKDIR /var/www/api
