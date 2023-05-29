# Dockerfile for Laravel application

# Use the official PHP image as base
FROM php:8.0-fpm

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Copy .env.example to .env
COPY .env.example .env
# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files
COPY . .

# Install dependencies using Composer
RUN composer install --no-interaction --no-scripts --no-suggest --prefer-dist

# Generate application key
RUN php artisan key:generate

# Set file permissions
RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

# Add custom entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
