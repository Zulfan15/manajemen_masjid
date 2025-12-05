<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPengeluaran extends Model
{
    use HasFactory;

    // Nama tabel (eksplisit agar aman)
    protected $table = 'kategori_pengeluaran';

    // Kolom yang boleh diisi
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    // Relasi: Satu Kategori punya banyak Pengeluaran
    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'kategori_id');
    }
}