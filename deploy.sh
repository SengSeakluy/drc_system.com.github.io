#!/bin/sh

echo "Deploying project on server staging"
ssh ubuntu@46.51.216.206 "cd /var/www/project_laravel_voyager23 && git pull origin main && sudo composer update && php artisan migrate && php artisan optimize:clear && php artisan config:clear"
echo "Application successfully deployed."
