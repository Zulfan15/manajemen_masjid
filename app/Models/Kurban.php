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
        'jenis_kelamin',
        'nama_hewan',
        'max_kuota',
        'berat_badan',
        'kondisi_kesehatan',
        'tanggal_persiapan',
        'tanggal_penyembelihan',
        'harga_hewan',
        'biaya_operasional',
        'total_biaya',
        'total_berat_daging',
        'harga_per_bagian',
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
        'total_berat_daging' => 'decimal:2',
        'harga_per_bagian' => 'decimal:2',
        'max_kuota' => 'integer',
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
     * Get maximum quota based on animal type
     * Sapi: max 7 people, Kambing/Domba: max 1 person
     */
    public function getMaxKuotaByJenisHewan(): int
    {
        return match($this->jenis_hewan) {
            'sapi' => 7,
            'kambing', 'domba' => 1,
            default => 1,
        };
    }

    /**
     * Calculate current quota usage (total participants)
     */
    public function getCurrentKuotaUsage(): int
    {
        return $this->pesertaKurbans()->count();
    }

    /**
     * Get remaining quota slots
     */
    public function getSisaKuota(): int
    {
        $maxKuota = $this->max_kuota ?: $this->getMaxKuotaByJenisHewan();
        $currentUsage = $this->getCurrentKuotaUsage();
        return max(0, $maxKuota - $currentUsage);
    }

    /**
     * Check if quota is full
     */
    public function isKuotaFull(): bool
    {
        return $this->getSisaKuota() <= 0;
    }

    /**
     * Get quota fill percentage
     */
    public function getKuotaPercentage(): float
    {
        $maxKuota = $this->max_kuota ?: $this->getMaxKuotaByJenisHewan();
        if ($maxKuota == 0) return 0;
        
        $currentUsage = $this->getCurrentKuotaUsage();
        return round(($currentUsage / $maxKuota) * 100, 2);
    }

    /**
     * Validate if new participant can be added
     * Returns true if can add, false if quota full
     */
    public function canAddParticipant(int $jumlahBagian = 1): bool
    {
        // For Kambing/Domba: must be 1 person = 1 unit
        if (in_array($this->jenis_hewan, ['kambing', 'domba'])) {
            if ($jumlahBagian != 1) {
                return false; // Kambing/Domba must be exactly 1 portion
            }
            return !$this->isKuotaFull();
        }

        // For Sapi: can have multiple portions (max 7 people)
        if ($this->jenis_hewan === 'sapi') {
            return !$this->isKuotaFull();
        }

        return !$this->isKuotaFull();
    }

    /**
     * Calculate price per portion (locked price)
     */
    public function calculateHargaPerBagian(): float
    {
        $maxKuota = $this->max_kuota ?: $this->getMaxKuotaByJenisHewan();
        
        if ($maxKuota == 0) return 0;
        
        return round($this->total_biaya / $maxKuota, 2);
    }

    /**
     * Calculate automatic payment for participant
     */
    public function calculatePembayaran(int $jumlahBagian = 1): float
    {
        // Use locked price if available, otherwise calculate
        $hargaPerBagian = $this->harga_per_bagian ?: $this->calculateHargaPerBagian();
        
        return round($hargaPerBagian * $jumlahBagian, 2);
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
