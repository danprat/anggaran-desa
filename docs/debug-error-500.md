# 🔍 Debugging Error 500 di Anggaran Desa

## ❌ Error yang Terlihat:
```
127.0.0.1 -  07/Oct/2025:09:31:56 +0000 "GET /index.php" 500
```

---

## 🔍 Step 1: Cek Laravel Logs

```bash
# Cek Laravel application logs
docker exec anggaran-desa-app cat storage/logs/laravel.log | tail -100

# Atau tail real-time
docker exec anggaran-desa-app tail -f storage/logs/laravel.log
```

---

## 🔍 Step 2: Cek PHP-FPM Logs

```bash
# Cek PHP errors
docker logs anggaran-desa-app 2>&1 | grep -i error | tail -50
```

---

## 🔍 Step 3: Test Database Connection

```bash
# Cek apakah database accessible
docker exec anggaran-desa-app php artisan migrate:status

# Cek database file
docker exec anggaran-desa-app ls -la /var/www/html/database/database.sqlite

# Test database query
docker exec anggaran-desa-app php artisan tinker --execute="DB::connection()->getPdo();"
```

---

## 🔍 Step 4: Cek Permissions

```bash
# Cek storage permissions
docker exec anggaran-desa-app ls -la storage/

# Cek bootstrap/cache permissions
docker exec anggaran-desa-app ls -la bootstrap/cache/

# Fix permissions jika perlu
docker exec anggaran-desa-app chown -R www-data:www-data storage bootstrap/cache database
docker exec anggaran-desa-app chmod -R 775 storage bootstrap/cache
```

---

## 🔧 Common Fixes:

### Fix 1: Clear All Cache

```bash
docker exec anggaran-desa-app php artisan cache:clear
docker exec anggaran-desa-app php artisan config:clear
docker exec anggaran-desa-app php artisan route:clear
docker exec anggaran-desa-app php artisan view:clear
```

### Fix 2: Regenerate Cache

```bash
docker exec anggaran-desa-app php artisan config:cache
docker exec anggaran-desa-app php artisan route:cache
docker exec anggaran-desa-app php artisan view:cache
```

### Fix 3: Check Environment

```bash
# Verify APP_KEY is set
docker exec anggaran-desa-app php artisan config:show | grep app.key

# Check database config
docker exec anggaran-desa-app php artisan config:show | grep database
```

### Fix 4: Run Migrations

```bash
# Check if tables exist
docker exec anggaran-desa-app php artisan migrate:status

# Run migrations if needed
docker exec anggaran-desa-app php artisan migrate --force

# Reseed if needed
docker exec anggaran-desa-app php artisan db:seed --force
```

### Fix 5: Enable Debug Mode (Temporarily)

```bash
# Stop container
docker stop anggaran-desa-app
docker rm anggaran-desa-app

# Run with DEBUG=true to see detailed error
docker run -d \
  --name anggaran-desa-app \
  --restart unless-stopped \
  -p 8075:80 \
  -e APP_NAME="Anggaran Desa" \
  -e APP_ENV=local \
  -e APP_DEBUG=true \
  -e APP_URL=http://129.150.57.43:8075 \
  -e APP_KEY=base64:ZVB4Q0tYMHhwN0FxSEdJT2Z4VjFSV3h0RjNtUTNXQnc= \
  -e DB_CONNECTION=sqlite \
  -e DB_DATABASE=/var/www/html/database/database.sqlite \
  -e APP_RUN_MIGRATIONS=true \
  -e APP_RUN_SEEDERS=true \
  -v anggaran-desa-storage:/var/www/html/storage \
  -v anggaran-desa-cache:/var/www/html/bootstrap/cache \
  -v anggaran-desa-db:/var/www/html/database \
  anggaran-desa:latest

# Now access http://129.150.57.43:8075 to see detailed error
```

---

## 🩺 Health Check Commands:

```bash
# Check if PHP-FPM is running
docker exec anggaran-desa-app ps aux | grep php-fpm

# Check if Nginx is running
docker exec anggaran-desa-app ps aux | grep nginx

# Test PHP
docker exec anggaran-desa-app php -v

# Test Artisan
docker exec anggaran-desa-app php artisan --version

# Check nginx config
docker exec anggaran-desa-app nginx -t
```

---

## 📋 Get Full Error Stack:

```bash
# Get last 200 lines of all logs
docker exec anggaran-desa-app sh -c "
echo '=== LARAVEL LOG ===' && \
tail -n 100 storage/logs/laravel.log 2>/dev/null || echo 'No laravel.log' && \
echo '' && \
echo '=== NGINX ACCESS LOG ===' && \
tail -n 50 /var/log/nginx/access.log 2>/dev/null && \
echo '' && \
echo '=== NGINX ERROR LOG ===' && \
tail -n 50 /var/log/nginx/error.log 2>/dev/null
"
```

---

## 🚨 Most Common Causes of Error 500:

1. **APP_KEY not set** → Run: `docker exec anggaran-desa-app php artisan key:generate`
2. **Database not migrated** → Run: `docker exec anggaran-desa-app php artisan migrate --force`
3. **Permission issues** → Run: `docker exec anggaran-desa-app chown -R www-data:www-data storage bootstrap/cache`
4. **Cache corruption** → Run: `docker exec anggaran-desa-app php artisan cache:clear`
5. **Missing .env** → Rebuild container with proper environment variables

---

## 📤 Share Error for Help:

Jalankan ini dan kirim outputnya:

```bash
docker exec anggaran-desa-app sh -c "
echo '=== ENVIRONMENT ===' && \
php artisan about && \
echo '' && \
echo '=== LAST 50 LINES OF LARAVEL LOG ===' && \
tail -n 50 storage/logs/laravel.log 2>/dev/null || echo 'No log file found'
"
```

---

**Jalankan command di Step 1 dulu untuk melihat error spesifiknya!** 🔍
