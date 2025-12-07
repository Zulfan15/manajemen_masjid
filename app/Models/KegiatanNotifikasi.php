<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanNotifikasi extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_notifikasi';

    protected $fillable = [
        'kegiatan_id',
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'is_read',
        'read_at',
        'channel',
        'is_sent',
        'sent_at',
        'metadata',
        'created_by',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_sent' => 'boolean',
        'read_at' => 'datetime',
        'sent_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'kegiatan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helpers
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function markAsSent()
    {
        $this->update([
            'is_sent' => true,
            'sent_at' => now(),
        ]);
    }

    public function getTipeBadgeClass()
    {
        return match($this->tipe) {
            'info' => 'bg-blue-100 text-blue-800',
            'reminder' => 'bg-yellow-100 text-yellow-800',
            'pengumuman' => 'bg-purple-100 text-purple-800',
            'konfirmasi' => 'bg-green-100 text-green-800',
            'pembatalan' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getTipeIcon()
    {
        return match($this->tipe) {
            'info' => 'fa-info-circle',
            'reminder' => 'fa-bell',
            'pengumuman' => 'fa-bullhorn',
            'konfirmasi' => 'fa-check-circle',
            'pembatalan' => 'fa-times-circle',
            default => 'fa-envelope'
        };
    }
}
