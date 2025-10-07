# Panduan Deployment ke Portainer VPS ARM

## Permasalahan yang Diselesaikan

Error yang Anda alami:
```
fatal error: ext/dom/dom_ce.h: No such file or directory
```

Terjadi karena PHP extension `xmlreader` membutuhkan `dom` extension yang harus diinstall terlebih dahulu. Dockerfile yang baru sudah mengatur urutan instalasi yang benar.

## Persiapan

### 1. Generate Application Key

Jika belum memiliki APP_KEY, generate dengan:

```bash
php artisan key:generate --show
```

Simpan output untuk digunakan di environment variable.

### 2. Persiapan File Environment

Di Portainer, Anda perlu menambahkan environment variables berikut:

```
APP_NAME=Anggaran Desa
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://your-domain.com

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

LOG_CHANNEL=stack
LOG_LEVEL=error

SESSION_DRIVER=file
SESSION_LIFETIME=120
```

## Deployment di Portainer

### Metode 1: Menggunakan Stack (Docker Compose)

1. Login ke Portainer
2. Pilih environment Anda
3. Pergi ke **Stacks** → **Add stack**
4. Berikan nama stack (contoh: `anggaran-desa`)
5. Pilih **Git Repository** atau **Upload**

#### Jika menggunakan Git Repository:
- Repository URL: `https://github.com/danprat/anggaran-desa`
- Repository reference: `main`
- Compose path: `docker-compose.yml`

6. Scroll ke bawah ke **Environment variables**
7. Tambahkan variable yang diperlukan (lihat section Persiapan File Environment)
8. Klik **Deploy the stack**

### Metode 2: Menggunakan Container Manual

1. Login ke Portainer
2. Pergi ke **Images**
3. Klik **Build a new image**
4. Berikan nama image: `anggaran-desa:latest`
5. Upload atau paste Dockerfile
6. Pilih **Build method**: Upload atau URL
7. Klik **Build the image**

Setelah image selesai dibuild:

1. Pergi ke **Containers** → **Add container**
2. Name: `anggaran-desa-app`
3. Image: `anggaran-desa:latest`
4. Network ports configuration:
   - Host: `80` → Container: `80`
5. Volumes:
   - `/path/on/host/storage` → `/var/www/html/storage`
   - `/path/on/host/bootstrap/cache` → `/var/www/html/bootstrap/cache`
6. Environment variables: Tambahkan semua variable yang diperlukan
7. Restart policy: `Unless stopped`
8. Klik **Deploy the container**

## Optimasi untuk ARM Architecture

Dockerfile yang dibuat sudah dioptimalkan untuk ARM64/aarch64:

1. **Base image**: Menggunakan `php:8.2-fpm-alpine` yang lebih ringan dan support ARM
2. **Dependencies**: Semua package disesuaikan untuk Alpine Linux
3. **Extension order**: DOM diinstall sebelum XMLReader untuk menghindari dependency error
4. **Multi-stage build**: Tidak digunakan untuk menghindari complexity di ARM

## Testing Setelah Deployment

Setelah container berjalan, akses aplikasi Anda:

```bash
# Cek status container
docker ps

# Lihat logs jika ada masalah
docker logs anggaran-desa-app

# Masuk ke container untuk debugging
docker exec -it anggaran-desa-app sh
```

Di dalam container, Anda bisa menjalankan:

```bash
# Cek PHP version dan extensions
php -v
php -m

# Jalankan Laravel commands
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Troubleshooting

### 1. Permission Issues

Jika ada masalah permission pada storage:

```bash
docker exec -it anggaran-desa-app sh -c "chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache"
docker exec -it anggaran-desa-app sh -c "chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache"
```

### 2. Database Not Found

Pastikan file `database.sqlite` ada:

```bash
docker exec -it anggaran-desa-app sh -c "touch /var/www/html/database/database.sqlite"
docker exec -it anggaran-desa-app sh -c "chown www-data:www-data /var/www/html/database/database.sqlite"
docker exec -it anggaran-desa-app sh -c "php artisan migrate --force"
```

### 3. Build Gagal dengan Memory Error

Jika build gagal karena memory, tambahkan swap atau build di mesin lain kemudian push ke registry:

```bash
# Di mesin lokal atau CI/CD
docker buildx build --platform linux/arm64 -t your-registry/anggaran-desa:latest .
docker push your-registry/anggaran-desa:latest

# Di Portainer, gunakan image dari registry
```

### 4. NPM Build Gagal (Opsional)

Jika Anda tidak perlu build assets di dalam container, edit Dockerfile dan hapus/comment bagian:

```dockerfile
# RUN apk add --no-cache nodejs npm
# RUN npm install && npm run build
```

Kemudian build assets di lokal dan commit hasilnya ke git.

## Performance Tuning untuk ARM

1. **OPcache**: Sudah diaktifkan di `docker/php/php.ini`
2. **Memory**: Adjust `memory_limit` di `php.ini` sesuai kapasitas VPS
3. **Workers**: Adjust `numprocs` di `supervisord.conf` untuk queue workers
4. **Nginx**: Adjust `worker_connections` di `nginx.conf` sesuai traffic

## Update Aplikasi

Untuk update aplikasi:

1. Di Portainer, pergi ke stack Anda
2. Klik **Editor**
3. Scroll ke bawah
4. Klik **Pull and redeploy**

Atau via command:

```bash
docker pull your-image:latest
docker-compose up -d --force-recreate
```

## Security Recommendations

1. Gunakan HTTPS dengan reverse proxy (Traefik, Nginx Proxy Manager)
2. Set `APP_DEBUG=false` di production
3. Gunakan strong `APP_KEY`
4. Restrict database file permissions
5. Regularly update Docker images
6. Monitor logs untuk suspicious activities

## Support

Jika masih ada masalah, cek:
- Container logs: `docker logs anggaran-desa-app`
- Laravel logs: `storage/logs/laravel.log`
- Nginx logs: `/var/log/nginx/error.log`
- PHP-FPM logs: `/var/log/php_errors.log`
