<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanAbsensi extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_absensi';

    protected $fillable = [
        'kegiatan_id',
        'peserta_id',
        'status_kehadiran',
        'waktu_absen',
        'metode_absen',
        'latitude',
        'longitude',
        'keterangan',
        'dicatat_oleh',
    ];

    protected $casts = [
        'waktu_absen' => 'datetime',
    ];

    // Relationships
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function peserta()
    {
        return $this->belongsTo(KegiatanPeserta::class, 'peserta_id');
    }

    public function pencatat()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    // Scopes
    public function scopeHadir($query)
    {
        return $query->where('status_kehadiran', 'hadir');
    }

    public function scopeTidakHadir($query)
    {
        return $query->where('status_kehadiran', 'tidak_hadir');
    }

    // Helpers
    public function getStatusBadgeClass()
    {
        return match($this->status_kehadiran) {
            'hadir' => 'bg-green-100 text-green-800',
            'tidak_hadir' => 'bg-red-100 text-red-800',
            'izin' => 'bg-yellow-100 text-yellow-800',
            'sakit' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
}
