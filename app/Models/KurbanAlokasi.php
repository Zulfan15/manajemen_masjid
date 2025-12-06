<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KurbanAlokasi extends Model
{
    protected $table = 'kurban_alokasi';

    protected $fillable = [
        'peserta_id',
        'hewan_id',
        'porsi',
        'nama_shohibul',
    ];

    public function peserta()
    {
        return $this->belongsTo(KurbanPeserta::class, 'peserta_id');
    }

    public function hewan()
    {
        return $this->belongsTo(KurbanHewan::class, 'hewan_id');
    }
}
