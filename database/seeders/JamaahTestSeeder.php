<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class JamaahTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Jamaah Seeder',
            'email' => 'jamaah@seed.com',
            'username' => 'jamaah_seed',
            'address' => 'Jl. Seeder No. 1',
            'password' => bcrypt('password'),
        ]);

        $user->assignRole('jamaah');
    }
}
