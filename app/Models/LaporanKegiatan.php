<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaporanKegiatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'laporan_kegiatan';

    protected $fillable = [
        'kegiatan_id',
        'nama_kegiatan',
        'jenis_kegiatan',
        'tanggal_pelaksanaan',
        'waktu_pelaksanaan',
        'lokasi',
        'jumlah_peserta',
        'jumlah_hadir',
        'jumlah_tidak_hadir',
        'penanggung_jawab',
        'deskripsi',
        'hasil_capaian',
        'catatan_kendala',
        'foto_dokumentasi',
        'status',
        'is_public',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
        'waktu_pelaksanaan' => 'datetime:H:i',
        'foto_dokumentasi' => 'array',
        'jumlah_peserta' => 'integer',
        'jumlah_hadir' => 'integer',
        'jumlah_tidak_hadir' => 'integer',
        'is_public' => 'boolean',
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
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByJenis($query, $jenis)
    {
        if ($jenis) {
            return $query->where('jenis_kegiatan', $jenis);
        }
        return $query;
    }

    public function scopeByBulan($query, $bulan)
    {
        if ($bulan) {
            return $query->whereYear('tanggal_pelaksanaan', substr($bulan, 0, 4))
                ->whereMonth('tanggal_pelaksanaan', substr($bulan, 5, 2));
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama_kegiatan', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Helper Methods
     */
    public function getPersentaseKehadiran()
    {
        if ($this->jumlah_peserta == 0) {
            return 0;
        }
        return round(($this->jumlah_hadir / $this->jumlah_peserta) * 100, 1);
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'published' => 'bg-green-100 text-green-800',
            'draft' => 'bg-gray-100 text-gray-800',
            default => 'bg-blue-100 text-blue-800',
        };
    }

    public function getJenisBadgeClass()
    {
        return match($this->jenis_kegiatan) {
            'kajian' => 'bg-blue-100 text-blue-800',
            'sosial' => 'bg-green-100 text-green-800',
            'pendidikan' => 'bg-purple-100 text-purple-800',
            'perayaan' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function hasFotoDokumentasi()
    {
        return !empty($this->foto_dokumentasi);
    }

    public function getJumlahFoto()
    {
        return $this->foto_dokumentasi ? count($this->foto_dokumentasi) : 0;
    }
}
