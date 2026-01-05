<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muzakki extends Model
{
    use HasFactory;

    protected $table = 'muzakki';

    protected $fillable = [
        'nama_lengkap', 'nik', 'no_hp', 'alamat', 'jenis_kelamin'
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}
