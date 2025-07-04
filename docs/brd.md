# 📘 Business Requirement Document (BRD)
_Aplikasi Laravel Anggaran Desa_

---

## 1. Latar Belakang

Pemerintah desa merupakan ujung tombak dalam penyelenggaraan pemerintahan yang langsung bersentuhan dengan masyarakat. Dalam mengelola dana desa, sering kali ditemukan permasalahan seperti kurangnya transparansi, pencatatan manual, keterlambatan laporan, dan kesulitan dalam proses audit.

Aplikasi ini dirancang untuk membantu pemerintah desa dalam mengelola perencanaan, pelaksanaan, dan pelaporan anggaran secara digital, sistematis, dan transparan. Proyek ini bertujuan mendukung akuntabilitas dan efisiensi pengelolaan Dana Desa (DD), Alokasi Dana Desa (ADD), dan sumber-sumber pendanaan lainnya.

---

## 2. Tujuan Proyek

- Menyediakan platform digital untuk manajemen anggaran desa dari tahap perencanaan hingga pelaporan.
- Memastikan transparansi dan akuntabilitas penggunaan anggaran desa.
- Mempermudah proses monitoring dan evaluasi realisasi anggaran.
- Memfasilitasi pembuatan laporan resmi (SPJ, RAPBDes, dll).
- Mendukung proses validasi dan persetujuan berjenjang antar perangkat desa.

---

## 3. Ruang Lingkup

### Termasuk:
- Perencanaan anggaran kegiatan tahunan desa.
- Input realisasi kegiatan dan keuangan.
- Upload bukti fisik (nota, foto kegiatan).
- Persetujuan dan validasi oleh Sekretaris dan Kepala Desa.
- Pelaporan realisasi anggaran.
- Manajemen pengguna dan peran.
- Audit trail / log aktivitas.
- Notifikasi dan pengingat deadline.

### Tidak Termasuk (untuk versi awal):
- Integrasi otomatis dengan sistem pemerintah pusat (SIPD/SIMDA).
- Modul pengadaan barang/jasa.
- Akses publik untuk warga desa (opsional versi lanjutan).

---

## 4. Pemangku Kepentingan (Stakeholders)

| Peran              | Deskripsi                                                                 |
|--------------------|---------------------------------------------------------------------------|
| Kepala Desa        | Pengambil keputusan dan pemberi persetujuan akhir atas anggaran.         |
| Sekretaris Desa    | Bertugas memverifikasi dan mengelola proses anggaran.                    |
| Bendahara Desa     | Bertugas mencatat realisasi keuangan dan mengunggah bukti.               |
| Operator Desa      | Petugas entry data awal dan manajemen kegiatan harian.                   |
| Auditor (Inspektorat)| Melakukan review atas pelaporan dan realisasi anggaran.               |
| Admin Sistem       | Pengelola sistem dan user management.                                     |

---

## 5. Kebutuhan Utama Pengguna (High-Level User Needs)

- **Operator Desa**: Bisa menambahkan, mengedit, dan menghapus rencana kegiatan.
- **Sekretaris**: Bisa melihat, memverifikasi, dan mengirimkan ke Kepala Desa.
- **Kepala Desa**: Bisa menyetujui atau menolak rencana kegiatan.
- **Bendahara**: Bisa mencatat realisasi dan mengunggah bukti.
- **Auditor**: Bisa mengakses laporan realisasi dan anggaran.
- **Admin**: Bisa mengelola akun pengguna dan pengaturan sistem.

---

## 6. Kebutuhan Regulasi (Opsional)

- Mematuhi prinsip transparansi dan akuntabilitas sesuai Permendagri No. 20 Tahun 2018 tentang Pengelolaan Keuangan Desa.
- Menyesuaikan struktur laporan dengan format APBDes dan SPJ standar.

---

## 7. Risiko Utama

| Risiko                         | Dampak                         | Mitigasi                                      |
|-------------------------------|--------------------------------|-----------------------------------------------|
| Ketidaksesuaian fitur         | Proyek tidak digunakan         | Validasi dengan perangkat desa sebelum dev    |
| Keterbatasan jaringan internet| Akses lambat / gagal           | Tambahkan mode offline atau caching ringan    |
| Ketidaksiapan pengguna        | Tidak optimal digunakan        | Sertakan pelatihan dan panduan penggunaan     |

---

## 8. Indikator Keberhasilan

- Seluruh kegiatan dan realisasi anggaran tahun berjalan tercatat di sistem.
- Laporan SPJ dan RAPBDes dapat dihasilkan secara otomatis.
- Peningkatan efisiensi waktu pembuatan laporan ≥ 50%.
- Sistem digunakan secara aktif oleh minimal 3 peran utama.

---

> 📌 Dokumen ini akan menjadi dasar pembuatan FRD, ERD, dan pengembangan fitur sistem.
