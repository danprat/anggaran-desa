# 🚀 Deploy Anggaran Desa di Portainer VPS ARM - 1x Copy Paste

## 📋 Prasyarat

1. VPS ARM dengan Docker terinstall
2. Portainer sudah terinstall dan berjalan
3. Port 8075 tersedia
4. GitHub repository: https://github.com/danprat/anggaran-desa

---

## 🐳 Langkah 1: Build Docker Image

### Opsi A: Build di GitHub Actions (Recommended)

Buat file `.github/workflows/docker-build.yml`:

```yaml
name: Build Docker Image

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]
  workflow_dispatch:

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Set up QEMU
        uses: docker/setup-qemu-action@v3

      - name: Set up Docker Buildx
        uses: docker/setup-buildx-action@v3

      - name: Login to GitHub Container Registry
        uses: docker/login-action@v3
        with:
          registry: ghcr.io
          username: ${{ github.actor }}
          password: ${{ secrets.GITHUB_TOKEN }}

      - name: Build and push
        uses: docker/build-push-action@v5
        with:
          context: .
          platforms: linux/amd64,linux/arm64
          push: true
          tags: |
            ghcr.io/danprat/anggaran-desa:latest
            ghcr.io/danprat/anggaran-desa:${{ github.sha }}
          cache-from: type=gha
          cache-to: type=gha,mode=max
```

### Opsi B: Build Manual di VPS

```bash
# Clone repository
git clone https://github.com/danprat/anggaran-desa.git
cd anggaran-desa

# Build image
docker build -t anggaran-desa:latest .

# Tag untuk local registry (optional)
docker tag anggaran-desa:latest localhost:5000/anggaran-desa:latest
```

---

## 📦 Langkah 2: Deploy di Portainer

### Copy-Paste Stack di Portainer

1. Buka Portainer UI
2. Pilih **Stacks** → **Add stack**
3. Nama stack: `anggaran-desa`
4. Copy-paste konfigurasi di bawah:

```yaml
version: '3.8'

services:
  app:
    image: ghcr.io/danprat/anggaran-desa:latest
    container_name: anggaran-desa-app
    restart: unless-stopped
    working_dir: /var/www/html
    volumes:
      - app-storage:/var/www/html/storage
      - app-bootstrap-cache:/var/www/html/bootstrap/cache
      - app-database:/var/www/html/database
    ports:
      - "8075:80"
    environment:
      # ===== APLIKASI =====
      - APP_NAME=Anggaran Desa
      - APP_ENV=production
      - APP_DEBUG=false
      - APP_URL=http://YOUR_VPS_IP:8075
      
      # ===== GENERATE KEY DULU! =====
      # Jalankan: docker run --rm anggaran-desa php artisan key:generate --show
      - APP_KEY=base64:GANTI_DENGAN_KEY_HASIL_GENERATE
      
      # ===== DATABASE SQLITE =====
      - DB_CONNECTION=sqlite
      - DB_DATABASE=/var/www/html/database/database.sqlite
      
      # ===== CACHE & SESSION =====
      - SESSION_DRIVER=file
      - SESSION_LIFETIME=120
      - CACHE_DRIVER=file
      - QUEUE_CONNECTION=sync
      
      # ===== OPTIONAL: AUTO MIGRATION =====
      - APP_RUN_MIGRATIONS=false
      - APP_RUN_SEEDERS=false
      
      # ===== LOG =====
      - LOG_CHANNEL=stack
      - LOG_LEVEL=info
    networks:
      - anggaran-desa-network
    healthcheck:
      test: ["CMD", "php", "artisan", "--version"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 40s

volumes:
  app-storage:
    driver: local
  app-bootstrap-cache:
    driver: local
  app-database:
    driver: local

networks:
  anggaran-desa-network:
    driver: bridge
```

5. Klik **Deploy the stack**

---

## 🔑 Langkah 3: Generate APP_KEY

Jika image sudah public di GHCR, jalankan:

```bash
docker run --rm ghcr.io/danprat/anggaran-desa:latest php artisan key:generate --show
```

Atau jika build lokal:

```bash
docker run --rm anggaran-desa:latest php artisan key:generate --show
```

**Copy hasilnya** (contoh: `base64:xxxxxxxxxxxxxxxxxxxx`) dan update di **Environment variables** di Portainer.

---

## 🗃️ Langkah 4: Setup Database (SQLite)

### Opsi A: Via Environment Variable (Automatic)

Update environment di Portainer:

```yaml
- APP_RUN_MIGRATIONS=true
- APP_RUN_SEEDERS=true  # Optional: jika ingin seed data awal
```

Restart stack.

### Opsi B: Manual via Exec

1. Di Portainer, buka **Containers** → `anggaran-desa-app`
2. Klik **Exec Console**
3. Jalankan:

```bash
php artisan migrate --force
php artisan db:seed --force  # Optional
```

---

## ✅ Langkah 5: Verifikasi

Akses aplikasi di browser:

```
http://YOUR_VPS_IP:8075
```

Cek health status:

```bash
curl http://YOUR_VPS_IP:8075
```

Cek logs:

```bash
docker logs anggaran-desa-app -f
```

---

## 🔧 Troubleshooting

### 1. Error "Permission denied" untuk database.sqlite

```bash
# Exec ke container
docker exec -it anggaran-desa-app sh

# Fix permissions
chown www-data:www-data /var/www/html/database/database.sqlite
chmod 664 /var/www/html/database/database.sqlite
```

### 2. Error "No application encryption key"

Generate key baru dan update di Portainer environment variables.

### 3. Container tidak start

```bash
# Cek logs
docker logs anggaran-desa-app

# Cek health status
docker inspect anggaran-desa-app | grep -A 10 Health
```

### 4. Build error di ARM: "ext/dom/dom_ce.h: No such file"

Sudah fixed di Dockerfile terbaru. Pastikan install `dom` sebelum `xmlreader`.

---

## 🔄 Update Aplikasi

### Via Portainer

1. Buka stack `anggaran-desa`
2. Klik **Pull and redeploy**
3. Atau restart service

### Via CLI

```bash
# Pull image terbaru
docker pull ghcr.io/danprat/anggaran-desa:latest

# Restart container
docker restart anggaran-desa-app
```

---

## 📊 Backup Database

```bash
# Backup SQLite database
docker cp anggaran-desa-app:/var/www/html/database/database.sqlite ./backup-$(date +%Y%m%d).sqlite

# Restore
docker cp ./backup-20250107.sqlite anggaran-desa-app:/var/www/html/database/database.sqlite
docker exec anggaran-desa-app chown www-data:www-data /var/www/html/database/database.sqlite
```

---

## 🚨 Production Tips

1. **Ubah APP_KEY** - Jangan gunakan default
2. **Set APP_DEBUG=false** - Jangan expose error di production
3. **Backup rutin** - Schedule backup database SQLite
4. **Monitor logs** - Setup log aggregation
5. **Update regular** - Pull image terbaru secara berkala
6. **Reverse Proxy** - Gunakan Nginx/Traefik di depan dengan SSL

---

## 📱 Akses Default

- **URL**: http://YOUR_VPS_IP:8075
- **Port**: 8075
- **Database**: SQLite (persistent via volume)

---

## 🆘 Support

- Repository: https://github.com/danprat/anggaran-desa
- Issues: https://github.com/danprat/anggaran-desa/issues

---

**✨ Happy Deploying! ✨**
