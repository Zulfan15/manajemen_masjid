<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KurbanPenyembelihan extends Model
{
    protected $table = 'kurban_penyembelihan';

    protected $fillable = [
        'hewan_id',
        'tanggal',
        'jam',
        'petugas',
        'status',
    ];

    public function hewan()
    {
        return $this->belongsTo(KurbanHewan::class, 'hewan_id');
    }

    public function hasil()
    {
        return $this->hasOne(KurbanHasilPotong::class, 'penyembelihan_id');
    }
}
