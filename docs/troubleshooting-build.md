# 🔧 Troubleshooting Docker Build Errors

## ❌ Error: PHP Extension Install Failed

### Error Message:
```
=> ERROR [stage-0  4/16] RUN docker-php-ext-install -j$(nproc)...
```

### 🎯 Penyebab:
1. Dependency libraries belum terinstall
2. Urutan instalasi extension salah
3. Build tools (gcc, make, autoconf) tidak tersedia di Alpine
4. Proses build timeout di ARM (kompilasi lebih lambat)

---

## ✅ Solusi 1: Gunakan Dockerfile yang Sudah Diperbaiki

File `Dockerfile` utama sudah diperbaiki dengan:
- Install build tools (autoconf, g++, make)
- Install library dependencies lebih lengkap
- Split instalasi extensions (satu per satu)
- Urutan yang benar: dom → xml → xmlreader

### Build dengan Dockerfile utama:

```bash
docker build -t anggaran-desa:latest .
```

---

## ✅ Solusi 2: Gunakan Dockerfile.simple (Lebih Reliable)

Jika masih error, gunakan `Dockerfile.simple`:

```bash
docker build -f Dockerfile.simple -t anggaran-desa:latest .
```

---

## ✅ Solusi 3: Build dengan More Memory & Timeout

Jika build di VPS ARM dengan resource terbatas:

```bash
# Increase Docker build timeout
DOCKER_BUILDKIT=0 docker build \
  --memory=2g \
  --memory-swap=4g \
  -t anggaran-desa:latest .
```

---

## ✅ Solusi 4: Pre-built Image dari GitHub Actions

Tunggu GitHub Actions selesai build, lalu pull:

```bash
docker pull ghcr.io/danprat/anggaran-desa:latest
```

---

## ✅ Solusi 5: Build di Local, Push ke VPS

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

| File | Build Time (ARM) | Reliability | Use Case |
|------|------------------|-------------|----------|
| `Dockerfile` | ~5-8 min | High | Production, Full features |
| `Dockerfile.simple` | ~3-5 min | Very High | Quick deploy, Simple setup |

---

## ⚡ Quick Fix Command

Copy-paste ini untuk rebuild dengan fix terbaru:

```bash
# Pull update terbaru dari GitHub
cd anggaran-desa
git pull origin main

# Clean build (remove cache)
docker builder prune -af

# Build with simple Dockerfile (most reliable)
docker build -f Dockerfile.simple -t anggaran-desa:latest .

# Verify build success
docker images | grep anggaran-desa

# Run container
docker run -d \
  --name anggaran-desa-app \
  --restart unless-stopped \
  -p 8075:80 \
  -e APP_KEY=base64:ZVB4Q0tYMHhwN0FxSEdJT2Z4VjFSV3h0RjNtUTNXQnc= \
  -e DB_CONNECTION=sqlite \
  -e APP_RUN_MIGRATIONS=true \
  -e APP_RUN_SEEDERS=true \
  -v anggaran-desa-storage:/var/www/html/storage \
  -v anggaran-desa-cache:/var/www/html/bootstrap/cache \
  -v anggaran-desa-db:/var/www/html/database \
  anggaran-desa:latest
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
