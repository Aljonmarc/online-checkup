# Use an official PHP image as the base image for Laravel
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js (required for Vite)
RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs

# Set the working directory
WORKDIR /var/www/html/public

# Copy application files
COPY . .

# Ensure proper permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/public \
    && chmod -R 755 /var/www/html/public \
    && chmod -R 775 /var/www/html/public/storage /var/www/html/public/bootstrap/cache

# Install PHP dependencies using Composer
RUN composer install --optimize-autoloader --no-dev

# Install NPM dependencies
RUN npm ci

# Build the frontend assets for production
RUN npm run build

# Expose port for PHP server
EXPOSE 8000

# Expose port for Vite (for hot module reloading in development)
EXPOSE 5173

CMD ["php artisan migrate --force"]

# Use entrypoint script to decide the mode (production/dev)
ENTRYPOINT ["sh", "-c"]
CMD ["php artisan serve --host=0.0.0.0 --port=8000"]
