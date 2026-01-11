<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('news')->insert([
            [
                'id' => 37,
                'title' => 'Masjid Al-Falah Gelar Khataman Al-Qur’an Akbar',
                'slug' => 'masjid-al-falah-gelar-khataman-al-quran-akbar-1765814129',
                'content' => 'Kegiatan Khataman Al-Qur’an Akbar yang diadakan pada Jumat, 13 Desember 2025, berjalan dengan khidmat dan penuh semangat.
Acara ini diikuti oleh lebih dari 120 jamaah dari berbagai kalangan, mulai dari anak-anak hingga lansia.
Kegiatan ditutup dengan doa bersama dan santunan bagi anak yatim.
Pengurus masjid menyampaikan terima kasih atas partisipasi seluruh jamaah.',
                'thumbnail' => 'news/MTwyQyGvyri9cz0X35sZ5GzEttVPqW7PrFPuShEt.jpg',
                'created_by' => 9,
                'published_at' => '2025-12-15 22:55:29',
                'created_at' => '2025-12-15 22:55:29',
                'updated_at' => '2025-12-15 22:55:29',
            ],
            [
                'id' => 38,
                'title' => 'Pemasangan Karpet Baru Selesai Dilakukan',
                'slug' => 'pemasangan-karpet-baru-selesai-dilakukan-1765814217',
                'content' => 'Alhamdulillah, proses pemasangan karpet baru di ruang utama Masjid Al-Falah telah selesai pada 10 Desember 2025.
Karpet ini merupakan hasil donasi dari jamaah dan donatur tetap masjid.
Diharapkan dengan karpet baru ini, kenyamanan jamaah dalam beribadah semakin meningkat.',
                'thumbnail' => 'news/Z8PIqPI7saaS6tO4Rc9ySoEEwlwAK3RRi3bm239x.jpg',
                'created_by' => 9,
                'published_at' => '2025-12-15 22:56:57',
                'created_at' => '2025-12-15 22:56:57',
                'updated_at' => '2025-12-15 22:56:57',
            ],
            [
                'id' => 39,
                'title' => 'Santri TPA Masjid Raih Juara 1 Lomba Hafalan Surah Pendek',
                'slug' => 'santri-tpa-masjid-raih-juara-1-lomba-hafalan-surah-pendek-1765814375',
                'content' => 'Anak didik TPA Masjid Al-Falah berhasil meraih Juara 1 Lomba Hafalan Surah Pendek tingkat kecamatan pada acara Festival Anak Sholeh, 7 Desember 2025.
Prestasi ini menjadi motivasi bagi santri lain untuk lebih semangat dalam belajar dan menghafal Al-Qur’an.',
                'thumbnail' => 'news/9Kgz1DBUPRAv0LGMtcHyzxrVoYhwrToh7ubQkvLP.jpg',
                'created_by' => 9,
                'published_at' => '2025-12-15 22:59:35',
                'created_at' => '2025-12-15 22:59:35',
                'updated_at' => '2025-12-15 22:59:35',
            ],
        ]);
    }
}
