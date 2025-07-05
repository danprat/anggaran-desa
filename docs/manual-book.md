# 📖 Manual Book - Sistem Anggaran Desa

Panduan lengkap penggunaan Sistem Anggaran Desa untuk pemerintah desa.

## 📑 Daftar Isi

1. [Pendahuluan](#pendahuluan)
2. [Login dan Akses Sistem](#login-dan-akses-sistem)
3. [Dashboard](#dashboard)
4. [Manajemen Profil Desa](#manajemen-profil-desa)
5. [Manajemen Anggaran](#manajemen-anggaran)
6. [Manajemen Kegiatan](#manajemen-kegiatan)
7. [Realisasi Anggaran](#realisasi-anggaran)
8. [Laporan](#laporan)
9. [Manajemen User](#manajemen-user)
10. [Tips dan Troubleshooting](#tips-dan-troubleshooting)

---

## 1. Pendahuluan

### Tentang Sistem
Sistem Anggaran Desa adalah aplikasi web yang dirancang untuk membantu pemerintah desa dalam mengelola keuangan dan anggaran secara digital, transparan, dan akuntabel.

### Tujuan Sistem
- Meningkatkan transparansi pengelolaan keuangan desa
- Mempermudah perencanaan dan monitoring anggaran
- Mengotomatisasi proses pelaporan keuangan
- Mendukung akuntabilitas pemerintah desa

### Role dan Permission
- **Super Admin**: Akses penuh ke semua fitur
- **Admin**: Manajemen data dan laporan
- **Bendahara**: Fokus pada keuangan dan realisasi
- **Viewer**: Hanya dapat melihat data

---

## 2. Login dan Akses Sistem

### Cara Login
1. Buka browser dan akses URL sistem
2. Masukkan email dan password
3. Klik tombol "Log in"
4. Sistem akan mengarahkan ke dashboard sesuai role

### Default Account
Setelah instalasi, gunakan akun berikut:
- **Super Admin**: admin@desa.id / password
- **Bendahara**: bendahara@desa.id / password

### Lupa Password
1. Klik "Forgot your password?" di halaman login
2. Masukkan email yang terdaftar
3. Cek email untuk link reset password
4. Ikuti instruksi untuk membuat password baru

---

## 3. Dashboard

### Overview Dashboard
Dashboard menampilkan ringkasan informasi penting:
- **Statistik Anggaran**: Total anggaran, realisasi, sisa anggaran
- **Progress Kegiatan**: Status kegiatan yang sedang berjalan
- **Grafik Keuangan**: Visualisasi data keuangan
- **Aktivitas Terbaru**: Log aktivitas sistem

### Navigasi
- **Sidebar**: Menu utama untuk navigasi antar fitur
- **Header**: Informasi user dan notifikasi
- **Breadcrumb**: Menunjukkan lokasi halaman saat ini

---

## 4. Manajemen Profil Desa

### 4.1 Informasi Dasar
Kelola data dasar desa:
- **Nama Desa**: Nama resmi desa
- **Alamat**: Alamat lengkap kantor desa
- **Kontak**: Telepon, email, website desa
- **Kode Pos**: Kode pos wilayah desa

**Cara Mengisi:**
1. Masuk ke menu "Profil Desa" → "Informasi Dasar"
2. Isi semua field yang tersedia
3. Klik "💾 Simpan Informasi Dasar"

### 4.2 Kepemimpinan
Data kepala desa dan periode jabatan:
- **Nama Kepala Desa**: Nama lengkap kepala desa
- **NIP**: Nomor Induk Pegawai (jika ada)
- **Periode Jabatan**: Tanggal mulai dan selesai jabatan

### 4.3 Visi & Misi
Dokumentasi visi, misi, dan sejarah desa:
- **Visi**: Visi desa untuk masa depan
- **Misi**: Misi dan program kerja
- **Sejarah**: Sejarah singkat desa

### 4.4 Data Demografis
Statistik penduduk:
- **Jumlah Penduduk**: Total penduduk desa
- **Jumlah KK**: Jumlah kepala keluarga
- **Luas Wilayah**: Luas wilayah dalam hektar

### 4.5 Geografis
Informasi batas wilayah:
- **Batas Utara**: Wilayah yang berbatasan di utara
- **Batas Selatan**: Wilayah yang berbatasan di selatan
- **Batas Timur**: Wilayah yang berbatasan di timur
- **Batas Barat**: Wilayah yang berbatasan di barat

### 4.6 Logo & Media
Upload dan kelola logo:
- **Logo Desa**: Logo resmi desa (wajib)
- **Logo Kabupaten**: Logo kabupaten (opsional)
- **Logo Provinsi**: Logo provinsi (opsional)

**Format yang Didukung:**
- JPG, PNG, SVG
- Maksimal 2MB per file

**Cara Upload:**
1. Klik tab "🏛️ Logo Desa"
2. Pilih file logo yang akan diupload
3. Preview akan muncul otomatis
4. Klik "💾 Simpan Logo Desa"

---

## 5. Manajemen Anggaran

### 5.1 Perencanaan Anggaran
Buat rencana anggaran tahunan:
- **Tahun Anggaran**: Pilih tahun anggaran
- **Bidang**: Kategorisasi berdasarkan bidang
- **Program**: Program kerja dalam bidang
- **Kegiatan**: Detail kegiatan dalam program
- **Anggaran**: Jumlah anggaran yang dialokasikan

### 5.2 Kategori Anggaran
Sistem menggunakan kategori standar:
- **Pendapatan**: Sumber pendapatan desa
- **Belanja**: Pengeluaran desa
- **Pembiayaan**: Pembiayaan dan investasi

### 5.3 Approval Workflow
Proses persetujuan anggaran:
1. **Draft**: Anggaran dalam tahap penyusunan
2. **Review**: Anggaran dalam tahap review
3. **Approved**: Anggaran telah disetujui
4. **Rejected**: Anggaran ditolak (perlu revisi)

---

## 6. Manajemen Kegiatan

### 6.1 Membuat Kegiatan Baru
1. Masuk ke menu "Kegiatan"
2. Klik tombol "Tambah Kegiatan"
3. Isi informasi kegiatan:
   - **Nama Kegiatan**: Nama kegiatan yang jelas
   - **Deskripsi**: Penjelasan detail kegiatan
   - **Tanggal**: Jadwal pelaksanaan
   - **Anggaran**: Alokasi anggaran
   - **Penanggung Jawab**: PIC kegiatan
4. Klik "Simpan"

### 6.2 Status Kegiatan
- **Planned**: Kegiatan dalam tahap perencanaan
- **In Progress**: Kegiatan sedang berjalan
- **Completed**: Kegiatan telah selesai
- **Cancelled**: Kegiatan dibatalkan

### 6.3 Monitoring Kegiatan
- **Progress Bar**: Visualisasi progress kegiatan
- **Timeline**: Jadwal dan milestone
- **Budget Tracking**: Monitoring penggunaan anggaran
- **Documents**: Upload dokumen pendukung

---

## 7. Realisasi Anggaran

### 7.1 Input Realisasi
Catat realisasi penggunaan anggaran:
1. Pilih kegiatan yang akan direalisasi
2. Input jumlah realisasi
3. Upload bukti transaksi
4. Tambahkan keterangan
5. Submit untuk approval

### 7.2 Verifikasi Realisasi
Proses verifikasi oleh bendahara:
1. Review data realisasi
2. Periksa dokumen pendukung
3. Approve atau reject realisasi
4. Berikan catatan jika diperlukan

### 7.3 Laporan Realisasi
- **Realisasi vs Target**: Perbandingan realisasi dengan target
- **Variance Analysis**: Analisis selisih anggaran
- **Trend Analysis**: Tren penggunaan anggaran

---

## 8. Laporan

### 8.1 Jenis Laporan
- **Laporan Anggaran**: Ringkasan anggaran per bidang
- **Laporan Realisasi**: Detail realisasi anggaran
- **Laporan Kegiatan**: Progress dan status kegiatan
- **Laporan Keuangan**: Laporan keuangan komprehensif

### 8.2 Export Laporan
Format export yang tersedia:
- **PDF**: Untuk dokumen formal
- **Excel**: Untuk analisis data
- **CSV**: Untuk import ke sistem lain

### 8.3 Penjadwalan Laporan
- **Harian**: Laporan aktivitas harian
- **Mingguan**: Ringkasan mingguan
- **Bulanan**: Laporan bulanan lengkap
- **Tahunan**: Laporan tahunan komprehensif

---

## 9. Manajemen User

### 9.1 Menambah User Baru
1. Masuk ke menu "Manajemen User"
2. Klik "Tambah User"
3. Isi data user:
   - **Nama**: Nama lengkap user
   - **Email**: Email untuk login
   - **Role**: Pilih role yang sesuai
   - **Password**: Password sementara
4. Klik "Simpan"

### 9.2 Mengelola Permission
- **View**: Hak untuk melihat data
- **Create**: Hak untuk membuat data baru
- **Edit**: Hak untuk mengubah data
- **Delete**: Hak untuk menghapus data
- **Approve**: Hak untuk menyetujui

### 9.3 Log Aktivitas
Monitor aktivitas user:
- **Login/Logout**: Riwayat akses sistem
- **Data Changes**: Perubahan data yang dilakukan
- **Approval Actions**: Aktivitas approval
- **Export Activities**: Aktivitas export data

---

## 10. Tips dan Troubleshooting

### 10.1 Tips Penggunaan
- **Backup Data**: Lakukan backup data secara berkala
- **Update Browser**: Gunakan browser terbaru untuk performa optimal
- **Strong Password**: Gunakan password yang kuat
- **Regular Review**: Review data secara berkala

### 10.2 Troubleshooting Umum

**Problem: Tidak bisa login**
- Periksa email dan password
- Pastikan akun aktif
- Clear browser cache
- Hubungi administrator

**Problem: Upload file gagal**
- Periksa ukuran file (max 2MB)
- Pastikan format file didukung
- Periksa koneksi internet
- Coba browser lain

**Problem: Laporan tidak muncul**
- Periksa filter tanggal
- Pastikan ada data untuk periode tersebut
- Refresh halaman
- Logout dan login kembali

### 10.3 Kontak Support
Jika mengalami masalah teknis:
- **Developer**: Dany Pratmanto
- **WhatsApp**: [08974041777](https://wa.me/6208974041777)
- **Email**: dany@example.com

---

**© 2024 Sistem Anggaran Desa - Manual Book v1.0**
