FROM php:8.3-apache

# Install PHP's MySQLi extension so PHP can connect to MySQL.
RUN docker-php-ext-install mysqli

# Copy the application into Apache's document root.
COPY . /var/www/html/

# Apache serves the app on container port 80.
EXPOSE 80
