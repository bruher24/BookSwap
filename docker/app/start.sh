#!/bin/bash

# Запуск Supervisor
exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
