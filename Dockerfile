# Multi-platform Dockerfile untuk Laravel (AMD64/ARM64)
# Optimized untuk SQLite dan deployment di Portainer
FROM php:8.2-fpm-alpine

LABEL maintainer="Anggaran Desa"
LABEL description="Laravel Application with SQLite for ARM/AMD64"

# Set working directory
WORKDIR /var/www/html

# Install system dependencies dan PHP extensions
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    sqlite \
    sqlite-dev \
    supervisor \
    nginx \
    bash

# Install PHP extensions dengan urutan yang benar (dom sebelum xmlreader)
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    opcache \
    dom \
    xmlreader \
    xml \
    zip

# Install GD extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

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

# Create startup script
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
# Wait for database file to be ready\n\
if [ ! -f /var/www/html/database/database.sqlite ]; then\n\
    touch /var/www/html/database/database.sqlite\n\
    chown www-data:www-data /var/www/html/database/database.sqlite\n\
    chmod 664 /var/www/html/database/database.sqlite\n\
fi\n\
\n\
# Run migrations if APP_RUN_MIGRATIONS is true\n\
if [ "$APP_RUN_MIGRATIONS" = "true" ]; then\n\
    php artisan migrate --force\n\
fi\n\
\n\
# Run seeders if APP_RUN_SEEDERS is true\n\
if [ "$APP_RUN_SEEDERS" = "true" ]; then\n\
    php artisan db:seed --force\n\
fi\n\
\n\
# Clear and cache config\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Start supervisor\n\
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf\n\
' > /usr/local/bin/startup.sh \
    && chmod +x /usr/local/bin/startup.sh

# Health check script
RUN echo '#!/bin/bash\n\
SCRIPT_NAME="/php-fpm-healthcheck" \
SCRIPT_FILENAME="/php-fpm-healthcheck" \
REQUEST_METHOD=GET \
cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1\n\
' > /usr/local/bin/php-fpm-healthcheck \
    && chmod +x /usr/local/bin/php-fpm-healthcheck

# Expose port
EXPOSE 80

# Health check
HEALTHCHECK --interval=30s --timeout=10s --retries=3 --start-period=40s \
    CMD php artisan --version || exit 1

# Start supervisor
CMD ["/usr/local/bin/startup.sh"]
