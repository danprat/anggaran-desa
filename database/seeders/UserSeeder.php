<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@anggarandesa.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create operator user
        $operator = User::create([
            'name' => 'Operator Desa',
            'email' => 'operator@anggarandesa.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $operator->assignRole('operator');

        // Create sekretaris user
        $sekretaris = User::create([
            'name' => 'Sekretaris Desa',
            'email' => 'sekretaris@anggarandesa.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $sekretaris->assignRole('sekretaris');

        // Create kepala desa user
        $kepalaDesa = User::create([
            'name' => 'Kepala Desa',
            'email' => 'kepaladesa@anggarandesa.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $kepalaDesa->assignRole('kepala-desa');

        // Create bendahara user
        $bendahara = User::create([
            'name' => 'Bendahara Desa',
            'email' => 'bendahara@anggarandesa.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $bendahara->assignRole('bendahara');

        // Create auditor user
        $auditor = User::create([
            'name' => 'Auditor',
            'email' => 'auditor@anggarandesa.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $auditor->assignRole('auditor');
    }
}
