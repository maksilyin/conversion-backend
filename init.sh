#!/bin/bash

echo "Starting Docker containers..."
docker-compose up -d --build

echo "Copying .env file if it doesn't exist..."
docker-compose exec app bash -c "if [ ! -f .env ]; then cp .env.example .env; fi"

echo "Generating application key..."
docker-compose exec app php artisan key:generate

echo "Installing dependencies..."
docker-compose exec app composer clear-cache
docker-compose exec app composer install --no-dev --optimize-autoloader

echo "Waiting for MySQL to be ready..."
sleep 15

echo "Running migrations..."
docker-compose exec app php artisan migrate

echo "Setting permissions..."
docker-compose exec app bash -c "chmod -R gu+w storage && chmod -R guo+w storage"

echo "Clearing cache..."
docker-compose exec app php artisan cache:clear

echo "Linking storage..."
docker-compose exec app php artisan storage:link

echo "Create admin user..."
docker-compose exec app php artisan make:filament-user

echo "Done!"