#!/usr/bin/env bash

echo "Club Manager API - Initializing..."
echo "Club Manager API - Setting permission to folder storage/ and bootstrap/"

chmod -R 777 storage/
chmod -R 777 bootstrap/

# echo "Club Manager API - Running composer..."

# composer install

# echo "Club Manager API - Migrate & seed"

# php artisan migrate
# php artisan db:seed

echo "Club Manager API - Initialization finished"

tail -f /dev/null