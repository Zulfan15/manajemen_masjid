---
description: Langkah-langkah untuk menyelesaikan masalah setelah git pull
---

# Panduan Menyelesaikan Masalah Setelah Git Pull

Ikuti langkah-langkah ini secara berurutan untuk memastikan website berjalan dengan benar setelah melakukan `git pull`:

## 1. Selesaikan Merge Conflicts
Jika ada merge conflict (seperti yang terlihat di terminal):

```bash
# Lihat file mana yang conflict
git status

# Untuk file log yang conflict (biasanya bisa diabaikan), gunakan versi mereka:
git checkout --theirs storage/logs/laravel.log

# Atau jika banyak file log:
git add storage/logs/laravel.log
git commit -m "Resolved merge conflict in laravel.log"
```

**PENTING**: Jika ada conflict di file kode (`.php`, `.blade.php`), harus diselesaikan manual!

## 2. Update Dependencies
```bash
# Update Composer dependencies
composer install

# Jika ada package.json, update juga:
npm install
```

## 3. Perbarui File .env
```bash
# Copy .env.example jika belum punya .env
copy .env.example .env

# Generate application key jika belum
php artisan key:generate
```

**CATATAN**: Pastikan konfigurasi database di `.env` sudah benar:
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

## 4. Update Database
```bash
# Jalankan migration yang baru
php artisan migrate

# Jika ada seeder baru (tanyakan ke teman yang punya kode asli):
php artisan db:seed
```

## 5. Clear All Cache
```bash
# Clear semua cache Laravel
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize ulang
php artisan optimize
```

## 6. Import Database (Jika Diperlukan)
Jika struktur database sangat berbeda, mungkin perlu import ulang:

```bash
# Stop server dulu (Ctrl+C)

# Import database dari file SQL
mysql -u username -p database_name < manajemen_masjid.sql

# Atau via phpMyAdmin dengan import file manajemen_masjid.sql
```

## 7. Restart Development Server
```bash
# Stop server yang sedang running (Ctrl+C)
# Jalankan ulang
php artisan serve
```

## 8. Cek Browser
- Clear browser cache (Ctrl+Shift+Delete)
- Hard reload (Ctrl+F5)
- Buka di incognito mode untuk memastikan

---

## ⚠️ Troubleshooting

### Masalah: Dropdown Tidak Muncul di Fitur Keuangan

Jika dropdown kategori di halaman keuangan kosong/tidak muncul:

**Penyebab**: Tabel `kategori_pengeluaran` di database kosong

**Solusi**:
```bash
# Jalankan seeder untuk mengisi data kategori
php artisan db:seed --class=PengeluaranSeeder

# Atau jalankan semua seeder sekaligus:
php artisan db:seed
```

**Alternatif**: Import database dari file SQL yang terbaru
```bash
# Import file manajemen_masjid.sql yang ada di root folder
# Via MySQL command line:
mysql -u username -p database_name < manajemen_masjid.sql

# Atau via phpMyAdmin:
# 1. Buka phpMyAdmin
# 2. Pilih database "manajemen_masjid"
# 3. Klik tab "Import"
# 4. Pilih file "manajemen_masjid.sql"
# 5. Klik "Go"
```

---

### Troubleshooting Umum

Jika masih berbeda, cek:

1. **Versi PHP**: Pastikan versi PHP sama
   ```bash
   php -v
   ```

2. **Lihat Git Branch**: Pastikan di branch yang sama
   ```bash
   git branch
   ```

3. **Lihat Commit Terakhir**: Pastikan commit-nya sama
   ```bash
   git log -1
   ```

4. **Bandingkan .env**: Tanyakan teman Anda untuk memastikan konfigurasi `.env` sama (terutama database)

5. **Cek Database**: Pastikan tabel sudah ada dan berisi data
   ```bash
   php artisan tinker
   # Lalu ketik:
   \App\Models\KategoriPengeluaran::count();
   # Jika hasilnya 0, berarti tabel kosong, jalankan seeder
   ```

---

## 📝 Checklist Cepat

- [ ] Selesaikan merge conflicts
- [ ] `composer install`
- [ ] Update `.env` (DB config)
- [ ] `php artisan migrate`
- [ ] `php artisan cache:clear`
- [ ] `php artisan config:clear`
- [ ] `php artisan view:clear`
- [ ] Restart server
- [ ] Hard reload browser (Ctrl+F5)
