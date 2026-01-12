<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use HasFactory;

    protected $table = 'aset';

    // PRIMARY KEY SESUAI DB
    protected $primaryKey = 'aset_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Timestamps: di DB ada created_at & updated_at, jadi biarkan default (true)

    // Kolom yang bisa diisi mass-assignment
    protected $fillable = [
        'nama_aset',
        'kategori',
        'lokasi',
        'tanggal_perolehan',
        'status',
        'harga_perolehan',
        'keterangan',
        'qr_payload',
    ];

    // Relasi – yang bawah ini boleh kamu biarkan seperti yang sudah ada
    public function kondisiBarang()
    {
        return $this->hasMany(KondisiBarang::class, 'aset_id', 'aset_id');
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiAset::class, 'aset_id', 'aset_id');
    }

    public function jadwalPerawatan()
    {
        return $this->hasMany(JadwalPerawatan::class, 'aset_id', 'aset_id');
    }
}
