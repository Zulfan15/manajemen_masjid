<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ZISUser;

class ZISUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ZISUser::create([
            'name' => 'Admin ZIS',
            'email' => 'admin@zis.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        ZISUser::create([
            'name' => 'Amil Zakat',
            'email' => 'amil@zis.com',
            'password' => bcrypt('password123'),
            'role' => 'amil',
        ]);
    }
}
