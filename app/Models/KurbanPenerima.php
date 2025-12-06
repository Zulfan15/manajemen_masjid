<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KurbanPenerima extends Model
{
    protected $table = 'kurban_penerima';

    protected $fillable = [
        'nama',
        'alamat',
    ];

    public function distribusi()
    {
        return $this->hasMany(KurbanDistribusi::class, 'penerima_id');
    }
}
