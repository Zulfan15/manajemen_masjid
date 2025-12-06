<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KurbanHasilPotong extends Model
{
    protected $table = 'kurban_hasil_potong';

    protected $fillable = [
        'penyembelihan_id',
        'daging',
        'tulang',
        'jeroan',
        'kulit',
        'total_kantong',
    ];

    public function penyembelihan()
    {
        return $this->belongsTo(KurbanPenyembelihan::class, 'penyembelihan_id');
    }

    public function distribusi()
    {
        return $this->hasMany(KurbanDistribusi::class, 'hasil_id');
    }
}
