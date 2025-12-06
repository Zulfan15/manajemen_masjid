<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KurbanPeserta extends Model
{
    protected $table = 'kurban_peserta';

    protected $fillable = [
        'nama',
        'kontak',
        'catatan',
    ];

    public function alokasi()
    {
        return $this->hasMany(KurbanAlokasi::class, 'peserta_id');
    }
}
