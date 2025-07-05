<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kegiatan;
use App\Models\TahunAnggaran;
use App\Models\User;
use Carbon\Carbon;

class DummyKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tahunList = TahunAnggaran::all();
        $operators = User::role('operator')->get();
        
        $kegiatanTemplates = [
            [
                'nama' => 'Pembangunan Jalan Desa',
                'deskripsi' => 'Pembangunan jalan desa sepanjang 2 km untuk meningkatkan akses transportasi warga',
                'bidang' => 'Infrastruktur',
                'pagu_anggaran' => 500000000,
            ],
            [
                'nama' => 'Program Bantuan Sosial Lansia',
                'deskripsi' => 'Program bantuan sosial untuk lansia berupa bantuan sembako dan kesehatan',
                'bidang' => 'Sosial',
                'pagu_anggaran' => 150000000,
            ],
            [
                'nama' => 'Pelatihan Keterampilan Pemuda',
                'deskripsi' => 'Pelatihan keterampilan untuk pemuda desa dalam bidang teknologi dan wirausaha',
                'bidang' => 'Pendidikan',
                'pagu_anggaran' => 75000000,
            ],
            [
                'nama' => 'Renovasi Balai Desa',
                'deskripsi' => 'Renovasi dan perbaikan balai desa untuk meningkatkan pelayanan publik',
                'bidang' => 'Infrastruktur',
                'pagu_anggaran' => 200000000,
            ],
            [
                'nama' => 'Program Posyandu Balita',
                'deskripsi' => 'Program kesehatan untuk balita melalui posyandu di setiap RT',
                'bidang' => 'Kesehatan',
                'pagu_anggaran' => 50000000,
            ],
            [
                'nama' => 'Pemberdayaan UMKM Desa',
                'deskripsi' => 'Program pemberdayaan usaha mikro kecil menengah untuk meningkatkan ekonomi desa',
                'bidang' => 'Ekonomi',
                'pagu_anggaran' => 300000000,
            ],
            [
                'nama' => 'Pengadaan Alat Pertanian',
                'deskripsi' => 'Pengadaan alat pertanian modern untuk meningkatkan produktivitas petani',
                'bidang' => 'Pertanian',
                'pagu_anggaran' => 250000000,
            ],
            [
                'nama' => 'Festival Budaya Desa',
                'deskripsi' => 'Penyelenggaraan festival budaya untuk melestarikan tradisi dan budaya lokal',
                'bidang' => 'Budaya',
                'pagu_anggaran' => 100000000,
            ],
            [
                'nama' => 'Pembangunan MCK Umum',
                'deskripsi' => 'Pembangunan mandi cuci kakus umum untuk meningkatkan sanitasi desa',
                'bidang' => 'Infrastruktur',
                'pagu_anggaran' => 180000000,
            ],
            [
                'nama' => 'Program Literasi Digital',
                'deskripsi' => 'Program pelatihan literasi digital untuk warga desa semua usia',
                'bidang' => 'Pendidikan',
                'pagu_anggaran' => 80000000,
            ],
            [
                'nama' => 'Bantuan Bibit Tanaman',
                'deskripsi' => 'Pembagian bibit tanaman produktif untuk program ketahanan pangan',
                'bidang' => 'Pertanian',
                'pagu_anggaran' => 120000000,
            ],
            [
                'nama' => 'Peningkatan Keamanan Desa',
                'deskripsi' => 'Program peningkatan keamanan desa melalui siskamling dan CCTV',
                'bidang' => 'Keamanan',
                'pagu_anggaran' => 150000000,
            ],
            [
                'nama' => 'Program Air Bersih',
                'deskripsi' => 'Pembangunan sistem air bersih untuk memenuhi kebutuhan air warga',
                'bidang' => 'Infrastruktur',
                'pagu_anggaran' => 400000000,
            ],
            [
                'nama' => 'Pelatihan Ibu PKK',
                'deskripsi' => 'Pelatihan keterampilan untuk ibu-ibu PKK dalam bidang kerajinan dan kuliner',
                'bidang' => 'Sosial',
                'pagu_anggaran' => 60000000,
            ],
            [
                'nama' => 'Pembangunan Taman Desa',
                'deskripsi' => 'Pembangunan taman desa sebagai ruang terbuka hijau dan rekreasi warga',
                'bidang' => 'Lingkungan',
                'pagu_anggaran' => 220000000,
            ],
            [
                'nama' => 'Program Beasiswa Anak Desa',
                'deskripsi' => 'Program beasiswa untuk anak-anak berprestasi dari keluarga kurang mampu',
                'bidang' => 'Pendidikan',
                'pagu_anggaran' => 90000000,
            ],
            [
                'nama' => 'Pengembangan Wisata Desa',
                'deskripsi' => 'Pengembangan potensi wisata desa untuk meningkatkan pendapatan asli desa',
                'bidang' => 'Pariwisata',
                'pagu_anggaran' => 350000000,
            ],
            [
                'nama' => 'Program Vaksinasi Ternak',
                'deskripsi' => 'Program vaksinasi ternak untuk mencegah penyakit dan meningkatkan produktivitas',
                'bidang' => 'Peternakan',
                'pagu_anggaran' => 70000000,
            ],
        ];

        foreach ($tahunList as $tahun) {
            // Ambil 15-18 kegiatan per tahun secara acak
            $selectedKegiatan = collect($kegiatanTemplates)->random(rand(15, 18));
            
            foreach ($selectedKegiatan as $index => $template) {
                $startDate = Carbon::create($tahun->tahun, rand(1, 6), rand(1, 28));
                $endDate = $startDate->copy()->addMonths(rand(3, 8));
                
                // Variasi status berdasarkan tahun
                $status = $this->getStatusByYear($tahun->tahun, $index);
                
                Kegiatan::create([
                    'nama_kegiatan' => $template['nama'] . ' ' . $tahun->tahun,
                    'keterangan' => $template['deskripsi'],
                    'bidang' => $template['bidang'],
                    'pagu_anggaran' => $template['pagu_anggaran'] + rand(-50000000, 50000000), // Variasi ±50jt
                    'sumber_dana' => $this->getSumberDana(),
                    'waktu_mulai' => $startDate,
                    'waktu_selesai' => $endDate,
                    'status' => $status,
                    'tahun_id' => $tahun->id,
                    'dibuat_oleh' => $operators->random()->id,
                    'created_at' => $startDate->copy()->subDays(rand(7, 30)),
                    'updated_at' => $this->getUpdatedAt($status, $startDate),
                ]);
            }
        }
    }

    private function getStatusByYear($tahun, $index)
    {
        if ($tahun == 2023) {
            // Tahun 2023: Sebagian besar sudah disetujui
            return ['disetujui', 'disetujui', 'disetujui', 'ditolak', 'disetujui'][$index % 5];
        } elseif ($tahun == 2024) {
            // Tahun 2024: Mix status
            return ['disetujui', 'disetujui', 'ditolak', 'disetujui', 'disetujui'][$index % 5];
        } else {
            // Tahun 2025: Masih ada yang dalam proses
            return ['disetujui', 'verifikasi', 'draft', 'disetujui', 'disetujui'][$index % 5];
        }
    }

    private function getSumberDana()
    {
        $sumberDana = [
            'ADD (Alokasi Dana Desa)',
            'DD (Dana Desa)',
            'PADes (Pendapatan Asli Desa)',
            'Bantuan Provinsi',
            'Bantuan Kabupaten',
            'Swadaya Masyarakat',
            'CSR (Corporate Social Responsibility)',
        ];

        return $sumberDana[array_rand($sumberDana)];
    }

    private function getUpdatedAt($status, $startDate)
    {
        switch ($status) {
            case 'draft':
                return $startDate->copy()->subDays(rand(1, 7));
            case 'verifikasi':
                return $startDate->copy()->subDays(rand(1, 14));
            case 'disetujui':
                return $startDate->copy()->subDays(rand(1, 21));
            case 'ditolak':
                return $startDate->copy()->subDays(rand(1, 30));
            default:
                return $startDate;
        }
    }
}
