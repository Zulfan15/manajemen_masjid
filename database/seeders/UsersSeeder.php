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
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@masjid.com',
            'username' => 'superadmin',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super_admin');

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
            $admin = User::create([
                'name' => "Admin {$moduleLabel}",
                'email' => "admin.{$moduleKey}@masjid.com",
                'username' => "admin_{$moduleKey}",
                'password' => Hash::make('password'),
                'phone' => '0812' . rand(10000000, 99999999),
                'email_verified_at' => now(),
            ]);
            $admin->assignRole("admin_{$moduleKey}");
        }

        // Create Sample Jamaah Users
        for ($i = 1; $i <= 5; $i++) {
            $jamaah = User::create([
                'name' => "Jamaah User {$i}",
                'email' => "jamaah{$i}@example.com",
                'username' => "jamaah{$i}",
                'password' => Hash::make('password'),
                'phone' => '0813' . rand(10000000, 99999999),
                'address' => "Alamat Jamaah {$i}",
                'email_verified_at' => now(),
            ]);
            $jamaah->assignRole('jamaah');
        }

        // Create Sample Pengurus (Officers)
        $sampleModules = ['keuangan', 'kegiatan', 'zis'];
        foreach ($sampleModules as $module) {
            $pengurus = User::create([
                'name' => "Pengurus " . ucfirst($module),
                'email' => "pengurus.{$module}@masjid.com",
                'username' => "pengurus_{$module}",
                'password' => Hash::make('password'),
                'phone' => '0814' . rand(10000000, 99999999),
                'email_verified_at' => now(),
            ]);
            $pengurus->assignRole("pengurus_{$module}");
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
