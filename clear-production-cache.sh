#!/bin/bash

# Script to clear production cache
# Run this on the production server to fix the HTTP 500 error

echo "Clearing Laravel caches on production..."

# Clear view cache
php artisan view:clear
echo "✓ View cache cleared"

# Clear application cache
php artisan cache:clear
echo "✓ Application cache cleared"

# Clear config cache
php artisan config:clear
echo "✓ Config cache cleared"

# Clear route cache
php artisan route:clear
echo "✓ Route cache cleared"

# Optimize for production
php artisan config:cache
echo "✓ Config cached"

php artisan route:cache
echo "✓ Route cached"

php artisan view:cache
echo "✓ Views cached"

echo ""
echo "All caches cleared and re-cached successfully!"
echo "The HTTP 500 error on /realisasi POST should now be fixed."
