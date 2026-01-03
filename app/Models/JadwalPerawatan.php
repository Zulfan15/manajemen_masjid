<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPerawatan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_perawatan';
    protected $primaryKey = 'jadwal_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'aset_id',
        'tanggal_jadwal',
        'jenis_perawatan',
        'status',
        'id_petugas',
        'note',
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
