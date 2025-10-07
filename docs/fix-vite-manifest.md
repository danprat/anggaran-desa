# 🔧 Fix Vite Manifest Not Found Error

## ❌ Error:
```
Illuminate\Foundation\ViteManifestNotFoundException
Vite manifest not found at: /var/www/html/public/build/manifest.json
```

---

## 🎯 Root Cause:
Assets frontend (CSS/JS) belum di-build dengan Vite. Laravel mencari file `manifest.json` hasil build.

---

## ✅ SOLUSI 1: Rebuild Image dengan Vite Build (RECOMMENDED)

Dockerfile sudah diupdate untuk auto-build Vite assets.

```bash
# 1. Stop container lama
docker stop anggaran-desa-app
docker rm anggaran-desa-app

# 2. Pull update terbaru (sudah include Vite build)
cd anggaran-desa
git pull origin main

# 3. Clean cache dan rebuild
docker builder prune -af
docker build -f Dockerfile.minimal -t anggaran-desa:latest .

# 4. Run container
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

# 5. Verify
curl http://129.150.57.43:8075
```

**✅ Build time akan lebih lama (~5-10 min) karena compile Vite, tapi setelah itu aplikasi jalan sempurna!**

---

## ✅ SOLUSI 2: Build Assets Manual di Container (Quick Fix)

Jika tidak mau rebuild, build assets di dalam container yang running:

```bash
# Install Node.js di container
docker exec anggaran-desa-app apk add --no-cache nodejs npm

# Build Vite assets
docker exec anggaran-desa-app npm ci
docker exec anggaran-desa-app npm run build

# Verify build directory
docker exec anggaran-desa-app ls -la public/build/

# Restart untuk reload
docker restart anggaran-desa-app
```

**⚠️ Ini temporary fix! Jika container di-recreate, harus build lagi.**

---

## ✅ SOLUSI 3: Build di Local, Copy ke Container

Jika punya Node.js di local:

```bash
# Di local machine (bukan VPS)
cd anggaran-desa
npm install
npm run build

# Copy build results ke VPS
scp -r public/build ubuntu@129.150.57.43:/tmp/

# Di VPS, copy ke container
docker cp /tmp/build anggaran-desa-app:/var/www/html/public/

# Fix permissions
docker exec anggaran-desa-app chown -R www-data:www-data /var/www/html/public/build

# Restart
docker restart anggaran-desa-app
```

---

## 📊 Verify Build Success:

```bash
# Check if build directory exists
docker exec anggaran-desa-app ls -la public/build/

# Should see files like:
# manifest.json
# assets/app-xxxxx.js
# assets/app-xxxxx.css

# Test aplikasi
curl http://129.150.57.43:8075
```

---

## 🚀 Expected Output After Fix:

✅ Aplikasi load tanpa error  
✅ CSS styling muncul dengan benar  
✅ JavaScript berfungsi  
✅ No more "Vite manifest not found" error  

---

## 📝 Notes:

**Build Time:**
- ARM64 VPS: ~5-10 menit
- AMD64 VPS: ~3-5 menit
- Local build + copy: ~2 menit

**Recommendation:**
- Production: Gunakan **Solusi 1** (rebuild image dengan Vite)
- Quick test: Gunakan **Solusi 2** (build di container)
- Fastest: Gunakan **Solusi 3** (build di local)

---

## 🆘 Build Failed?

### Error: "npm ci" failed

**Solusi:** Gunakan `npm install` instead:
```bash
docker exec anggaran-desa-app npm install
docker exec anggaran-desa-app npm run build
```

### Error: Out of memory during build

**Solusi:** Increase Docker memory limit:
```bash
# Build dengan memory limit lebih besar
docker build \
  --memory=2g \
  --memory-swap=4g \
  -f Dockerfile.minimal \
  -t anggaran-desa:latest .
```

### Error: Node version incompatible

**Solusi:** Check Node version di `package.json` dan update Alpine package:
```bash
docker exec anggaran-desa-app node -v
# Jika versi terlalu lama, update dengan:
docker exec anggaran-desa-app apk add --no-cache nodejs-current npm
```

---

**Gunakan Solusi 1 untuk hasil terbaik! Rebuild image dengan Vite build included.** 🚀
