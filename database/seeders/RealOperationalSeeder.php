<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\KategoriPengeluaran;
use App\Models\Kegiatan;
use App\Models\Aset;
use App\Models\Transaksi; // ZIS
use App\Models\Musakki; // Perlu cek nama class yg benar
use App\Models\Muzakki; // Biasanya ini
use App\Models\JamaahCategory;
use Carbon\Carbon;
use Faker\Factory as Faker;

class RealOperationalSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Ambil user jamaah yang sudah ada
        $jamaahUsers = User::role('jamaah')->get();
        if ($jamaahUsers->isEmpty()) {
            $this->command->error("Harap jalankan RealJamaahSeeder terlebih dahulu!");
            return;
        }

        // ==========================================
        // 1. KEUANGAN & ZIS
        // ==========================================
        $this->command->info("Creating Financial Data...");

        // Kategori Pengeluaran
        $catListrik = KategoriPengeluaran::firstOrCreate(['nama_kategori' => 'Listrik & Air'], ['deskripsi' => 'Tagihan bulanan']);
        $catKebersihan = KategoriPengeluaran::firstOrCreate(['nama_kategori' => 'Kebersihan'], ['deskripsi' => 'Alat dan upah kebersihan']);
        $catInventaris = KategoriPengeluaran::firstOrCreate(['nama_kategori' => 'Inventaris & Aset'], ['deskripsi' => 'Pembelian aset']);

        // A. Pemasukan (Infak Jumat & Donasi)
        for ($i = 0; $i < 5; $i++) {
            Pemasukan::create([
                'jenis' => $faker->randomElement(['Infak', 'Sedekah']), // Capitalized
                'jumlah' => $faker->numberBetween(50000, 500000),
                'tanggal' => Carbon::now()->subDays(rand(1, 28)),
                'sumber' => 'Kotak Amal Jumat',
                'keterangan' => 'Infak rutin jamaah',
                'user_id' => $jamaahUsers->random()->id,
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => 1 // Asumsi admin ID 1
            ]);
        }

        // B. Pengeluaran (Listrik & Air)
        Pengeluaran::create([
            'user_id' => 1,
            'kategori_id' => $catListrik->id,
            'judul_pengeluaran' => 'Bayar Listrik Bulan Ini',
            'deskripsi' => 'Tagihan PLN Masjid',
            'jumlah' => 1500000,
            'tanggal' => Carbon::now()->startOfMonth()->addDays(5),
            'bukti_transaksi' => null
        ]);

        // C. Transaksi ZIS (Sinkronisasi Otomatis akan jalan logicnya di controller, tapi di seeder kita manual)
        // Kita butuh Muzakki dulu
        $muzakkiUser = $jamaahUsers->random();

        // Cek dulu apakah model Muzakki ada
        if (class_exists('App\Models\Muzakki')) {
            $muzakki = \App\Models\Muzakki::firstOrCreate(
                ['nama_lengkap' => $muzakkiUser->name],
                [
                    // 'nik' => $faker->nik, // Jika perlu
                    'no_hp' => $faker->phoneNumber,
                    'alamat' => $faker->address,
                    'jenis_kelamin' => 'L' // Default
                ]
            );

            // Buat Transaksi ZIS
            if (class_exists('App\Models\Transaksi')) {
                \App\Models\Transaksi::create([
                    'kode_transaksi' => 'ZIS-' . strtoupper(\Illuminate\Support\Str::random(6)),
                    'muzakki_id' => $muzakki->id,
                    'user_id' => 1,
                    'jenis_transaksi' => 'zakat mal', // Match Enum
                    'nominal' => 2500000,
                    'keterangan' => 'Zakat Maal Tahunan',
                    'tanggal_transaksi' => now()->subDays(2),
                ]);

                // Manual Sync ke Pemasukan (karena seeder tidak lewat controller)
                Pemasukan::create([
                    'jenis' => 'Zakat', // Match Enum
                    'jumlah' => 2500000,
                    'tanggal' => now()->subDays(2),
                    'sumber' => "ZIS - {$muzakki->nama_lengkap}",
                    'keterangan' => "[Seeder] Zakat Maal",
                    'user_id' => 1,
                    'status' => 'verified',
                    'verified_at' => now(),
                    'verified_by' => 1,
                ]);
            }
        }

        // ==========================================
        // 2. KEGIATAN
        // ==========================================
        $this->command->info("Creating Kegiatan Data...");

        $kegiatan = [
            [
                'nama' => 'Kajian Rutin Malam Jumat',
                'jenis' => 'rutin',
                'kategori' => 'kajian',
                'desc' => 'Pembahasan Kitab Riyadhus Shalihin',
                'tgl' => Carbon::now()->next('Thursday')->setTime(19, 30),
                'status' => 'direncanakan'
            ],
            [
                'nama' => 'Gotong Royong Masjid',
                'jenis' => 'insidental',
                'kategori' => 'sosial',
                'desc' => 'Membersihkan area masjid dan perbaikan ringan',
                'tgl' => Carbon::now()->previous('Sunday')->setTime(7, 0),
                'status' => 'selesai'
            ],
            [
                'nama' => 'Santunan Anak Yatim',
                'jenis' => 'event_khusus',
                'kategori' => 'sosial',
                'desc' => 'Pemberian santunan bulanan',
                'tgl' => Carbon::now()->addDays(7)->setTime(16, 0),
                'status' => 'direncanakan'
            ]
        ];

        foreach ($kegiatan as $k) {
            Kegiatan::create([
                'nama_kegiatan' => $k['nama'],
                'jenis_kegiatan' => $k['jenis'],
                'kategori' => $k['kategori'],
                'deskripsi' => $k['desc'],
                'tanggal_mulai' => $k['tgl']->format('Y-m-d'),
                'tanggal_selesai' => $k['tgl']->format('Y-m-d'),
                'waktu_mulai' => $k['tgl']->format('H:i'),
                'waktu_selesai' => $k['tgl']->addHours(2)->format('H:i'),
                'lokasi' => 'Masjid Raya',
                'pic' => 'Ustadz Abdullah',
                'kontak_pic' => '08123456789',
                'kuota_peserta' => 100,
                'jumlah_peserta' => 0,
                'status' => $k['status'],
                'created_by' => 1
            ]);
        }

        // ==========================================
        // 3. INVENTARIS
        // ==========================================
        $this->command->info("Creating Inventaris Data...");

        $aset = Aset::create([
            'nama_aset' => 'Wireless Mic Shure',
            'kategori' => 'Elektronik',
            'lokasi' => 'Mimbar Utama',
            'tanggal_perolehan' => now()->subMonths(1),
            'status' => 'aktif',
            'harga_perolehan' => 3500000,
            'keterangan' => 'Mic utama imam',
        ]);

        // Manual Sync Pengeluaran Inventaris
        Pengeluaran::create([
            'user_id' => 1,
            'kategori_id' => $catInventaris->id,
            'judul_pengeluaran' => "Pembelian Aset: {$aset->nama_aset}",
            'deskripsi' => "[Seeder] Pembelian aset baru",
            'jumlah' => $aset->harga_perolehan,
            'tanggal' => $aset->tanggal_perolehan,
        ]);
    }
}
