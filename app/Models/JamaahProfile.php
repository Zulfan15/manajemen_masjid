<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class JamaahProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'alamat',
        'status_aktif',
    ];
    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Kategori Jamaah
     */
    public function categories()
    {
        return $this->belongsToMany(
            JamaahCategory::class,
            'jamaah_category_jamaah',
            'jamaah_profile_id',
            'jamaah_category_id'
        );
    }
    public function donations()
    {
        return $this->hasMany(Donation::class, 'jamaah_profile_id');
    }

    public function participations()
    {
        return $this->hasMany(Participation::class);
    }
}
