<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            TahunAnggaranSeeder::class,
            KegiatanSeeder::class,
            RealisasiSeeder::class,

            // Dummy data seeders
            DummyUsersSeeder::class,
            DummyKegiatanSeeder::class,
            DummyRealisasiSeeder::class,
            DummyLogAktivitasSeeder::class,
        ]);
    }
}
