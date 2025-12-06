<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KurbanHewan extends Model
{
    protected $table = 'kurban_hewan';

    protected $fillable = [
        'jenis',
        'berat',
        'status',
    ];

    public function alokasi()
    {
        return $this->hasMany(KurbanAlokasi::class, 'hewan_id');
    }

    public function penyembelihan()
    {
        return $this->hasOne(KurbanPenyembelihan::class, 'hewan_id');
    }
}
