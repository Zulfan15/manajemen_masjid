<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Participation extends Model
{
    protected $fillable = [
        'jamaah_profile_id',
        'activity_id',
        'hadir',
    ];

    public function jamaah()
    {
        return $this->belongsTo(JamaahProfile::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}

