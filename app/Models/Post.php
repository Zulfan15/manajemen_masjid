<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'category',
        'thumbnail',
        'status',
        'created_by'
    ];

    // Relasi: Setiap Post dimiliki oleh satu User (penulis)
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Fitur filter: Ambil hanya yang statusnya 'published'
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}