#!/bin/bash

# 🔧 Quick Fix: Build Vite Assets in Running Container
# Untuk VPS di 129.150.57.43:8075

echo "🚀 Starting Vite build in container..."

# Install Node.js dan npm
echo "📦 Installing Node.js and npm..."
ssh ubuntu@129.150.57.43 "docker exec anggaran-desa-app apk add --no-cache nodejs npm"

# Build Vite assets
echo "🔨 Building Vite assets (ini akan makan waktu 2-3 menit)..."
ssh ubuntu@129.150.57.43 "docker exec anggaran-desa-app npm ci"
ssh ubuntu@129.150.57.43 "docker exec anggaran-desa-app npm run build"

# Verify build directory
echo "✅ Verifying build files..."
ssh ubuntu@129.150.57.43 "docker exec anggaran-desa-app ls -la /var/www/html/public/build/"

# Fix permissions
echo "🔒 Fixing permissions..."
ssh ubuntu@129.150.57.43 "docker exec anggaran-desa-app chown -R www-data:www-data /var/www/html/public/build"

# Clear Laravel cache
echo "🧹 Clearing Laravel cache..."
ssh ubuntu@129.150.57.43 "docker exec anggaran-desa-app php artisan config:clear"
ssh ubuntu@129.150.57.43 "docker exec anggaran-desa-app php artisan cache:clear"
ssh ubuntu@129.150.57.43 "docker exec anggaran-desa-app php artisan view:clear"

# Restart container
echo "🔄 Restarting container..."
ssh ubuntu@129.150.57.43 "docker restart anggaran-desa-app"

echo ""
echo "✅ Done! Wait 10 seconds for container to restart..."
sleep 10

echo ""
echo "🌐 Testing application..."
curl -I http://129.150.57.43:8075

echo ""
echo "✅ Vite fix applied!"
echo "📍 Access: http://129.150.57.43:8075"
