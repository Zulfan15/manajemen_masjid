<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kegiatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kegiatans';

    protected $fillable = [
        'nama_kegiatan',
        'jenis_kegiatan',
        'kategori',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'pic',
        'kontak_pic',
        'kuota_peserta',
        'jumlah_peserta',
        'status',
        'budget',
        'realisasi_biaya',
        'is_recurring',
        'recurring_type',
        'recurring_day',
        'gambar',
        'catatan',
        'butuh_pendaftaran',
        'sertifikat_tersedia',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
        'is_recurring' => 'boolean',
        'butuh_pendaftaran' => 'boolean',
        'sertifikat_tersedia' => 'boolean',
        'budget' => 'decimal:2',
        'realisasi_biaya' => 'decimal:2',
    ];

    // Relationships
    public function peserta()
    {
        return $this->hasMany(KegiatanPeserta::class, 'kegiatan_id');
    }

    public function absensi()
    {
        return $this->hasMany(KegiatanAbsensi::class, 'kegiatan_id');
    }

    public function notifikasi()
    {
        return $this->hasMany(KegiatanNotifikasi::class, 'kegiatan_id');
    }

    public function laporan()
    {
        return $this->hasOne(LaporanKegiatan::class, 'kegiatan_id');
    }

    public function pengumuman()
    {
        return $this->hasMany(Pengumuman::class, 'kegiatan_id');
    }

    public function sertifikat()
    {
        return $this->hasMany(Sertifikat::class, 'kegiatan_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['direncanakan', 'berlangsung']);
    }

    public function scopeRutin($query)
    {
        return $query->where('jenis_kegiatan', 'rutin');
    }

    public function scopeEventKhusus($query)
    {
        return $query->where('jenis_kegiatan', 'event_khusus');
    }

    public function scopeMendatang($query)
    {
        return $query->where('tanggal_mulai', '>=', now()->format('Y-m-d'))
                    ->orderBy('tanggal_mulai', 'asc');
    }

    public function scopeBerlangsung($query)
    {
        return $query->where('status', 'berlangsung');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    // Helpers
    public function isFull()
    {
        if (is_null($this->kuota_peserta)) {
            return false; // unlimited
        }
        return $this->jumlah_peserta >= $this->kuota_peserta;
    }

    public function sisaKuota()
    {
        if (is_null($this->kuota_peserta)) {
            return null; // unlimited
        }
        return max(0, $this->kuota_peserta - $this->jumlah_peserta);
    }

    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'direncanakan' => 'bg-blue-100 text-blue-800',
            'berlangsung' => 'bg-green-100 text-green-800',
            'selesai' => 'bg-gray-100 text-gray-800',
            'dibatalkan' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getJenisBadgeClass()
    {
        return match($this->jenis_kegiatan) {
            'rutin' => 'bg-purple-100 text-purple-800',
            'insidental' => 'bg-yellow-100 text-yellow-800',
            'event_khusus' => 'bg-pink-100 text-pink-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getKategoriIcon()
    {
        return match($this->kategori) {
            'kajian' => 'fa-book-quran',
            'sosial' => 'fa-hands-helping',
            'ibadah' => 'fa-mosque',
            'pendidikan' => 'fa-graduation-cap',
            'ramadan' => 'fa-moon',
            'maulid' => 'fa-birthday-cake',
            'qurban' => 'fa-mosque',
            default => 'fa-calendar'
        };
    }

    public function sudahDaftar($userId)
    {
        return $this->peserta()->where('user_id', $userId)->exists();
    }

    public function incrementPeserta()
    {
        $this->increment('jumlah_peserta');
    }

    public function decrementPeserta()
    {
        $this->decrement('jumlah_peserta');
    }
}
