<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanPeserta extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_peserta';

    protected $fillable = [
        'kegiatan_id',
        'user_id',
        'nama_peserta',
        'email',
        'no_hp',
        'alamat',
        'status_pendaftaran',
        'tanggal_daftar',
        'keterangan',
        'metode_pendaftaran',
    ];

    protected $casts = [
        'tanggal_daftar' => 'datetime',
    ];

    // Relationships
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function absensi()
    {
        return $this->hasOne(KegiatanAbsensi::class, 'peserta_id');
    }

    // Scopes
    public function scopeTerdaftar($query)
    {
        return $query->where('status_pendaftaran', 'terdaftar');
    }

    public function scopeDikonfirmasi($query)
    {
        return $query->where('status_pendaftaran', 'dikonfirmasi');
    }

    public function scopeDibatalkan($query)
    {
        return $query->where('status_pendaftaran', 'dibatalkan');
    }

    // Helpers
    public function getStatusBadgeClass()
    {
        return match($this->status_pendaftaran) {
            'terdaftar' => 'bg-blue-100 text-blue-800',
            'dikonfirmasi' => 'bg-green-100 text-green-800',
            'dibatalkan' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function sudahAbsen()
    {
        return $this->absensi()->exists();
    }
}
