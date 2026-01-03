<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KondisiBarang extends Model
{
    use HasFactory;

    protected $table = 'kondisi_barang';
    protected $primaryKey = 'kondisi_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'aset_id',
        'tanggal_pemeriksaan',
        'kondisi',
        'petugas_pemeriksa',
        'id_petugas',
        'catatan',
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class, 'aset_id', 'aset_id');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas', 'id');
    }
}
