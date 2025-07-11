#!/bin/bash

# Запуск PHP-FPM в фоне
php-fpm -D

# Запуск воркера очереди
php artisan queue:work redis --queue=job,app --sleep=3 --tries=3 &

# Держим контейнер активным
tail -f /dev/null