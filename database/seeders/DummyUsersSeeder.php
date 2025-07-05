<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DummyUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Admin
            [
                'name' => 'Administrator Sistem',
                'email' => 'admin@desa.id',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'created_at' => '2023-01-01 08:00:00',
            ],
            
            // Kepala Desa
            [
                'name' => 'Budi Santoso',
                'email' => 'kepala.desa@desa.id',
                'password' => Hash::make('password'),
                'role' => 'kepala-desa',
                'email_verified_at' => now(),
                'created_at' => '2023-01-01 08:00:00',
            ],
            
            // Sekretaris
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'sekretaris@desa.id',
                'password' => Hash::make('password'),
                'role' => 'sekretaris',
                'email_verified_at' => now(),
                'created_at' => '2023-01-01 08:00:00',
            ],
            
            // Bendahara
            [
                'name' => 'Ahmad Wijaya',
                'email' => 'bendahara@desa.id',
                'password' => Hash::make('password'),
                'role' => 'bendahara',
                'email_verified_at' => now(),
                'created_at' => '2023-01-01 08:00:00',
            ],
            
            // Operator 1
            [
                'name' => 'Rina Kusuma',
                'email' => 'operator1@desa.id',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'email_verified_at' => now(),
                'created_at' => '2023-01-01 08:00:00',
            ],
            
            // Operator 2
            [
                'name' => 'Dedi Prasetyo',
                'email' => 'operator2@desa.id',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'email_verified_at' => now(),
                'created_at' => '2023-01-01 08:00:00',
            ],
            
            // Auditor
            [
                'name' => 'Dr. Indira Sari',
                'email' => 'auditor@desa.id',
                'password' => Hash::make('password'),
                'role' => 'auditor',
                'email_verified_at' => now(),
                'created_at' => '2023-01-01 08:00:00',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']); // Remove role from user data

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            // Assign role using Spatie Laravel Permission
            $user->assignRole($role);
        }
    }
}
