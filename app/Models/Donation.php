<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'jamaah_profile_id',
        'amount',
        'type',
        'keterangan',
        'donation_date',
        'status',
        'metode_pembayaran',
        'bukti_transfer',
    ];

    protected $casts = [
        'donation_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Types constants
     */
    const TYPE_INFAK = 'infak';
    const TYPE_SEDEKAH = 'sedekah';
    const TYPE_ZAKAT = 'zakat';
    const TYPE_WAKAF = 'wakaf';
    const TYPE_LAINNYA = 'lainnya';

    /**
     * Relasi ke Jamaah Profile
     */
    public function jamaah()
    {
        return $this->belongsTo(JamaahProfile::class, 'jamaah_profile_id');
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute()
    {
        $labels = [
            'infak' => 'Infak',
            'sedekah' => 'Sedekah',
            'zakat' => 'Zakat',
            'wakaf' => 'Wakaf',
            'lainnya' => 'Lainnya',
        ];
        return $labels[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Dikonfirmasi',
            'cancelled' => 'Dibatalkan',
        ];
        return $labels[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get status color class
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'yellow',
            'confirmed' => 'green',
            'cancelled' => 'red',
        ];
        return $colors[$this->status] ?? 'gray';
    }

    /**
     * Scope: hanya donasi yang confirmed
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }

    /**
     * Scope: hanya donasi yang pending
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Available types for dropdown
     */
    public static function getTypes()
    {
        return [
            self::TYPE_INFAK => 'Infak',
            self::TYPE_SEDEKAH => 'Sedekah',
            self::TYPE_ZAKAT => 'Zakat',
            self::TYPE_WAKAF => 'Wakaf',
            self::TYPE_LAINNYA => 'Lainnya',
        ];
    }

    /**
     * Available statuses for dropdown
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Menunggu Konfirmasi',
            self::STATUS_CONFIRMED => 'Dikonfirmasi',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }
}
