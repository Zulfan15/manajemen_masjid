<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Announcement extends Model
{
    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        $model->slug = Str::slug($model->title);
    });

    static::updating(function ($model) {
        $model->slug = Str::slug($model->title);
    });
}
    use HasFactory;

    protected $fillable = [
        'title','slug','content','type',
        'start_date','end_date','created_by'
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
