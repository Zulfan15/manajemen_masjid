<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Aset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AsetSeeder extends Seeder
{
    public function run(): void
    {
        $srcDir = database_path('seeders/assets/aset');
        $map = [
            'Karpet Sholat' => 'karpet.jpg',
            'Sarung'        => 'sarung.jpg',
            'Peci'          => 'peci.jpg',
        ];

        $items = [
            [
                'nama_aset' => 'Karpet Sholat',
                'kategori' => 'barang',
                'lokasi' => 'Ruang Utama',
                'tanggal_perolehan' => now()->subMonths(8)->toDateString(),
                'status' => 'aktif',
                'keterangan' => 'Dummy seed',
            ],
            [
                'nama_aset' => 'Sarung',
                'kategori' => 'barang',
                'lokasi' => 'Lemari',
                'tanggal_perolehan' => now()->subMonths(2)->toDateString(),
                'status' => 'aktif',
                'keterangan' => 'Dummy seed',
            ],
            [
                'nama_aset' => 'Peci',
                'kategori' => 'barang',
                'lokasi' => 'Rak Depan',
                'tanggal_perolehan' => now()->subMonths(1)->toDateString(),
                'status' => 'hilang',
                'keterangan' => 'Dummy seed',
            ],
        ];

        foreach ($items as $it) {
            $name = $it['nama_aset'];

            // copy dummy image ke storage public/aset (kalau file tersedia)
            $fotoPath = null;
            if (isset($map[$name])) {
                $filename = $map[$name];
                $src = $srcDir . DIRECTORY_SEPARATOR . $filename;

                if (file_exists($src)) {
                    $dest = 'aset/' . $filename;
                    Storage::disk('public')->put($dest, file_get_contents($src));
                    $fotoPath = $dest; // ini yang disimpan ke DB
                }
            }

            $aset = Aset::create(array_merge($it, [
                'foto_path' => $fotoPath,
            ]));

            // qr_payload konsisten sama controller kamu
            $payload = 'AST-' . str_pad((string)$aset->aset_id, 6, '0', STR_PAD_LEFT);
            $aset->update(['qr_payload' => $payload]);

            // kondisi_barang dummy (biar filter kondisi & badge ada datanya)
            DB::table('kondisi_barang')->insert([
                'aset_id' => $aset->aset_id,
                'kondisi' => 'baik',
                'tanggal_pemeriksaan' => now()->toDateString(),
            ]);
        }
    }
}
