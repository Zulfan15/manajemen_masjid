<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasHarian extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_harian';

    protected $fillable = [
        'takmir_id',
        'tanggal',
        'jenis_aktivitas',
        'deskripsi',
        'durasi_jam',
        'bukti_foto',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'durasi_jam' => 'decimal:2',
    ];

    /**
     * Relasi ke Takmir
     */
    public function takmir()
    {
        return $this->belongsTo(Takmir::class);
    }

    /**
     * Accessor untuk URL bukti foto
     */
    public function getBuktiFotoUrlAttribute()
    {
        return $this->bukti_foto ? asset('storage/' . $this->bukti_foto) : null;
    }

    /**
     * Scope untuk filter berdasarkan pengurus
     */
    public function scopeByTakmir($query, $takmirId)
    {
        return $query->where('takmir_id', $takmirId);
    }

    /**
     * Scope untuk filter berdasarkan tanggal range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    /**
     * Scope untuk filter berdasarkan jenis aktivitas
     */
    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_aktivitas', $jenis);
    }
}
