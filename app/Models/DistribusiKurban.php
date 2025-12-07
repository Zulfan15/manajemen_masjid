<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class DistribusiKurban extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'distribusi_kurbans';

    protected $fillable = [
        'kurban_id',
        'peserta_kurban_id',
        'penerima_nama',
        'penerima_nomor_telepon',
        'penerima_alamat',
        'berat_daging',
        'estimasi_harga',
        'jenis_distribusi',
        'tanggal_distribusi',
        'status_distribusi',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_distribusi' => 'date',
        'berat_daging' => 'decimal:2',
        'estimasi_harga' => 'decimal:2',
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
     * Relasi ke Peserta Kurban
     */
    public function pesertaKurban(): BelongsTo
    {
        return $this->belongsTo(PesertaKurban::class, 'peserta_kurban_id');
    }

    /**
     * Relasi ke User yang membuat
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke User yang mengupdate
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
            ->setDescriptionForEvent(fn(string $eventName) => "Distribusi kurban telah {$eventName}")
            ->dontSubmitEmptyLogs();
    }

    // ===== HELPER METHODS =====

    /**
     * Check apakah distribusi sudah dilakukan
     */
    public function sudahDidistribusi(): bool
    {
        return $this->status_distribusi === 'sudah_didistribusi';
    }

    /**
     * Check apakah distribusi belum dilakukan
     */
    public function belumDidistribusi(): bool
    {
        return $this->status_distribusi === 'belum_didistribusi';
    }

    /**
     * Check apakah distribusi sedang disiapkan
     */
    public function sedangDisiapkan(): bool
    {
        return $this->status_distribusi === 'sedang_disiapkan';
    }

    /**
     * Update status distribusi
     */
    public function updateStatusDistribusi(string $status, ?int $userId = null): bool
    {
        $this->status_distribusi = $status;
        if ($status === 'sudah_didistribusi' && is_null($this->tanggal_distribusi)) {
            $this->tanggal_distribusi = now()->toDateString();
        }
        if ($userId) {
            $this->updated_by = $userId;
        }
        return $this->save();
    }

    /**
     * Get label jenis distribusi dalam bahasa Indonesia
     */
    public function getJenisDistribusiLabel(): string
    {
        $labels = [
            'keluarga_peserta' => 'Keluarga Peserta',
            'fakir_miskin' => 'Fakir Miskin',
            'saudara' => 'Saudara',
            'kerabat' => 'Kerabat',
            'lainnya' => 'Lainnya',
        ];
        return $labels[$this->jenis_distribusi] ?? 'Tidak Diketahui';
    }
}
