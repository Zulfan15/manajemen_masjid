<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Pengeluaran2Seeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // KATEGORI (JIKA BELUM ADA)
        // =========================
        if (DB::table('kategori_pengeluaran')->count() === 0) {
            DB::table('kategori_pengeluaran')->insert([
                ['nama_kategori' => 'Operasional', 'deskripsi' => 'Biaya operasional masjid', 'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'Pemeliharaan', 'deskripsi' => 'Biaya perawatan', 'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'Listrik & Air', 'deskripsi' => 'Tagihan listrik dan air', 'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'Kebersihan', 'deskripsi' => 'Kebersihan masjid', 'created_at' => now(), 'updated_at' => now()],
                ['nama_kategori' => 'Konsumsi', 'deskripsi' => 'Konsumsi kegiatan', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // =========================
        // PENGELUARAN 1 TAHUN PENUH
        // =========================
        $tahun = now()->year;
        $data = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $tanggal = Carbon::create($tahun, $bulan, rand(1, 25));

            $data[] = [
                'user_id' => 1,
                'kategori_id' => rand(1, 5),
                'judul_pengeluaran' => 'Pengeluaran Bulan ' . $tanggal->translatedFormat('F Y'),
                'deskripsi' => 'Pengeluaran rutin masjid bulan ' . $tanggal->translatedFormat('F'),
                'jumlah' => rand(500000, 5000000),
                'tanggal' => $tanggal->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Tambahan 1–2 transaksi agar grafik lebih hidup
            if (rand(0, 1)) {
                $data[] = [
                    'user_id' => 1,
                    'kategori_id' => rand(1, 5),
                    'judul_pengeluaran' => 'Pengeluaran Tambahan ' . $tanggal->translatedFormat('F'),
                    'deskripsi' => 'Biaya tambahan kegiatan',
                    'jumlah' => rand(300000, 2000000),
                    'tanggal' => $tanggal->addDays(rand(1, 3))->format('Y-m-d'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('pengeluaran')->insert($data);
    }
}
