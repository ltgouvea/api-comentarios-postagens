#!/bin/bash

# PostgreSQL
# Create DB
docker-compose exec pgdb postgres psql --command="CREATE USER laravel-admin WITH PASSWORD '123456';"
docker-compose exec pgdb postgres psql --command="CREATE DATABASE laravel-api OWNER laravel-admin;"

#docker-compose exec pgdb postgres psql --dbname=laravel-api --command="CREATE EXTENSION adminpack; CREATE EXTENSION postgis; CREATE EXTENSION postgis_topology;"
