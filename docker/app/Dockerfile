FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    apt-utils \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip \
    git \
    supervisor \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    pgsql \
    bcmath \
    gd \
    zip \
    intl \
    opcache \
    mbstring \
    exif \
    pcntl \
    xml

RUN pecl install redis && docker-php-ext-enable redis

# Конфигурация Supervisor
RUN mkdir -p /var/log/supervisor

# Основной конфиг Supervisor
RUN echo "[supervisord]" > /etc/supervisor/supervisord.conf && \
    echo "nodaemon=true" >> /etc/supervisor/supervisord.conf && \
    echo "logfile=/var/log/supervisor/supervisord.log" >> /etc/supervisor/supervisord.conf && \
    echo "pidfile=/var/run/supervisord.pid" >> /etc/supervisor/supervisord.conf && \
    echo "" >> /etc/supervisor/supervisord.conf && \
    echo "[include]" >> /etc/supervisor/supervisord.conf && \
    echo "files = /etc/supervisor/conf.d/*.conf" >> /etc/supervisor/supervisord.conf

# Конфигурация для PHP-FPM
RUN echo "[program:php-fpm]" > /etc/supervisor/conf.d/php-fpm.conf && \
    echo "command=/usr/local/sbin/php-fpm -F" >> /etc/supervisor/conf.d/php-fpm.conf && \
    echo "autostart=true" >> /etc/supervisor/conf.d/php-fpm.conf && \
    echo "autorestart=true" >> /etc/supervisor/conf.d/php-fpm.conf && \
    echo "stdout_logfile=/dev/stdout" >> /etc/supervisor/conf.d/php-fpm.conf && \
    echo "stdout_logfile_maxbytes=1024" >> /etc/supervisor/conf.d/php-fpm.conf && \
    echo "stderr_logfile=/dev/stderr" >> /etc/supervisor/conf.d/php-fpm.conf && \
    echo "stderr_logfile_maxbytes=1024" >> /etc/supervisor/conf.d/php-fpm.conf

# Конфигурация для воркера очереди
RUN echo "[program:laravel-worker]" > /etc/supervisor/conf.d/laravel-worker.conf && \
    echo "command=php /var/www/artisan queue:work redis --queue=job,app --sleep=3 --tries=3" >> /etc/supervisor/conf.d/laravel-worker.conf && \
    echo "autostart=true" >> /etc/supervisor/conf.d/laravel-worker.conf && \
    echo "autorestart=true" >> /etc/supervisor/conf.d/laravel-worker.conf && \
    echo "user=root" >> /etc/supervisor/conf.d/laravel-worker.conf && \
    echo "numprocs=3" >> /etc/supervisor/conf.d/laravel-worker.conf && \
    echo "redirect_stderr=true" >> /etc/supervisor/conf.d/laravel-worker.conf && \
    echo "stdout_logfile=/var/www/storage/logs/worker.log" >> /etc/supervisor/conf.d/laravel-worker.conf

COPY ./docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

WORKDIR /var/www

COPY ./docker/app/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh
CMD ["/usr/local/bin/start.sh"]
