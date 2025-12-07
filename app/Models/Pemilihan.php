<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pemilihan extends Model
{
    use HasFactory;

    protected $table = 'pemilihan';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'tampilkan_hasil',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'tampilkan_hasil' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function kandidat()
    {
        return $this->hasMany(Kandidat::class)->orderBy('nomor_urut');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Scopes
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeBerlangsung($query)
    {
        return $query->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now());
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai')
            ->orWhere(function($q) {
                $q->where('tanggal_selesai', '<', now());
            });
    }

    /**
     * Helper Methods
     */
    public function isBerlangsung()
    {
        return $this->status === 'aktif' 
            && $this->tanggal_mulai <= now() 
            && $this->tanggal_selesai >= now();
    }

    public function isSelesai()
    {
        return $this->status === 'selesai' || $this->tanggal_selesai < now();
    }

    public function totalVotes()
    {
        return $this->votes()->count();
    }

    public function userHasVoted($userId)
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }

    public function getHasilPemilihan()
    {
        return $this->kandidat()
            ->withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->get();
    }
}
