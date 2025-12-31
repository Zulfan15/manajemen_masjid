<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@masjid.com'],
            [
                'name' => 'Super Administrator',
                'username' => 'superadmin',
                'password' => Hash::make('password'),
                'phone' => '081234567890',
                'email_verified_at' => now(),
            ]
        );
        if (!$superAdmin->hasRole('super_admin')) {
            $superAdmin->assignRole('super_admin');
        }

        // Define modules
        $modules = [
            'jamaah' => 'Jamaah',
            'keuangan' => 'Keuangan',
            'kegiatan' => 'Kegiatan',
            'zis' => 'ZIS',
            'kurban' => 'Kurban',
            'inventaris' => 'Inventaris',
            'takmir' => 'Takmir',
            'informasi' => 'Informasi',
            'laporan' => 'Laporan',
        ];

        // Create Module Admins
        foreach ($modules as $moduleKey => $moduleLabel) {
            $admin = User::firstOrCreate(
                ['email' => "admin.{$moduleKey}@masjid.com"],
                [
                    'name' => "Admin {$moduleLabel}",
                    'username' => "admin_{$moduleKey}",
                    'password' => Hash::make('password'),
                    'phone' => '0812' . rand(10000000, 99999999),
                    'email_verified_at' => now(),
                ]
            );
            if (!$admin->hasRole("admin_{$moduleKey}")) {
                $admin->assignRole("admin_{$moduleKey}");
            }
        }

        // Create Sample Jamaah Users
        for ($i = 1; $i <= 5; $i++) {
            $jamaah = User::firstOrCreate(
                ['email' => "jamaah{$i}@example.com"],
                [
                    'name' => "Jamaah User {$i}",
                    'username' => "jamaah{$i}",
                    'password' => Hash::make('password'),
                    'phone' => '0813' . rand(10000000, 99999999),
                    'address' => "Alamat Jamaah {$i}",
                    'email_verified_at' => now(),
                ]
            );
            if (!$jamaah->hasRole('jamaah')) {
                $jamaah->assignRole('jamaah');
            }
        }

        // Create Sample Pengurus (Officers)
        $sampleModules = ['keuangan', 'kegiatan', 'zis'];
        foreach ($sampleModules as $module) {
            $pengurus = User::firstOrCreate(
                ['email' => "pengurus.{$module}@masjid.com"],
                [
                    'name' => "Pengurus " . ucfirst($module),
                    'username' => "pengurus_{$module}",
                    'password' => Hash::make('password'),
                    'phone' => '0814' . rand(10000000, 99999999),
                    'email_verified_at' => now(),
                ]
            );
            if (!$pengurus->hasRole("pengurus_{$module}")) {
                $pengurus->assignRole("pengurus_{$module}");
            }
        }

        echo "\nUsers created successfully!\n";
        echo "Total Users: " . User::count() . "\n\n";
        echo "=== LOGIN CREDENTIALS ===\n";
        echo "Super Admin:\n";
        echo "  Username: superadmin\n";
        echo "  Password: password\n\n";
        echo "Module Admins (example):\n";
        echo "  Username: admin_keuangan\n";
        echo "  Password: password\n\n";
        echo "Jamaah:\n";
        echo "  Username: jamaah1\n";
        echo "  Password: password\n";
        echo "=========================\n";
    }
}
