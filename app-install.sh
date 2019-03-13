#!/bin/bash

# Install all dependencies
docker-compose exec app composer install

# Grant user and group read, write and execute permissions on storage folder
docker-compose exec app chmod -R 775 storage

# Create Laravel app keys
docker-compose exec app php artisan key:generate

# Cache settings into files
docker-compose exec app php artisan config:cache

# Execute the projects first migrations
docker-compose exec app php artisan migrate
