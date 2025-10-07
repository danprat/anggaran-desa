# 🚀 Quick Deploy - Portainer Stack

## Copy-Paste ke Portainer (Port 8075)

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
      - APP_KEY=base64:GENERATE_KEY_TERLEBIH_DAHULU
      - DB_CONNECTION=sqlite
      - DB_DATABASE=/var/www/html/database/database.sqlite
      - SESSION_DRIVER=file
      - CACHE_DRIVER=file
      - APP_RUN_MIGRATIONS=true
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

## 🔑 Generate APP_KEY

```bash
docker run --rm ghcr.io/danprat/anggaran-desa:latest php artisan key:generate --show
```

Copy hasilnya dan ganti `APP_KEY` di atas.

## 📖 Dokumentasi Lengkap

Lihat [docs/portainer-deploy-guide.md](./docs/portainer-deploy-guide.md)

## ✅ Akses Aplikasi

```
http://YOUR_VPS_IP:8075
```
