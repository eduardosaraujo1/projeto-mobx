#!/usr/bin/env bash

if [ ! -f ./vendor/autoload.php ]; then
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
fi

./vendor/bin/sail up -d
./vendor/bin/sail npm install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm run dev
