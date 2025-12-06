<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Takmir extends Model
{
    use HasFactory;

    protected $table = 'takmir';

    protected $fillable = [
        'nama',
        'jabatan',
        'email',
        'phone',
        'alamat',
        'periode_mulai',
        'periode_akhir',
        'status',
        'foto',
        'keterangan',
    ];

    protected $casts = [
        'periode_mulai' => 'date',
        'periode_akhir' => 'date',
    ];

    /**
     * Scope untuk filter status aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope untuk filter status nonaktif
     */
    public function scopeNonaktif($query)
    {
        return $query->where('status', 'nonaktif');
    }

    /**
     * Accessor untuk mendapatkan URL foto
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        // Gunakan UI Avatars sebagai default
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) . '&size=200&background=10b981&color=ffffff';
    }
}
