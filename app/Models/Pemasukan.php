<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemasukan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pemasukan';

    protected $fillable = [
        'jenis',
        'jumlah',
        'tanggal',
        'sumber',
        'keterangan',
        'user_id',
        'status',
        'verified_at',
        'verified_by',
        'rejected_at',
        'rejected_by',
        'alasan_tolak',
        'bukti_transfer',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah' => 'decimal:2',
        'verified_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    protected $dates = ['deleted_at'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function rejector()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // Accessors
    public function getJumlahRupiahAttribute()
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning text-dark">⏳ Pending</span>',
            'verified' => '<span class="badge bg-success">✅ Verified</span>',
            'rejected' => '<span class="badge bg-danger">❌ Rejected</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
        ];

        return $statuses[$this->status] ?? 'Tidak Diketahui';
    }

    // Scopes
    public function scopeForUser($query, $user)
    {
        if ($user->hasRole('jamaah')) {
            return $query->where('user_id', $user->id);
        }

        return $query;
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    // Helper Methods
    public function isVerified()
    {
        return $this->status === 'verified';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}