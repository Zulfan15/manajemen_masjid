<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TakmirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $takmir = [
            [
                'nama' => 'H. Ahmad Fauzi',
                'jabatan' => 'Ketua (DKM)',
                'email' => 'ahmad.fauzi@masjid.com',
                'phone' => '081234567890',
                'alamat' => 'Jl. Masjid Raya No. 1, Jakarta',
                'periode_mulai' => '2024-01-01',
                'periode_akhir' => '2027-12-31',
                'status' => 'aktif',
                'keterangan' => 'Ketua DKM periode 2024-2027',
            ],
            [
                'nama' => 'Budi Santoso, S.Ag',
                'jabatan' => 'Wakil Ketua',
                'email' => 'budi.santoso@masjid.com',
                'phone' => '081234567891',
                'alamat' => 'Jl. Masjid Raya No. 2, Jakarta',
                'periode_mulai' => '2024-01-01',
                'periode_akhir' => '2027-12-31',
                'status' => 'aktif',
                'keterangan' => 'Wakil Ketua DKM periode 2024-2027',
            ],
            [
                'nama' => 'Hj. Siti Aminah',
                'jabatan' => 'Sekretaris',
                'email' => 'siti.aminah@masjid.com',
                'phone' => '081234567892',
                'alamat' => 'Jl. Masjid Raya No. 3, Jakarta',
                'periode_mulai' => '2024-01-01',
                'periode_akhir' => '2027-12-31',
                'status' => 'aktif',
                'keterangan' => 'Sekretaris DKM periode 2024-2027',
            ],
            [
                'nama' => 'Muhammad Rizki, SE',
                'jabatan' => 'Bendahara',
                'email' => 'muhammad.rizki@masjid.com',
                'phone' => '081234567893',
                'alamat' => 'Jl. Masjid Raya No. 4, Jakarta',
                'periode_mulai' => '2024-01-01',
                'periode_akhir' => '2027-12-31',
                'status' => 'aktif',
                'keterangan' => 'Bendahara DKM periode 2024-2027',
            ],
            [
                'nama' => 'Abdul Malik',
                'jabatan' => 'Pengurus',
                'email' => 'abdul.malik@masjid.com',
                'phone' => '081234567894',
                'alamat' => 'Jl. Masjid Raya No. 5, Jakarta',
                'periode_mulai' => '2024-01-01',
                'periode_akhir' => '2027-12-31',
                'status' => 'aktif',
                'keterangan' => 'Pengurus bidang keamanan',
            ],
            [
                'nama' => 'H. Abdullah',
                'jabatan' => 'Ketua (DKM)',
                'email' => 'abdullah@masjid.com',
                'phone' => '081234567895',
                'alamat' => 'Jl. Masjid Raya No. 1, Jakarta',
                'periode_mulai' => '2021-01-01',
                'periode_akhir' => '2023-12-31',
                'status' => 'nonaktif',
                'keterangan' => 'Ketua DKM periode 2021-2023 (periode sebelumnya)',
            ],
        ];

        foreach ($takmir as $data) {
            \App\Models\Takmir::create($data);
        }
    }
}
