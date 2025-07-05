<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Realisasi;
use App\Models\BuktiFile;
use App\Models\Kegiatan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DummyRealisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kegiatanDisetujui = Kegiatan::where('status', 'disetujui')->get();
        $bendahara = User::role('bendahara')->first();
        
        foreach ($kegiatanDisetujui as $kegiatan) {
            // Buat 2-5 realisasi per kegiatan
            $jumlahRealisasi = rand(2, 5);
            $totalRealisasi = 0;
            $sisaAnggaran = $kegiatan->pagu_anggaran;
            
            for ($i = 1; $i <= $jumlahRealisasi; $i++) {
                // Hitung jumlah realisasi (tidak boleh melebihi sisa anggaran)
                if ($i == $jumlahRealisasi) {
                    // Realisasi terakhir, gunakan sisa anggaran (80-95% dari sisa)
                    $jumlahRealisasi = $sisaAnggaran * (rand(80, 95) / 100);
                } else {
                    // Realisasi biasa, 15-30% dari pagu anggaran
                    $jumlahRealisasi = $kegiatan->pagu_anggaran * (rand(15, 30) / 100);
                    if ($jumlahRealisasi > $sisaAnggaran) {
                        $jumlahRealisasi = $sisaAnggaran * 0.8;
                    }
                }
                
                $tanggalRealisasi = $kegiatan->waktu_mulai->copy()->addDays(rand(30, 180));
                
                $realisasi = Realisasi::create([
                    'kegiatan_id' => $kegiatan->id,
                    'tanggal' => $tanggalRealisasi,
                    'jumlah_realisasi' => $jumlahRealisasi,
                    'deskripsi' => $this->getDeskripsiRealisasi($i, $kegiatan->nama_kegiatan),
                    'status' => $i == $jumlahRealisasi ? 'selesai' : 'sebagian',
                    'dibuat_oleh' => $bendahara->id,
                    'created_at' => $tanggalRealisasi->copy()->addDays(rand(1, 7)),
                    'updated_at' => $tanggalRealisasi->copy()->addDays(rand(1, 7)),
                ]);
                
                // Buat 1-3 bukti file per realisasi
                $jumlahFile = rand(1, 3);
                for ($j = 1; $j <= $jumlahFile; $j++) {
                    $this->createDummyFile($realisasi, $j);
                }
                
                $totalRealisasi += $jumlahRealisasi;
                $sisaAnggaran -= $jumlahRealisasi;
                
                if ($sisaAnggaran <= 0) break;
            }
        }
    }
    
    private function getDeskripsiRealisasi($urutan, $namaKegiatan)
    {
        $deskripsi = [
            1 => "Tahap persiapan dan pengadaan material untuk {$namaKegiatan}",
            2 => "Pelaksanaan tahap awal {$namaKegiatan}",
            3 => "Pelaksanaan tahap lanjutan {$namaKegiatan}",
            4 => "Penyelesaian dan finishing {$namaKegiatan}",
            5 => "Evaluasi dan pelaporan akhir {$namaKegiatan}",
        ];
        
        return $deskripsi[$urutan] ?? "Pelaksanaan {$namaKegiatan} tahap {$urutan}";
    }
    
    private function createDummyFile($realisasi, $urutan)
    {
        $fileTypes = [
            ['name' => 'Nota_Pembelian_' . $urutan . '.pdf', 'type' => 'application/pdf'],
            ['name' => 'Bukti_Transfer_' . $urutan . '.jpg', 'type' => 'image/jpeg'],
            ['name' => 'Dokumentasi_' . $urutan . '.png', 'type' => 'image/png'],
            ['name' => 'Invoice_' . $urutan . '.pdf', 'type' => 'application/pdf'],
            ['name' => 'Kwitansi_' . $urutan . '.jpg', 'type' => 'image/jpeg'],
        ];
        
        $fileData = $fileTypes[array_rand($fileTypes)];
        
        // Buat dummy file content
        $dummyContent = "Dummy file content for realisasi ID: {$realisasi->id}, File: {$fileData['name']}";
        
        // Simpan ke storage
        $filePath = 'bukti_realisasi/' . $realisasi->id . '/' . $fileData['name'];
        Storage::disk('public')->put($filePath, $dummyContent);
        
        BuktiFile::create([
            'realisasi_id' => $realisasi->id,
            'file_name' => $fileData['name'],
            'file_path' => $filePath,
            'file_size' => strlen($dummyContent),
            'file_type' => $fileData['type'],
            'uploaded_by' => $realisasi->dibuat_oleh,
            'created_at' => $realisasi->created_at,
            'updated_at' => $realisasi->updated_at,
        ]);
    }
}
