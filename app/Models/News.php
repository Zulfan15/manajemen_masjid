<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class News extends Model
{
    use HasFactory;

    protected $fillable = [
    'title', 'slug', 'content', 'thumbnail',
    'created_by', 'published_at'
    ];
    protected $casts = [
    'published_at' => 'datetime',
];


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
