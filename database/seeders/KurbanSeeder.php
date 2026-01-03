<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kurban;
use App\Models\PesertaKurban;
use App\Models\DistribusiKurban;
use App\Models\User;
use Carbon\Carbon;

class KurbanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminKurban = User::where('username', 'admin_kurban')->first();
        $jamaahs = User::role('jamaah')->get();

        // 1. Sapi Limosin - Sudah Terdistribusi
        $sapi1 = Kurban::create([
            'nomor_kurban' => 'KRB-' . date('Y') . '-001',
            'jenis_hewan' => 'sapi',
            'nama_hewan' => 'Sapi Limosin',
            'berat_badan' => 450.5,
            'kondisi_kesehatan' => 'sehat',
            'harga_hewan' => 24000000,
            'biaya_operasional' => 1000000,
            'total_biaya' => 25000000,
            'status' => 'didistribusi',
            'tanggal_persiapan' => Carbon::now()->subDays(10),
            'tanggal_penyembelihan' => Carbon::now()->subDays(3),
            'catatan' => 'Sapi limosin kualitas premium, sudah disembelih dan didistribusikan. Dibagi menjadi 7 bagian untuk peserta.',
            'created_by' => $adminKurban->id ?? 1,
        ]);

        // Peserta untuk Sapi 1 (7 orang)
        $pesertaSapi1 = [
            ['nama' => 'H. Ahmad Fauzi', 'jumlah_bagian' => 1, 'total_bayar' => 3500000],
            ['nama' => 'Budi Santoso', 'jumlah_bagian' => 1, 'total_bayar' => 3500000],
            ['nama' => 'Siti Aminah', 'jumlah_bagian' => 1, 'total_bayar' => 3500000],
            ['nama' => 'Muhammad Rizki', 'jumlah_bagian' => 1, 'total_bayar' => 3500000],
            ['nama' => 'Abdul Malik', 'jumlah_bagian' => 1, 'total_bayar' => 3500000],
            ['nama' => 'Fatimah Zahra', 'jumlah_bagian' => 1, 'total_bayar' => 3500000],
            ['nama' => 'Usman bin Affan', 'jumlah_bagian' => 1, 'total_bayar' => 3500000],
        ];

        foreach ($pesertaSapi1 as $index => $peserta) {
            PesertaKurban::create([
                'kurban_id' => $sapi1->id,
                'nama_peserta' => $peserta['nama'],
                'nomor_telepon' => '08123456789' . $index,
                'alamat' => 'Jl. Masjid Raya No. ' . ($index + 1) . ', Jakarta',
                'tipe_peserta' => 'perorangan',
                'jumlah_jiwa' => 1,
                'jumlah_bagian' => $peserta['jumlah_bagian'],
                'nominal_pembayaran' => $peserta['total_bayar'],
                'status_pembayaran' => 'lunas',
                'tanggal_pembayaran' => Carbon::now()->subDays(10),
                'created_by' => $adminKurban->id ?? 1,
            ]);
        }

        // Distribusi untuk Sapi 1
        $penerima = [
            ['nama' => 'Yayasan Anak Yatim Al-Ikhlas', 'jenis' => 'fakir_miskin', 'berat' => 20],
            ['nama' => 'Masjid Al-Hidayah', 'jenis' => 'lainnya', 'berat' => 18],
            ['nama' => 'Panti Asuhan Ar-Rahman', 'jenis' => 'fakir_miskin', 'berat' => 22],
            ['nama' => 'Keluarga Dhuafa Kampung Melayu', 'jenis' => 'fakir_miskin', 'berat' => 15],
            ['nama' => 'Lansia Dhuafa Kelurahan Pasar Rebo', 'jenis' => 'fakir_miskin', 'berat' => 17],
        ];

        foreach ($penerima as $data) {
            DistribusiKurban::create([
                'kurban_id' => $sapi1->id,
                'penerima_nama' => $data['nama'],
                'penerima_alamat' => 'Jakarta',
                'berat_daging' => $data['berat'],
                'estimasi_harga' => $data['berat'] * 120000,
                'jenis_distribusi' => $data['jenis'],
                'tanggal_distribusi' => Carbon::now()->subDays(2),
                'status_distribusi' => 'sudah_didistribusi',
                'catatan' => 'Distribusi daging kurban ' . Carbon::now()->year,
                'created_by' => $adminKurban->id ?? 1,
            ]);
        }

        // 2. Kambing Etawa - Tersedia
        $kambing1 = Kurban::create([
            'nomor_kurban' => 'KRB-' . date('Y') . '-002',
            'jenis_hewan' => 'kambing',
            'nama_hewan' => 'Kambing Etawa',
            'berat_badan' => 45.0,
            'kondisi_kesehatan' => 'sehat',
            'harga_hewan' => 3200000,
            'biaya_operasional' => 300000,
            'total_biaya' => 3500000,
            'status' => 'siap_sembelih',
            'tanggal_persiapan' => Carbon::now()->subDays(5),
            'tanggal_penyembelihan' => Carbon::create(2026, 6, 16),
            'catatan' => 'Kambing etawa jantan, siap disembelih pada Idul Adha',
            'created_by' => $adminKurban->id ?? 1,
        ]);

        // 3. Sapi Ongole - Sudah Disembelih
        $sapi2 = Kurban::create([
            'nomor_kurban' => 'KRB-' . date('Y') . '-003',
            'jenis_hewan' => 'sapi',
            'nama_hewan' => 'Sapi Ongole',
            'berat_badan' => 420.0,
            'kondisi_kesehatan' => 'sehat',
            'harga_hewan' => 21000000,
            'biaya_operasional' => 1000000,
            'total_biaya' => 22000000,
            'status' => 'disembelih',
            'tanggal_persiapan' => Carbon::now()->subDays(8),
            'tanggal_penyembelihan' => Carbon::now()->subDays(2),
            'catatan' => 'Sapi ongole sudah disembelih, proses distribusi sedang berlangsung',
            'created_by' => $adminKurban->id ?? 1,
        ]);

        // Peserta untuk Sapi 2 (5 orang)
        $pesertaSapi2 = [
            ['nama' => 'Ali bin Abi Thalib', 'jumlah_bagian' => 1],
            ['nama' => 'Umar bin Khattab', 'jumlah_bagian' => 1],
            ['nama' => 'Aisyah binti Abu Bakar', 'jumlah_bagian' => 1],
            ['nama' => 'Khadijah binti Khuwailid', 'jumlah_bagian' => 1],
            ['nama' => 'Bilal bin Rabah', 'jumlah_bagian' => 1],
        ];

        foreach ($pesertaSapi2 as $index => $peserta) {
            PesertaKurban::create([
                'kurban_id' => $sapi2->id,
                'nama_peserta' => $peserta['nama'],
                'nomor_telepon' => '08123456780' . $index,
                'alamat' => 'Jakarta',
                'tipe_peserta' => 'perorangan',
                'jumlah_jiwa' => 1,
                'jumlah_bagian' => $peserta['jumlah_bagian'],
                'nominal_pembayaran' => 3150000,
                'status_pembayaran' => $index < 3 ? 'lunas' : 'belum_lunas',
                'tanggal_pembayaran' => $index < 3 ? Carbon::now()->subDays(5) : null,
                'created_by' => $adminKurban->id ?? 1,
            ]);
        }

        // 4. Kambing Gibas
        $kambing2 = Kurban::create([
            'nomor_kurban' => 'KRB-' . date('Y') . '-004',
            'jenis_hewan' => 'kambing',
            'nama_hewan' => 'Kambing Gibas',
            'berat_badan' => 38.5,
            'kondisi_kesehatan' => 'sehat',
            'harga_hewan' => 2600000,
            'biaya_operasional' => 200000,
            'total_biaya' => 2800000,
            'status' => 'disiapkan',
            'tanggal_persiapan' => Carbon::now()->subDays(3),
            'tanggal_penyembelihan' => Carbon::create(2026, 6, 16),
            'catatan' => 'Kambing gibas berkualitas, masih dalam tahap persiapan',
            'created_by' => $adminKurban->id ?? 1,
        ]);

        // 5. Sapi Bali - Selesai
        $sapi3 = Kurban::create([
            'nomor_kurban' => 'KRB-' . date('Y') . '-005',
            'jenis_hewan' => 'sapi',
            'nama_hewan' => 'Sapi Bali',
            'berat_badan' => 380.0,
            'kondisi_kesehatan' => 'sehat',
            'harga_hewan' => 19000000,
            'biaya_operasional' => 1000000,
            'total_biaya' => 20000000,
            'status' => 'selesai',
            'tanggal_persiapan' => Carbon::now()->subDays(12),
            'tanggal_penyembelihan' => Carbon::now()->subDays(4),
            'catatan' => 'Semua proses kurban sudah selesai, daging sudah terdistribusi semua',
            'created_by' => $adminKurban->id ?? 1,
        ]);

        // Peserta untuk Sapi 3 (7 orang)
        for ($i = 0; $i < 7; $i++) {
            PesertaKurban::create([
                'kurban_id' => $sapi3->id,
                'nama_peserta' => 'Peserta ' . ($i + 1),
                'nomor_telepon' => '08123456785' . $i,
                'alamat' => 'Jakarta',
                'tipe_peserta' => 'perorangan',
                'jumlah_jiwa' => 1,
                'jumlah_bagian' => 1,
                'nominal_pembayaran' => 2850000,
                'status_pembayaran' => 'lunas',
                'tanggal_pembayaran' => Carbon::now()->subDays(rand(3, 15)),
                'created_by' => $adminKurban->id ?? 1,
            ]);
        }

        echo "\n✅ Berhasil membuat 5 data kurban:\n";
        echo "   - 3 Sapi (1 didistribusikan, 2 terjual)\n";
        echo "   - 2 Kambing (tersedia)\n";
        echo "   - Total " . PesertaKurban::count() . " peserta kurban\n";
        echo "   - Total " . DistribusiKurban::count() . " distribusi\n";
    }
}
