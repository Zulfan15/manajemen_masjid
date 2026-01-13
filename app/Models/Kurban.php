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
        'jenis_kelamin',     // Baru
        'nama_hewan',
        'max_kuota',         // Baru
        'berat_badan',
        'kondisi_kesehatan',
        'tanggal_persiapan',
        'tanggal_penyembelihan',
        'harga_hewan',
        'biaya_operasional',
        'total_biaya',
        'total_berat_daging', // Baru
        'harga_per_bagian',   // Baru
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

    // ===== HELPER METHODS (BASIC) =====

    /**
     * Hitung total peserta kurban (Jumlah Orang)
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

    // ===== STATUS CHECKS =====

    public function bisaDipersiapkan(): bool
    {
        return $this->status === 'disiapkan';
    }

    public function bisaDiyembelih(): bool
    {
        return $this->status === 'siap_sembelih';
    }

    public function sudahDisembelih(): bool
    {
        return in_array($this->status, ['disembelih', 'didistribusi', 'selesai']);
    }

    // ===== LOGIKA KUOTA & PERHITUNGAN =====

    /**
     * Dapatkan Max Kuota berdasarkan jenis hewan
     * Sapi: 7, Kambing/Domba: 1
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
     * Hitung penggunaan kuota saat ini.
     * Menggunakan SUM(jumlah_bagian) bukan COUNT(), karena 1 orang bisa ambil 2 bagian sapi.
     */
    public function getCurrentKuotaUsage(): float
    {
        return (float) $this->pesertaKurbans()->sum('jumlah_bagian');
    }

    /**
     * Sisa slot yang tersedia
     */
    public function getSisaKuota(): float
    {
        $maxKuota = $this->max_kuota ?: $this->getMaxKuotaByJenisHewan();
        $currentUsage = $this->getCurrentKuotaUsage();
        return max(0, $maxKuota - $currentUsage);
    }

    /**
     * Cek apakah kuota sudah penuh
     */
    public function isKuotaFull(): bool
    {
        return $this->getSisaKuota() <= 0;
    }

    /**
     * Persentase keterisian kuota (untuk Progress Bar)
     */
    public function getKuotaPercentage(): float
    {
        $maxKuota = $this->max_kuota ?: $this->getMaxKuotaByJenisHewan();
        if ($maxKuota == 0) return 0;
        
        $currentUsage = $this->getCurrentKuotaUsage();
        return round(($currentUsage / $maxKuota) * 100, 2);
    }

    /**
     * Validasi apakah peserta baru bisa ditambahkan
     */
    public function canAddParticipant(float $jumlahBagian = 1): bool
    {
        // Validasi Kambing/Domba harus tepat 1
        if (in_array($this->jenis_hewan, ['kambing', 'domba'])) {
            if ($jumlahBagian != 1) {
                return false; 
            }
            return !$this->isKuotaFull();
        }

        // Validasi Sapi: Bagian yang diminta tidak boleh melebihi sisa
        return $jumlahBagian <= $this->getSisaKuota();
    }

    /**
     * Hitung harga per bagian (Terkunci di DB atau hitung manual)
     */
    public function calculateHargaPerBagian(): float
    {
        // Jika sudah diset di database, gunakan itu
        if ($this->harga_per_bagian > 0) {
            return $this->harga_per_bagian;
        }

        $maxKuota = $this->max_kuota ?: $this->getMaxKuotaByJenisHewan();
        
        if ($maxKuota == 0) return 0;
        
        return round($this->total_biaya / $maxKuota, 2);
    }

    /**
     * Hitung nominal pembayaran otomatis berdasarkan jumlah bagian
     */
    public function calculatePembayaran(float $jumlahBagian = 1): float
    {
        $hargaPerBagian = $this->calculateHargaPerBagian();
        return round($hargaPerBagian * $jumlahBagian, 2);
    }

    /**
     * Update status helper
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