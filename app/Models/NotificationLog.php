<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_id', 'jamaah_id',
        'status', 'response_message', 'sent_at'
    ];

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function jamaah()
    {
        return $this->belongsTo(Jamaah::class);
    }
}
