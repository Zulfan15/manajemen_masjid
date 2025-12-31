<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemilihan_id',
        'kandidat_id',
        'user_id',
        'voted_at',
    ];

    protected $casts = [
        'voted_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function pemilihan()
    {
        return $this->belongsTo(Pemilihan::class);
    }

    public function kandidat()
    {
        return $this->belongsTo(Kandidat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
