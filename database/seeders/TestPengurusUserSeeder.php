<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Takmir;
use Illuminate\Support\Facades\Hash;

class TestPengurusUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user untuk pengurus (ambil data takmir pertama yang aktif)
        $takmir = Takmir::aktif()->first();

        if (!$takmir) {
            $this->command->error('Tidak ada takmir aktif. Jalankan TakmirSeeder dulu.');
            return;
        }

        // Cek apakah user sudah ada
        $existingUser = User::where('username', 'pengurus1')->first();
        if ($existingUser) {
            $this->command->info('User pengurus1 sudah ada.');
            return;
        }

        // Buat user baru
        $user = User::create([
            'username' => 'pengurus1',
            'name' => 'Pengurus Test 1',
            'email' => 'pengurus1@masjid.com',
            'password' => Hash::make('password'),
        ]);

        // Assign role pengurus_takmir
        $user->assignRole('pengurus_takmir');

        // Link user dengan takmir (update user_id di tabel takmir)
        $takmir->update(['user_id' => $user->id]);

        $this->command->info('✓ User pengurus1 berhasil dibuat dan di-link dengan takmir: ' . $takmir->nama);
        $this->command->info('  Username: pengurus1');
        $this->command->info('  Password: password');
        $this->command->info('  Role: pengurus_takmir');
    }
}
