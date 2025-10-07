# 📦 Stack Portainer - Inline Build (100% Works!)

## 🎯 Copy-Paste Ini ke Portainer - Dijamin Jalan!

**Solusi jika build dari GitHub URL tidak work.**

---

## 🚀 Cara Deploy

### **Metode 1: Upload via Repository (Recommended)**

1. **Download repository** sebagai ZIP dari GitHub:
   ```
   https://github.com/danprat/anggaran-desa/archive/refs/heads/main.zip
   ```

2. **Extract** di local

3. Buka **Portainer** → **Stacks** → **Add Stack**

4. Pilih tab **"Upload"** atau **"Git Repository"**

5. Jika **Upload**: 
   - Upload file `docker-compose.yml` dari folder yang sudah di-extract
   
6. Jika **Git Repository**:
   - Repository URL: `https://github.com/danprat/anggaran-desa`
   - Repository reference: `refs/heads/main`
   - Compose path: `docker-compose.yml`

7. Di bagian **Environment variables**, tambahkan:
   ```
   YOUR_VPS_IP=GANTI_DENGAN_IP_ANDA
   ```

8. Klik **Deploy the stack**

---

### **Metode 2: Build Manual Dulu (Tercepat)**

Jika Portainer tidak support build dari Git, build manual dulu di VPS:

#### **Step 1: Build Image di VPS**

```bash
# Clone repository
git clone https://github.com/danprat/anggaran-desa.git
cd anggaran-desa

# Build image
docker build -t anggaran-desa:local .

# Cek image berhasil
docker images | grep anggaran-desa
```

#### **Step 2: Copy Stack Ini ke Portainer**

```yaml
version: '3.8'

services:
  app:
    image: anggaran-desa:local
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

Ganti `YOUR_VPS_IP` dan deploy!

---

### **Metode 3: Direct Docker Command (Paling Simple)**

Jika tidak mau pakai Portainer stack, jalankan langsung via terminal VPS:

```bash
# Clone & masuk ke folder
git clone https://github.com/danprat/anggaran-desa.git
cd anggaran-desa

# Edit environment
cat > .env.portainer << 'EOF'
APP_NAME=Anggaran Desa
APP_ENV=production
APP_DEBUG=false
APP_URL=http://YOUR_VPS_IP:8075
APP_KEY=base64:ZVB4Q0tYMHhwN0FxSEdJT2Z4VjFSV3h0RjNtUTNXQnc=
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
SESSION_DRIVER=file
CACHE_DRIVER=file
APP_RUN_MIGRATIONS=true
APP_RUN_SEEDERS=true
EOF

# Build image
docker build -t anggaran-desa:latest .

# Run container
docker run -d \
  --name anggaran-desa-app \
  --restart unless-stopped \
  -p 8075:80 \
  --env-file .env.portainer \
  -v anggaran-desa-storage:/var/www/html/storage \
  -v anggaran-desa-cache:/var/www/html/bootstrap/cache \
  -v anggaran-desa-db:/var/www/html/database \
  anggaran-desa:latest

# Cek logs
docker logs -f anggaran-desa-app
```

Akses: `http://YOUR_VPS_IP:8075`

---

## 🔍 Troubleshooting Portainer Errors

### Error: "Unable to create stack"

**Penyebab:**
- Portainer CE versi lama tidak support build dari Git URL
- Network issue ke GitHub
- Syntax error di YAML

**Solusi:**
1. Gunakan **Metode 2** (build manual dulu)
2. Atau gunakan **Metode 3** (direct docker command)

### Error: "Build context URL not supported"

**Solusi:** Gunakan Metode 2 - build di VPS dulu, lalu reference image local.

### Error: "Cannot connect to GitHub"

**Solusi:**
```bash
# Test koneksi dari VPS
curl -I https://github.com

# Jika tidak bisa, clone manual
git clone https://github.com/danprat/anggaran-desa.git
cd anggaran-desa
docker build -t anggaran-desa:local .
```

### Error: "Port 8075 already in use"

```bash
# Cek port
netstat -tuln | grep 8075

# Ganti port di stack jadi 8076 atau 8077
```

---

## 📊 Monitoring Setelah Deploy

```bash
# Cek container running
docker ps

# Cek logs
docker logs -f anggaran-desa-app

# Cek database
docker exec anggaran-desa-app ls -la /var/www/html/database/

# Test health
curl http://localhost:8075
```

---

## ✅ Akses Aplikasi

```
http://YOUR_VPS_IP:8075
```

**Login:**
- Email: `admin@example.com`
- Password: `password`

---

## 🔄 Update Aplikasi

```bash
# Pull update dari GitHub
cd anggaran-desa
git pull origin main

# Rebuild image
docker build -t anggaran-desa:local .

# Restart container via Portainer
# Atau manual:
docker restart anggaran-desa-app
```

---

**✨ Pilih metode yang paling cocok untuk setup Anda!**
