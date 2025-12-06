<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KurbanDistribusi extends Model
{
    protected $table = 'kurban_distribusi';

    protected $fillable = [
        'hasil_id',
        'penerima_id',
        'jumlah_kantong',
        'status',
    ];

    public function hasil()
    {
        return $this->belongsTo(KurbanHasilPotong::class, 'hasil_id');
    }

    public function penerima()
    {
        return $this->belongsTo(KurbanPenerima::class, 'penerima_id');
    }
}
