FROM php:8.3-fpm

# Установка зависимостей
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

# Установка php-расширений
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

# Установка Redis
RUN pecl install redis && docker-php-ext-enable redis

# Установка Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

# Копирование php.ini
COPY ./docker/app/php.ini /usr/local/etc/php/conf.d/php.ini

# Создание директории для конфига Supervisor
RUN mkdir -p /var/log/supervisor && \
    chown -R www-data:www-data /var/log/supervisor

# Копирование конфигурации Supervisor
COPY ./docker/app/supervisord.conf /etc/supervisor/supervisord.conf
COPY ./docker/app/supervisor-programs.conf /etc/supervisor/conf.d/

# Настройка рабочей директории
WORKDIR /var/www

# Запуск Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]
