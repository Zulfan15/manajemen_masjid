<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemilihan;
use App\Models\Kandidat;
use App\Models\Takmir;
use Carbon\Carbon;

class PemilihanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah ada pengurus aktif
        $pengurusAktif = Takmir::aktif()->where('jabatan', '!=', 'Ketua DKM')->limit(3)->get();

        if ($pengurusAktif->count() < 3) {
            $this->command->warn('Minimal butuh 3 pengurus aktif (bukan Ketua) untuk kandidat.');
            return;
        }

        // Buat pemilihan yang sedang berlangsung
        $pemilihanAktif = Pemilihan::create([
            'judul' => 'Pemilihan Ketua DKM Periode 2026-2028',
            'deskripsi' => 'Pemilihan Ketua Dewan Kemakmuran Masjid (DKM) untuk periode 2026-2028. Setiap jamaah berhak memberikan 1 suara untuk memilih ketua DKM yang akan memimpin selama 2 tahun ke depan.',
            'tanggal_mulai' => Carbon::now()->subDays(1),
            'tanggal_selesai' => Carbon::now()->addDays(7),
            'status' => 'aktif',
            'tampilkan_hasil' => true, // Hasil langsung terlihat
        ]);

        // Tambahkan 3 kandidat
        $kandidatData = [
            [
                'nomor_urut' => 1,
                'visi' => 'Mewujudkan masjid yang ramai, bersih, dan menjadi pusat kegiatan umat.',
                'misi' => "1. Meningkatkan kualitas kajian Islam rutin\n2. Memperbaiki fasilitas masjid secara berkala\n3. Menggalang dana untuk renovasi masjid\n4. Mengadakan kegiatan sosial untuk masyarakat sekitar\n5. Membina remaja masjid menjadi generasi Qurani",
            ],
            [
                'nomor_urut' => 2,
                'visi' => 'Masjid sebagai rumah Allah yang nyaman, bersih, dan menjadi sentral pembinaan umat.',
                'misi' => "1. Meningkatkan program tahfidz dan TPA\n2. Mengoptimalkan pengelolaan keuangan masjid\n3. Membangun hubungan baik dengan masyarakat\n4. Mengadakan program santunan rutin\n5. Digitalisasi administrasi masjid",
            ],
            [
                'nomor_urut' => 3,
                'visi' => 'Masjid yang inklusif, modern, dan menjadi teladan dalam pengelolaan.',
                'misi' => "1. Transparansi pengelolaan keuangan masjid\n2. Program kajian yang beragam dan berkualitas\n3. Pemberdayaan ekonomi jamaah\n4. Renovasi masjid secara bertahap\n5. Membangun ekosistem digital masjid",
            ],
        ];

        foreach ($kandidatData as $index => $data) {
            Kandidat::create([
                'pemilihan_id' => $pemilihanAktif->id,
                'takmir_id' => $pengurusAktif[$index]->id,
                'nomor_urut' => $data['nomor_urut'],
                'visi' => $data['visi'],
                'misi' => $data['misi'],
                'foto' => null, // Akan menggunakan foto dari takmir
            ]);
        }

        $this->command->info('✓ Pemilihan aktif berhasil dibuat dengan 3 kandidat');
        $this->command->info('  Judul: ' . $pemilihanAktif->judul);
        $this->command->info('  Periode: ' . $pemilihanAktif->tanggal_mulai->format('d/m/Y') . ' - ' . $pemilihanAktif->tanggal_selesai->format('d/m/Y'));
        $this->command->info('  Kandidat:');
        foreach ($pengurusAktif as $index => $pengurus) {
            $this->command->info('    ' . ($index + 1) . '. ' . $pengurus->nama . ' (' . $pengurus->jabatan . ')');
        }
    }
}

