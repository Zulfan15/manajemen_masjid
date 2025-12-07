<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Kandidat extends Model
{
    use HasFactory;

    protected $table = 'kandidat';

    protected $fillable = [
        'pemilihan_id',
        'takmir_id',
        'nomor_urut',
        'visi',
        'misi',
        'foto',
    ];

    /**
     * Relationships
     */
    public function pemilihan()
    {
        return $this->belongsTo(Pemilihan::class);
    }

    public function takmir()
    {
        return $this->belongsTo(Takmir::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Accessor
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return Storage::disk('public')->url($this->foto);
        }
        // Fallback ke foto takmir
        return $this->takmir->foto_url;
    }

    /**
     * Helper Methods
     */
    public function totalVotes()
    {
        return $this->votes()->count();
    }

    public function persentaseVotes()
    {
        $totalVotes = $this->pemilihan->totalVotes();
        if ($totalVotes == 0) return 0;
        
        return round(($this->totalVotes() / $totalVotes) * 100, 2);
    }
}
