FROM composer:latest AS composer-builder

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --ignore-platform-reqs

COPY . .

RUN composer dump-autoload --optimize

FROM php:8.4-fpm-alpine

ENV TZ="Asia/Jakarta"
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Install system dependencies
RUN apk add --no-cache \
    supervisor \
    nginx \
    curl \
    ca-certificates \
    zip \
    unzip \
    p7zip \
    gzip \
    git \
    sqlite \
    nano \
    wget \
    mysql-client \
    libpng-dev \
    bind-tools \
    librsvg-dev \
    ffmpeg \
    freetype-dev \
    libjpeg-turbo-dev \
    oniguruma-dev \
    icu-dev \
    libzip-dev \
    && docker-php-ext-configure gd \
    --with-freetype=/usr/include/ \
    --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) \
    bcmath \
    gd \
    intl \
    mbstring \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    xml \
    zip \
    exif \
    pcntl \
    && apk del icu-dev freetype-dev libjpeg-turbo-dev libpng-dev \
    && rm -rf /var/cache/apk/* /tmp/*

# Create application user (UID/GID 1000 to match host)
RUN addgroup -g 1000 -S appgroup && \
    adduser -u 1000 -S appuser -G appgroup

# Configure PHP-FPM to run as appuser
RUN sed -i 's/^user = www-data/user = appuser/' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/^group = www-data/group = appgroup/' /usr/local/etc/php-fpm.d/www.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY --chown=appuser:appgroup . .
COPY --from=composer-builder --chown=appuser:appgroup /app/vendor ./vendor

# Create necessary directories and set permissions
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R appuser:appgroup /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Copy configuration files
COPY ./docker/php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./docker/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy and set up entrypoint script
COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Create nginx directories and set permissions
RUN mkdir -p /var/log/nginx /var/lib/nginx/tmp /run/nginx \
    && chown -R appuser:appgroup /var/log/nginx /var/lib/nginx /run/nginx \
    && chmod -R 755 /var/log/nginx

# Laravel optimizations (will be overridden by mounted volumes in dev, but useful for production)
# Note: These will run with environment variables from docker-compose
RUN php artisan config:clear || true \
    && php artisan route:clear || true \
    && php artisan view:clear || true

# Expose port
EXPOSE 8080

# Use entrypoint to fix permissions before starting supervisor
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Start supervisor (runs as root, but nginx/php-fpm run as appuser via their configs)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
