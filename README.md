# 🏛️ Sistem Anggaran Desa

Sistem manajemen anggaran desa yang komprehensif untuk membantu pemerintah desa dalam mengelola keuangan, perencanaan, dan pelaporan anggaran secara digital.

## 📋 Deskripsi

Sistem Anggaran Desa adalah aplikasi web berbasis Laravel yang dirancang khusus untuk membantu pemerintah desa dalam:
- Mengelola profil dan identitas desa
- Merencanakan dan mengelola anggaran desa
- Melakukan tracking realisasi anggaran
- Membuat laporan keuangan yang transparan
- Mengelola kegiatan dan program desa

## ✨ Fitur Utama

### 🏠 Manajemen Profil Desa
- **Informasi Dasar**: Nama desa, alamat, kontak
- **Kepemimpinan**: Data kepala desa dan periode jabatan
- **Visi & Misi**: Dokumentasi visi, misi, dan sejarah desa
- **Data Demografis**: Statistik penduduk dan kepala keluarga
- **Geografis**: Batas wilayah dan informasi geografis
- **Logo & Media**: Upload dan manajemen logo desa, kabupaten, provinsi

### 💰 Manajemen Anggaran
- Perencanaan anggaran tahunan
- Kategorisasi anggaran berdasarkan bidang
- Tracking realisasi vs target
- Laporan keuangan real-time

### �� Dashboard & Laporan
- Dashboard statistik yang informatif
- Grafik dan visualisasi data
- Export laporan dalam berbagai format
- Monitoring progress kegiatan

### 👥 Manajemen User
- Role-based access control
- Multi-level authorization
- Log aktivitas sistem
- Manajemen permissions

## 🛠️ Teknologi

- **Backend**: Laravel 10.x
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Breeze
- **File Storage**: Laravel Storage
- **Build Tools**: Vite
- **Package Manager**: Composer, NPM

## 📦 Instalasi

### 🐳 Quick Deploy dengan Docker + Portainer (1x Copy-Paste!)

**✅ Support:** AMD64, ARM64/aarch64 (VPS ARM, Apple Silicon, Raspberry Pi)  
**📦 Database:** SQLite (No MySQL required!)  
**🔌 Port:** 8075

#### Langkah 1: Deploy Stack di Portainer

1. Buka **Portainer** → **Stacks** → **Add Stack**
2. Nama: `anggaran-desa`
3. **Copy-paste** konfigurasi ini:

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

4. **Jangan deploy dulu!** Generate APP_KEY terlebih dahulu (langkah 2)

#### Langkah 2: Generate APP_KEY

Jalankan di terminal VPS atau local (pastikan Docker terinstall):

```bash
docker run --rm ghcr.io/danprat/anggaran-desa:latest php artisan key:generate --show
```

Atau gunakan script helper:

```bash
./generate-key.sh
```

**Copy hasilnya** (contoh: `base64:xxxxxxxxxxxxxxxx`) dan **ganti** `APP_KEY=base64:GENERATE_KEY_DULU` di Portainer.

#### Langkah 3: Update & Deploy

1. Ganti `YOUR_VPS_IP` dengan IP VPS Anda
2. Ganti `APP_KEY` dengan hasil dari langkah 2
3. Klik **Deploy the stack**
4. Tunggu 2-3 menit

#### Langkah 4: Akses Aplikasi

```
http://YOUR_VPS_IP:8075
```

**Default login:**
- Email: admin@example.com
- Password: password

#### 📖 Dokumentasi Lengkap

Lihat [DEPLOY.md](./DEPLOY.md) atau [docs/portainer-deploy-guide.md](./docs/portainer-deploy-guide.md)

---

### 💻 Instalasi Manual (Development)
5. Akses aplikasi di `http://localhost:8075` atau `http://[IP-VPS]:8075`
6. Akses phpMyAdmin di `http://localhost:8076` atau `http://[IP-VPS]:8076`

**📝 Catatan untuk VPS ARM:**
- Image Docker sudah support ARM64/aarch64 secara native
- Build PHP extensions mungkin membutuhkan waktu lebih lama di ARM
- Pastikan VPS memiliki minimal 2GB RAM untuk proses build
- Jika terjadi timeout, coba tingkatkan timeout di Portainer settings

**Selesai!** 🎉

---

### 💻 Instalasi Manual (Tanpa Docker)

### Prasyarat
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Web Server (Apache/Nginx)

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone https://github.com/danprat/anggaran-desa.git
cd anggaran-desa
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Configuration**
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=anggaran_desa
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Database Migration & Seeding**
```bash
php artisan migrate
php artisan db:seed
```

6. **Storage Link**
```bash
php artisan storage:link
```

7. **Build Assets**
```bash
npm run build
```

8. **Start Development Server**
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 🔐 Default Login

Setelah seeding, gunakan kredensial berikut:

**Super Admin**
- Email: `admin@desa.id`
- Password: `password`

**Bendahara**
- Email: `bendahara@desa.id`
- Password: `password`

## 📁 Struktur Proyek

```
anggaran-desa/
├── app/
│   ├── Http/Controllers/     # Controllers
│   ├── Models/              # Eloquent Models
│   ├── Policies/            # Authorization Policies
│   └── Services/            # Business Logic Services
├── database/
│   ├── migrations/          # Database Migrations
│   ├── seeders/            # Database Seeders
│   └── factories/          # Model Factories
├── resources/
│   ├── views/              # Blade Templates
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript Files
├── public/
│   ├── images/             # Static Images
│   └── storage/            # Uploaded Files
├── docs/                   # Documentation
└── tests/                  # Unit & Feature Tests
```

## 🎨 UI/UX Features

- **Responsive Design**: Optimized untuk desktop, tablet, dan mobile
- **Village Branding**: Customizable dengan logo dan identitas desa
- **Clean Interface**: Design yang bersih dan user-friendly
- **Accessibility**: Mendukung standar aksesibilitas web

## 📚 Dokumentasi

Dokumentasi lengkap tersedia di folder `docs/`:
- [Manual Book](docs/manual-book.md) - Panduan penggunaan lengkap

## 👨‍💻 Developer

**Dany Pratmanto**
- 📱 WhatsApp: [08974041777](https://wa.me/6208974041777)
- 📧 Email: pratmanto@gmail.com

---

**© 2024 Sistem Anggaran Desa - Dibuat dengan ❤️ untuk kemajuan desa di Indonesia**
