<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sertifikat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sertifikat';

    protected $fillable = [
        'kegiatan_id',
        'user_id',
        'nomor_sertifikat',
        'nama_peserta',
        'nama_kegiatan',
        'tanggal_kegiatan',
        'tempat_kegiatan',
        'template',
        'ttd_pejabat',
        'jabatan_pejabat',
        'file_path',
        'download_count',
        'last_downloaded_at',
        'metadata',
        'generated_by',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'download_count' => 'integer',
        'last_downloaded_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relationships
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    /**
     * Scopes
     */
    public function scopeByKegiatan($query, $kegiatanId)
    {
        if ($kegiatanId) {
            return $query->where('kegiatan_id', $kegiatanId);
        }
        return $query;
    }

    public function scopeByTemplate($query, $template)
    {
        if ($template) {
            return $query->where('template', $template);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama_peserta', 'like', "%{$search}%")
                    ->orWhere('nama_kegiatan', 'like', "%{$search}%")
                    ->orWhere('nomor_sertifikat', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Helper Methods
     */
    public static function generateNomorSertifikat($kegiatanId, $urutan)
    {
        $tanggal = now()->format('Ymd');
        $kode = str_pad($kegiatanId, 3, '0', STR_PAD_LEFT);
        $urut = str_pad($urutan, 4, '0', STR_PAD_LEFT);
        
        return "CERT/{$tanggal}/{$kode}/{$urut}";
    }

    public function incrementDownload()
    {
        $this->increment('download_count');
        $this->update(['last_downloaded_at' => now()]);
    }

    public function getTemplateBadgeClass()
    {
        return match($this->template) {
            'kajian' => 'bg-green-100 text-green-800',
            'workshop' => 'bg-blue-100 text-blue-800',
            'pelatihan' => 'bg-amber-100 text-amber-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTemplateIcon()
    {
        return match($this->template) {
            'kajian' => 'fa-book-open',
            'workshop' => 'fa-tools',
            'pelatihan' => 'fa-graduation-cap',
            default => 'fa-certificate',
        };
    }

    public function hasFile()
    {
        return !empty($this->file_path) && file_exists(public_path($this->file_path));
    }

    public function getFileUrl()
    {
        return $this->file_path ? asset($this->file_path) : null;
    }
}
