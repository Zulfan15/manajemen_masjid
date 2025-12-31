# Modul Manajemen Takmir/Pengurus

## Deskripsi

Modul ini digunakan untuk mengelola data takmir/pengurus masjid, termasuk informasi personal, jabatan, periode kepengurusan, dan status keaktifan.

## Fitur Utama

1. **CRUD Data Takmir/Pengurus**

   - Tambah pengurus baru
   - Edit data pengurus
   - Hapus data pengurus
   - Lihat detail pengurus

2. **Filter & Search**

   - Filter berdasarkan status (aktif/nonaktif)
   - Filter berdasarkan jabatan
   - Pencarian berdasarkan nama, email, atau telepon

3. **Manajemen Foto**

   - Upload foto profil pengurus
   - Default avatar jika tidak ada foto

4. **Tracking Periode**
   - Periode mulai dan periode akhir kepengurusan
   - Status aktif/nonaktif

## Struktur Database

### Tabel: `takmir`

| Kolom         | Tipe              | Keterangan                                                |
| ------------- | ----------------- | --------------------------------------------------------- |
| id            | bigint            | Primary key                                               |
| nama          | string            | Nama lengkap pengurus                                     |
| jabatan       | enum              | Ketua (DKM), Wakil Ketua, Sekretaris, Bendahara, Pengurus |
| email         | string (nullable) | Email pengurus                                            |
| phone         | string (nullable) | Nomor telepon                                             |
| alamat        | text (nullable)   | Alamat lengkap                                            |
| periode_mulai | date              | Tanggal mulai periode                                     |
| periode_akhir | date              | Tanggal akhir periode                                     |
| status        | enum              | aktif/nonaktif                                            |
| foto          | string (nullable) | Path file foto                                            |
| keterangan    | text (nullable)   | Keterangan tambahan                                       |
| created_at    | timestamp         | Waktu dibuat                                              |
| updated_at    | timestamp         | Waktu terakhir diupdate                                   |

## File-File Penting

### Migration

- `database/migrations/2025_12_06_203104_create_takmir_table.php`

### Model

- `app/Models/Takmir.php`

### Controller

- `app/Http/Controllers/TakmirController.php`

### Views

- `resources/views/modules/takmir/index.blade.php` - Halaman listing
- `resources/views/modules/takmir/create.blade.php` - Form tambah
- `resources/views/modules/takmir/edit.blade.php` - Form edit
- `resources/views/modules/takmir/show.blade.php` - Detail pengurus

### Routes

```php
Route::middleware(['module.access:takmir'])->prefix('takmir')->name('takmir.')->group(function () {
    Route::resource('/', \App\Http\Controllers\TakmirController::class)->parameters(['' => 'takmir']);
});
```

## Permissions

- `takmir.view` - Melihat data takmir
- `takmir.create` - Menambah data takmir
- `takmir.update` - Mengupdate data takmir
- `takmir.delete` - Menghapus data takmir

## Roles yang Memiliki Akses

- `super_admin` - Akses penuh (view only untuk CRUD)
- `admin_takmir` - Akses penuh untuk manajemen takmir
- `pengurus_takmir` - Akses terbatas (sesuai permission)

## Cara Menggunakan

### 1. Akses Modul

Login sebagai user dengan role `admin_takmir` atau `super_admin`, lalu akses:

```
http://localhost:8000/takmir
```

### 2. Menambah Pengurus Baru

1. Klik tombol "Tambah Pengurus"
2. Isi form dengan data lengkap
3. Upload foto (opsional)
4. Klik "Simpan"

### 3. Edit Data Pengurus

1. Di halaman listing, klik ikon edit (pensil)
2. Ubah data yang diperlukan
3. Klik "Update"

### 4. Hapus Data Pengurus

1. Di halaman listing, klik ikon hapus (tempat sampah)
2. Konfirmasi penghapusan

### 5. Filter & Search

- Gunakan dropdown filter untuk memfilter berdasarkan status atau jabatan
- Gunakan search box untuk mencari berdasarkan nama, email, atau telepon
- Klik "Filter" untuk menerapkan filter
- Klik "Reset" untuk menghapus filter

## Seeder

Untuk menambah data dummy:

```bash
php artisan db:seed --class=TakmirSeeder
```

## Testing

1. Login sebagai `admin_takmir` (username: `admin_takmir`, password: `password`)
2. Akses modul takmir di menu navigasi
3. Test semua fitur CRUD
4. Test filter dan search
5. Test upload foto

## Validasi Form

- **Nama**: Wajib diisi
- **Jabatan**: Wajib diisi, pilih dari dropdown
- **Email**: Format email valid (opsional)
- **Phone**: String (opsional)
- **Periode Mulai**: Wajib diisi, format date
- **Periode Akhir**: Wajib diisi, harus setelah periode mulai
- **Status**: Wajib diisi (aktif/nonaktif)
- **Foto**: Image (jpg/png), maksimal 2MB (opsional)

## Activity Log

Setiap aksi CRUD akan tercatat dalam activity log dengan detail:

- User yang melakukan aksi
- Jenis aksi (create/update/delete/view)
- Timestamp
- Detail perubahan

## Troubleshooting

### Error: Storage symlink not found

```bash
php artisan storage:link
```

### Error: Permission denied saat upload foto

Pastikan folder `storage/app/public/takmir` memiliki permission write.

### Error: Class not found

```bash
composer dump-autoload
```

## Developer Notes

- Default avatar menggunakan UI Avatars API
- Foto disimpan di `storage/app/public/takmir`
- Pagination: 10 items per halaman
- Soft delete belum diimplementasikan

## Kelompok B7

**Topik**: Modul Manajemen Takmir/Pengurus
**Tanggal**: 6 Desember 2025
