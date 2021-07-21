#!/bin/zsh

git submodule update --init --recursive
cp laradock-env laradock/.env
cp createdb.sql laradock/mysql/docker-entrypoint-initdb.d/createdb.sql

cd laradock
docker-compose build --no-cache nginx workspace mysql
docker-compose up -d nginx mysql workspace
docker-compose exec workspace composer install
docker-compose exec workspace npm install
docker-compose exec workspace php artisan migrate
docker-compose exec workspace npm run watch
