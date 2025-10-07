# 🔧 Fix Container Startup Error

## Error yang Terjadi:
```
docker: Error response from daemon: Conflict. The container name "/anggaran-desa-app" is already in use
/usr/local/bin/startup.sh: not found
```

---

## ✅ Solusi Lengkap (Copy-Paste di VPS):

```bash
# 1. Stop dan hapus container lama
docker stop anggaran-desa-app 2>/dev/null || true
docker rm anggaran-desa-app 2>/dev/null || true

# 2. Hapus image lama (optional, untuk rebuild fresh)
docker rmi anggaran-desa:latest 2>/dev/null || true

# 3. Pull update terbaru dari GitHub
cd anggaran-desa
git pull origin main

# 4. Rebuild dengan Dockerfile.minimal (sudah include startup.sh)
docker build -f Dockerfile.minimal -t anggaran-desa:latest .

# 5. Run container dengan command yang benar
docker run -d \
  --name anggaran-desa-app \
  --restart unless-stopped \
  -p 8075:80 \
  -e APP_NAME="Anggaran Desa" \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
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

# 6. Monitor logs
docker logs -f anggaran-desa-app
```

---

## 🎯 Apa yang Diperbaiki:

1. ✅ **startup.sh** sekarang file terpisah (bukan inline script)
2. ✅ File **docker/startup.sh** ter-COPY dengan benar
3. ✅ Container lama di-remove sebelum create baru
4. ✅ Better error handling dan logging

---

## 📋 Verifikasi Setelah Deploy:

```bash
# Cek container running
docker ps | grep anggaran-desa

# Cek logs (harus muncul emoji logs)
docker logs anggaran-desa-app

# Test akses
curl http://129.150.57.43:8075

# Cek database
docker exec anggaran-desa-app ls -la /var/www/html/database/
```

Expected logs:
```
🚀 Starting Anggaran Desa Application...
📦 Creating SQLite database...
🔄 Running migrations...
🌱 Running seeders...
⚡ Caching configuration...
✅ Application ready!
```

---

## 🌐 Akses Aplikasi:

```
http://129.150.57.43:8075
```

**Login:**
- Email: `admin@example.com`
- Password: `password`

---

## 🆘 Masih Error?

### Error: "startup.sh not found" setelah rebuild

**Penyebab:** Build cache lama masih ada

**Solusi:**
```bash
# Clean semua cache
docker builder prune -af
docker system prune -af

# Rebuild from scratch
docker build --no-cache -f Dockerfile.minimal -t anggaran-desa:latest .
```

### Error: Port 8075 already in use

**Solusi:**
```bash
# Cek yang pakai port 8075
netstat -tuln | grep 8075

# Stop container yang pakai port itu
docker ps -a | grep 8075
docker stop <container_id>
```

### Error: Migration failed

**Solusi:**
```bash
# Reset database
docker exec anggaran-desa-app php artisan migrate:fresh --seed --force
```

---

**✅ Setelah fix, aplikasi langsung jalan!** 🚀
