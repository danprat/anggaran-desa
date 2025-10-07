# Multi-platform Dockerfile untuk Laravel (AMD64/ARM64)
# Optimized untuk SQLite dan deployment di Portainer
FROM php:8.2-fpm-alpine

LABEL maintainer="Anggaran Desa"
LABEL description="Laravel Application with SQLite for ARM/AMD64"

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    bash \
    zip \
    unzip \
    supervisor \
    nginx \
    sqlite \
    sqlite-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    oniguruma-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    icu-dev \
    autoconf \
    g++ \
    make

# Install PHP extensions - Basic extensions first
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath

# Install opcache separately
RUN docker-php-ext-install opcache

# Install XML extensions (dom dan xml saja, xmlreader included di xml)
RUN docker-php-ext-install dom && \
    docker-php-ext-install xml

# Install zip extension
RUN docker-php-ext-install zip

# Install GD extension with configuration
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY --chown=www-data:www-data . /var/www/html

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist \
    && composer dump-autoload --optimize

# Create and setup directories
RUN mkdir -p \
    storage/app/public \
    storage/framework/{sessions,views,cache} \
    storage/logs \
    bootstrap/cache \
    database \
    /var/log/supervisor \
    /var/log/nginx \
    && touch database/database.sqlite \
    && chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache \
    && chmod 664 database/database.sqlite

# Copy configuration files
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copy startup script
COPY docker/startup.sh /usr/local/bin/startup.sh
RUN chmod +x /usr/local/bin/startup.sh

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=10s --retries=3 --start-period=40s \
    CMD php artisan --version || exit 1

# Start supervisor
CMD ["/bin/bash", "/usr/local/bin/startup.sh"]
