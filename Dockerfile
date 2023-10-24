# Use an official PHP runtime as a parent image
FROM php:7.4-fpm

# Set the working directory to /app
WORKDIR /app

# Install any needed packages
RUN apt-get update && apt-get install -y \
    libpq-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
