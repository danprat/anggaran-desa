Berikut adalah gabungan dokumen **Functional Flow + Role Mapping** untuk aplikasi *Anggaran Desa*, disusun dalam format **Markdown** agar mudah dibaca dan diintegrasikan ke dokumentasi proyek.

---

```markdown
# 🔁 Functional Flow + Role Mapping
_Aplikasi Laravel Anggaran Desa_

Dokumen ini menjelaskan alur proses antar fitur utama (Functional Flow) dan distribusi peran pengguna terhadap masing-masing modul (Role Mapping).

---

## 🧭 A. Functional Flow Diagram (Deskriptif)

### 1. ✍️ Perencanaan Anggaran

**Flow:**
```

\[Operator] → input kegiatan
↓
\[Sekretaris] → verifikasi kegiatan
↓
\[Kepala Desa] → menyetujui / menolak
↓
\[Status kegiatan] = disetujui → siap direalisasikan

```

### 2. 💵 Realisasi Kegiatan

**Flow:**
```

\[Bendahara] → input realisasi kegiatan + upload bukti
↓
\[Status realisasi] → otomatis terupdate (belum / sebagian / selesai)
↓
\[Laporan SPJ & APBDes] → otomatis diperbarui

```

### 3. 📤 Pelaporan

**Flow:**
```

\[Sistem] → menghitung total anggaran & realisasi
↓
\[Auditor / Kepala Desa / Sekretaris] → akses laporan
↓
\[Export] → PDF / Excel

```

### 4. 🔔 Notifikasi

**Flow:**
```

\[Trigger Sistem] jika:

* kegiatan belum disetujui 7 hari setelah input
* realisasi belum diinput setelah tanggal selesai
  ↓
  \[Sistem] → kirim notifikasi ke peran terkait

```

### 5. 🧾 Audit Trail

**Flow:**
```

Setiap tindakan (input, edit, delete, approve) → tercatat di log aktivitas
↓
\[Auditor/Admin] → akses daftar log berdasarkan filter user & tanggal

```

---

## 👥 B. Role Mapping Matrix

| Modul / Fitur             | Admin | Operator | Sekretaris | Kepala Desa | Bendahara | Auditor |
|---------------------------|:-----:|:--------:|:----------:|:-----------:|:---------:|:-------:|
| Login & Dashboard         | ✔     | ✔        | ✔          | ✔           | ✔         | ✔       |
| Manajemen User            | ✔     | ❌       | ❌         | ❌          | ❌        | ❌      |
| Input Kegiatan            | ❌    | ✔        | ❌         | ❌          | ❌        | ❌      |
| Edit / Hapus Kegiatan     | ❌    | ✔        | ✔*         | ❌          | ❌        | ❌      |
| Verifikasi Kegiatan       | ❌    | ❌       | ✔          | ❌          | ❌        | ❌      |
| Persetujuan Kegiatan      | ❌    | ❌       | ❌         | ✔           | ❌        | ❌      |
| Input Realisasi           | ❌    | ❌       | ❌         | ❌          | ✔         | ❌      |
| Upload Bukti              | ❌    | ❌       | ❌         | ❌          | ✔         | ❌      |
| Lihat Laporan             | ✔     | ✔        | ✔          | ✔           | ✔         | ✔       |
| Ekspor PDF / Excel        | ✔     | ✔        | ✔          | ✔           | ✔         | ✔       |
| Log Aktivitas             | ✔     | ❌       | ❌         | ❌          | ❌        | ✔       |
| Ganti Tahun Anggaran      | ✔     | ❌       | ❌         | ❌          | ❌        | ❌      |

**Catatan:**
- ✔ = akses penuh
- ✔* = akses edit hanya saat status kegiatan belum diverifikasi
- ❌ = tidak memiliki akses

---

## 📌 Catatan Tambahan

- Validasi alur multi-level sangat penting: Operator → Sekretaris → Kepala Desa
- Auditor memiliki akses **read-only** untuk semua data + log
- Admin hanya mengelola sistem dan user, tidak ikut dalam alur kegiatan
- Fitur log aktivitas wajib mencatat semua aktivitas penting: siapa, kapan, dan apa yang diubah

---

> Dokumen ini menjadi referensi penting saat implementasi middleware `role:xxx`, menu dinamis, dan validasi level akses di controller.
```

---

Jika kamu ingin, saya bisa bantu lanjut ke:

* 📁 Dokumentasi Struktur Folder Laravel & Stack Setup
* ✅ Contoh Test Case / Acceptance Criteria
* 🧪 Contoh implementasi fitur (migration, seeder, atau controller awal)

