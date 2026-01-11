<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mustahiq extends Model
{
    use HasFactory;
    
    protected $table = 'mustahiq';

    protected $fillable = [
        'nama_lengkap', 'nik', 'no_hp', 'alamat', 'kategori', 'status_aktif'
    ];

    public function penyaluran()
    {
        return $this->hasMany(Penyaluran::class);
    }
}
