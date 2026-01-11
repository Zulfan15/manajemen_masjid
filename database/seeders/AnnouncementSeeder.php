<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnnouncementsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('announcements')->insert([
            [
                'id' => 5,
                'title' => 'Kegiatan Gotong Royong Bersih Masjid',
                'slug' => 'kegiatan-gotong-royong-bersih-masjid',
                'content' => 'Assalamu’alaikum warahmatullahi wabarakatuh.
Diumumkan kepada seluruh jamaah agar hadir pada kegiatan gotong royong bersih-bersih masjid yang akan dilaksanakan pada Sabtu, 20 Desember 2025 pukul 07.00 WIB.
Mari bersama menjaga kebersihan rumah Allah. Diharapkan membawa alat kebersihan masing-masing.
Jazakumullah khairan katsiran.',
                'type' => 'penting',
                'start_date' => '2025-12-20',
                'end_date' => '2025-12-20',
                'created_by' => 9,
                'created_at' => '2025-12-15 22:49:40',
                'updated_at' => '2025-12-15 22:49:40',
            ],
            [
                'id' => 6,
                'title' => 'Kajian Rutin Ahad Pagi',
                'slug' => 'kajian-rutin-ahad-pagi',
                'content' => 'InsyaAllah akan dilaksanakan kajian rutin Ahad pagi bersama Ustadz Ahmad Zainuddin, Lc.
Hari/Tanggal: Ahad, 21 Desember 2025
Waktu: Pukul 05.30 WIB – selesai
Tempat: Masjid Al-Falah
Tema: Menjaga Hati di Era Digital',
                'type' => 'penting',
                'start_date' => '2025-12-21',
                'end_date' => '2025-12-21',
                'created_by' => 9,
                'created_at' => '2025-12-15 22:50:24',
                'updated_at' => '2025-12-15 22:50:24',
            ],
            [
                'id' => 7,
                'title' => 'Pengumpulan Zakat, Infaq, dan Sedekah',
                'slug' => 'pengumpulan-zakat-infaq-dan-sedekah',
                'content' => 'Masjid Al-Falah membuka kesempatan bagi jamaah untuk menunaikan Zakat, Infaq, dan Sedekah selama bulan Januari 2026.
Penyaluran dapat dilakukan melalui kotak amal di masjid dan transfer ke rekening BSI.
Semoga Allah memberkahi rezeki kita semua.',
                'type' => 'penting',
                'start_date' => '2026-01-01',
                'end_date' => '2026-01-31',
                'created_by' => 9,
                'created_at' => '2025-12-15 22:51:27',
                'updated_at' => '2025-12-15 22:51:27',
            ],
            [
                'id' => 8,
                'title' => 'Lomba Hafalan Juz 30 Anak-Anak',
                'slug' => 'lomba-hafalan-juz-30-anak-anak',
                'content' => 'Dalam rangka menyambut bulan Rajab, Masjid Al-Falah mengadakan Lomba Hafalan Juz 30 untuk anak-anak usia SD–SMP.
Pendaftaran dibuka: 10–12 Februari 2026
Pelaksanaan lomba: 15 Februari 2026',
                'type' => 'penting',
                'start_date' => '2026-02-10',
                'end_date' => '2026-02-15',
                'created_by' => 9,
                'created_at' => '2025-12-15 22:52:47',
                'updated_at' => '2025-12-15 22:52:47',
            ],
        ]);
    }
}
