<?php

namespace Database\Seeders;

use App\Models\DesaProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DesaProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create main active profile
        DesaProfile::create([
            'nama_desa' => 'Sukamaju',
            'kecamatan' => 'Cikarang Utara',
            'kabupaten' => 'Bekasi',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '17530',
            'alamat_lengkap' => 'Jl. Raya Sukamaju No. 123, RT 001/RW 002',
            'kepala_desa' => 'Budi Santoso, S.Sos',
            'nip_kepala_desa' => '196512121990031001',
            'periode_jabatan_mulai' => '2019-08-17',
            'periode_jabatan_selesai' => '2025-08-17',
            'website' => 'https://sukamaju-desa.go.id',
            'email' => 'admin@sukamaju-desa.go.id',
            'telepon' => '(021) 8901234',
            'fax' => '(021) 8901235',
            'visi' => 'Mewujudkan Desa Sukamaju yang Maju, Mandiri, dan Sejahtera berbasis Teknologi dan Kearifan Lokal',
            'misi' => "1. Meningkatkan kualitas pelayanan publik yang prima\n2. Mengembangkan ekonomi desa berbasis potensi lokal\n3. Memperkuat tata kelola pemerintahan yang transparan\n4. Meningkatkan kualitas sumber daya manusia\n5. Melestarikan budaya dan lingkungan hidup",
            'sejarah_singkat' => 'Desa Sukamaju didirikan pada tahun 1945 sebagai hasil pemekaran dari desa induk. Nama Sukamaju diambil dari harapan masyarakat untuk mencapai kemajuan dan kesejahteraan bersama.',
            'luas_wilayah' => 1250.75,
            'jumlah_penduduk' => 8542,
            'jumlah_kk' => 2156,
            'batas_utara' => 'Desa Mekarjaya',
            'batas_selatan' => 'Desa Sukasari',
            'batas_timur' => 'Kecamatan Cikarang Selatan',
            'batas_barat' => 'Sungai Citarum',
            'is_active' => true,
            'additional_settings' => [
                'timezone' => 'Asia/Jakarta',
                'currency' => 'IDR',
                'language' => 'id'
            ]
        ]);

        // Create additional sample profiles
        DesaProfile::create([
            'nama_desa' => 'Mekarjaya',
            'kecamatan' => 'Cikarang Utara',
            'kabupaten' => 'Bekasi',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '17531',
            'alamat_lengkap' => 'Jl. Mekar Raya No. 456, RT 003/RW 001',
            'kepala_desa' => 'Siti Nurhaliza, S.Pd',
            'nip_kepala_desa' => '197203151995032002',
            'periode_jabatan_mulai' => '2020-01-15',
            'periode_jabatan_selesai' => '2026-01-15',
            'website' => 'https://mekarjaya-desa.go.id',
            'email' => 'admin@mekarjaya-desa.go.id',
            'telepon' => '(021) 8902345',
            'visi' => 'Desa Mekarjaya yang Sejahtera dan Berbudaya',
            'misi' => "1. Meningkatkan kesejahteraan masyarakat\n2. Melestarikan budaya lokal\n3. Mengembangkan pariwisata desa",
            'luas_wilayah' => 980.50,
            'jumlah_penduduk' => 6234,
            'jumlah_kk' => 1567,
            'batas_utara' => 'Kecamatan Tambun Utara',
            'batas_selatan' => 'Desa Sukamaju',
            'batas_timur' => 'Desa Sukasari',
            'batas_barat' => 'Sungai Bekasi',
            'is_active' => false
        ]);

        DesaProfile::create([
            'nama_desa' => 'Sukasari',
            'kecamatan' => 'Cikarang Selatan',
            'kabupaten' => 'Bekasi',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '17532',
            'alamat_lengkap' => 'Jl. Sukasari Indah No. 789, RT 002/RW 003',
            'kepala_desa' => 'Ahmad Fauzi, S.E',
            'nip_kepala_desa' => '198506101998031003',
            'periode_jabatan_mulai' => '2021-06-01',
            'periode_jabatan_selesai' => '2027-06-01',
            'email' => 'admin@sukasari-desa.go.id',
            'telepon' => '(021) 8903456',
            'visi' => 'Desa Sukasari yang Mandiri dan Berkelanjutan',
            'luas_wilayah' => 1456.25,
            'jumlah_penduduk' => 9876,
            'jumlah_kk' => 2489,
            'batas_utara' => 'Desa Sukamaju',
            'batas_selatan' => 'Kecamatan Cikarang Barat',
            'batas_timur' => 'Kabupaten Karawang',
            'batas_barat' => 'Desa Mekarjaya',
            'is_active' => false
        ]);
    }
}
