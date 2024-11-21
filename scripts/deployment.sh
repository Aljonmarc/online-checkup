#!/usr/bin/env bash
echo "Deploying application..."
echo "version: 1.0.0"
echo "Github: AlienWolfX"
echo ""

echo "Caching config..."
php artisan config:cache

echo "Caching routes..."
php artisan route:cache

echo "Running migrations..."
php artisan migrate --force

# Start in dev
npm run dev

# Start Laravel server
php artisan serve --host=0.0.0.0 --port=8000
