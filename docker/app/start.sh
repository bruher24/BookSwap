#!/bin/bash

# Запуск PHP-FPM в фоне
php-fpm -D

# Запуск воркера очереди
php artisan queue:work redis --queue=job,app,listeners,cache --sleep=3 --tries=3 &

# Запуск сервера вебсокетов
php artisan reverb:start &

# Держим контейнер активным
tail -f /dev/null