<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use HasFactory;

    protected $table = 'aset';
    protected $primaryKey = 'aset_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_aset',
        'kategori',
        'lokasi',
        'tanggal_perolehan',
        'status',
        'keterangan',
        'qr_payload',
    ];

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
