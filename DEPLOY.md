# 🚀 Quick Deploy - Portainer Stack

## Copy-Paste ke Portainer (Port 8075)

**1x Copy-Paste! Build otomatis dari GitHub!**

```yaml
version: '3.8'

services:
  app:
    build:
      context: https://github.com/danprat/anggaran-desa.git#main
      dockerfile: Dockerfile
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
      - APP_KEY=base64:ZVB4Q0tYMHhwN0FxSEdJT2Z4VjFSV3h0RjNtUTNXQnc=
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

**✅ Sudah include APP_KEY default!**

---

## 📝 Langkah Deploy

1. Copy stack YAML di atas
2. Paste di Portainer → Stacks → Add Stack
3. Ganti `YOUR_VPS_IP` dengan IP VPS Anda
4. Klik **Deploy the stack**
5. Tunggu 5-10 menit (build dari GitHub)
6. Akses: `http://YOUR_VPS_IP:8075`

---

## 🔑 (Opsional) Generate APP_KEY Baru

Untuk keamanan production, generate APP_KEY unik:

```bash
# Generate di local dengan PHP
php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
```

Copy dan ganti `APP_KEY` di stack.

## 📖 Dokumentasi Lengkap

Lihat [docs/portainer-deploy-guide.md](./docs/portainer-deploy-guide.md)

## ✅ Akses Aplikasi

```
http://YOUR_VPS_IP:8075
```
