<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UsersSeeder::class,
            PengeluaranSeeder::class,
            Pengeluaran2Seeder::class,
            TakmirSeeder::class,
            AktivitasHarianSeeder::class,
            PemilihanSeeder::class,
            KegiatanSeeder::class,
            KurbanSeeder::class,
            ModulTambahanSeeder::class,
        ]);
    }
}
