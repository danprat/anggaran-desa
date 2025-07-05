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
        $tahunAnggaran = [
            [
                'tahun' => 2023,
                'status' => 'nonaktif',
                'created_at' => '2023-01-01 00:00:00',
                'updated_at' => '2023-01-01 00:00:00',
            ],
            [
                'tahun' => 2024,
                'status' => 'nonaktif',
                'created_at' => '2024-01-01 00:00:00',
                'updated_at' => '2024-01-01 00:00:00',
            ],
            [
                'tahun' => 2025,
                'status' => 'aktif',
                'created_at' => '2025-01-01 00:00:00',
                'updated_at' => '2025-01-01 00:00:00',
            ],
        ];

        foreach ($tahunAnggaran as $tahun) {
            TahunAnggaran::updateOrCreate(
                ['tahun' => $tahun['tahun']],
                $tahun
            );
        }
    }
}
