<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'jamaah_profile_id',
        'amount',
        'type',
        'donation_date',
    ];

    public function jamaah()
    {
        return $this->belongsTo(JamaahProfile::class, 'jamaah_profile_id');
    }
}

