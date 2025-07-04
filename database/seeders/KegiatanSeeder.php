<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kegiatan;
use App\Models\TahunAnggaran;
use App\Models\User;

class KegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunAktif = TahunAnggaran::where('status', 'aktif')->first();
        $operator = User::role('operator')->first();

        if (!$tahunAktif || !$operator) {
            return;
        }

        $kegiatanList = [
            [
                'nama_kegiatan' => 'Pembangunan Jalan Desa RT 01',
                'bidang' => 'Pembangunan',
                'pagu_anggaran' => 150000000,
                'sumber_dana' => 'DD (Dana Desa)',
                'waktu_mulai' => '2025-02-01',
                'waktu_selesai' => '2025-04-30',
                'status' => 'disetujui',
                'keterangan' => 'Pembangunan jalan sepanjang 500 meter dengan lebar 3 meter menggunakan material beton.'
            ],
            [
                'nama_kegiatan' => 'Pelatihan UMKM Ibu-ibu PKK',
                'bidang' => 'Pemberdayaan Masyarakat',
                'pagu_anggaran' => 25000000,
                'sumber_dana' => 'ADD (Alokasi Dana Desa)',
                'waktu_mulai' => '2025-03-15',
                'waktu_selesai' => '2025-03-17',
                'status' => 'verifikasi',
                'keterangan' => 'Pelatihan pembuatan kerajinan tangan dan manajemen usaha untuk 50 peserta.'
            ],
            [
                'nama_kegiatan' => 'Renovasi Balai Desa',
                'bidang' => 'Pelayanan Umum',
                'pagu_anggaran' => 75000000,
                'sumber_dana' => 'PADes (Pendapatan Asli Desa)',
                'waktu_mulai' => '2025-05-01',
                'waktu_selesai' => '2025-06-30',
                'status' => 'draft',
                'keterangan' => 'Renovasi atap, cat ulang, dan perbaikan fasilitas balai desa.'
            ],
            [
                'nama_kegiatan' => 'Posyandu Balita dan Lansia',
                'bidang' => 'Kesehatan',
                'pagu_anggaran' => 15000000,
                'sumber_dana' => 'DD (Dana Desa)',
                'waktu_mulai' => '2025-01-01',
                'waktu_selesai' => '2025-12-31',
                'status' => 'disetujui',
                'keterangan' => 'Program posyandu rutin setiap bulan untuk balita dan lansia.'
            ],
            [
                'nama_kegiatan' => 'Bantuan Pendidikan Anak Kurang Mampu',
                'bidang' => 'Pendidikan',
                'pagu_anggaran' => 30000000,
                'sumber_dana' => 'ADD (Alokasi Dana Desa)',
                'waktu_mulai' => '2025-07-01',
                'waktu_selesai' => '2025-12-31',
                'status' => 'draft',
                'keterangan' => 'Bantuan biaya sekolah untuk 100 anak dari keluarga kurang mampu.'
            ]
        ];

        foreach ($kegiatanList as $kegiatanData) {
            Kegiatan::create([
                'tahun_id' => $tahunAktif->id,
                'nama_kegiatan' => $kegiatanData['nama_kegiatan'],
                'bidang' => $kegiatanData['bidang'],
                'pagu_anggaran' => $kegiatanData['pagu_anggaran'],
                'sumber_dana' => $kegiatanData['sumber_dana'],
                'waktu_mulai' => $kegiatanData['waktu_mulai'],
                'waktu_selesai' => $kegiatanData['waktu_selesai'],
                'status' => $kegiatanData['status'],
                'keterangan' => $kegiatanData['keterangan'],
                'dibuat_oleh' => $operator->id,
            ]);
        }
    }
}
