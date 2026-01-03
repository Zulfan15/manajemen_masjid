<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title','slug','content','category_id',
        'author_name','thumbnail','published_at'
    ];

    protected $casts = [
    'published_at' => 'datetime',
    'created_at'   => 'datetime',
    'updated_at'   => 'datetime',
];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
