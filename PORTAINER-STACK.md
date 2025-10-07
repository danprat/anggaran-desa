# 📦 Stack Portainer - Anggaran Desa (SQLite + Port 8075)

## 🎯 Siap Deploy 1x Copy-Paste!

### ✅ Spesifikasi
- **Database:** SQLite (No MySQL!)
- **Port:** 8075
- **Platform:** ARM64/AMD64 (Universal)
- **Image:** ghcr.io/danprat/anggaran-desa:latest

---

## 🚀 Copy Stack Ini ke Portainer

```yaml
version: '3.8'

services:
  app:
    image: ghcr.io/danprat/anggaran-desa:latest
    container_name: anggaran-desa-app
    restart: unless-stopped
    volumes:
      - app-storage:/var/www/html/storage
      - app-bootstrap-cache:/var/www/html/bootstrap/cache
      - app-database:/var/www/html/database
    ports:
      - "8075:80"
    environment:
      - APP_NAME=Anggaran Desa
      - APP_ENV=production
      - APP_DEBUG=false
      - APP_URL=http://YOUR_VPS_IP:8075
      - APP_KEY=base64:GENERATE_KEY_DULU
      - DB_CONNECTION=sqlite
      - DB_DATABASE=/var/www/html/database/database.sqlite
      - SESSION_DRIVER=file
      - CACHE_DRIVER=file
      - APP_RUN_MIGRATIONS=true
      - APP_RUN_SEEDERS=true
    healthcheck:
      test: ["CMD", "php", "artisan", "--version"]
      interval: 30s
      timeout: 10s
      retries: 3

volumes:
  app-storage:
  app-bootstrap-cache:
  app-database:
```

---

## 🔑 Generate APP_KEY

**PENTING:** Generate dulu sebelum deploy!

```bash
docker run --rm ghcr.io/danprat/anggaran-desa:latest php artisan key:generate --show
```

Hasilnya seperti ini:
```
base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

**Copy dan ganti** `APP_KEY=base64:GENERATE_KEY_DULU` di stack Portainer.

---

## 📝 Langkah Deploy

1. ✅ Generate APP_KEY (lihat di atas)
2. ✅ Copy stack YAML ke Portainer
3. ✅ Ganti `YOUR_VPS_IP` dengan IP VPS Anda
4. ✅ Ganti `APP_KEY` dengan hasil generate
5. ✅ Deploy stack
6. ✅ Tunggu 2-3 menit
7. ✅ Akses: `http://YOUR_VPS_IP:8075`

---

## 👤 Default Login

```
Email: admin@example.com
Password: password
```

**⚠️ PENTING:** Ganti password setelah login pertama!

---

## 🔧 Troubleshooting

### Container tidak start?
```bash
docker logs anggaran-desa-app
```

### Reset database?
```bash
docker exec anggaran-desa-app php artisan migrate:fresh --seed --force
```

### Permission error database.sqlite?
```bash
docker exec anggaran-desa-app sh -c "chown www-data:www-data /var/www/html/database/database.sqlite && chmod 664 /var/www/html/database/database.sqlite"
```

---

## 📊 Monitoring

```bash
# Cek status
docker ps | grep anggaran-desa

# Cek logs real-time
docker logs -f anggaran-desa-app

# Cek health
docker inspect anggaran-desa-app | grep -A 10 Health
```

---

## 💾 Backup Database

```bash
# Backup
docker cp anggaran-desa-app:/var/www/html/database/database.sqlite ./backup-$(date +%Y%m%d).sqlite

# Restore
docker cp ./backup-20250107.sqlite anggaran-desa-app:/var/www/html/database/database.sqlite
docker exec anggaran-desa-app chown www-data:www-data /var/www/html/database/database.sqlite
```

---

## 📖 Dokumentasi

- [DEPLOY.md](./DEPLOY.md) - Quick start guide
- [docs/portainer-deploy-guide.md](./docs/portainer-deploy-guide.md) - Full documentation
- [README.md](./README.md) - Project documentation

---

## 🆘 Butuh Bantuan?

- **Repository:** https://github.com/danprat/anggaran-desa
- **Issues:** https://github.com/danprat/anggaran-desa/issues

---

**✨ Selamat Deploy! ✨**
