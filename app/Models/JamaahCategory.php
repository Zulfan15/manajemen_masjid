<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamaahCategory extends Model
{
    protected $fillable = ['nama'];

    public function jamaahs()
    {
        return $this->belongsToMany(
            JamaahProfile::class,
            'jamaah_category_jamaah'
        );
    }
}
