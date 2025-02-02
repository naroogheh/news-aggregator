#!/bin/bash

# شروع سرویس cron
service cron start

php-fpm


while true; do
    php /var/www/artisan schedule:run --no-interaction &
    sleep 60
done
