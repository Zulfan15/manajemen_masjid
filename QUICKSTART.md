# ⚡ QUICK START GUIDE
# Sistem Manajemen Masjid - Mulai dalam 5 Menit!

## 🎯 Instalasi Cepat

### Prasyarat
- ✅ PHP 8.1+ terinstall
- ✅ Composer terinstall
- ✅ MySQL/MariaDB berjalan
- ✅ Terminal/Command Prompt

### Langkah Instalasi (Copy-Paste)

```bash
# 1. Masuk ke folder project
cd "Manpro Masjid"

# 2. Install dependencies
composer install

# 3. Copy environment file
# Windows:
copy .env.example .env
# Linux/Mac:
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Buat database (lewat phpMyAdmin atau command line)
# Nama database: manajemen_masjid

# 6. Edit .env (sesuaikan DB_USERNAME dan DB_PASSWORD)

# 7. Publish Spatie Permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# 8. Run migrations
php artisan migrate

# 9. Run seeders
php artisan db:seed

# 10. Jalankan server
php artisan serve
```

## 🌐 Akses Aplikasi

Buka browser: **http://localhost:8000**

## 🔑 Default Login Credentials

### Super Admin (READ-ONLY semua modul)
```
Username: superadmin
Password: password
```

### Admin Keuangan (FULL CRUD Keuangan)
```
Username: admin_keuangan
Password: password
```

### Admin Jamaah (FULL CRUD Jamaah)
```
Username: admin_jamaah
Password: password
```

### Jamaah Biasa
```
Username: jamaah1
Password: password
```

## 🎮 Test Drive

### 1. Login sebagai Super Admin
- Masuk dengan: `superadmin` / `password`
- Lihat: Dashboard menampilkan SEMUA modul
- Coba: Buka modul apapun → Tampil notifikasi "Mode View Only"
- Perhatikan: Tidak ada tombol "Tambah" atau "Edit"

### 2. Login sebagai Admin Module
- Logout
- Login dengan: `admin_keuangan` / `password`
- Lihat: Dashboard hanya menampilkan modul Keuangan
- Coba: Buka modul Keuangan → Ada tombol edit/tambah
- Coba: Akses modul lain → Error 403 Forbidden ✅

### 3. Test Promosi User
- Login sebagai admin_keuangan
- Sidebar → "Kelola Pengurus"
- Pilih user jamaah → Promosikan
- User tersebut sekarang punya akses ke modul Keuangan

### 4. Login sebagai Jamaah
- Logout
- Login dengan: `jamaah1` / `password`
- Lihat: Dashboard kosong / minimal access
- Coba: Akses modul → Error 403 Forbidden ✅

## 📋 Fitur yang Sudah Berfungsi

✅ **Authentication**
- Login (username atau email)
- Register (auto role jamaah)
- Logout
- Account lock setelah 5x salah password
- Remember me

✅ **Navigation**
- Sidebar dinamis berdasarkan role
- Dashboard dengan stats
- Module cards dengan icon

✅ **Authorization**
- Super admin: View all, edit none
- Module admin: Full CRUD di modulnya
- Module officer: Full CRUD di modulnya (setelah dipromosikan)
- Jamaah: Minimal access

✅ **User Management**
- Promosi jamaah → pengurus (by module admin)
- View semua user (by super admin)

✅ **Activity Logging**
- Semua login/logout tercatat
- Role assignment tercatat
- Super admin bisa lihat all logs

✅ **9 Module Navigation**
- Jamaah, Keuangan, Kegiatan, ZIS, Kurban
- Inventaris, Takmir, Informasi, Laporan

## 🔥 Tips Cepat

### Reset Database
```bash
php artisan migrate:fresh --seed
```

### Clear Cache
```bash
php artisan optimize:clear
```

### Lihat All Routes
```bash
php artisan route:list
```

### Buat User Baru Manual
```bash
php artisan tinker

$user = User::create([
    'name' => 'User Baru',
    'email' => 'user@example.com',
    'username' => 'userbaru',
    'password' => Hash::make('password')
]);
$user->assignRole('jamaah');
exit;
```

## 🐛 Troubleshooting Cepat

### Error saat composer install
```bash
composer install --ignore-platform-reqs
```

### Error database connection
- Cek MySQL/MariaDB running?
- Cek username/password di .env
- Cek database sudah dibuat?

### Halaman blank setelah login
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Error "Class not found"
```bash
composer dump-autoload
php artisan config:clear
```

## 📚 Dokumentasi Lengkap

- **INSTALLATION.md** - Panduan instalasi detail
- **README.md** - Overview proyek
- **API_DOCUMENTATION.md** - Dokumentasi API & Services
- **PROJECT_STRUCTURE.md** - Struktur file proyek

## 🎯 Next Steps

### Untuk Developer Modul:

1. **Buat Controller untuk modulmu**
   ```bash
   php artisan make:controller ModulController
   ```

2. **Gunakan AuthService untuk cek permission**
   ```php
   if (!app(AuthService::class)->hasPermission('keuangan.create')) {
       abort(403);
   }
   ```

3. **Log aktivitas penting**
   ```php
   app(ActivityLogService::class)->log(
       'create', 
       'keuangan', 
       'Membuat transaksi baru'
   );
   ```

4. **Update view modul di resources/views/modules/**

5. **Test dengan berbagai role**

## 🎨 Customization

### Ganti Warna Theme
Edit `resources/views/layouts/app.blade.php` dan ganti class Tailwind:
- `green-700` → warna lain (blue-700, purple-700, etc)

### Ganti Logo
Edit `resources/views/layouts/navbar.blade.php`:
```blade
<i class="fas fa-mosque text-2xl"></i>
<!-- ganti dengan <img src="/logo.png"> -->
```

### Tambah Field User
1. Buat migration: `php artisan make:migration add_field_to_users`
2. Tambah field di migration
3. Run: `php artisan migrate`
4. Update model User.php ($fillable)

## ⚠️ Keamanan

**PENTING sebelum production:**

1. ✅ Ubah semua password default
2. ✅ Set `APP_DEBUG=false` di .env
3. ✅ Set `APP_ENV=production` di .env
4. ✅ Generate key baru: `php artisan key:generate`
5. ✅ Setup SSL certificate
6. ✅ Backup database reguler
7. ✅ Update dependencies: `composer update`

## 📞 Bantuan

Jika stuck:
1. Cek file `storage/logs/laravel.log`
2. Cek browser console (F12)
3. Baca dokumentasi lengkap
4. Hubungi tim development

## 🎉 Selamat!

Sistem Manajemen Masjid siap digunakan!

**Autentikasi ✅ | Navigasi ✅ | Authorization ✅**

Sekarang tim lain bisa fokus develop fitur CRUD di masing-masing modul. Foundation sudah kuat! 💪

---

**Happy Coding! 🚀**
