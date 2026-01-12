# Dokumentasi Sinkronisasi Menyeluruh Sistem Masjid

## 1. ZIS (Zakat, Infak, Sedekah) ↔ Keuangan
- **Arah**: ZIS -> Pemasukan
- **Mekanisme**: Setiap transaksi ZIS (Zakat Fitrah, Maal, dll) otomatis dicatat ke tabel `pemasukan`.
- **Status**: ✅ Selesai
- **Detail**:
  - Pelunasan ZIS otomatis verified di keuangan.
  - Hapus transaksi ZIS otomatis hapus pemasukan terkait (rollback).

## 2. Kurban ↔ Keuangan
- **Arah**: Peserta Kurban -> Pemasukan
- **Mekanisme**: Pembayaran peserta kurban (shohibul qurban) otomatis dicatat ke tabel `pemasukan`.
- **Status**: ✅ Selesai
- **Detail**:
  - Hanya pembayaran dengan status 'Lunas' yang disinkronkan.
  - Jika peserta update status jadi 'Lunas', otomatis catat pemasukan.
  - Hapus peserta otomatis hapus pemasukan terkait (asumsi refund/batal).

## 3. Inventaris ↔ Keuangan
- **Arah**: Pembelian Aset -> Pengeluaran
- **Mekanisme**: Input aset baru dengan `harga_perolehan` > 0 otomatis mencatat `pengeluaran`.
- **Status**: ✅ Selesai
- **Detail**:
  - Menambahkan kolom `harga_perolehan` di tabel aset.
  - Otomatis membuat kategori pengeluaran 'Inventaris & Aset' jika belum ada.
  - Mencatat pengeluaran sesuai tanggal beli aset.

## 4. Laporan (Pusat Data)
- **Arah**: Semua Modul -> Laporan
- **Mekanisme**: Modul laporan mengagregasi data dari:
  - Keuangan (Pemasukan & Pengeluaran Manual)
  - ZIS (Transaksi ZIS)
  - Kurban (Pembayaran Peserta)
  - Inventaris (Pembelian Aset)
  - Kegiatan & Jamaah (Statistik Non-Finansial)
- **Status**: ✅ Selesai
- **Detail**:
  - Laporan Keuangan real-time mencerminkan semua transaksi di atas.

---
**Catatan Penting untuk User:**
- Jangan menginput ulang Pemasukan untuk ZIS atau Kurban secara manual di menu Keuangan, karena sudah otomatis.
- Jangan menginput ulang Pengeluaran untuk beli Aset secara manual, cukup input di menu Inventaris.
