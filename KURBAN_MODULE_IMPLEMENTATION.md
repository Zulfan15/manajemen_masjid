# MODUL MANAJEMEN KURBAN - DOKUMENTASI IMPLEMENTASI
## Sistem Manajemen Masjid

**Tanggal Implementasi:** 12 Januari 2026  
**Status:** ✅ FULLY IMPLEMENTED

---

## 📋 RINGKASAN MODUL

Modul Manajemen Kurban adalah sistem lengkap untuk mengelola seluruh alur ibadah kurban, mulai dari:
- Pendaftaran peserta kurban
- Pengelolaan hewan kurban
- Jadwal penyembelihan
- Distribusi daging
- Laporan pertanggungjawaban

---

## ✨ FITUR UTAMA YANG TELAH DIIMPLEMENTASI

### 1. ✅ MANAJEMEN INVENTARIS HEWAN (MASTER DATA)

#### **Pencatatan Hewan**
- ✅ Jenis hewan: Sapi, Kambing, Domba
- ✅ Jenis kelamin: Jantan/Betina
- ✅ Berat badan (kg)
- ✅ Kondisi kesehatan: Sehat, Cacat Ringan, Cacat Berat

#### **Pricing Management**
- ✅ Harga hewan (pokok)
- ✅ Biaya operasional (upah jagal, kebersihan, pakan)
- ✅ Total harga jual ke jamaah
- ✅ **HARGA PER BAGIAN (LOCKED PRICE)** - Harga terkunci di sistem

#### **Tracking Status Hewan**
- ✅ **Disiapkan** (Open order)
- ✅ **Siap Sembelih** (Kuota penuh/Lunas)
- ✅ **Disembelih** (Hari H)
- ✅ **Selesai** (Daging habis terbagi)

---

### 2. ✅ MANAJEMEN PESERTA (SHOHIBUL QURBAN)

#### **Sistem Slot & Kuota (Smart Validation)**
- ✅ **SAPI**: Support sistem patungan (Maks 7 orang/bagian)
- ✅ **KAMBING/DOMBA**: Validasi mutlak 1 orang = 1 ekor
- ✅ **SISTEM OTOMATIS MENOLAK** jika input ke-8 masuk untuk sapi
- ✅ **PROGRESS BAR** menampilkan sisa kuota per hewan

#### **Tipe Kepesertaan**
- ✅ **Perorangan** (Eceran)
- ✅ **Keluarga/Kolektif** (Misal: 1 keluarga borong 1 bagian atau 3 bagian sapi)

#### **Data Jamaah**
- ✅ Nama peserta
- ✅ **Bin/Binti** (nama ayah/ibu)
- ✅ No HP (wajib)
- ✅ Alamat (wajib)
- ✅ Nomor identitas (KTP)

---

### 3. ✅ KEUANGAN & TRANSAKSI

#### **Status Pembayaran**
- ✅ **Belum Lunas**
- ✅ **Cicilan**
- ✅ **Lunas**

#### **Kalkulator Otomatis**
- ✅ Sistem otomatis menghitung total tagihan berdasarkan jumlah bagian yang diambil
- ✅ Mencegah *human error* panitia salah hitung harga

#### **Locking Price**
- ✅ Harga terkunci di sistem
- ✅ Panitia **TIDAK BISA** manipulasi harga sembarangan saat input peserta
- ✅ Harga per bagian dihitung otomatis: `Total Biaya ÷ Max Kuota`

---

### 4. ✅ MANAJEMEN DISTRIBUSI (PASCA SEMBELIH)

#### **Pencatatan Hasil Sembelih**
- ✅ Input **total berat daging** yang didapat dari hewan tersebut

#### **Alokasi Distribusi**
- ✅ **Hak Shohibul Qurban** (biasanya 1/3 bagian = 33.33%)
- ✅ **Fakir Miskin / Warga Sekitar** (1/3 bagian = 33.33%)
- ✅ **Yayasan / Pihak Luar** (1/3 bagian = 33.34%)

#### **Tracking Status Distribusi**
- ✅ **Sedang Disiapkan** (Packing)
- ✅ **Sudah Didistribusi**

---

### 5. ✅ LAPORAN & OUTPUT (REPORTING)

#### **Cetak Laporan PDF**
- ✅ Laporan pertanggungjawaban per hewan
- ✅ Mencakup:
  - Data hewan (jenis, berat, kondisi)
  - Data keuangan (harga, biaya, pembayaran)
  - Data shohibul qurban (peserta lengkap)
  - Detail distribusi daging (penerima, alokasi, status)

#### **Dashboard Visual**
- ✅ **Progress bar sisa kuota per hewan**
  - Contoh: "Sapi A: Terisi 5/7 - Sisa 2 Slot"
- ✅ Statistik agregat:
  - Total kurban
  - Total peserta
  - Total pembayaran
  - Total daging terdistribusi
- ✅ Status distribusi per hewan

---

## 🗄️ DATABASE STRUCTURE

### **Tabel: kurbans**
```
- id
- nomor_kurban (unique)
- jenis_hewan (enum: sapi, kambing, domba)
- jenis_kelamin (enum: jantan, betina) [NEW]
- nama_hewan
- max_kuota (default based on jenis_hewan) [NEW]
- berat_badan
- kondisi_kesehatan
- tanggal_persiapan
- tanggal_penyembelihan
- harga_hewan
- biaya_operasional
- total_biaya
- total_berat_daging [NEW]
- harga_per_bagian (locked price) [NEW]
- status
- catatan
- created_by, updated_by
- timestamps
```

### **Tabel: peserta_kurbans**
```
- id
- kurban_id (foreign key)
- user_id (foreign key, nullable)
- nama_peserta
- bin_binti [NEW]
- nomor_identitas
- nomor_telepon
- alamat
- tipe_peserta (enum: perorangan, keluarga)
- jumlah_jiwa
- jumlah_bagian
- nominal_pembayaran
- status_pembayaran (enum: belum_lunas, lunas, cicilan)
- tanggal_pembayaran
- catatan
- created_by, updated_by
- timestamps
```

### **Tabel: distribusi_kurbans**
```
- id
- kurban_id (foreign key)
- peserta_kurban_id (foreign key, nullable)
- penerima_nama
- penerima_nomor_telepon
- penerima_alamat
- berat_daging
- estimasi_harga
- jenis_distribusi (enum: shohibul_qurban, fakir_miskin, yayasan) [UPDATED]
- persentase_alokasi (default 33.33) [NEW]
- tanggal_distribusi
- status_distribusi (enum: belum_didistribusi, sedang_disiapkan, sudah_didistribusi)
- catatan
- created_by, updated_by
- timestamps
```

---

## 🚀 SMART FEATURES

### **1. Smart Quota Validation**
```php
// Kurban Model
public function canAddParticipant(int $jumlahBagian = 1): bool
{
    // Kambing/Domba: must be 1 person = 1 unit
    if (in_array($this->jenis_hewan, ['kambing', 'domba'])) {
        if ($jumlahBagian != 1) {
            return false;
        }
        return !$this->isKuotaFull();
    }

    // Sapi: can have multiple portions (max 7 people)
    if ($this->jenis_hewan === 'sapi') {
        return !$this->isKuotaFull();
    }

    return !$this->isKuotaFull();
}
```

### **2. Automatic Price Calculator**
```php
public function calculatePembayaran(int $jumlahBagian = 1): float
{
    // Use locked price
    $hargaPerBagian = $this->harga_per_bagian ?: $this->calculateHargaPerBagian();
    
    return round($hargaPerBagian * $jumlahBagian, 2);
}
```

### **3. Price Locking Mechanism**
```php
// Set on kurban creation
$validated['max_kuota'] = match($validated['jenis_hewan']) {
    'sapi' => 7,
    'kambing', 'domba' => 1,
    default => 1,
};

$validated['harga_per_bagian'] = round($validated['total_biaya'] / $validated['max_kuota'], 2);
```

---

## 📊 ROUTES

```php
// Dashboard & Reports
GET  /kurban/dashboard                         - Dashboard visual
GET  /kurban/{kurban}/report/download          - Download PDF
GET  /kurban/{kurban}/report/view              - View PDF in browser

// Kurban CRUD
GET  /kurban                                   - List all kurban
GET  /kurban/create                            - Create form
POST /kurban                                   - Store kurban
GET  /kurban/{kurban}                          - Show detail
GET  /kurban/{kurban}/edit                     - Edit form
PUT  /kurban/{kurban}                          - Update kurban
DELETE /kurban/{kurban}                        - Delete kurban

// Peserta Management
GET  /kurban/{kurban}/peserta/create           - Add participant
POST /kurban/{kurban}/peserta                  - Store participant
GET  /kurban/{kurban}/peserta/{peserta}/edit   - Edit participant
PUT  /kurban/{kurban}/peserta/{peserta}        - Update participant
DELETE /kurban/{kurban}/peserta/{peserta}      - Delete participant

// Distribusi Management
GET  /kurban/{kurban}/distribusi/create        - Add distribution
POST /kurban/{kurban}/distribusi               - Store distribution
GET  /kurban/{kurban}/distribusi/{distribusi}/edit - Edit distribution
PUT  /kurban/{kurban}/distribusi/{distribusi}  - Update distribution
DELETE /kurban/{kurban}/distribusi/{distribusi} - Delete distribution
```

---

## 📁 FILES CREATED/MODIFIED

### **New Files:**
1. ✅ `database/migrations/2026_01_12_000001_enhance_kurban_tables_for_smart_features.php`
2. ✅ `app/Exports/KurbanReportExport.php`
3. ✅ `resources/views/modules/kurban/reports/pdf-laporan.blade.php`
4. ✅ `resources/views/modules/kurban/dashboard.blade.php`
5. ✅ `KURBAN_MODULE_IMPLEMENTATION.md` (this file)

### **Modified Files:**
1. ✅ `app/Models/Kurban.php` - Added smart validation methods
2. ✅ `app/Models/PesertaKurban.php` - Added bin_binti field
3. ✅ `app/Models/DistribusiKurban.php` - Added persentase_alokasi
4. ✅ `app/Http/Controllers/KurbanController.php` - Enhanced with validation & reports
5. ✅ `routes/web.php` - Added dashboard and report routes

---

## 🎯 USAGE EXAMPLES

### **Example 1: Adding a Cow with Smart Pricing**
```
1. Admin creates Kurban:
   - Jenis: Sapi
   - Harga Hewan: Rp 20,000,000
   - Biaya Operasional: Rp 500,000
   - System calculates:
     * Total Biaya: Rp 20,500,000
     * Max Kuota: 7
     * Harga per Bagian: Rp 2,928,571 (LOCKED)

2. Panitia adds participants:
   - Peserta 1: 1 bagian → Auto calculate: Rp 2,928,571
   - Peserta 2: 2 bagian → Auto calculate: Rp 5,857,142
   - ...
   - Peserta 7: System accepts
   - Peserta 8: ❌ SYSTEM REJECTS! "Quota full"
```

### **Example 2: Goat Validation**
```
1. Admin creates Kurban:
   - Jenis: Kambing
   - Max Kuota: 1 (automatic)

2. Panitia tries to add participant:
   - Input 0.5 bagian → ❌ REJECTED! "Must be 1 person = 1 unit"
   - Input 1 bagian → ✅ ACCEPTED
   - Try to add 2nd participant → ❌ REJECTED! "Quota full"
```

### **Example 3: Distribution**
```
After slaughter:
1. Record total meat weight: 150 kg
2. Distribute:
   - Shohibul Qurban: 50 kg (33.33%)
   - Fakir Miskin: 50 kg (33.33%)
   - Yayasan: 50 kg (33.34%)
3. Track status: Sedang Disiapkan → Sudah Didistribusi
```

---

## ⚙️ MIGRATION & SETUP

### **Run Migration:**
```bash
php artisan migrate
```

This will add new fields:
- `jenis_kelamin` to kurbans table
- `max_kuota` to kurbans table
- `total_berat_daging` to kurbans table
- `harga_per_bagian` to kurbans table
- `bin_binti` to peserta_kurbans table
- `jenis_distribusi` updated in distribusi_kurbans table
- `persentase_alokasi` to distribusi_kurbans table

### **Access Points:**
- Dashboard: `/kurban/dashboard`
- Kurban List: `/kurban`
- Create Kurban: `/kurban/create`

---

## 🔒 PERMISSIONS

All routes protected with:
- `module.access:kurban` - Module level access
- `permission:kurban.*` - Granular permissions

---

## 📝 NOTES

- PDF generation uses `barryvdh/laravel-dompdf` package (already installed)
- All monetary values use decimal(12,2) precision
- All weight values use decimal(8,2) precision
- Activity logging integrated for audit trail
- Smart validation prevents common mistakes
- Price locking ensures fair pricing

---

## ✅ TESTING CHECKLIST

- [x] Create kurban with automatic kuota setting
- [x] Add participant with automatic price calculation
- [x] Validate quota limit (reject 8th participant for sapi)
- [x] Validate kambing/domba must be 1 person = 1 unit
- [x] Record total meat weight after slaughter
- [x] Create distribution with allocation percentages
- [x] Generate PDF report
- [x] View dashboard with progress bars
- [x] Check locked price cannot be manipulated

---

## 📞 SUPPORT

For questions or issues regarding this module, refer to:
- `app/Http/Controllers/KurbanController.php` - Main controller logic
- `app/Models/Kurban.php` - Smart validation methods
- `app/Exports/KurbanReportExport.php` - Report generation

---

**Status:** ✅ FULLY IMPLEMENTED & READY FOR PRODUCTION

**Implementer:** GitHub Copilot AI Assistant  
**Date:** January 12, 2026
