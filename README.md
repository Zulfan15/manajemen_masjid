<<<<<<< HEAD
# Sistem Manajemen Masjid

<p align="center">
<img src="https://img.shields.io/badge/Laravel-10%2B-red" alt="Laravel">
<img src="https://img.shields.io/badge/PHP-8.1%2B-blue" alt="PHP">
<img src="https://img.shields.io/badge/MySQL-8.0%2B-orange" alt="MySQL">
</p>

## 📖 Tentang Proyek

Sistem Manajemen Masjid adalah aplikasi web berbasis Laravel yang dirancang untuk mengelola berbagai aspek operasional masjid dengan sistem autentikasi dan otorisasi yang robust.

### Modul Utama

1. **Manajemen Jamaah** - Kelola data jamaah masjid
2. **Keuangan Masjid** - Kelola transaksi keuangan
3. **Kegiatan & Acara** - Kelola kegiatan dan acara masjid
4. **ZIS** - Kelola Zakat, Infaq, dan Sedekah
5. **Kurban** - Kelola data kurban
6. **Inventaris** - Kelola inventaris masjid
7. **Takmir** - Kelola data takmir
8. **Informasi & Pengumuman** - Kelola informasi dan pengumuman
9. **Laporan & Statistik** - Kelola laporan dan statistik
10. **Autentikasi & Otorisasi** - Sistem keamanan dan akses

## 🔐 Sistem Autentikasi & Otorisasi

### Hierarki Role

#### 1. Super Admin
- **Role:** `super_admin`
- **Akses:** READ-ONLY ke SEMUA modul
- **Fungsi:** Monitoring dan oversight melalui activity logs
- **Batasan:** TIDAK dapat create, update, atau delete data

#### 2. Module Admin
- **Role:** `admin_{module_name}` (contoh: `admin_keuangan`, `admin_jamaah`)
- **Akses:** FULL CRUD pada modul yang ditugaskan
- **Fungsi:** Mengelola modul dan mempromosikan jamaah menjadi pengurus
- **Batasan:** Hanya dapat akses modul yang ditugaskan

#### 3. Module Officer (Pengurus)
- **Role:** `pengurus_{module_name}` (contoh: `pengurus_keuangan`)
- **Akses:** FULL CRUD pada modul yang ditugaskan
- **Fungsi:** Membantu admin mengelola modul
- **Cara Mendapat Role:** Dipromosikan oleh module admin dari role jamaah

#### 4. Jamaah
- **Role:** `jamaah`
- **Akses:** Hanya dapat melihat data pribadi
- **Fungsi:** User biasa
- **Default Role:** Otomatis diberikan saat registrasi

### Dynamic Role Assignment

Module admin dapat mempromosikan user jamaah menjadi pengurus:

```
User "Ahmad" → Role: jamaah
↓ (dipromosikan oleh admin_keuangan)
User "Ahmad" → Role: jamaah + pengurus_keuangan
```

## 🚀 Instalasi

### Persyaratan Sistem

- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js & NPM (optional, untuk asset compilation)

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone <repository-url>
cd "Manpro Masjid"
```

2. **Install Dependencies**
```bash
composer install
```

3. **Setup Environment**
```bash
copy .env.example .env
php artisan key:generate
```

4. **Konfigurasi Database**

Edit file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=manajemen_masjid
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. **Jalankan Migrasi & Seeder**
```bash
php artisan migrate
php artisan db:seed
```

6. **Publish Spatie Permission Config**
```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

7. **Jalankan Development Server**
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## 👥 Default User Credentials

Setelah menjalankan seeder, Anda dapat login dengan akun berikut:

### Super Admin
```
Username: superadmin
Password: password
```

### Module Admin (contoh)
```
Username: admin_keuangan
Password: password
```

### Jamaah
```
Username: jamaah1
Password: password
```

## 📁 Struktur Proyek

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── UserManagementController.php
│   │   └── ActivityLogController.php
│   └── Middleware/
│       ├── CheckRole.php
│       ├── CheckPermission.php
│       ├── CheckModuleAccess.php
│       └── LogActivity.php
├── Models/
│   ├── User.php
│   └── ActivityLog.php
└── Services/
    ├── AuthService.php
    ├── RoleService.php
    └── ActivityLogService.php

database/
├── migrations/
│   ├── 2024_01_01_000000_create_users_table.php
│   ├── 2024_01_01_000001_create_password_reset_tokens_table.php
│   ├── 2024_01_01_000002_create_sessions_table.php
│   └── 2024_01_01_000003_create_activity_logs_table.php
└── seeders/
    ├── RolesAndPermissionsSeeder.php
    ├── UsersSeeder.php
    └── DatabaseSeeder.php

resources/views/
├── layouts/
│   ├── app.blade.php
│   ├── navbar.blade.php
│   └── sidebar.blade.php
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── dashboard/
│   └── index.blade.php
└── modules/
    ├── jamaah/
    ├── keuangan/
    ├── kegiatan/
    ├── zis/
    ├── kurban/
    ├── inventaris/
    ├── takmir/
    ├── informasi/
    └── laporan/

routes/
└── web.php
```

## 🔑 Permission Structure

Format: `{module}.{action}`

Contoh:
- `jamaah.view` - Melihat data jamaah
- `keuangan.create` - Membuat transaksi keuangan
- `kegiatan.update` - Mengubah data kegiatan
- `zis.delete` - Menghapus data ZIS

### Permission Matrix

| Role | Permissions |
|------|-------------|
| super_admin | *.view (semua modul read-only) |
| admin_jamaah | jamaah.* (full CRUD) |
| admin_keuangan | keuangan.* (full CRUD) |
| pengurus_keuangan | keuangan.* (full CRUD) |
| jamaah | jamaah.view (data pribadi) |

## 📝 API Service Layer

### AuthService

```php
// Get current user
$authService->getCurrentUser();

// Check role
$authService->hasRole('super_admin');

// Check permission
$authService->hasPermission('keuangan.create');

// Check module access
$authService->canAccessModule('keuangan');

// Get accessible modules
$authService->getAccessibleModules();
```

### RoleService

```php
// Assign role
$roleService->assignRole($user, 'admin_keuangan', $assignedBy);

// Remove role
$roleService->removeRole($user, 'admin_keuangan', $removedBy);

// Promote jamaah to officer
$roleService->promoteToOfficer($user, 'keuangan', $assignedBy);

// Get users by role
$roleService->getUsersByRole('pengurus_keuangan');
```

### ActivityLogService

```php
// Log activity
$activityLogService->log('create', 'keuangan', 'Created transaction', [
    'transaction_id' => 123,
    'amount' => 1000000
]);

// Log authentication
$activityLogService->logAuth('login', $user);

// Log CRUD operation
$activityLogService->logCrud('create', 'keuangan', 'transaction', 123);

// Get user activities
$activityLogService->getUserActivities($userId, $filters);

// Get module activities
$activityLogService->getModuleActivities('keuangan', $filters);
```

## 🛡️ Middleware Usage

### Protect Routes

```php
// Check role
Route::middleware(['auth', 'role:admin_keuangan'])->group(function() {
    // Routes
});

// Check permission
Route::middleware(['auth', 'permission:keuangan.create'])->group(function() {
    // Routes
});

// Check module access
Route::middleware(['auth', 'module.access:keuangan'])->group(function() {
    // Routes
});
```

## 📊 Activity Logging

Semua aktivitas penting dilog otomatis:

- Login/Logout
- CRUD operations
- Role assignments
- Permission changes

Super admin dapat melihat semua log di `/activity-logs`

## 🔧 Konfigurasi Middleware

Edit `app/Http/Kernel.php` atau `bootstrap/app.php` (Laravel 11+):

```php
protected $routeMiddleware = [
    'role' => \App\Http\Middleware\CheckRole::class,
    'permission' => \App\Http\Middleware\CheckPermission::class,
    'module.access' => \App\Http\Middleware\CheckModuleAccess::class,
];
```

## 🎨 Frontend

- **Framework CSS:** Tailwind CSS (via CDN)
- **Icons:** Font Awesome 6
- **JavaScript:** Alpine.js (untuk interaktivitas)
- **Responsive:** Full mobile-friendly

## 🧪 Testing

```bash
# Run tests
php artisan test

# Run specific test
php artisan test --filter=AuthTest
```

## 📦 Package Dependencies

- **spatie/laravel-permission** - Role & Permission management
- **spatie/laravel-activitylog** - Activity logging (optional, custom implementation included)

## 🤝 Contributing

Untuk mengembangkan modul baru:

1. Buat controller di `app/Http/Controllers`
2. Buat route di `routes/web.php` dengan middleware yang sesuai
3. Buat view di `resources/views/modules/{module_name}`
4. Gunakan service layer untuk logic bisnis
5. Log semua aktivitas penting

## 📄 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Developer

Dikembangkan untuk Sistem Manajemen Masjid Terpadu

## 📞 Support

Untuk pertanyaan atau bantuan, hubungi tim development.

---

**© 2024 Manajemen Masjid. All Rights Reserved.**
=======
# manajemen_masjid-main
>>>>>>> 0bd98f2d0321a80273f60fd9dd71424d94a20dcd
