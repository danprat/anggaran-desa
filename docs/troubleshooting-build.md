# 🔧 Troubleshooting Docker Build Errors

## ❌ Error: PHP Extension Install Failed

### Error Message:
```
ERROR: failed to solve: process "/bin/sh -c docker-php-ext-install xmlreader" did not complete successfully: exit code: 2
```

### 🎯 Penyebab:
1. **`xmlreader` bukan extension terpisah** - Sudah included dalam extension `xml` di PHP 8.2+
2. Dependency libraries belum terinstall
3. Urutan instalasi extension salah
4. Build tools (gcc, make, autoconf) tidak tersedia di Alpine
5. Proses build timeout di ARM (kompilasi lebih lambat)

### 💡 Catatan Penting:
Di PHP 8.2, extensions ini sudah satu paket:
- ✅ `xml` - Includes: SimpleXML, XMLReader, XMLWriter
- ✅ `dom` - Separate extension
- ❌ `xmlreader` - Tidak perlu install terpisah (error!)
- ❌ `xmlwriter` - Tidak perlu install terpisah (error!)

---

## ✅ Solusi 1: Gunakan Dockerfile.minimal (PALING RELIABLE!)

**File baru: `Dockerfile.minimal`** - Hanya extension essential, pasti work!

```bash
docker build -f Dockerfile.minimal -t anggaran-desa:latest .
```

**✅ Recommended untuk production!**

---

## ✅ Solusi 2: Gunakan Dockerfile.simple

File `Dockerfile.simple` sudah diperbaiki (removed xmlreader):

```bash
docker build -f Dockerfile.simple -t anggaran-desa:latest .
```

---

## ✅ Solusi 3: Gunakan Dockerfile yang Sudah Diperbaiki

File `Dockerfile` utama sudah diperbaiki dengan:
- ✅ Removed `xmlreader` (sudah included di `xml`)
- ✅ Install build tools (autoconf, g++, make)
- ✅ Install library dependencies lebih lengkap
- ✅ Split instalasi extensions (satu per satu)

### Build dengan Dockerfile utama:

```bash
docker build -t anggaran-desa:latest .
```

---

## ✅ Solusi 4: Build dengan More Memory & Timeout

Jika build di VPS ARM dengan resource terbatas:

```bash
# Increase Docker build timeout
DOCKER_BUILDKIT=0 docker build \
  --memory=2g \
  --memory-swap=4g \
  -f Dockerfile.minimal \
  -t anggaran-desa:latest .
```

---

## ✅ Solusi 5: Pre-built Image dari GitHub Actions

Tunggu GitHub Actions selesai build, lalu pull:

```bash
docker pull ghcr.io/danprat/anggaran-desa:latest
```

---

## ✅ Solusi 6: Build di Local, Push ke VPS

Jika punya Mac/PC dengan Docker:

```bash
# Build multi-platform di local
docker buildx create --use
docker buildx build \
  --platform linux/arm64,linux/amd64 \
  -t anggaran-desa:latest \
  --load .

# Save image
docker save anggaran-desa:latest | gzip > anggaran-desa.tar.gz

# Upload ke VPS
scp anggaran-desa.tar.gz user@vps:/tmp/

# Di VPS, load image
ssh user@vps
docker load < /tmp/anggaran-desa.tar.gz
```

---

## 🔍 Debug Build Error

### Cek log detail:

```bash
# Build dengan verbose output
docker build --progress=plain -t anggaran-desa:latest . 2>&1 | tee build.log

# Cek error specific
grep -i error build.log
```

### Cek resource VPS:

```bash
# Cek memory
free -h

# Cek disk space
df -h

# Cek CPU
nproc
```

### Test instalasi manual:

```bash
# Run container untuk test
docker run -it --rm php:8.2-fpm-alpine sh

# Test install dependencies
apk add --no-cache autoconf g++ make libxml2-dev

# Test install PHP extension
docker-php-ext-install dom
```

---

## 📊 Perbandingan Dockerfile

| File | Extensions | Build Time (ARM) | Reliability | Use Case |
|------|------------|------------------|-------------|----------|
| `Dockerfile.minimal` | Essential only | ~2-3 min | **Very High** ⭐ | **Production Ready** |
| `Dockerfile.simple` | Standard | ~3-5 min | High | Full features |
| `Dockerfile` | All features | ~5-8 min | Medium | Development |

---

## ⚡ Quick Fix Command

Copy-paste ini untuk rebuild dengan fix terbaru:

```bash
# Pull update terbaru dari GitHub
cd anggaran-desa
git pull origin main

# Clean build (remove cache)
docker builder prune -af

# Build with MINIMAL Dockerfile (PALING RELIABLE!)
docker build -f Dockerfile.minimal -t anggaran-desa:latest .

# Verify build success
docker images | grep anggaran-desa

# Run container
docker run -d \
  --name anggaran-desa-app \
  --restart unless-stopped \
  -p 8075:80 \
  -e APP_NAME="Anggaran Desa" \
  -e APP_ENV=production \
  -e APP_URL=http://YOUR_VPS_IP:8075 \
  -e APP_KEY=base64:ZVB4Q0tYMHhwN0FxSEdJT2Z4VjFSV3h0RjNtUTNXQnc= \
  -e DB_CONNECTION=sqlite \
  -e DB_DATABASE=/var/www/html/database/database.sqlite \
  -e APP_RUN_MIGRATIONS=true \
  -e APP_RUN_SEEDERS=true \
  -v anggaran-desa-storage:/var/www/html/storage \
  -v anggaran-desa-cache:/var/www/html/bootstrap/cache \
  -v anggaran-desa-db:/var/www/html/database \
  anggaran-desa:latest

# Cek logs
docker logs -f anggaran-desa-app
```

---

## 🆘 Masih Error?

1. **Cek GitHub Issues:** https://github.com/danprat/anggaran-desa/issues
2. **Report error** dengan:
   - OS & Architecture (`uname -a`)
   - Docker version (`docker --version`)
   - Build log (`docker build --progress=plain ...`)
   - VPS specs (RAM, CPU, Disk)

---

**✨ Build berhasil? Lanjut akses:** `http://YOUR_VPS_IP:8075`
