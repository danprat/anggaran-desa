Berikut adalah dokumen **Struktur Folder Laravel & Stack Setup** untuk proyek *Aplikasi Anggaran Desa*, diformat dalam **Markdown**, mencakup arsitektur awal proyek, rekomendasi struktur folder modular, serta setup stack yang disarankan.

---

```markdown
# ⚙️ Dokumentasi Tech Stack & Struktur Folder
_Aplikasi Laravel Anggaran Desa_

Dokumen ini menjelaskan pilihan teknologi, struktur direktori Laravel, dan konfigurasi dasar untuk efisiensi pengembangan dan pemeliharaan jangka panjang.

---

## 🧰 A. Tech Stack

| Komponen         | Teknologi                          | Keterangan                                           |
|------------------|------------------------------------|------------------------------------------------------|
| Backend Framework| **Laravel 11.x**                   | Clean MVC, Migration, Routing, Artisan               |
| Frontend         | Laravel Blade + Tailwind CSS       | Ringan, cocok untuk admin panel                     |
| Auth System      | Laravel Breeze atau Fortify        | Otentikasi dasar & middleware                       |
| Database         | SQLite (dev), MySQL (prod)         | Ringan saat dev, performa baik untuk produksi        |
| Role & Permission| [Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission) | Role-based akses pengguna |
| Ekspor Laporan   | Laravel Excel, DomPDF / Snappy     | Excel + PDF untuk laporan                           |
| Notifikasi       | Laravel Notifications (in-app/email)| Reminder status kegiatan/realisasi                 |
| File Storage     | Laravel Filesystem (public/local)  | Untuk bukti realisasi                               |
| Log Audit        | Custom logging (DB/table)          | Log aktivitas user                                  |
| Optional Tools   | Livewire/Inertia.js (UI interaktif)| Jika butuh komponen dinamis                        |

---

## 🗂️ B. Struktur Folder Laravel (Direkomendasikan)

```

app/
├── Models/                  -> Semua model Eloquent (User, Kegiatan, Realisasi, ...)
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   ├── Auth/
│   │   ├── Kegiatan/
│   │   ├── Realisasi/
│   │   └── Laporan/
│   ├── Middleware/
│   └── Requests/            -> Form validation logic
├── Services/                -> (opsional) logic helper khusus (contoh: PDFService.php)
├── Traits/                  -> reusable logic trait
resources/
├── views/
│   ├── layouts/             -> blade layout dasar (header/sidebar)
│   ├── dashboard/
│   ├── kegiatan/
│   ├── realisasi/
│   ├── laporan/
│   └── auth/                -> login/register blade
├── js/                      -> Jika pakai Livewire/Inertia
routes/
├── web.php                  -> Routing utama web
├── admin.php                -> Group route dengan prefix & middleware
database/
├── migrations/              -> Struktur tabel
├── seeders/                 -> Data awal: roles, user dummy
├── factories/               -> (opsional) untuk test
config/
├── permission.php           -> Dari Spatie
├── filesystems.php          -> Setting penyimpanan
public/
├── uploads/                 -> folder file bukti realisasi (disimpan via symlink)

```

---

## 📋 C. Konvensi Penamaan

| Entity        | Route prefix | Controller Folder | View Folder   |
|---------------|--------------|-------------------|---------------|
| Kegiatan      | `/kegiatan`  | `Kegiatan/`       | `kegiatan/`   |
| Realisasi     | `/realisasi` | `Realisasi/`      | `realisasi/`  |
| Laporan       | `/laporan`   | `Laporan/`        | `laporan/`    |
| Manajemen User| `/pengguna`  | `Admin/`          | `admin/`      |

---

## 🔐 Middleware & Role Protection

- Buat middleware `role:<nama_role>` untuk setiap role utama.
- Gunakan `Route::middleware(['auth', 'role:kepala-desa'])->group(...)` untuk akses terbatas.
- Gunakan policy dan gate untuk proteksi granular (edit hanya jika status = draft).

---

## 🔧 Environment Setup

### .env Contoh (Development)

```

APP\_NAME="AnggaranDesa"
APP\_ENV=local
APP\_DEBUG=true
APP\_URL=[http://localhost](http://localhost)

DB\_CONNECTION=sqlite
DB\_DATABASE=database/database.sqlite

FILESYSTEM\_DISK=public

MAIL\_MAILER=log

```

### .env Contoh (Production)

```

DB\_CONNECTION=mysql
DB\_HOST=127.0.0.1
DB\_PORT=3306
DB\_DATABASE=anggaran\_desa
DB\_USERNAME=root
DB\_PASSWORD=secret

MAIL\_MAILER=smtp
MAIL\_HOST=smtp.mailgun.org
MAIL\_PORT=587
MAIL\_USERNAME=...

```

---

## 🧪 Testing & Debugging

- Gunakan Laravel Telescope untuk dev debugging
- PHPUnit / Pest untuk testing logika jika diperlukan
- Laravel Seed untuk generate data dummy

---

## 📦 Deployment Plan (Garis Besar)

1. VPS atau Shared Hosting dengan PHP 8.2+
2. Setup `.env`, permission folder (`storage`, `bootstrap/cache`)
3. Jalankan `php artisan migrate --seed`
4. Jalankan `php artisan storage:link`
5. Setup cronjob untuk queue (opsional)
6. SSL + domain

---