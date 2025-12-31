<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use App\Models\LaporanKegiatan;
use App\Models\Sertifikat;
use App\Models\Kegiatan;
use App\Models\User;
use Illuminate\Database\Seeder;

class ModulTambahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminKegiatan = User::where('username', 'admin_kegiatan')->first();
        if (!$adminKegiatan) {
            $this->command->warn('User admin_kegiatan not found. Skipping...');
            return;
        }

        // Get some completed kegiatans
        $kegiatans = Kegiatan::where('status', 'selesai')->take(5)->get();
        
        // =========================================================================
        // 1. PENGUMUMAN (Announcements)
        // =========================================================================
        $this->command->info('Creating Pengumuman...');
        
        $pengumumans = [
            [
                'judul' => 'Kajian Rutin Malam Jumat - Tema Keikhlasan',
                'kategori' => 'kajian',
                'konten' => 'Assalamualaikum Warahmatullahi Wabarakatuh. Mengundang seluruh jamaah untuk mengikuti kajian rutin yang akan dilaksanakan pada Jumat, 15 Desember 2025 pukul 19.30 WIB. Tema kali ini adalah "Keikhlasan dalam Beramal". Narasumber: Ustadz Ahmad Fauzi, Lc. Lokasi: Ruang Utama Masjid. Jazakumullahu khairan.',
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(10),
                'status' => 'aktif',
                'prioritas' => 'tinggi',
                'views' => 245,
                'kegiatan_id' => $kegiatans->first()?->id,
                'created_by' => $adminKegiatan->id,
            ],
            [
                'judul' => 'Peringatan Maulid Nabi Muhammad SAW 1447 H',
                'kategori' => 'event',
                'konten' => 'Dalam rangka memperingati Maulid Nabi Muhammad SAW 1447 H, Takmir Masjid mengundang seluruh jamaah untuk hadir dalam acara peringatan yang akan diadakan pada hari Minggu, 22 Desember 2025 pukul 08.00 WIB. Acara meliputi: Tausiyah, Sholawat Nabi, dan Doa Bersama. Diharapkan hadir tepat waktu.',
                'tanggal_mulai' => now()->addDays(5),
                'tanggal_berakhir' => now()->addDays(15),
                'status' => 'aktif',
                'prioritas' => 'mendesak',
                'views' => 589,
                'created_by' => $adminKegiatan->id,
            ],
            [
                'judul' => 'Tahsin Al-Quran Batch 3 - Pendaftaran Dibuka',
                'kategori' => 'kegiatan',
                'konten' => 'Program Tahsin Al-Quran Batch 3 akan segera dimulai bulan Januari 2026. Pendaftaran dibuka mulai hari ini sampai 31 Desember 2025. Kuota terbatas hanya 30 peserta. Waktu pembelajaran: Setiap hari Selasa dan Kamis pukul 16.00-17.30 WIB. Biaya: Gratis. Hubungi Admin Kegiatan untuk pendaftaran.',
                'tanggal_mulai' => now(),
                'tanggal_berakhir' => now()->addDays(25),
                'status' => 'aktif',
                'prioritas' => 'tinggi',
                'views' => 412,
                'created_by' => $adminKegiatan->id,
            ],
            [
                'judul' => 'Pengumuman Libur Sekretariat Masjid',
                'kategori' => 'umum',
                'konten' => 'Diberitahukan kepada seluruh jamaah bahwa sekretariat masjid akan libur pada tanggal 25-31 Desember 2025 dalam rangka cuti bersama akhir tahun. Untuk keperluan mendesak dapat menghubungi nomor darurat yang tertera di papan pengumuman masjid. Mohon maaf atas ketidaknyamanannya.',
                'tanggal_mulai' => now()->addDays(10),
                'tanggal_berakhir' => now()->addDays(20),
                'status' => 'aktif',
                'prioritas' => 'normal',
                'views' => 156,
                'created_by' => $adminKegiatan->id,
            ],
            [
                'judul' => 'Pelatihan Manasik Haji & Umroh 2026',
                'kategori' => 'kegiatan',
                'konten' => 'Takmir Masjid mengadakan Pelatihan Manasik Haji & Umroh untuk calon jamaah tahun 2026. Pelatihan akan dilaksanakan selama 3 hari (20-22 Januari 2026). Materi: Tata cara Haji & Umroh, Praktik Manasik, Kesehatan Haji, dll. Biaya: Rp 150.000/peserta (sudah termasuk modul dan makan). Pendaftaran: 081234567890 (Ustadz Ali)',
                'tanggal_mulai' => now()->addDays(-5),
                'tanggal_berakhir' => now()->addDays(40),
                'status' => 'aktif',
                'prioritas' => 'tinggi',
                'views' => 678,
                'created_by' => $adminKegiatan->id,
            ],
        ];

        foreach ($pengumumans as $data) {
            Pengumuman::create($data);
        }

        $this->command->info('Created ' . count($pengumumans) . ' pengumuman.');

        // =========================================================================
        // 2. LAPORAN KEGIATAN (Activity Reports)
        // =========================================================================
        $this->command->info('Creating Laporan Kegiatan...');
        
        if ($kegiatans->count() > 0) {
            foreach ($kegiatans as $index => $kegiatan) {
                $peserta = rand(50, 150);
                $hadir = rand(40, $peserta);
                
                LaporanKegiatan::create([
                    'kegiatan_id' => $kegiatan->id,
                    'nama_kegiatan' => $kegiatan->nama_kegiatan,
                    'jenis_kegiatan' => match($kegiatan->kategori) {
                        'kajian' => 'kajian',
                        'sosial' => 'sosial',
                        'pendidikan' => 'pendidikan',
                        'ramadan', 'maulid', 'qurban' => 'perayaan',
                        default => 'lainnya',
                    },
                    'tanggal_pelaksanaan' => $kegiatan->tanggal_mulai,
                    'waktu_pelaksanaan' => $kegiatan->waktu_mulai,
                    'lokasi' => $kegiatan->lokasi,
                    'jumlah_peserta' => $peserta,
                    'jumlah_hadir' => $hadir,
                    'jumlah_tidak_hadir' => $peserta - $hadir,
                    'penanggung_jawab' => $kegiatan->pic,
                    'deskripsi' => "Kegiatan {$kegiatan->nama_kegiatan} telah dilaksanakan dengan lancar. Antusiasme jamaah cukup tinggi dengan kehadiran {$hadir} dari {$peserta} peserta yang terdaftar. Materi disampaikan dengan baik dan peserta aktif bertanya.",
                    'hasil_capaian' => "Target kegiatan tercapai dengan baik. Peserta memperoleh pemahaman yang lebih mendalam tentang materi yang disampaikan. Feedback dari peserta sangat positif.",
                    'catatan_kendala' => $index % 3 == 0 ? "Sound system kurang optimal di bagian belakang ruangan. Perlu penambahan speaker." : null,
                    'foto_dokumentasi' => null,
                    'status' => 'published',
                    'is_public' => true,
                    'created_by' => $adminKegiatan->id,
                ]);
            }
            
            $this->command->info('Created ' . $kegiatans->count() . ' laporan kegiatan.');
        }

        // =========================================================================
        // 3. SERTIFIKAT (Certificates)
        // =========================================================================
        $this->command->info('Creating Sertifikat...');
        
        if ($kegiatans->count() > 0) {
            $kegiatan = $kegiatans->first();
            $pesertaNames = [
                'Ahmad Fauzi',
                'Siti Nurhaliza',
                'Budi Santoso',
                'Aisyah Rahmawati',
                'Muhammad Rizki',
                'Fatimah Azzahra',
                'Abdullah Rahman',
                'Khadijah Salsabila',
            ];

            foreach ($pesertaNames as $index => $nama) {
                Sertifikat::create([
                    'kegiatan_id' => $kegiatan->id,
                    'nomor_sertifikat' => Sertifikat::generateNomorSertifikat($kegiatan->id, $index + 1),
                    'nama_peserta' => $nama,
                    'nama_kegiatan' => $kegiatan->nama_kegiatan,
                    'tanggal_kegiatan' => $kegiatan->tanggal_mulai,
                    'tempat_kegiatan' => $kegiatan->lokasi,
                    'template' => ['kajian', 'workshop', 'pelatihan'][rand(0, 2)],
                    'ttd_pejabat' => 'Ustadz Ahmad Fauzi, Lc.',
                    'jabatan_pejabat' => 'Ketua Takmir Masjid',
                    'download_count' => rand(0, 3),
                    'last_downloaded_at' => rand(0, 1) ? now()->subDays(rand(1, 7)) : null,
                    'metadata' => [
                        'generated_at' => now()->toDateTimeString(),
                        'batch_id' => 'BATCH-' . now()->format('Ymd') . '-001',
                    ],
                    'generated_by' => $adminKegiatan->id,
                ]);
            }
            
            $this->command->info('Created ' . count($pesertaNames) . ' sertifikat.');
        }

        $this->command->info('Modul Tambahan seeding completed successfully!');
    }
}
