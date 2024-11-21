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
