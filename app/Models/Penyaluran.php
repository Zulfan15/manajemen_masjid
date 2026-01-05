<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyaluran extends Model
{
    use HasFactory;

    protected $table = 'penyaluran';

    protected $fillable = [
        'kode_penyaluran', 'mustahiq_id', 'user_id',
        'jenis_bantuan', 'nominal', 'keterangan', 'tanggal_penyaluran'
    ];

    public function mustahiq()
    {
        return $this->belongsTo(Mustahiq::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
