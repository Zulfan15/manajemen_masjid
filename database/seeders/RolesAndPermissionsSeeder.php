<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define modules
        $modules = [
            'jamaah' => 'Manajemen Jamaah',
            'keuangan' => 'Keuangan Masjid',
            'kegiatan' => 'Kegiatan & Acara',
            'zis' => 'Manajemen ZIS',
            'kurban' => 'Manajemen Kurban',
            'inventaris' => 'Manajemen Inventaris',
            'takmir' => 'Manajemen Takmir',
            'informasi' => 'Informasi & Pengumuman',
            'laporan' => 'Laporan & Statistik',
        ];

        // Define actions
        $actions = ['view', 'create', 'update', 'delete'];

        // Create permissions for each module
        foreach ($modules as $moduleKey => $moduleLabel) {
            foreach ($actions as $action) {
                Permission::create([
                    'name' => "{$moduleKey}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // Create Super Admin Role (READ-ONLY)
        $superAdmin = Role::create([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        // Give super admin VIEW permission to all modules
        foreach ($modules as $moduleKey => $moduleLabel) {
            $superAdmin->givePermissionTo("{$moduleKey}.view");
        }

        // Create Module Admin Roles with FULL CRUD
        foreach ($modules as $moduleKey => $moduleLabel) {
            $adminRole = Role::create([
                'name' => "admin_{$moduleKey}",
                'guard_name' => 'web',
            ]);

            // Give full CRUD permissions for their module
            foreach ($actions as $action) {
                $adminRole->givePermissionTo("{$moduleKey}.{$action}");
            }
        }

        // Create Module Officer (Pengurus) Roles with FULL CRUD
        foreach ($modules as $moduleKey => $moduleLabel) {
            $pengurusRole = Role::create([
                'name' => "pengurus_{$moduleKey}",
                'guard_name' => 'web',
            ]);

            // Give full CRUD permissions for their module
            foreach ($actions as $action) {
                $pengurusRole->givePermissionTo("{$moduleKey}.{$action}");
            }
        }

        // Create Jamaah Role (limited access)
        $jamaahRole = Role::create([
            'name' => 'jamaah',
            'guard_name' => 'web',
        ]);

        // Jamaah can only view their own data
        $jamaahRole->givePermissionTo('jamaah.view');

        echo "Roles and permissions created successfully!\n";
        echo "Total Roles: " . Role::count() . "\n";
        echo "Total Permissions: " . Permission::count() . "\n";
    }
}
