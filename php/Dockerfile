FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    cron \
    supervisor \
    clamav \
    clamav-daemon \
    && docker-php-ext-install pdo pdo_mysql intl zip pcntl sockets \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN echo "upload_max_filesize=100M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=120M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit=2048M" > /usr/local/etc/php/conf.d/memory.ini

COPY crontab /etc/cron.d/laravel-cron
RUN chmod 0644 /etc/cron.d/laravel-cron && crontab /etc/cron.d/laravel-cron

COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

COPY supervisord.conf /etc/supervisor/conf.d/laravel-worker.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN mkdir -p /var/log/supervisor

CMD ["/entrypoint.sh"]