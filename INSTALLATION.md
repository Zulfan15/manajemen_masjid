# 📋 PANDUAN INSTALASI LENGKAP
# Sistem Manajemen Masjid

## Persyaratan Sistem

Pastikan sistem Anda memenuhi persyaratan berikut:

- **PHP:** >= 8.1
- **Composer:** Latest version
- **Database:** MySQL 8.0+ atau MariaDB 10.3+
- **Web Server:** Apache/Nginx (atau gunakan PHP built-in server untuk development)
- **Extensions PHP yang diperlukan:**
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Fileinfo

---

## Langkah-langkah Instalasi

### 1. Persiapan Awal

#### Windows
```powershell
# Pastikan Composer terinstall
composer --version

# Pastikan PHP terinstall
php -v
```

#### Linux/Mac
```bash
# Pastikan Composer terinstall
composer --version

# Pastikan PHP terinstall
php -v
```

### 2. Clone atau Copy Project

Jika menggunakan Git:
```bash
git clone <repository-url> manajemen-masjid
cd manajemen-masjid
```

Atau jika sudah memiliki folder:
```bash
cd "Manpro Masjid"
```

### 3. Install Dependencies PHP

```bash
composer install
```

**Catatan:** Proses ini akan menginstall semua package yang diperlukan termasuk:
- Laravel Framework
- Spatie Laravel Permission
- Spatie Laravel Activity Log
- Dan dependencies lainnya

**Jika muncul error**, coba:
```bash
composer install --ignore-platform-reqs
```

### 4. Setup Environment File

#### Windows (PowerShell)
```powershell
Copy-Item .env.example .env
```

#### Linux/Mac
```bash
cp .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

Command ini akan generate key unik untuk aplikasi Anda di file `.env`

### 6. Konfigurasi Database

Edit file `.env` dan sesuaikan dengan konfigurasi database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=manajemen_masjid
DB_USERNAME=root
DB_PASSWORD=
```

**Untuk XAMPP/WAMP:**
- DB_HOST: 127.0.0.1
- DB_PORT: 3306
- DB_USERNAME: root
- DB_PASSWORD: (kosongkan)

**Untuk Laragon:**
- DB_HOST: localhost
- DB_PORT: 3306
- DB_USERNAME: root
- DB_PASSWORD: (kosongkan atau sesuai setting)

### 7. Buat Database

#### Menggunakan phpMyAdmin:
1. Buka http://localhost/phpmyadmin
2. Klik "New" atau "Baru"
3. Masukkan nama database: `manajemen_masjid`
4. Pilih Collation: `utf8mb4_unicode_ci`
5. Klik "Create"

#### Menggunakan Command Line:
```bash
# Login ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE manajemen_masjid CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 8. Publish Config Spatie Permission

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

Jika muncul pilihan, pilih nomor untuk `spatie/laravel-permission`

### 9. Jalankan Migrasi Database

```bash
php artisan migrate
```

Command ini akan membuat semua tabel yang diperlukan:
- users
- password_reset_tokens
- sessions
- activity_logs
- roles
- permissions
- role_user
- permission_role
- model_has_roles
- model_has_permissions
- role_has_permissions

### 10. Jalankan Seeder (Data Awal)

```bash
php artisan db:seed
```

Ini akan membuat:
- Roles (super_admin, admin_*, pengurus_*, jamaah)
- Permissions untuk semua modul
- Sample users dengan berbagai roles

**Output yang diharapkan:**
```
Roles and permissions created successfully!
Total Roles: 20
Total Permissions: 36

Users created successfully!
Total Users: 15

=== LOGIN CREDENTIALS ===
Super Admin:
  Username: superadmin
  Password: password

Module Admins (example):
  Username: admin_keuangan
  Password: password

Jamaah:
  Username: jamaah1
  Password: password
=========================
```

### 11. Create Storage Link (Optional)

Jika menggunakan file upload:
```bash
php artisan storage:link
```

### 12. Set Permissions (Linux/Mac)

Jika menggunakan Linux/Mac, set permissions:
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### 13. Jalankan Development Server

```bash
php artisan serve
```

Aplikasi akan berjalan di: **http://127.0.0.1:8000** atau **http://localhost:8000**

---

## Verifikasi Instalasi

### 1. Akses Aplikasi
Buka browser dan akses: http://localhost:8000

### 2. Test Login

#### Login sebagai Super Admin:
- **Username:** superadmin
- **Password:** password
- **Hasil:** Anda akan melihat dashboard dengan akses VIEW ke semua modul

#### Login sebagai Admin Module:
- **Username:** admin_keuangan (atau admin module lain)
- **Password:** password
- **Hasil:** Anda akan melihat modul yang bisa Anda kelola

#### Login sebagai Jamaah:
- **Username:** jamaah1
- **Password:** password
- **Hasil:** Akses terbatas

### 3. Test Navigasi
- Cek sidebar → Modul yang muncul sesuai dengan role
- Coba akses modul → Harus bisa akses modul yang diizinkan
- Coba akses modul lain → Harus ditolak (403)

---

## Troubleshooting

### Error: "Class 'Spatie\Permission\...' not found"

**Solusi:**
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Error: "SQLSTATE[HY000] [1045] Access denied"

**Solusi:**
- Periksa username dan password database di `.env`
- Pastikan MySQL/MariaDB berjalan
- Test koneksi database:
```bash
php artisan tinker
DB::connection()->getPdo();
```

### Error: "Specified key was too long"

**Solusi:**
Tambahkan di `app/Providers/AppServiceProvider.php`:
```php
use Illuminate\Support\Facades\Schema;

public function boot()
{
    Schema::defaultStringLength(191);
}
```

### Error: "No application encryption key"

**Solusi:**
```bash
php artisan key:generate
```

### Halaman tidak bisa diakses setelah login

**Solusi:**
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Session tidak tersimpan

**Solusi:**
Pastikan folder `storage` writable:
```bash
# Windows (run as administrator)
icacls storage /grant Everyone:(OI)(CI)F /T

# Linux/Mac
chmod -R 775 storage
```

---

## Konfigurasi Production (Deployment)

### 1. Set Environment ke Production

Edit `.env`:
```env
APP_ENV=production
APP_DEBUG=false
```

### 2. Optimize Aplikasi

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Setup Web Server

#### Apache (.htaccess sudah included)

Pastikan `mod_rewrite` aktif:
```apache
<VirtualHost *:80>
    ServerName masjid.local
    DocumentRoot /path/to/manajemen-masjid/public
    
    <Directory /path/to/manajemen-masjid/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx

```nginx
server {
    listen 80;
    server_name masjid.local;
    root /path/to/manajemen-masjid/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 4. Setup Scheduled Tasks (Optional)

Tambahkan ke crontab untuk maintenance otomatis:
```bash
* * * * * cd /path/to/manajemen-masjid && php artisan schedule:run >> /dev/null 2>&1
```

### 5. Setup Supervisor (Optional)

Untuk menjalankan queue workers:
```ini
[program:manajemen-masjid-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/manajemen-masjid/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/manajemen-masjid/storage/logs/worker.log
```

---

## Command Berguna

```bash
# Clear semua cache
php artisan optimize:clear

# Lihat routes
php artisan route:list

# Lihat semua commands
php artisan list

# Buat user baru
php artisan tinker
User::create([...])

# Reset database (HATI-HATI!)
php artisan migrate:fresh --seed

# Rollback migrasi
php artisan migrate:rollback

# Check status migrasi
php artisan migrate:status
```

---

## Next Steps

1. **Ubah Password Default** - Segera ubah password akun-akun default
2. **Konfigurasi Email** - Setup SMTP untuk reset password dan notifikasi
3. **Backup Database** - Setup backup otomatis database
4. **SSL Certificate** - Install SSL untuk production
5. **Monitoring** - Setup monitoring dan logging

---

## Support

Jika mengalami masalah:

1. Periksa log di `storage/logs/laravel.log`
2. Periksa error di browser console (F12)
3. Pastikan semua persyaratan sistem terpenuhi
4. Hubungi tim development

---

**Selamat! Aplikasi Manajemen Masjid siap digunakan! 🎉**
