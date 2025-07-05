<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogAktivitas;
use App\Models\User;
use App\Models\Kegiatan;
use App\Models\Realisasi;
use Carbon\Carbon;

class DummyLogAktivitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $kegiatan = Kegiatan::all();
        $realisasi = Realisasi::all();
        
        $aktivitasTemplates = [
            // Login activities
            ['aksi' => 'login', 'deskripsi' => 'User berhasil login ke sistem'],
            ['aksi' => 'logout', 'deskripsi' => 'User logout dari sistem'],
            
            // Kegiatan activities
            ['aksi' => 'create_kegiatan', 'deskripsi' => 'Membuat kegiatan baru'],
            ['aksi' => 'update_kegiatan', 'deskripsi' => 'Mengupdate data kegiatan'],
            ['aksi' => 'verify_kegiatan', 'deskripsi' => 'Memverifikasi kegiatan'],
            ['aksi' => 'approve_kegiatan', 'deskripsi' => 'Menyetujui kegiatan'],
            ['aksi' => 'reject_kegiatan', 'deskripsi' => 'Menolak kegiatan'],
            ['aksi' => 'view_kegiatan', 'deskripsi' => 'Melihat detail kegiatan'],
            
            // Realisasi activities
            ['aksi' => 'create_realisasi', 'deskripsi' => 'Membuat realisasi anggaran'],
            ['aksi' => 'update_realisasi', 'deskripsi' => 'Mengupdate data realisasi'],
            ['aksi' => 'upload_bukti', 'deskripsi' => 'Mengupload bukti realisasi'],
            ['aksi' => 'view_realisasi', 'deskripsi' => 'Melihat detail realisasi'],
            
            // Report activities
            ['aksi' => 'export_laporan', 'deskripsi' => 'Mengexport laporan'],
            ['aksi' => 'view_laporan', 'deskripsi' => 'Melihat laporan'],
            ['aksi' => 'print_laporan', 'deskripsi' => 'Mencetak laporan'],
            
            // User management
            ['aksi' => 'create_user', 'deskripsi' => 'Membuat user baru'],
            ['aksi' => 'update_user', 'deskripsi' => 'Mengupdate data user'],
            ['aksi' => 'view_user', 'deskripsi' => 'Melihat data user'],
            
            // System activities
            ['aksi' => 'view_dashboard', 'deskripsi' => 'Mengakses dashboard'],
            ['aksi' => 'change_password', 'deskripsi' => 'Mengubah password'],
            ['aksi' => 'update_profile', 'deskripsi' => 'Mengupdate profil'],
        ];
        
        // Generate log untuk 3 tahun terakhir
        $startDate = Carbon::create(2023, 1, 1);
        $endDate = Carbon::now();
        
        // Generate 500-800 log activities
        for ($i = 0; $i < rand(500, 800); $i++) {
            $randomDate = Carbon::createFromTimestamp(
                rand($startDate->timestamp, $endDate->timestamp)
            );
            
            $template = $aktivitasTemplates[array_rand($aktivitasTemplates)];
            $user = $users->random();
            
            // Tentukan tabel terkait berdasarkan aksi
            $tabelTerkait = null;
            $rowId = null;

            if (str_contains($template['aksi'], 'kegiatan') && $kegiatan->count() > 0) {
                $tabelTerkait = 'kegiatan';
                $rowId = $kegiatan->random()->id;
            } elseif (str_contains($template['aksi'], 'realisasi') && $realisasi->count() > 0) {
                $tabelTerkait = 'realisasi';
                $rowId = $realisasi->random()->id;
            } elseif (str_contains($template['aksi'], 'user')) {
                $tabelTerkait = 'users';
                $rowId = $users->random()->id;
            }

            LogAktivitas::create([
                'user_id' => $user->id,
                'aktivitas' => $template['deskripsi'],
                'tabel_terkait' => $tabelTerkait,
                'row_id' => $rowId,
                'data_lama' => null,
                'data_baru' => null,
                'ip_address' => $this->generateRandomIP(),
                'user_agent' => $this->generateRandomUserAgent(),
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);
        }
    }
    
    private function generateRandomIP()
    {
        return rand(192, 203) . '.' . rand(168, 255) . '.' . rand(1, 255) . '.' . rand(1, 254);
    }
    
    private function generateRandomUserAgent()
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        ];
        
        return $userAgents[array_rand($userAgents)];
    }
}
