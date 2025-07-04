# 🧩 Wireframe Deskriptif
_Aplikasi Laravel Anggaran Desa_

Wireframe ini mendeskripsikan tampilan dan layout halaman-halaman penting secara tekstual. Visualisasi ini bertujuan sebagai acuan awal dalam desain antarmuka pengguna.

---

## 1. 🏠 Dashboard (semua role)

**Tujuan:** Menampilkan ringkasan data utama dan akses cepat ke fitur.

**Layout:**

- [Sidebar Menu]
  - Dashboard
  - Kegiatan
  - Realisasi
  - Laporan
  - Pengguna (Admin saja)
  - Log Aktivitas
  - Logout

- [Topbar Header]
  - Nama user & role
  - Tahun Anggaran Aktif (dropdown)

- [Konten Tengah - Card Statistik]
  - 🔹 Total Kegiatan
  - 🔹 Total Realisasi (% vs pagu)
  - 🔹 Kegiatan Belum Disetujui
  - 🔹 Total Bukti Diunggah

- [Grafik Ringkasan]
  - Bar chart: Anggaran vs Realisasi per bidang
  - Pie chart: Distribusi kegiatan per bidang

---

## 2. 📋 Halaman Data Kegiatan

**Tujuan:** Melihat, menambah, dan mengelola rencana kegiatan per tahun.

**Komponen:**
- [Tombol Tambah Kegiatan] (+)
- [Filter dropdown]: Tahun, Bidang, Status
- [Tabel Daftar Kegiatan]
  - Kolom: Nama, Bidang, Pagu, Status, Waktu, Aksi (✏️🗑️👁️)
- [Aksi Bulk Export] (PDF/Excel)
- Warna label status: Draft, Verifikasi, Disetujui (dengan badge)

---

## 3. 📝 Form Tambah / Edit Kegiatan

**Tujuan:** Input rencana kegiatan.

**Field:**
- Nama Kegiatan (input text)
- Bidang (dropdown)
- Sumber Dana (dropdown)
- Pagu Anggaran (number)
- Waktu Mulai & Selesai (datepicker)
- Keterangan Tambahan (textarea)
- [Tombol Simpan / Batal]

---

## 4. 💰 Halaman Realisasi Kegiatan

**Tujuan:** Melihat dan mencatat realisasi dana dari kegiatan yang disetujui.

**Komponen:**
- Filter: Tahun, Status, Sudah / Belum Realisasi
- [Tabel Realisasi]
  - Kolom: Kegiatan, Pagu, Realisasi, Sisa, Status, Aksi (👁️💾📎)
- Status progres: bar progres visual (realisasi / pagu)

---

## 5. 📤 Upload Bukti Realisasi

**Tujuan:** Unggah nota/foto bukti penggunaan dana.

**Komponen:**
- [List file yang sudah diunggah]
- [Drag & Drop area / Button Upload]
- Catatan: "Minimal 1 bukti untuk kegiatan selesai"
- Validasi file type: PDF, JPG, PNG

---

## 6. 📑 Halaman Laporan

**Tujuan:** Lihat dan ekspor laporan keuangan dan kegiatan.

**Fitur:**
- Filter tahun, bidang, status kegiatan
- Jenis laporan:
  - APBDes
  - RAPBDes
  - SPJ
- [Preview laporan (embed PDF)]
- Tombol Ekspor: 🖨️ PDF / 📥 Excel

---

## 7. 🔐 Halaman Login

**Komponen:**
- Email
- Password
- Tombol Login
- (Opsional) Lupa Password

---

## 8. 🛠️ Halaman Manajemen User (admin)

**Komponen:**
- Tambah User
- Daftar User
  - Nama, Email, Role, Aktif/Nonaktif
  - Tombol Reset Password / Hapus

---

## 9. 📜 Log Aktivitas

**Tujuan:** Menampilkan semua aktivitas pengguna dalam sistem.

**Tabel:**
- Tanggal
- User
- Aktivitas (e.g. “Operator menambah kegiatan ‘Pelatihan UMKM’”)
- Tabel Terkait (kegiatan, realisasi)
- ID Referensi

---

> 📌 *Catatan:* Semua halaman berbasis layout Laravel Blade + Tailwind CSS. Gunakan layout responsive (mobile-friendly) jika memungkinkan.

