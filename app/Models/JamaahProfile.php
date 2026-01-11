<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class JamaahProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'tanggal_lahir',
        'tempat_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'pendidikan_terakhir',
        'status_pernikahan',
        'no_hp',
        'alamat',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'status_aktif',
        'foto',
        'catatan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'status_aktif' => 'boolean',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Kategori Jamaah
     */
    public function categories()
    {
        return $this->belongsToMany(
            JamaahCategory::class,
            'jamaah_category_jamaah',
            'jamaah_profile_id',
            'jamaah_category_id'
        );
    }

    /**
     * Relasi ke Donasi
     */
    public function donations()
    {
        return $this->hasMany(Donation::class, 'jamaah_profile_id');
    }

    /**
     * Relasi ke Partisipasi Kegiatan
     */
    public function participations()
    {
        return $this->hasMany(Participation::class, 'jamaah_profile_id');
    }

    /**
     * Relasi ke Kegiatan melalui KegiatanPeserta
     */
    public function kegiatan()
    {
        return $this->hasManyThrough(
            Kegiatan::class,
            KegiatanPeserta::class,
            'user_id',
            'id',
            'user_id',
            'kegiatan_id'
        );
    }

    /**
     * Relasi langsung ke KegiatanPeserta
     */
    public function kegiatanPeserta()
    {
        return $this->hasMany(KegiatanPeserta::class, 'user_id', 'user_id');
    }

    /**
     * Get alamat lengkap
     */
    public function getAlamatLengkapAttribute()
    {
        $parts = array_filter([
            $this->alamat,
            $this->rt ? 'RT ' . $this->rt : null,
            $this->rw ? 'RW ' . $this->rw : null,
            $this->kelurahan,
            $this->kecamatan,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Get umur jamaah
     */
    public function getUmurAttribute()
    {
        if (!$this->tanggal_lahir) {
            return null;
        }
        return $this->tanggal_lahir->diffInYears(now());
    }

    /**
     * Get jenis kelamin label
     */
    public function getJenisKelaminLabelAttribute()
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Get status pernikahan label
     */
    public function getStatusPernikahanLabelAttribute()
    {
        $labels = [
            'belum_menikah' => 'Belum Menikah',
            'menikah' => 'Menikah',
            'duda' => 'Duda',
            'janda' => 'Janda',
        ];
        return $labels[$this->status_pernikahan] ?? '-';
    }

    /**
     * Total donasi jamaah
     */
    public function getTotalDonasiAttribute()
    {
        return $this->donations()->where('status', 'confirmed')->sum('amount');
    }

    /**
     * Jumlah kegiatan yang diikuti
     */
    public function getJumlahKegiatanAttribute()
    {
        return $this->kegiatanPeserta()->count();
    }
}
