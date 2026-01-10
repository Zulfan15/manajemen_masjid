<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Kurban;
use App\Models\PesertaKurban;
use App\Models\DistribusiKurban;

class KurbanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setup Admin
        $admin = User::first() ?? User::factory()->create([
            'name' => 'Admin DKM',
            'email' => 'admin@masjid.com',
            'password' => bcrypt('password'),
        ]);
        $adminId = $admin->id;

        // ==========================================================
        // SKENARIO 1: SAPI WARGA (SELESAI - KOMPLIT)
        // Kasus: 7 Orang Eceran, Daging sudah dibagikan semua
        // ==========================================================
        $sapi1 = Kurban::create([
            'nomor_kurban' => 'KB-2025-001',
            'jenis_hewan' => 'sapi',
            'nama_hewan' => 'Si Bagong (Limosin)',
            'berat_badan' => 450.00,
            'kondisi_kesehatan' => 'sehat',
            'tanggal_persiapan' => Carbon::now()->subDays(30),
            'tanggal_penyembelihan' => Carbon::now()->subDays(2), // Disembelih 2 hari lalu
            'harga_hewan' => 24500000,
            'biaya_operasional' => 700000,
            'total_biaya' => 25200000,
            'status' => 'selesai', // STATUS SELESAI
            'catatan' => 'Alhamdulillah lancar.',
            'created_by' => $adminId,
        ]);

        $hargaSapi1 = 25200000 / 7;
        $jamaahSapi1 = ['Pak H. Mamat', 'Ibu Hj. Romlah', 'Kang Asep', 'Teh Nining', 'Pak RT Dadang', 'Mas Joko', 'Ibu Susi'];

        // Loop buat peserta
        foreach ($jamaahSapi1 as $index => $nama) {
            $peserta = PesertaKurban::create([
                'kurban_id' => $sapi1->id,
                'nama_peserta' => $nama,
                'tipe_peserta' => 'perorangan',
                'jumlah_jiwa' => 1,
                'jumlah_bagian' => 1,
                'nominal_pembayaran' => $hargaSapi1,
                'status_pembayaran' => 'lunas',
                'tanggal_pembayaran' => Carbon::now()->subDays(20),
                'created_by' => $adminId,
            ]);

            // GENERATE DISTRIBUSI (Jatah Shohibul Qurban)
            // Setiap peserta dapat 3kg daging
            DistribusiKurban::create([
                'kurban_id' => $sapi1->id,
                'peserta_kurban_id' => $peserta->id,
                'penerima_nama' => $nama . " (Shohibul)",
                'berat_daging' => 3.0,
                'estimasi_harga' => 360000,
                'jenis_distribusi' => 'keluarga_peserta',
                'status_distribusi' => 'sudah_didistribusi',
                'tanggal_distribusi' => Carbon::now()->subDays(2),
                'created_by' => $adminId,
            ]);
        }

        // GENERATE DISTRIBUSI (Jatah Warga/Fakir Miskin)
        DistribusiKurban::create([
            'kurban_id' => $sapi1->id,
            'penerima_nama' => "Warga RW 05 (Total 150 Kantong)",
            'penerima_alamat' => "Wilayah RW 05",
            'berat_daging' => 150.0, // 150 kg total
            'estimasi_harga' => 18000000,
            'jenis_distribusi' => 'fakir_miskin',
            'status_distribusi' => 'sudah_didistribusi',
            'catatan' => 'Dibagikan via Pak RT',
            'created_by' => $adminId,
        ]);


        // ==========================================================
        // SKENARIO 2: SAPI SULTAN BORONGAN (SELESAI - KOMPLIT)
        // Kasus: 1 Orang Borong 7 Bagian, Minta daging dikirim ke Panti
        // ==========================================================
        $sapi2 = Kurban::create([
            'nomor_kurban' => 'KB-2025-002',
            'jenis_hewan' => 'sapi',
            'nama_hewan' => 'Monster (Simental)',
            'berat_badan' => 800.00,
            'kondisi_kesehatan' => 'sehat',
            'tanggal_persiapan' => Carbon::now()->subDays(25),
            'tanggal_penyembelihan' => Carbon::now()->subDays(2),
            'harga_hewan' => 45000000,
            'biaya_operasional' => 1000000,
            'total_biaya' => 46000000,
            'status' => 'selesai', // STATUS SELESAI
            'created_by' => $adminId,
        ]);

        $pesertaSultan = PesertaKurban::create([
            'kurban_id' => $sapi2->id,
            'nama_peserta' => "Bapak H. Rhoma Irama (Sultan)",
            'tipe_peserta' => 'keluarga',
            'jumlah_jiwa' => 7,
            'jumlah_bagian' => 7.00, // BORONG 7 SLOT
            'nominal_pembayaran' => 46000000,
            'status_pembayaran' => 'lunas',
            'created_by' => $adminId,
        ]);

        // Distribusi 1: Jatah Keluarga Sultan (Paha Belakang & Hati)
        DistribusiKurban::create([
            'kurban_id' => $sapi2->id,
            'peserta_kurban_id' => $pesertaSultan->id,
            'penerima_nama' => "Keluarga Pak H. Rhoma",
            'penerima_alamat' => "Jl. Pondok Jaya",
            'berat_daging' => 20.0, // Minta 20kg
            'estimasi_harga' => 2400000,
            'jenis_distribusi' => 'keluarga_peserta',
            'status_distribusi' => 'sudah_didistribusi',
            'catatan' => 'Request Paha Belakang Utuh',
            'created_by' => $adminId,
        ]);

        // Distribusi 2: Sisanya ke Panti Asuhan (Request Sultan)
        DistribusiKurban::create([
            'kurban_id' => $sapi2->id,
            'penerima_nama' => "Panti Asuhan Yatim Piatu",
            'penerima_alamat' => "Jl. Raya Bogor",
            'berat_daging' => 200.0, // 200kg daging murni
            'estimasi_harga' => 24000000,
            'jenis_distribusi' => 'fakir_miskin',
            'status_distribusi' => 'sudah_didistribusi',
            'catatan' => 'Amanah dari Pak Haji untuk diserahkan utuh',
            'created_by' => $adminId,
        ]);


        // ==========================================================
        // SKENARIO 3: SAPI PERUSAHAAN (SELESAI - KOMPLIT)
        // Kasus: PT. Maju Mundur (7 Bagian), Daging buat staff
        // ==========================================================
        $sapi3 = Kurban::create([
            'nomor_kurban' => 'KB-2025-003',
            'jenis_hewan' => 'sapi',
            'nama_hewan' => 'Sapi PO Super',
            'berat_badan' => 500.00,
            'kondisi_kesehatan' => 'sehat',
            'tanggal_persiapan' => Carbon::now()->subDays(15),
            'tanggal_penyembelihan' => Carbon::now()->subDays(1),
            'harga_hewan' => 28000000,
            'biaya_operasional' => 700000,
            'total_biaya' => 28700000,
            'status' => 'selesai', // STATUS SELESAI
            'created_by' => $adminId,
        ]);

        PesertaKurban::create([
            'kurban_id' => $sapi3->id,
            'nama_peserta' => "CSR PT. Maju Mundur",
            'tipe_peserta' => 'perorangan', // Dianggap entitas
            'jumlah_jiwa' => 7,
            'jumlah_bagian' => 7.00,
            'nominal_pembayaran' => 28700000,
            'status_pembayaran' => 'lunas',
            'created_by' => $adminId,
        ]);

        DistribusiKurban::create([
            'kurban_id' => $sapi3->id,
            'penerima_nama' => "Staff Cleaning Service & Security",
            'berat_daging' => 50.0,
            'estimasi_harga' => 6000000,
            'jenis_distribusi' => 'lainnya',
            'status_distribusi' => 'sudah_didistribusi',
            'created_by' => $adminId,
        ]);
        
         DistribusiKurban::create([
            'kurban_id' => $sapi3->id,
            'penerima_nama' => "Warga Sekitar Kantor RW 02",
            'berat_daging' => 100.0,
            'estimasi_harga' => 12000000,
            'jenis_distribusi' => 'fakir_miskin',
            'status_distribusi' => 'sudah_didistribusi',
            'created_by' => $adminId,
        ]);


        // ==========================================================
        // SKENARIO 4: SAPI KELUARGA (SIAP SEMBELIH)
        // Kasus: 3 KK (2+2+3), Status Siap Sembelih (Belum ada distribusi)
        // ==========================================================
        $sapi4 = Kurban::create([
            'nomor_kurban' => 'KB-2025-004',
            'jenis_hewan' => 'sapi',
            'nama_hewan' => 'Si Putih',
            'berat_badan' => 380.00,
            'kondisi_kesehatan' => 'sehat',
            'tanggal_persiapan' => Carbon::now()->subDays(10),
            'harga_hewan' => 21000000,
            'biaya_operasional' => 700000,
            'total_biaya' => 21700000,
            'status' => 'siap_sembelih', // STATUS SIAP SEMBELIH
            'catatan' => 'Menunggu jadwal penyembelihan jam 10:00',
            'created_by' => $adminId,
        ]);

        $hargaPerBagian4 = 21700000 / 7;
        
        // Input 3 Keluarga (Total 7 Bagian)
        PesertaKurban::create(['kurban_id' => $sapi4->id, 'nama_peserta' => "Kel. Gunawan", 'tipe_peserta' => 'keluarga', 'jumlah_jiwa' => 2, 'jumlah_bagian' => 2, 'nominal_pembayaran' => $hargaPerBagian4 * 2, 'status_pembayaran' => 'lunas', 'created_by' => $adminId]);
        PesertaKurban::create(['kurban_id' => $sapi4->id, 'nama_peserta' => "Kel. Rina", 'tipe_peserta' => 'keluarga', 'jumlah_jiwa' => 2, 'jumlah_bagian' => 2, 'nominal_pembayaran' => $hargaPerBagian4 * 2, 'status_pembayaran' => 'lunas', 'created_by' => $adminId]);
        PesertaKurban::create(['kurban_id' => $sapi4->id, 'nama_peserta' => "Kel. Sanusi", 'tipe_peserta' => 'keluarga', 'jumlah_jiwa' => 5, 'jumlah_bagian' => 3, 'nominal_pembayaran' => $hargaPerBagian4 * 3, 'status_pembayaran' => 'lunas', 'created_by' => $adminId]);


        // ==========================================================
        // SKENARIO 5: SAPI CAMPUR (DISIAPKAN / BELUM PENUH)
        // Kasus: Baru 3 orang, Sisa 4 Slot (Progress Bar Kuning)
        // ==========================================================
        $sapi5 = Kurban::create([
            'nomor_kurban' => 'KB-2025-005',
            'jenis_hewan' => 'sapi',
            'nama_hewan' => 'Sapi Madura',
            'berat_badan' => 320.00,
            'kondisi_kesehatan' => 'sehat',
            'tanggal_persiapan' => Carbon::now()->subDays(2),
            'harga_hewan' => 19000000,
            'biaya_operasional' => 700000,
            'total_biaya' => 19700000,
            'status' => 'disiapkan', // STATUS DISIAPKAN
            'catatan' => 'Masih mencari 4 orang lagi.',
            'created_by' => $adminId,
        ]);

        $hargaPerBagian5 = 19700000 / 7;

        PesertaKurban::create(['kurban_id' => $sapi5->id, 'nama_peserta' => "Kang Dedi", 'tipe_peserta' => 'perorangan', 'jumlah_jiwa' => 1, 'jumlah_bagian' => 1, 'nominal_pembayaran' => 1000000, 'status_pembayaran' => 'cicilan', 'created_by' => $adminId]);
        PesertaKurban::create(['kurban_id' => $sapi5->id, 'nama_peserta' => "Kel. Ibu Ani", 'tipe_peserta' => 'keluarga', 'jumlah_jiwa' => 2, 'jumlah_bagian' => 2, 'nominal_pembayaran' => $hargaPerBagian5 * 2, 'status_pembayaran' => 'lunas', 'created_by' => $adminId]);


        // ==========================================================
        // KAMBING / DOMBA
        // ==========================================================
        
        // Kambing A (Selesai)
        $kambing1 = Kurban::create(['nomor_kurban' => 'KB-2025-006', 'jenis_hewan' => 'kambing', 'berat_badan' => 30, 'harga_hewan' => 3000000, 'total_biaya' => 3100000, 'status' => 'selesai', 'tanggal_persiapan' => Carbon::now()->subDays(10), 'tanggal_penyembelihan' => Carbon::now()->subDays(2), 'created_by' => $adminId]);
        $pk1 = PesertaKurban::create(['kurban_id' => $kambing1->id, 'nama_peserta' => 'Ustadz Solmed', 'jumlah_bagian' => 1, 'nominal_pembayaran' => 3100000, 'status_pembayaran' => 'lunas', 'created_by' => $adminId]);
        DistribusiKurban::create(['kurban_id' => $kambing1->id, 'peserta_kurban_id' => $pk1->id, 'penerima_nama' => 'Ust Solmed', 'berat_daging' => 3, 'estimasi_harga' => 300000, 'jenis_distribusi' => 'keluarga_peserta', 'status_distribusi' => 'sudah_didistribusi', 'created_by' => $adminId]);

        // Domba B (Siap Sembelih)
        $domba1 = Kurban::create(['nomor_kurban' => 'KB-2025-007', 'jenis_hewan' => 'domba', 'berat_badan' => 45, 'harga_hewan' => 4500000, 'total_biaya' => 4600000, 'status' => 'siap_sembelih', 'tanggal_persiapan' => Carbon::now()->subDays(5), 'created_by' => $adminId]);
        PesertaKurban::create(['kurban_id' => $domba1->id, 'nama_peserta' => 'Habib Jafar', 'jumlah_bagian' => 1, 'nominal_pembayaran' => 4600000, 'status_pembayaran' => 'lunas', 'created_by' => $adminId]);
    }
}