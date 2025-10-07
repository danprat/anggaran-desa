#!/bin/bash
set -e

echo "🚀 Starting Anggaran Desa Application..."

# Create required directories
mkdir -p /var/log/supervisor
mkdir -p /var/www/html/database

# Ensure database file exists
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "📦 Creating SQLite database..."
    touch /var/www/html/database/database.sqlite
    chown www-data:www-data /var/www/html/database/database.sqlite
    chmod 664 /var/www/html/database/database.sqlite
fi

# Ensure proper permissions
chown www-data:www-data /var/www/html/database/database.sqlite
chmod 664 /var/www/html/database/database.sqlite

# Run migrations if enabled
if [ "$APP_RUN_MIGRATIONS" = "true" ]; then
    echo "🔄 Running migrations..."
    php artisan migrate --force || echo "⚠️  Migration failed, continuing..."
fi

# Run seeders if enabled
if [ "$APP_RUN_SEEDERS" = "true" ]; then
    echo "🌱 Running seeders..."
    php artisan db:seed --force || echo "⚠️  Seeding failed, continuing..."
fi

# Cache configuration
echo "⚡ Caching configuration..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "✅ Application ready!"
echo "📍 Access at: $APP_URL"

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
