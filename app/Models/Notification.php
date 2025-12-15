<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id', 'news_id', 'article_id',
        'type', 'status', 'sent_at'
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function logs()
    {
        return $this->hasMany(NotificationLog::class);
    }
}
