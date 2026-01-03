<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventarisSeeder extends Seeder
{
    public function run(): void
    {
        // Aset
        $asets = [
            [
                'nama_aset' => 'Karpet Masjid Utama',
                'kategori' => 'Furnitur',
                'lokasi' => 'Ruang Sholat Utama',
                'tanggal_perolehan' => Carbon::now()->subYears(2)->format('Y-m-d'),
                'status' => 'aktif',
                'keterangan' => 'Karpet import ukuran 10x15 meter',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_aset' => 'AC Split 2 PK',
                'kategori' => 'Elektronik',
                'lokasi' => 'Ruang Sholat Utama',
                'tanggal_perolehan' => Carbon::now()->subYears(1)->format('Y-m-d'),
                'status' => 'aktif',
                'keterangan' => 'AC LG 2 PK untuk ruang utama',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_aset' => 'Sound System Masjid',
                'kategori' => 'Elektronik',
                'lokasi' => 'Ruang Sholat Utama',
                'tanggal_perolehan' => Carbon::now()->subMonths(18)->format('Y-m-d'),
                'status' => 'aktif',
                'keterangan' => 'TOA Sound System dengan 6 speaker',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_aset' => 'Lemari Es',
                'kategori' => 'Elektronik',
                'lokasi' => 'Dapur',
                'tanggal_perolehan' => Carbon::now()->subYears(3)->format('Y-m-d'),
                'status' => 'aktif',
                'keterangan' => 'Lemari es 2 pintu untuk penyimpanan',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_aset' => 'Meja Kayu',
                'kategori' => 'Furnitur',
                'lokasi' => 'Ruang Takmir',
                'tanggal_perolehan' => Carbon::now()->subYears(5)->format('Y-m-d'),
                'status' => 'aktif',
                'keterangan' => 'Meja kayu jati ukuran 2x1 meter',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_aset' => 'Kursi Plastik',
                'kategori' => 'Furnitur',
                'lokasi' => 'Aula Serbaguna',
                'tanggal_perolehan' => Carbon::now()->subYears(2)->format('Y-m-d'),
                'status' => 'aktif',
                'keterangan' => '100 buah kursi plastik untuk acara',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_aset' => 'Proyektor LCD',
                'kategori' => 'Elektronik',
                'lokasi' => 'Aula Serbaguna',
                'tanggal_perolehan' => Carbon::now()->subMonths(10)->format('Y-m-d'),
                'status' => 'aktif',
                'keterangan' => 'Proyektor Epson untuk presentasi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_aset' => 'Kipas Angin Gantung',
                'kategori' => 'Elektronik',
                'lokasi' => 'Ruang Sholat Wanita',
                'tanggal_perolehan' => Carbon::now()->subYears(4)->format('Y-m-d'),
                'status' => 'rusak',
                'keterangan' => '5 unit kipas angin, 1 unit rusak perlu perbaikan',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_aset' => 'Al-Quran Standar',
                'kategori' => 'Alat Ibadah',
                'lokasi' => 'Rak Buku Masjid',
                'tanggal_perolehan' => Carbon::now()->subYears(1)->format('Y-m-d'),
                'status' => 'aktif',
                'keterangan' => '50 mushaf Al-Quran ukuran A4',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_aset' => 'Mimbar Khatib',
                'kategori' => 'Furnitur',
                'lokasi' => 'Ruang Sholat Utama',
                'tanggal_perolehan' => Carbon::now()->subYears(10)->format('Y-m-d'),
                'status' => 'aktif',
                'keterangan' => 'Mimbar kayu jati ukir',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        
        DB::table('aset')->insert($asets);

        // Kondisi Barang (untuk beberapa aset)
        $kondisiBarang = [
            [
                'aset_id' => 1,
                'tanggal_pemeriksaan' => Carbon::now()->subDays(30)->format('Y-m-d'),
                'kondisi' => 'baik',
                'catatan' => 'Karpet dalam kondisi baik, tidak ada kerusakan',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'aset_id' => 2,
                'tanggal_pemeriksaan' => Carbon::now()->subDays(60)->format('Y-m-d'),
                'kondisi' => 'baik',
                'catatan' => 'AC berfungsi normal, sudah dilakukan service rutin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'aset_id' => 8,
                'tanggal_pemeriksaan' => Carbon::now()->subDays(10)->format('Y-m-d'),
                'kondisi' => 'perlu_perbaikan',
                'catatan' => '1 unit kipas tidak berputar, perlu penggantian motor',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        
        DB::table('kondisi_barang')->insert($kondisiBarang);

        // Jadwal Perawatan
        $jadwalPerawatan = [
            [
                'aset_id' => 2,
                'tanggal_jadwal' => Carbon::now()->addMonths(3)->format('Y-m-d'),
                'jenis_perawatan' => 'Service AC Rutin',
                'status' => 'dijadwalkan',
                'note' => 'Service rutin 3 bulanan untuk AC ruang utama',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'aset_id' => 3,
                'tanggal_jadwal' => Carbon::now()->addMonths(6)->format('Y-m-d'),
                'jenis_perawatan' => 'Kalibrasi Sound System',
                'status' => 'dijadwalkan',
                'note' => 'Kalibrasi dan pengecekan speaker',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'aset_id' => 8,
                'tanggal_jadwal' => Carbon::now()->addDays(7)->format('Y-m-d'),
                'jenis_perawatan' => 'Perbaikan Kipas Angin',
                'status' => 'dijadwalkan',
                'note' => 'Perbaikan motor kipas yang rusak',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        
        DB::table('jadwal_perawatan')->insert($jadwalPerawatan);
    }
}
