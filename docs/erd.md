# 🔶 Entity Relationship Diagram (ERD)
_Aplikasi Laravel Anggaran Desa_

Dokumen ini mendeskripsikan entitas dan relasinya dalam aplikasi, sebagai dasar struktur database dan implementasi Laravel Eloquent ORM.

---

## 🧱 TABEL UTAMA & RELASI

### 1. `users`
Menyimpan data pengguna dan relasinya ke role.

| Field           | Tipe         | Keterangan               |
|----------------|--------------|--------------------------|
| id             | bigint (PK)  |                          |
| name           | string       | Nama lengkap             |
| email          | string       | Unik, digunakan login    |
| password       | string       | Hashed password          |
| role_id        | FK → roles   | Role pengguna            |
| created_at     | timestamp    |                          |
| updated_at     | timestamp    |                          |

---

### 2. `roles`
Daftar jenis peran pengguna.

| Field         | Tipe         | Keterangan               |
|---------------|--------------|--------------------------|
| id            | bigint (PK)  |                          |
| name          | string       | e.g., admin, operator, auditor |
| created_at    | timestamp    |                          |
| updated_at    | timestamp    |                          |

🔗 Relasi:
- `roles` → `users`: one-to-many

---

### 3. `tahun_anggaran`
Tahun anggaran aktif (2023, 2024, dst).

| Field         | Tipe         | Keterangan       |
|---------------|--------------|------------------|
| id            | bigint (PK)  |                  |
| tahun         | year / int   | e.g., 2025       |
| status        | enum         | aktif/nonaktif   |

---

### 4. `kegiatan`
Daftar rencana kegiatan dalam satu tahun anggaran.

| Field             | Tipe            | Keterangan                       |
|------------------|-----------------|----------------------------------|
| id               | bigint (PK)     |                                  |
| tahun_id         | FK → tahun_anggaran |                              |
| bidang           | string          | e.g., pembangunan, pemberdayaan |
| nama_kegiatan    | string          |                                  |
| pagu_anggaran    | decimal(15,2)   |                                  |
| sumber_dana      | string          | DD, ADD, PADes, dll              |
| waktu_mulai      | date            |                                  |
| waktu_selesai    | date            |                                  |
| status           | enum            | draft, verifikasi, disetujui     |
| dibuat_oleh      | FK → users      | user_id dari operator            |
| created_at       | timestamp       |                                  |
| updated_at       | timestamp       |                                  |

🔗 Relasi:
- `kegiatan` → `tahun_anggaran`: many-to-one
- `kegiatan` → `users`: many-to-one (dibuat_oleh)

---

### 5. `realisasi`
Realisasi anggaran per kegiatan.

| Field             | Tipe            | Keterangan                         |
|------------------|-----------------|------------------------------------|
| id               | bigint (PK)     |                                    |
| kegiatan_id      | FK → kegiatan   |                                    |
| jumlah_realisasi | decimal(15,2)   |                                    |
| tanggal          | date            |                                    |
| deskripsi        | text (nullable) | Opsional keterangan                |
| status           | enum            | belum, sebagian, selesai           |
| dibuat_oleh      | FK → users      |                                    |
| created_at       | timestamp       |                                    |
| updated_at       | timestamp       |                                    |

---

### 6. `bukti_files`
Bukti nota, foto, PDF terkait realisasi.

| Field         | Tipe          | Keterangan                          |
|---------------|---------------|-------------------------------------|
| id            | bigint (PK)   |                                     |
| realisasi_id  | FK → realisasi|                                     |
| file_path     | string        | Lokasi file di storage              |
| file_type     | string        | pdf, jpg, png, dll                  |
| uploaded_by   | FK → users    |                                     |
| created_at    | timestamp     |                                     |

🔗 Relasi:
- `realisasi` → `bukti_files`: one-to-many

---

### 7. `log_aktivitas`
Audit trail aktivitas pengguna.

| Field         | Tipe          | Keterangan                     |
|---------------|---------------|--------------------------------|
| id            | bigint (PK)   |                                |
| user_id       | FK → users    |                                |
| aktivitas     | text          | Deskripsi aktivitas            |
| tabel_terkait | string        | Optional: mis. `kegiatan`      |
| row_id        | bigint        | Optional: id baris terkait     |
| created_at    | timestamp     |                                |

---

## 🔁 RELASI ANTAR ENTITAS (RINGKASAN)

- `users` ↔ `roles`: many-to-one
- `users` ↔ `kegiatan`: one-to-many (dibuat_oleh)
- `tahun_anggaran` ↔ `kegiatan`: one-to-many
- `kegiatan` ↔ `realisasi`: one-to-many
- `realisasi` ↔ `bukti_files`: one-to-many
- `users` ↔ `log_aktivitas`: one-to-many

---

## 🧩 CATATAN IMPLEMENTASI DI LARAVEL

- Gunakan migration dan foreign key constraint di setiap relasi.
- Gunakan Eloquent Relationship:
  - `hasMany`, `belongsTo`, `hasOne`, `morphMany` jika nanti perlu polymorphic file
- Gunakan UUID untuk tabel sensitif (opsional)
- Gunakan `softDeletes` untuk `kegiatan`, `realisasi`, dan `users` jika dibutuhkan

---

> ✅ Diagram ini bisa dibuat visualisasi menggunakan tools seperti [dbdiagram.io](https://dbdiagram.io), MySQL Workbench, atau Laravel Model Generator.
