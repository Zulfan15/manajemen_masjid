# Dokumentasi Sinkronisasi Antar Modul

## Tanggal Update
12 Januari 2026

## Deskripsi
Dokumen ini menjelaskan hubungan dan sinkronisasi data antar modul dalam Sistem Manajemen Masjid.

---

## Diagram Hubungan Antar Modul

```
┌─────────────────────────────────────────────────────────────────────┐
│                        SISTEM MANAJEMEN MASJID                       │
├─────────────────────────────────────────────────────────────────────┤
│                                                                      │
│  ┌───────────┐     ┌───────────┐     ┌───────────┐                  │
│  │    ZIS    │────►│ KEUANGAN  │────►│  LAPORAN  │                  │
│  │ Transaksi │     │ Pemasukan │     │ Statistik │                  │
│  └───────────┘     └───────────┘     └───────────┘                  │
│       │                 ▲                  ▲                        │
│       │                 │                  │                        │
│       └─────────────────┘                  │                        │
│                                            │                        │
│  ┌───────────┐                             │                        │
│  │  KEGIATAN │─────────────────────────────┤                        │
│  │  Absensi  │                             │                        │
│  └───────────┘                             │                        │
│                                            │                        │
│  ┌───────────┐                             │                        │
│  │  JAMAAH   │─────────────────────────────┘                        │
│  │   Users   │                                                      │
│  └───────────┘                                                      │
│                                                                      │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 1. ZIS → KEUANGAN (Sinkronisasi Otomatis)

### Deskripsi
Setiap transaksi ZIS (Zakat, Infak, Sedekah) otomatis tercatat ke tabel `pemasukan` di modul Keuangan.

### Implementasi
**File:** `app/Http/Controllers/ZISTransaksiController.php`

```php
// Saat menyimpan transaksi ZIS
Transaksi::create([...]);  // Simpan ke tabel transaksi (ZIS)

// Otomatis sync ke Pemasukan (Keuangan)
Pemasukan::create([
    'jenis' => $request->jenis_transaksi,
    'jumlah' => $request->nominal,
    'tanggal' => $request->tanggal_transaksi,
    'sumber' => "ZIS - {$sumberNama}",
    'keterangan' => "[ZIS Sync - {$kode}]",
    'status' => 'verified',  // Auto-verified
]);
```

### Alur Data
1. User input transaksi ZIS di modul ZIS
2. Data disimpan ke tabel `transaksi`
3. Data juga otomatis disimpan ke tabel `pemasukan` dengan status `verified`
4. Laporan keuangan menampilkan data dari `pemasukan`
5. Laporan ZIS menampilkan data gabungan dari `transaksi` dan `pemasukan`

---

## 2. KEUANGAN → LAPORAN (Agregasi Data)

### Deskripsi
Modul Laporan mengambil data dari modul Keuangan (Pemasukan & Pengeluaran) untuk menampilkan statistik.

### Implementasi
**File:** `app/Http/Controllers/LaporanController.php`

```php
// Data Pemasukan (hanya yang verified)
$totalPemasukan = Pemasukan::verified()
    ->whereYear('tanggal', $tahun)
    ->sum('jumlah');

// Data Pengeluaran
$totalPengeluaran = Pengeluaran::whereYear('tanggal', $tahun)
    ->sum('jumlah');

// Saldo
$saldo = $totalPemasukan - $totalPengeluaran;
```

### Data yang Ditampilkan
- Total Pemasukan per bulan/tahun
- Total Pengeluaran per bulan/tahun
- Saldo (Pemasukan - Pengeluaran)
- Grafik trend keuangan

---

## 3. ZIS → LAPORAN (Agregasi Data Gabungan)

### Deskripsi
Laporan statistik ZIS mengambil data dari dua sumber untuk memastikan kelengkapan.

### Implementasi
**File:** `app/Http/Controllers/LaporanController.php`

```php
public function getDataZIS(Request $request)
{
    // SUMBER 1: Tabel Transaksi (Modul ZIS)
    $dataZakat = DB::table('transaksi')
        ->where('jenis_transaksi', 'like', '%zakat%')
        ->sum('nominal');

    // SUMBER 2: Tabel Pemasukan (Modul Keuangan)
    $pemasukanZakat = Pemasukan::verified()
        ->where('jenis', 'like', '%zakat%')
        ->sum('jumlah');

    // Gabungkan kedua sumber
    $totalZakat = $dataZakat + $pemasukanZakat;
}
```

### Catatan
Data digabung untuk mencegah duplikasi perhitungan, meskipun sinkronisasi otomatis sudah berjalan.

---

## 4. JAMAAH → LAPORAN (Statistik User)

### Deskripsi
Modul Laporan mengambil data jamaah untuk statistik registrasi dan pertumbuhan.

### Implementasi
**File:** `app/Http/Controllers/LaporanController.php`

```php
public function getDataJamaah(Request $request)
{
    // Hitung jamaah berdasarkan role
    $totalJamaah = User::whereHas('roles', function($q) {
        $q->where('name', 'jamaah');
    })->count();

    // Jamaah terverifikasi
    $jamaahVerified = User::whereHas('roles', function($q) {
        $q->where('name', 'jamaah');
    })->where('is_verified', true)->count();
}
```

---

## 5. KEGIATAN → LAPORAN (Statistik Kegiatan)

### Deskripsi
Modul Laporan mengambil data kegiatan untuk menampilkan statistik event masjid.

### Implementasi
**File:** `app/Http/Controllers/LaporanController.php`

```php
public function getDataKegiatanBulanan(Request $request)
{
    $tahun = $request->get('tahun', date('Y'));

    $data = Kegiatan::selectRaw('MONTH(tanggal_mulai) as bulan, COUNT(id) as total')
        ->whereYear('tanggal_mulai', $tahun)
        ->groupByRaw('MONTH(tanggal_mulai)')
        ->get();
}
```

---

## Tabel Referensi Sinkronisasi

| Modul Sumber | Tabel | Modul Tujuan | Tabel | Jenis Sync |
|--------------|-------|--------------|-------|------------|
| ZIS | `transaksi` | Keuangan | `pemasukan` | **Otomatis (Create/Delete)** |
| ZIS | `transaksi` | Laporan | - | Agregasi (Read-only) |
| Keuangan | `pemasukan` | Laporan | - | Agregasi (Read-only) |
| Keuangan | `pengeluaran` | Laporan | - | Agregasi (Read-only) |
| Jamaah | `users` | Laporan | - | Agregasi (Read-only) |
| Kegiatan | `kegiatan` | Laporan | - | Agregasi (Read-only) |

---

## Integritas Data

### Transaksi Database
Semua operasi sinkronisasi menggunakan `DB::beginTransaction()` untuk menjaga konsistensi data:

```php
DB::beginTransaction();
try {
    // Operasi 1
    // Operasi 2
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
}
```

### Validasi
- ZIS → Keuangan: Auto-verified karena sudah melalui validasi modul ZIS
- Data yang ditampilkan di Laporan hanya yang berstatus `verified`

---

## Catatan Pengembangan

1. **Jangan duplikasi data manual** - Jika input melalui ZIS, tidak perlu input ulang di Keuangan
2. **Gunakan kode transaksi** - Sinkronisasi menggunakan kode transaksi di field keterangan untuk tracking
3. **Delete cascade** - Menghapus transaksi ZIS juga menghapus record pemasukan terkait

---

## Author
Dokumentasi oleh AI Assistant - Januari 2026
