#!/bin/sh

composer update
npm i
yarn

php artisan migrate
php artisan cache:clear
php artisan config:clear
php artisan key:generate
php artisan jwt:secret

chmod -R 775 storage/*
