<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'judul_pengeluaran',
        'deskripsi',
        'jumlah',
        'tanggal',
        'bukti_transaksi',
    ];

    // Relasi: Pengeluaran milik satu Kategori
    public function kategori()
    {
        return $this->belongsTo(KategoriPengeluaran::class, 'kategori_id');
    }

    // Relasi: Pengeluaran diinput oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}