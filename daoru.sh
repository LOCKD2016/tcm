#!/bin/sh
composer install --no-dev
composer dump-autoload
php artisan key:generate
php artisan passport:keys
php artisan migrate:refresh --force
php artisan db:seed --force