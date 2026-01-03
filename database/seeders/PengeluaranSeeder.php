<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengeluaranSeeder extends Seeder
{
    public function run(): void
    {
        // Kategori Pengeluaran
        $kategoris = [
            ['nama_kategori' => 'Operasional', 'deskripsi' => 'Biaya operasional masjid harian', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Pemeliharaan', 'deskripsi' => 'Biaya pemeliharaan dan perbaikan', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Listrik & Air', 'deskripsi' => 'Biaya listrik dan air bersih', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Kebersihan', 'deskripsi' => 'Biaya kebersihan dan sanitasi', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Konsumsi', 'deskripsi' => 'Biaya konsumsi kegiatan', 'created_at' => now(), 'updated_at' => now()],
        ];
        
        DB::table('kategori_pengeluaran')->insert($kategoris);
        
        // Pengeluaran
        $pengeluarans = [
            [
                'user_id' => 1,
                'kategori_id' => 1,
                'judul_pengeluaran' => 'Gaji Imam dan Muazin',
                'deskripsi' => 'Gaji bulan Desember 2024',
                'jumlah' => 3000000,
                'tanggal' => Carbon::now()->subDays(30)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'kategori_id' => 3,
                'judul_pengeluaran' => 'Pembayaran Listrik',
                'deskripsi' => 'Listrik bulan Desember 2024',
                'jumlah' => 850000,
                'tanggal' => Carbon::now()->subDays(25)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'kategori_id' => 3,
                'judul_pengeluaran' => 'Pembayaran PDAM',
                'deskripsi' => 'Air bersih bulan Desember 2024',
                'jumlah' => 250000,
                'tanggal' => Carbon::now()->subDays(20)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'kategori_id' => 2,
                'judul_pengeluaran' => 'Perbaikan AC',
                'deskripsi' => 'Perbaikan AC ruang utama',
                'jumlah' => 1500000,
                'tanggal' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'kategori_id' => 4,
                'judul_pengeluaran' => 'Peralatan Kebersihan',
                'deskripsi' => 'Pembelian sapu, pel, sabun, dll',
                'jumlah' => 350000,
                'tanggal' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'kategori_id' => 5,
                'judul_pengeluaran' => 'Konsumsi Kajian Rutin',
                'deskripsi' => 'Snack dan minuman untuk 100 jamaah',
                'jumlah' => 750000,
                'tanggal' => Carbon::now()->subDays(7)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'kategori_id' => 1,
                'judul_pengeluaran' => 'ATK dan Fotocopy',
                'deskripsi' => 'Alat tulis kantor dan biaya fotocopy',
                'jumlah' => 150000,
                'tanggal' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'kategori_id' => 5,
                'judul_pengeluaran' => 'Konsumsi Maulid Nabi',
                'deskripsi' => 'Nasi kotak untuk 200 jamaah',
                'jumlah' => 2500000,
                'tanggal' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'kategori_id' => 2,
                'judul_pengeluaran' => 'Lampu LED Masjid',
                'deskripsi' => 'Pembelian dan pemasangan lampu LED 20 unit',
                'jumlah' => 650000,
                'tanggal' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 1,
                'kategori_id' => 4,
                'judul_pengeluaran' => 'Gaji Petugas Kebersihan',
                'deskripsi' => 'Gaji bulan Desember 2024',
                'jumlah' => 1200000,
                'tanggal' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        
        DB::table('pengeluaran')->insert($pengeluarans);
    }
}
