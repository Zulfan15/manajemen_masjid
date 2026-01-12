<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PesertaKurban extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'peserta_kurbans';

    protected $fillable = [
        'kurban_id',
        'user_id',
        'nama_peserta',
        'bin_binti',
        'nomor_identitas',
        'nomor_telepon',
        'alamat',
        'tipe_peserta',
        'jumlah_jiwa',
        'jumlah_bagian',
        'nominal_pembayaran',
        'status_pembayaran',
        'tanggal_pembayaran',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'jumlah_bagian' => 'decimal:2',
        'nominal_pembayaran' => 'decimal:2',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Relasi ke Kurban
     */
    public function kurban(): BelongsTo
    {
        return $this->belongsTo(Kurban::class, 'kurban_id');
    }

    /**
     * Relasi ke User (jika peserta adalah user)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke User yang membuat data
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke User yang mengupdate data
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ===== ACTIVITY LOG CONFIGURATION =====

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('kurban')
            ->setDescriptionForEvent(fn(string $eventName) => "Peserta kurban telah {$eventName}")
            ->dontSubmitEmptyLogs();
    }

    // ===== HELPER METHODS =====

    /**
     * Check apakah peserta sudah lunas
     */
    public function sudahLunas(): bool
    {
        return $this->status_pembayaran === 'lunas';
    }

    /**
     * Check apakah peserta masih cicilan
     */
    public function masihCicilan(): bool
    {
        return $this->status_pembayaran === 'cicilan';
    }

    /**
     * Check apakah peserta belum lunas
     */
    public function belumLunas(): bool
    {
        return $this->status_pembayaran === 'belum_lunas';
    }

    /**
     * Update status pembayaran
     */
    public function updateStatusPembayaran(string $status, ?int $userId = null): bool
    {
        $this->status_pembayaran = $status;
        if ($status === 'lunas' && is_null($this->tanggal_pembayaran)) {
            $this->tanggal_pembayaran = now()->toDateString();
        }
        if ($userId) {
            $this->updated_by = $userId;
        }
        return $this->save();
    }

    /**
     * Dapatkan nama peserta atau nama user
     */
    public function getNamaPesertaLengkap(): string
    {
        return $this->nama_peserta ?: ($this->user?->name ?? 'Tidak Diketahui');
    }
}
