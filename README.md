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
- 📧 Email: dany@example.com

---

**© 2024 Sistem Anggaran Desa - Dibuat dengan ❤️ untuk kemajuan desa di Indonesia**
