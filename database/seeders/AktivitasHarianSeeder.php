<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AktivitasHarian;
use App\Models\Takmir;
use Carbon\Carbon;

class AktivitasHarianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil pengurus aktif
        $pengurusAktif = Takmir::aktif()->get();

        if ($pengurusAktif->isEmpty()) {
            $this->command->warn('Tidak ada pengurus aktif. Jalankan TakmirSeeder terlebih dahulu.');
            return;
        }

        $jenisAktivitas = ['Ibadah', 'Kebersihan', 'Administrasi', 'Pengajaran', 'Pembinaan', 'Keuangan', 'Sosial', 'Lainnya'];

        // Data aktivitas untuk 30 hari terakhir
        $aktivitasData = [
            [
                'jenis' => 'Ibadah',
                'deskripsi' => [
                    'Memimpin sholat Jumat dan memberikan khutbah tentang akhlak kepada sesama.',
                    'Mengimami sholat subuh berjamaah dan memberikan kultum singkat.',
                    'Mengadakan kajian rutin setelah sholat Maghrib tentang tafsir Al-Quran.',
                    'Memimpin sholat tarawih dan memberikan tausiyah Ramadhan.',
                ],
                'durasi' => [2.0, 1.5, 1.0, 2.5],
            ],
            [
                'jenis' => 'Kebersihan',
                'deskripsi' => [
                    'Membersihkan area wudhu dan toilet masjid serta mengecek ketersediaan air.',
                    'Menyapu dan mengepel lantai utama masjid sebelum sholat Jumat.',
                    'Membersihkan halaman masjid dari daun-daun kering dan sampah.',
                    'Menata karpet masjid dan membersihkan debu di sudut-sudut ruangan.',
                ],
                'durasi' => [1.5, 2.0, 1.0, 1.5],
            ],
            [
                'jenis' => 'Administrasi',
                'deskripsi' => [
                    'Mencatat dan merekap data jamaah yang mendaftar untuk mengikuti umrah.',
                    'Membuat laporan keuangan bulanan dan mengarsipkan bukti transaksi.',
                    'Menyusun jadwal kegiatan masjid untuk bulan depan dan mensosialisasikannya.',
                    'Input data donasi ke sistem dan membuat surat terima kasih untuk donatur.',
                ],
                'durasi' => [3.0, 2.5, 2.0, 1.5],
            ],
            [
                'jenis' => 'Pengajaran',
                'deskripsi' => [
                    'Mengajar TPA anak-anak tentang tajwid dan hafalan surat-surat pendek.',
                    'Memberikan bimbingan baca Al-Quran untuk dewasa pemula (iqro).',
                    'Mengadakan kelas bahasa Arab dasar untuk remaja masjid.',
                    'Membimbing kajian kitab kuning untuk santri tingkat lanjut.',
                ],
                'durasi' => [2.0, 1.5, 2.5, 3.0],
            ],
            [
                'jenis' => 'Pembinaan',
                'deskripsi' => [
                    'Rapat koordinasi dengan pengurus masjid membahas program kerja triwulan.',
                    'Pembinaan remaja masjid dan perencanaan kegiatan pemuda.',
                    'Membina majelis taklim ibu-ibu tentang fiqih sehari-hari.',
                    'Konseling dan bimbingan untuk jamaah yang memiliki masalah keluarga.',
                ],
                'durasi' => [2.0, 1.5, 2.0, 1.0],
            ],
            [
                'jenis' => 'Keuangan',
                'deskripsi' => [
                    'Menghitung dan merekap penerimaan infaq sholat Jumat beserta rinciannya.',
                    'Membayar tagihan listrik dan air masjid serta mencatat pengeluaran.',
                    'Melakukan verifikasi kas bulanan dan mencocokkan dengan laporan.',
                    'Menyiapkan proposal pengajuan dana untuk renovasi masjid.',
                ],
                'durasi' => [1.5, 1.0, 2.0, 2.5],
            ],
            [
                'jenis' => 'Sosial',
                'deskripsi' => [
                    'Mengunjungi dan mendoakan jamaah yang sedang sakit di rumah sakit.',
                    'Mengkoordinir pengumpulan dan distribusi zakat fitrah kepada mustahik.',
                    'Mengadakan acara bukber dan santunan anak yatim di bulan Ramadhan.',
                    'Membantu jamaah yang terkena musibah dengan memberikan bantuan.',
                ],
                'durasi' => [2.0, 3.0, 4.0, 1.5],
            ],
            [
                'jenis' => 'Lainnya',
                'deskripsi' => [
                    'Mengecek dan memperbaiki sound system masjid yang mengalami gangguan.',
                    'Berkoordinasi dengan panitia untuk persiapan perayaan PHBI.',
                    'Memasang spanduk pengumuman kegiatan masjid di lokasi strategis.',
                    'Rapat dengan arsitek untuk pembahasan rencana perluasan masjid.',
                ],
                'durasi' => [1.5, 2.0, 1.0, 2.5],
            ],
        ];

        $createdCount = 0;

        // Generate 50 aktivitas random untuk 30 hari terakhir
        for ($i = 0; $i < 50; $i++) {
            $randomPengurus = $pengurusAktif->random();
            $randomJenis = $jenisAktivitas[array_rand($jenisAktivitas)];

            // Cari data yang sesuai dengan jenis
            $dataJenis = collect($aktivitasData)->firstWhere('jenis', $randomJenis);

            if ($dataJenis) {
                $randomIndex = array_rand($dataJenis['deskripsi']);
                $deskripsi = $dataJenis['deskripsi'][$randomIndex];
                $durasi = $dataJenis['durasi'][$randomIndex];
            } else {
                $deskripsi = 'Aktivitas ' . $randomJenis . ' yang dilakukan oleh pengurus masjid.';
                $durasi = rand(10, 40) / 10; // 1.0 - 4.0 jam
            }

            AktivitasHarian::create([
                'takmir_id' => $randomPengurus->id,
                'tanggal' => Carbon::now()->subDays(rand(0, 30))->format('Y-m-d'),
                'jenis_aktivitas' => $randomJenis,
                'deskripsi' => $deskripsi,
                'durasi_jam' => $durasi,
                'bukti_foto' => null, // Foto opsional
            ]);

            $createdCount++;
        }

        $this->command->info("✓ Berhasil membuat {$createdCount} data aktivitas harian");
    }
}
