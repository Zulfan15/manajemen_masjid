<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'tanggal',
    ];

    public function participations()
    {
        return $this->hasMany(Participation::class);
    }
}
