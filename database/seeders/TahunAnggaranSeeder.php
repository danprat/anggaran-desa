<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TahunAnggaran;

class TahunAnggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create tahun anggaran untuk beberapa tahun
        $tahunList = [
            ['tahun' => 2023, 'status' => 'nonaktif'],
            ['tahun' => 2024, 'status' => 'nonaktif'],
            ['tahun' => 2025, 'status' => 'aktif'], // Tahun aktif
            ['tahun' => 2026, 'status' => 'nonaktif'],
        ];

        foreach ($tahunList as $tahun) {
            TahunAnggaran::create($tahun);
        }
    }
}
