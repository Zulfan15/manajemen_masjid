<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'kode_transaksi', 'muzakki_id', 'user_id', 
        'jenis_transaksi', 'nominal', 'keterangan', 'tanggal_transaksi'
    ];

    public function muzakki()
    {
        return $this->belongsTo(Muzakki::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
