# 📦 Stack Portainer - Anggaran Desa (SQLite + Port 8075)

## 🎯 Siap Deploy 1x Copy-Paste!

### ✅ Spesifikasi
- **Database:** SQLite (No MySQL!)
- **Port:** 8075
- **Platform:** ARM64/AMD64 (Universal)
- **Build:** From GitHub Repository

---

## 🚀 METODE 1: Build Manual di VPS (Paling Mudah & Pasti Jalan!)

### Step 1: Build Image di VPS

SSH ke VPS Anda, lalu jalankan:

```bash
# Clone repository
git clone https://github.com/danprat/anggaran-desa.git
cd anggaran-desa

# Build Docker image
docker build -t anggaran-desa:latest .

# Verify image created
docker images | grep anggaran-desa
```

### Step 2: Copy Stack Ini ke Portainer

Buka **Portainer** → **Stacks** → **Add Stack**, lalu paste:

```yaml
version: '3.8'

services:
  app:
    image: anggaran-desa:latest
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
      start_period: 40s

volumes:
  app-storage:
  app-bootstrap-cache:
  app-database:
```

### Step 3: Deploy

1. Ganti `YOUR_VPS_IP` dengan IP VPS Anda
2. Klik **Deploy the stack**
3. Tunggu 10-30 detik
4. Akses: `http://YOUR_VPS_IP:8075`

---

## � METODE 2: Via Git Repository di Portainer

Jika Portainer Anda support Git integration:

1. **Portainer** → **Stacks** → **Add Stack**
2. Pilih tab **"Git Repository"**
3. Isi:
   - **Repository URL:** `https://github.com/danprat/anggaran-desa`
   - **Repository reference:** `refs/heads/main`
   - **Compose path:** `docker-compose.yml`
4. Di **Environment variables**, tambahkan:
   ```
   YOUR_VPS_IP=GANTI_IP_ANDA
   ```
5. Klik **Deploy the stack**

---

## 🚀 METODE 3: Direct Docker Command (Tanpa Portainer)

---

## � METODE 3: Direct Docker Command (Tanpa Portainer)

Paling simple, langsung via terminal:

```bash
# Clone repository
git clone https://github.com/danprat/anggaran-desa.git
cd anggaran-desa

# Build image
docker build -t anggaran-desa:latest .

# Run container
docker run -d \
  --name anggaran-desa-app \
  --restart unless-stopped \
  -p 8075:80 \
  -e APP_NAME="Anggaran Desa" \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  -e APP_URL=http://YOUR_VPS_IP:8075 \
  -e APP_KEY=base64:ZVB4Q0tYMHhwN0FxSEdJT2Z4VjFSV3h0RjNtUTNXQnc= \
  -e DB_CONNECTION=sqlite \
  -e DB_DATABASE=/var/www/html/database/database.sqlite \
  -e SESSION_DRIVER=file \
  -e CACHE_DRIVER=file \
  -e APP_RUN_MIGRATIONS=true \
  -e APP_RUN_SEEDERS=true \
  -v anggaran-desa-storage:/var/www/html/storage \
  -v anggaran-desa-cache:/var/www/html/bootstrap/cache \
  -v anggaran-desa-db:/var/www/html/database \
  anggaran-desa:latest

# Cek logs
docker logs -f anggaran-desa-app
```

Ganti `YOUR_VPS_IP` dengan IP Anda.

---

## 🔑 (Opsional) Generate APP_KEY Baru

Untuk keamanan production, generate APP_KEY unik:

```bash
# Generate di local dengan PHP
php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
```

Copy dan ganti `APP_KEY` di stack atau docker command.

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
