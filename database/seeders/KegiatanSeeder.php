<?php

namespace Database\Seeders;

use App\Models\Kegiatan;
use App\Models\KegiatanPeserta;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class KegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminKegiatan = User::where('username', 'admin_kegiatan')->first();
        $jamaahs = User::role('jamaah')->take(5)->get();

        // 1. Kegiatan Rutin - Sholat Berjamaah Subuh
        $subuh = Kegiatan::create([
            'nama_kegiatan' => 'Sholat Berjamaah Subuh',
            'jenis_kegiatan' => 'rutin',
            'kategori' => 'ibadah',
            'deskripsi' => 'Sholat berjamaah Subuh setiap hari di masjid',
            'tanggal_mulai' => Carbon::now()->startOfMonth(),
            'waktu_mulai' => '04:30:00',
            'waktu_selesai' => '05:30:00',
            'lokasi' => 'Masjid - Ruang Utama',
            'pic' => 'Ustadz Ahmad',
            'kontak_pic' => '081234567890',
            'status' => 'berlangsung',
            'is_recurring' => true,
            'recurring_type' => 'harian',
            'butuh_pendaftaran' => false,
            'created_by' => $adminKegiatan->id,
        ]);

        // 2. Kegiatan Rutin - Kajian Jumat
        $kajianJumat = Kegiatan::create([
            'nama_kegiatan' => 'Kajian Rutin Malam Jumat',
            'jenis_kegiatan' => 'rutin',
            'kategori' => 'kajian',
            'deskripsi' => 'Kajian Islam rutin setiap malam Jumat membahas berbagai tema keislaman',
            'tanggal_mulai' => Carbon::now()->next(Carbon::THURSDAY),
            'waktu_mulai' => '19:30:00',
            'waktu_selesai' => '21:00:00',
            'lokasi' => 'Masjid - Ruang Serbaguna',
            'pic' => 'Ustadz Abdullah',
            'kontak_pic' => '081234567891',
            'kuota_peserta' => 100,
            'jumlah_peserta' => 45,
            'status' => 'direncanakan',
            'is_recurring' => true,
            'recurring_type' => 'mingguan',
            'recurring_day' => 'Jumat',
            'butuh_pendaftaran' => true,
            'sertifikat_tersedia' => false,
            'created_by' => $adminKegiatan->id,
        ]);

        // Add peserta to Kajian Jumat
        foreach ($jamaahs as $index => $jamaah) {
            KegiatanPeserta::create([
                'kegiatan_id' => $kajianJumat->id,
                'user_id' => $jamaah->id,
                'nama_peserta' => $jamaah->name,
                'email' => $jamaah->email,
                'no_hp' => '0812345678' . ($index + 10),
                'status_pendaftaran' => 'dikonfirmasi',
                'metode_pendaftaran' => 'online',
            ]);
        }

        // 3. Kegiatan Rutin - Tahsin Quran
        $tahsin = Kegiatan::create([
            'nama_kegiatan' => 'Tahsin & Tahfidz Quran',
            'jenis_kegiatan' => 'rutin',
            'kategori' => 'pendidikan',
            'deskripsi' => 'Program pembelajaran membaca Al-Quran dengan baik dan benar serta menghafal',
            'tanggal_mulai' => Carbon::now()->addDays(2),
            'tanggal_selesai' => Carbon::now()->addMonths(3),
            'waktu_mulai' => '15:00:00',
            'waktu_selesai' => '17:00:00',
            'lokasi' => 'Masjid - Ruang Kelas',
            'pic' => 'Ustadzah Fatimah',
            'kontak_pic' => '081234567892',
            'kuota_peserta' => 30,
            'jumlah_peserta' => 28,
            'status' => 'direncanakan',
            'is_recurring' => true,
            'recurring_type' => 'mingguan',
            'recurring_day' => 'Sabtu,Minggu',
            'butuh_pendaftaran' => true,
            'sertifikat_tersedia' => true,
            'budget' => 5000000,
            'created_by' => $adminKegiatan->id,
        ]);

        // 4. Event Khusus - Peringatan Maulid Nabi
        $maulid = Kegiatan::create([
            'nama_kegiatan' => 'Peringatan Maulid Nabi Muhammad SAW 1446 H',
            'jenis_kegiatan' => 'event_khusus',
            'kategori' => 'maulid',
            'deskripsi' => 'Peringatan Maulid Nabi Muhammad SAW dengan ceramah, pembacaan sholawat, dan santunan anak yatim',
            'tanggal_mulai' => Carbon::create(2025, 12, 15),
            'waktu_mulai' => '08:00:00',
            'waktu_selesai' => '12:00:00',
            'lokasi' => 'Masjid - Area Parkir & Ruang Utama',
            'pic' => 'Panitia Maulid',
            'kontak_pic' => '081234567893',
            'kuota_peserta' => 500,
            'jumlah_peserta' => 120,
            'status' => 'direncanakan',
            'butuh_pendaftaran' => true,
            'sertifikat_tersedia' => false,
            'budget' => 15000000,
            'realisasi_biaya' => 0,
            'created_by' => $adminKegiatan->id,
        ]);

        // 5. Event Khusus - Ramadan 1446
        $ramadan = Kegiatan::create([
            'nama_kegiatan' => 'Program Ramadan 1446 H',
            'jenis_kegiatan' => 'event_khusus',
            'kategori' => 'ramadan',
            'deskripsi' => 'Program lengkap selama bulan Ramadan meliputi: Tarawih, Tadarus, Kajian Ramadan, Iftar Bersama, dan Tadarus Akbar',
            'tanggal_mulai' => Carbon::create(2026, 2, 28),
            'tanggal_selesai' => Carbon::create(2026, 3, 29),
            'waktu_mulai' => '04:00:00',
            'waktu_selesai' => '23:00:00',
            'lokasi' => 'Masjid - Semua Area',
            'pic' => 'Panitia Ramadan',
            'kontak_pic' => '081234567894',
            'status' => 'direncanakan',
            'butuh_pendaftaran' => false,
            'sertifikat_tersedia' => false,
            'budget' => 50000000,
            'catatan' => 'Mencakup berbagai program selama sebulan penuh',
            'created_by' => $adminKegiatan->id,
        ]);

        // 6. Event Khusus - Qurban 1446
        $qurban = Kegiatan::create([
            'nama_kegiatan' => 'Penyembelihan Hewan Qurban Idul Adha 1446 H',
            'jenis_kegiatan' => 'event_khusus',
            'kategori' => 'qurban',
            'deskripsi' => 'Pelaksanaan penyembelihan hewan qurban dan pembagian daging kepada mustahik',
            'tanggal_mulai' => Carbon::create(2026, 6, 7),
            'waktu_mulai' => '06:00:00',
            'waktu_selesai' => '14:00:00',
            'lokasi' => 'Masjid - Area Terbuka',
            'pic' => 'Panitia Qurban',
            'kontak_pic' => '081234567895',
            'status' => 'direncanakan',
            'butuh_pendaftaran' => true,
            'sertifikat_tersedia' => false,
            'budget' => 30000000,
            'catatan' => 'Pendaftaran untuk penyembelihan dan penerimaan daging',
            'created_by' => $adminKegiatan->id,
        ]);

        // 7. Kegiatan Insidental - Workshop Parenting Islami
        $workshop = Kegiatan::create([
            'nama_kegiatan' => 'Workshop Parenting Islami',
            'jenis_kegiatan' => 'insidental',
            'kategori' => 'pendidikan',
            'deskripsi' => 'Workshop mendidik anak dengan metode Islami untuk para orang tua',
            'tanggal_mulai' => Carbon::now()->addDays(10),
            'waktu_mulai' => '09:00:00',
            'waktu_selesai' => '15:00:00',
            'lokasi' => 'Masjid - Aula',
            'pic' => 'Ustadz Yusuf',
            'kontak_pic' => '081234567896',
            'kuota_peserta' => 50,
            'jumlah_peserta' => 32,
            'status' => 'direncanakan',
            'butuh_pendaftaran' => true,
            'sertifikat_tersedia' => true,
            'budget' => 8000000,
            'created_by' => $adminKegiatan->id,
        ]);

        // 8. Kegiatan Sosial - Bakti Sosial
        $baksos = Kegiatan::create([
            'nama_kegiatan' => 'Bakti Sosial ke Panti Asuhan',
            'jenis_kegiatan' => 'insidental',
            'kategori' => 'sosial',
            'deskripsi' => 'Kegiatan bakti sosial memberikan bantuan dan santunan ke panti asuhan sekitar masjid',
            'tanggal_mulai' => Carbon::now()->addDays(15),
            'waktu_mulai' => '08:00:00',
            'waktu_selesai' => '13:00:00',
            'lokasi' => 'Panti Asuhan Ar-Rahman',
            'pic' => 'Tim Sosial Masjid',
            'kontak_pic' => '081234567897',
            'kuota_peserta' => 25,
            'jumlah_peserta' => 18,
            'status' => 'direncanakan',
            'butuh_pendaftaran' => true,
            'budget' => 5000000,
            'created_by' => $adminKegiatan->id,
        ]);

        // 9. Kegiatan yang sudah selesai
        $kajianSelesai = Kegiatan::create([
            'nama_kegiatan' => 'Kajian Tafsir Al-Quran',
            'jenis_kegiatan' => 'rutin',
            'kategori' => 'kajian',
            'deskripsi' => 'Kajian Tafsir Juz 30 yang telah selesai dilaksanakan',
            'tanggal_mulai' => Carbon::now()->subDays(7),
            'tanggal_selesai' => Carbon::now()->subDays(1),
            'waktu_mulai' => '16:00:00',
            'waktu_selesai' => '17:30:00',
            'lokasi' => 'Masjid - Ruang Serbaguna',
            'pic' => 'Ustadz Ibrahim',
            'kontak_pic' => '081234567898',
            'kuota_peserta' => 60,
            'jumlah_peserta' => 55,
            'status' => 'selesai',
            'butuh_pendaftaran' => true,
            'sertifikat_tersedia' => true,
            'budget' => 3000000,
            'realisasi_biaya' => 2750000,
            'created_by' => $adminKegiatan->id,
        ]);

        // 10. Kegiatan yang dibatalkan
        $dibatalkan = Kegiatan::create([
            'nama_kegiatan' => 'Rekreasi Keluarga Jamaah',
            'jenis_kegiatan' => 'insidental',
            'kategori' => 'sosial',
            'deskripsi' => 'Kegiatan rekreasi bersama keluarga jamaah (DIBATALKAN karena cuaca)',
            'tanggal_mulai' => Carbon::now()->addDays(5),
            'waktu_mulai' => '07:00:00',
            'waktu_selesai' => '18:00:00',
            'lokasi' => 'Taman Wisata',
            'pic' => 'Panitia Rekreasi',
            'kontak_pic' => '081234567899',
            'kuota_peserta' => 100,
            'jumlah_peserta' => 45,
            'status' => 'dibatalkan',
            'butuh_pendaftaran' => true,
            'budget' => 10000000,
            'catatan' => 'Dibatalkan karena prediksi cuaca buruk',
            'created_by' => $adminKegiatan->id,
        ]);

        $this->command->info('✅ 10 Kegiatan berhasil dibuat!');
        $this->command->info('   - 3 Kegiatan Rutin');
        $this->command->info('   - 3 Event Khusus (Maulid, Ramadan, Qurban)');
        $this->command->info('   - 2 Kegiatan Insidental');
        $this->command->info('   - 1 Kegiatan Selesai');
        $this->command->info('   - 1 Kegiatan Dibatalkan');
    }
}
