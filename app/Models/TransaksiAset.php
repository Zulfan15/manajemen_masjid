<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiAset extends Model
{
    use HasFactory;

    protected $table = 'add_min_aset';
    protected $primaryKey = 'transaksi_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'aset_id',
        'jenis_transaksi',
        'tanggal_transaksi',
        'jumlah',
        'id_petugas',
        'keterangan',
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
