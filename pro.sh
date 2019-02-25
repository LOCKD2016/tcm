#!/bin/sh
#部署优化项目
php artisan down
composer install --optimize-autoloader --no-dev
composer dump-autoload --optimize
php artisan clear-compiled
php artisan optimize
php artisan config:cache
#php artisan route:cache
php artisan up