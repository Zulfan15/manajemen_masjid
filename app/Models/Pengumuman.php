<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Pengumuman extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'kategori',
        'konten',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status',
        'prioritas',
        'views',
        'kegiatan_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_berakhir' => 'date',
        'views' => 'integer',
    ];

    /**
     * Relationships
     */
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scopes
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', now())
            ->where(function ($q) {
                $q->whereNull('tanggal_berakhir')
                    ->orWhere('tanggal_berakhir', '>=', now());
            });
    }

    public function scopeByKategori($query, $kategori)
    {
        if ($kategori) {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }

    public function scopeByPrioritas($query, $prioritas)
    {
        if ($prioritas) {
            return $query->where('prioritas', $prioritas);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('konten', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Helper Methods
     */
    public function isAktif()
    {
        if ($this->status !== 'aktif') {
            return false;
        }

        $now = now()->startOfDay();
        $mulai = $this->tanggal_mulai->startOfDay();
        $berakhir = $this->tanggal_berakhir ? $this->tanggal_berakhir->startOfDay() : null;

        return $mulai <= $now && (!$berakhir || $berakhir >= $now);
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function getStatusBadgeClass()
    {
        return $this->isAktif() 
            ? 'bg-green-100 text-green-800' 
            : 'bg-gray-100 text-gray-800';
    }

    public function getPrioritasBadgeClass()
    {
        return match($this->prioritas) {
            'mendesak' => 'bg-red-100 text-red-800',
            'tinggi' => 'bg-orange-100 text-orange-800',
            default => 'bg-blue-100 text-blue-800',
        };
    }

    public function getKategoriIcon()
    {
        return match($this->kategori) {
            'kajian' => 'fa-book-open',
            'kegiatan' => 'fa-calendar',
            'event' => 'fa-star',
            default => 'fa-info-circle',
        };
    }

    public function getExcerpt($length = 150)
    {
        return Str::limit(strip_tags($this->konten), $length);
    }
}
