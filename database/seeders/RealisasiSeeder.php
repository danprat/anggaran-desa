<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Realisasi;
use App\Models\Kegiatan;
use App\Models\User;

class RealisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bendahara = User::role('bendahara')->first();

        if (!$bendahara) {
            return;
        }

        // Get kegiatan yang sudah disetujui
        $kegiatanDisetujui = Kegiatan::where('status', 'disetujui')->get();

        foreach ($kegiatanDisetujui as $kegiatan) {
            // Buat beberapa realisasi untuk setiap kegiatan
            $jumlahRealisasi = rand(1, 3); // 1-3 realisasi per kegiatan
            $totalRealisasi = 0;

            for ($i = 1; $i <= $jumlahRealisasi; $i++) {
                // Hitung sisa anggaran
                $sisaAnggaran = $kegiatan->pagu_anggaran - $totalRealisasi;

                if ($sisaAnggaran <= 0) {
                    break;
                }

                // Tentukan jumlah realisasi (20-60% dari sisa anggaran)
                $persentase = rand(20, 60) / 100;
                $jumlah = min($sisaAnggaran * $persentase, $sisaAnggaran);

                // Buat tanggal random dalam 3 bulan terakhir
                $tanggal = now()->subDays(rand(1, 90));

                $realisasi = Realisasi::create([
                    'kegiatan_id' => $kegiatan->id,
                    'jumlah_realisasi' => $jumlah,
                    'tanggal' => $tanggal,
                    'deskripsi' => $this->generateDeskripsi($kegiatan->nama_kegiatan, $i),
                    'dibuat_oleh' => $bendahara->id,
                    'status' => 'sebagian',
                ]);

                $totalRealisasi += $jumlah;
            }

            // Update status realisasi terakhir jika sudah mencapai 100%
            if ($totalRealisasi >= $kegiatan->pagu_anggaran * 0.9) {
                $kegiatan->realisasi()->latest()->first()?->update(['status' => 'selesai']);
            }
        }
    }

    private function generateDeskripsi($namaKegiatan, $tahap)
    {
        $deskripsiTemplates = [
            "Pembayaran tahap {$tahap} untuk {$namaKegiatan}",
            "Realisasi anggaran {$namaKegiatan} tahap {$tahap}",
            "Pelaksanaan {$namaKegiatan} - pembayaran ke-{$tahap}",
            "Pencairan dana {$namaKegiatan} tahap {$tahap}",
        ];

        return $deskripsiTemplates[array_rand($deskripsiTemplates)];
    }
}
