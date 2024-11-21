#!/usr/bin/env bash
echo "Deploying application..."
echo "version: 1.0.0"
echo "Github: AlienWolfX"
echo ""

echo "Running composer"
composer global require hirak/prestissimo
composer install --no-dev --working-dir=/var/www/html

echo "generating application key..."
php artisan key:generate --show

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

echo "Building vite"

npm install

npm run build

