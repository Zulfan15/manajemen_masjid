<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kurban extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'kurbans';

    protected $fillable = [
        'nomor_kurban',
        'jenis_hewan',
        'nama_hewan',
        'berat_badan',
        'kondisi_kesehatan',
        'tanggal_persiapan',
        'tanggal_penyembelihan',
        'harga_hewan',
        'biaya_operasional',
        'total_biaya',
        'status',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_persiapan' => 'date',
        'tanggal_penyembelihan' => 'date',
        'berat_badan' => 'decimal:2',
        'harga_hewan' => 'decimal:2',
        'biaya_operasional' => 'decimal:2',
        'total_biaya' => 'decimal:2',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Relasi ke User yang membuat kurban
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke User yang mengupdate kurban
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relasi ke Peserta Kurban (satu kurban bisa punya banyak peserta)
     */
    public function pesertaKurbans(): HasMany
    {
        return $this->hasMany(PesertaKurban::class, 'kurban_id');
    }

    /**
     * Relasi ke Distribusi Kurban
     */
    public function distribusiKurbans(): HasMany
    {
        return $this->hasMany(DistribusiKurban::class, 'kurban_id');
    }

    // ===== ACTIVITY LOG CONFIGURATION =====

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->useLogName('kurban')
            ->setDescriptionForEvent(fn(string $eventName) => "Kurban telah {$eventName}")
            ->dontSubmitEmptyLogs();
    }

    // ===== HELPER METHODS =====

    /**
     * Hitung total peserta kurban
     */
    public function totalPeserta(): int
    {
        return $this->pesertaKurbans()->count();
    }

    /**
     * Hitung total pembayaran yang masuk
     */
    public function totalPembayaran(): float
    {
        return (float) $this->pesertaKurbans()
            ->where('status_pembayaran', '!=', 'belum_lunas')
            ->sum('nominal_pembayaran');
    }

    /**
     * Hitung total daging yang sudah didistribusi
     */
    public function totalDagingDidistribusi(): float
    {
        return (float) $this->distribusiKurbans()
            ->where('status_distribusi', 'sudah_didistribusi')
            ->sum('berat_daging');
    }

    /**
     * Check apakah kurban sudah bisa disembelih
     */
    public function bisaDipersiapkan(): bool
    {
        return $this->status === 'disiapkan';
    }

    /**
     * Check apakah kurban siap disembelih
     */
    public function bisaDiyembelih(): bool
    {
        return $this->status === 'siap_sembelih';
    }

    /**
     * Check apakah kurban sudah disembelih
     */
    public function sudahDisembelih(): bool
    {
        return in_array($this->status, ['disembelih', 'didistribusi', 'selesai']);
    }

    /**
     * Update status kurban
     */
    public function updateStatus(string $status, ?int $userId = null): bool
    {
        $this->status = $status;
        if ($userId) {
            $this->updated_by = $userId;
        }
        return $this->save();
    }
}
