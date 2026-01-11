<?php

namespace Database\Seeders;

use App\Models\Mustahiq;
use Illuminate\Database\Seeder;

class MustahiqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mustahiqList = [
            [
                'nama_lengkap' => 'Rahmat Hidayat',
                'nik' => '3173120501800006',
                'no_hp' => '081234567811',
                'alamat' => 'Jl. Kramat Jaya No. 15, Jakarta Pusat',
                'kategori' => 'fakir',
                'status_aktif' => true,
            ],
            [
                'nama_lengkap' => 'Nur Aisyah',
                'nik' => '3176012002920007',
                'no_hp' => '081234567812',
                'alamat' => 'Gang Melur No. 4, Bogor',
                'kategori' => 'miskin',
                'status_aktif' => true,
            ],
            [
                'nama_lengkap' => 'Umar Zaki',
                'nik' => null,
                'no_hp' => '081234567813',
                'alamat' => 'Jl. Raya Serpong KM 2, Tangerang Selatan',
                'kategori' => 'gharimin',
                'status_aktif' => true,
            ],
            [
                'nama_lengkap' => 'Fatimah Zahra',
                'nik' => '3174101119760008',
                'no_hp' => '081234567814',
                'alamat' => 'Perumahan Sakura Blok D5 No. 9, Depok',
                'kategori' => 'mualaf',
                'status_aktif' => false,
            ],
            [
                'nama_lengkap' => 'Yusuf Maulana',
                'nik' => '3172010801830009',
                'no_hp' => '081234567815',
                'alamat' => 'Jl. Cempaka Putih No. 2, Bekasi',
                'kategori' => 'fisabilillah',
                'status_aktif' => true,
            ],
        ];

        foreach ($mustahiqList as $mustahiq) {
            Mustahiq::create($mustahiq);
        }
    }
}
