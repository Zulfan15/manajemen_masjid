<?php

namespace Database\Seeders;

use App\Models\Muzakki;
use Illuminate\Database\Seeder;

class MuzakkiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $muzakkiList = [
            [
                'nama_lengkap' => 'Ahmad Sulaiman',
                'nik' => '3173051201990001',
                'no_hp' => '081234567801',
                'alamat' => 'Jl. Masjid Raya No. 10, Jakarta Timur',
                'jenis_kelamin' => 'L',
            ],
            [
                'nama_lengkap' => 'Siti Nurjanah',
                'nik' => '3175082202980002',
                'no_hp' => '081234567802',
                'alamat' => 'Jl. Kemuning III No. 5, Bekasi',
                'jenis_kelamin' => 'P',
            ],
            [
                'nama_lengkap' => 'Budi Hartono',
                'nik' => '3174091501900003',
                'no_hp' => '081234567803',
                'alamat' => 'Perumahan Melati Blok B2 No. 7, Depok',
                'jenis_kelamin' => 'L',
            ],
            [
                'nama_lengkap' => 'Dewi Lestari',
                'nik' => null,
                'no_hp' => '081234567804',
                'alamat' => 'Jl. Anggrek Loka No. 21, Tangerang',
                'jenis_kelamin' => 'P',
            ],
            [
                'nama_lengkap' => 'Hendra Wijaya',
                'nik' => '3171020311850005',
                'no_hp' => '081234567805',
                'alamat' => 'Cluster Cendana, Blok C1 No. 3, Jakarta Selatan',
                'jenis_kelamin' => 'L',
            ],
        ];

        foreach ($muzakkiList as $muzakki) {
            Muzakki::create($muzakki);
        }
    }
}
