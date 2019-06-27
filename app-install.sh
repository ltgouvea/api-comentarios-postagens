#!/bin/bash

cp .env.example .env
docker-compose exec app composer install
docker-compose exec app chmod -R 775 storage
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan migrate:fresh --seed
docker-compose exec app php artisan passport:install
