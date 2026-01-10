<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PetugasSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['super_admin', 'petugas'] as $r) {
            Role::firstOrCreate(['name' => $r]);
        }

        $admin = User::updateOrCreate(
            ['email' => 'admin@masjid.test'],
            [
                'name' => 'Super Admin',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'locked_until' => null,
            ]
        );
        $admin->syncRoles(['super_admin']);

        $p1 = User::updateOrCreate(
            ['email' => 'petugas1@masjid.test'],
            [
                'name' => 'Petugas 1',
                'username' => 'petugas1',
                'password' => Hash::make('password123'),
                'locked_until' => null,
            ]
        );
        $p1->syncRoles(['petugas']);
    }
}
