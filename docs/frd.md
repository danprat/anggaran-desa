# 📗 Functional Requirement Document (FRD)
_Aplikasi Laravel Anggaran Desa_

---

## 1. Tujuan Dokumen

Dokumen ini menjabarkan kebutuhan fungsional sistem aplikasi Anggaran Desa untuk memastikan pengembangan sesuai dengan proses bisnis dan kebutuhan pengguna. FRD ini menjadi dasar teknis untuk pengembangan modul dan fitur sistem.

---

## 2. Ruang Lingkup Sistem

Aplikasi ini akan mencakup fitur perencanaan anggaran, input realisasi, pengunggahan bukti, alur validasi multi-level, serta pelaporan anggaran desa berbasis tahunan. Sistem akan diakses oleh berbagai role dengan hak akses yang berbeda-beda.

---

## 3. Daftar Fitur Utama

### 3.1 Manajemen Pengguna & Akses
- **CRUD pengguna** (oleh admin)
- **Role & permission system**:
  - Admin
  - Kepala Desa
  - Sekretaris
  - Bendahara
  - Operator Desa
  - Auditor

### 3.2 Rencana Anggaran (Input Kegiatan)
- Input kegiatan per tahun anggaran
  - Bidang (pembangunan, pemberdayaan, dll)
  - Nama kegiatan
  - Pagu anggaran
  - Sumber dana
  - Waktu pelaksanaan (mulai–selesai)
- Status kegiatan:
  - Draft → Diverifikasi → Disetujui → Direalisasikan
- Fitur duplikat kegiatan untuk tahun berikutnya

### 3.3 Verifikasi & Persetujuan
- Sekretaris melakukan **verifikasi kegiatan** yang diinput oleh operator
- Kepala Desa dapat **menyetujui atau menolak** rencana anggaran
- Log keputusan dengan timestamp & user

### 3.4 Realisasi Anggaran
- Input nilai realisasi kegiatan
- Status realisasi: belum, sebagian, selesai
- Upload bukti realisasi (nota, foto, file PDF)
- Validasi nominal tidak boleh melebihi pagu
- Fitur multi-upload bukti

### 3.5 Laporan & Ekspor
- **Laporan per kegiatan**: Rencana vs Realisasi
- **Laporan tahunan**: RAPBDes, APBDes, SPJ
- Filter laporan per tahun, bidang, atau status
- Ekspor laporan ke PDF dan Excel
- Preview laporan dalam aplikasi

### 3.6 Notifikasi & Reminder
- Notifikasi otomatis:
  - “Kegiatan belum disetujui oleh Kepala Desa”
  - “Realisasi belum diinput setelah waktu pelaksanaan berakhir”
- Email atau in-app alert

### 3.7 Log Aktivitas (Audit Trail)
- Catat semua aksi user (login, input, edit, delete, approve)
- Riwayat bisa ditampilkan & dicetak untuk auditor

---

## 4. Use Case per Role

| Role            | Akses                                                                 |
|-----------------|----------------------------------------------------------------------|
| Admin           | CRUD user, konfigurasi sistem, pengaturan role                        |
| Operator Desa   | Input rencana kegiatan, upload bukti realisasi                        |
| Sekretaris      | Verifikasi rencana dan realisasi                                      |
| Kepala Desa     | Persetujuan rencana, melihat laporan                                  |
| Bendahara       | Input realisasi keuangan, upload bukti                                |
| Auditor         | Akses hanya baca seluruh data & log                                   |

---

## 5. Aturan Validasi

- Tidak boleh ada kegiatan tanpa sumber dana.
- Realisasi tidak boleh melebihi pagu anggaran.
- Hanya kegiatan yang sudah disetujui yang dapat direalisasikan.
- Nama kegiatan dalam satu tahun tidak boleh duplikat.
- Upload bukti minimal 1 file jika realisasi sudah 100%.

---

## 6. Integrasi Eksternal (Opsional / Future)
- Integrasi ke sistem pelaporan nasional (SIPD, SIMDA)
- Integrasi ke email gateway (untuk notifikasi keluar)

---

## 7. Persyaratan Keamanan

- Autentikasi berbasis email dan password
- Enkripsi file upload dan dokumen penting
- Pembatasan akses berdasarkan role
- CSRF protection dan validasi server-side

---

## 8. Mockup Referensi / Wireframe
- Form input kegiatan (multi-tab)
- Tabel realisasi anggaran
- Dashboard grafik perbandingan
- Laporan PDF Preview + tombol ekspor

(*Detail akan dijabarkan dalam wireframe terpisah*)

---

## 9. Ketergantungan (Dependency)
- Laravel 11.x / 10.x
- Spatie Laravel-Permission
- Laravel Excel (untuk .xls)
- DomPDF / SnappyPDF
- Tailwind CSS + Livewire/Inertia

---

## 10. Penutup

Dokumen ini menjadi acuan utama untuk pengembangan sistem. Perubahan kebutuhan fungsional akan dikelola melalui proses revisi terkontrol.

> Revisi #1 – Tanggal: [isi saat mulai development]
