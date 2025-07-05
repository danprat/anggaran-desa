<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrator sistem dengan akses penuh'
            ],
            [
                'name' => 'operator',
                'display_name' => 'Operator Desa',
                'description' => 'Operator yang bertugas input kegiatan'
            ],
            [
                'name' => 'sekretaris',
                'display_name' => 'Sekretaris Desa',
                'description' => 'Sekretaris yang bertugas verifikasi kegiatan'
            ],
            [
                'name' => 'kepala-desa',
                'display_name' => 'Kepala Desa',
                'description' => 'Kepala Desa yang bertugas menyetujui kegiatan'
            ],
            [
                'name' => 'bendahara',
                'display_name' => 'Bendahara Desa',
                'description' => 'Bendahara yang bertugas input realisasi'
            ],
            [
                'name' => 'auditor',
                'display_name' => 'Auditor',
                'description' => 'Auditor dengan akses read-only untuk audit'
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate([
                'name' => $roleData['name'],
                'guard_name' => 'web'
            ]);
        }

        // Create permissions
        $permissions = [
            // User management
            'manage-users',
            'view-users',

            // Desa Profile permissions
            'manage-desa-profile',
            'view-desa-profile',

            // Kegiatan permissions
            'create-kegiatan',
            'edit-kegiatan',
            'delete-kegiatan',
            'view-kegiatan',
            'verify-kegiatan',
            'approve-kegiatan',

            // Realisasi permissions
            'create-realisasi',
            'edit-realisasi',
            'delete-realisasi',
            'view-realisasi',
            'upload-bukti',

            // Laporan permissions
            'view-laporan',
            'export-laporan',

            // Log permissions
            'view-log',

            // System permissions
            'manage-tahun-anggaran',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Assign permissions to roles
        $admin = Role::findByName('admin');
        $admin->givePermissionTo(Permission::all());

        $operator = Role::findByName('operator');
        $operator->givePermissionTo([
            'create-kegiatan',
            'edit-kegiatan',
            'view-kegiatan',
            'view-laporan',
            'view-desa-profile',
        ]);

        $sekretaris = Role::findByName('sekretaris');
        $sekretaris->givePermissionTo([
            'view-kegiatan',
            'verify-kegiatan',
            'edit-kegiatan',
            'view-realisasi',
            'view-laporan',
            'export-laporan',
            'view-desa-profile',
        ]);

        $kepalaDesa = Role::findByName('kepala-desa');
        $kepalaDesa->givePermissionTo([
            'view-kegiatan',
            'approve-kegiatan',
            'view-realisasi',
            'view-laporan',
            'export-laporan',
            'manage-desa-profile',
            'view-desa-profile',
        ]);

        $bendahara = Role::findByName('bendahara');
        $bendahara->givePermissionTo([
            'view-kegiatan',
            'create-realisasi',
            'edit-realisasi',
            'view-realisasi',
            'upload-bukti',
            'view-laporan',
            'export-laporan',
            'view-desa-profile',
        ]);

        $auditor = Role::findByName('auditor');
        $auditor->givePermissionTo([
            'view-kegiatan',
            'view-realisasi',
            'view-laporan',
            'export-laporan',
            'view-log',
            'view-desa-profile',
        ]);
    }
}
