# Dokumentasi Modul Autentikasi & Akses Pengguna

## Ringkasan
Modul ini mengimplementasikan sistem Role-Based Access Control (RBAC) yang komprehensif untuk aplikasi manajemen masjid.

---

## 1. Hierarki User & Roles

### A. Super Admin
- **Role:** `super_admin`
- **Akses:** READ-ONLY ke semua modul
- **Fungsi:** Monitoring dan pengawasan melalui activity logs
- **Tidak bisa:** Create, Update, atau Delete data apapun

### B. Admin Modul
Setiap modul memiliki admin dengan akses FULL CRUD:
- `admin_jamaah` - Modul Manajemen Jamaah
- `admin_keuangan` - Modul Keuangan
- `admin_kegiatan` - Modul Kegiatan & Acara
- `admin_zis` - Modul ZIS
- `admin_kurban` - Modul Kurban
- `admin_inventaris` - Modul Inventaris
- `admin_takmir` - Modul Takmir
- `admin_informasi` - Modul Informasi
- `admin_laporan` - Modul Laporan

### C. Pengurus Modul
- Role: `pengurus_[nama_modul]` (contoh: `pengurus_keuangan`)
- Dipromosikan oleh admin modul terkait
- Akses CRUD ke modul yang ditentukan

### D. Jamaah
- **Role:** `jamaah`
- **Akses:** Hanya data pribadi dan view kegiatan
- **Default:** Role otomatis untuk user baru

---

## 2. Dynamic Role Assignment

### Alur Promosi:
```
1. User "Ahmad" mendaftar → Role: jamaah
2. admin_keuangan mempromosikan Ahmad → Ahmad dapat role: pengurus_keuangan
3. Ahmad sekarang memiliki akses CRUD ke modul Keuangan
4. Ahmad TIDAK bisa mengakses modul lain
```

### Implementasi:
- **Service:** `App\Services\RoleService`
- **Method:** `promoteToOfficer($user, $module, $assignedBy)`
- **Menu:** Sidebar > Admin [Module] > Kelola Pengurus

---

## 3. Permission Structure

### Naming Convention:
```
[module].[action]

Contoh:
- keuangan.view
- keuangan.create
- keuangan.update
- keuangan.delete
```

### Permission Matrix:
| Role | Permissions |
|------|-------------|
| super_admin | *.view (semua modul, read-only) |
| admin_jamaah | jamaah.* (full CRUD) |
| pengurus_keuangan | keuangan.* (full CRUD) |
| jamaah | jamaah.view (data sendiri) |

---

## 4. Activity Logging System

### Data yang Di-log:
- **Authentication:** login, logout, failed attempts, account locked
- **CRUD Operations:** create, update, delete dengan before/after values
- **Role Changes:** role_assigned, role_removed
- **Permission Changes**

### Tabel `activity_logs`:
| Column | Type | Description |
|--------|------|-------------|
| user_id | bigint | User yang melakukan aksi |
| action | string | Jenis aksi (login, create, dll) |
| module | string | Modul terkait |
| description | text | Deskripsi detail |
| properties | json | Data tambahan (before/after) |
| ip_address | string | IP pengguna |
| user_agent | string | Browser info |
| created_at | timestamp | Waktu aksi |

### Service:
- **Class:** `App\Services\ActivityLogService`
- **Methods:**
  - `log($action, $module, $description, $properties)`
  - `logAuth($action, $user, $properties)`
  - `logCrud($operation, $module, $resourceType, $resourceId, $changes)`
  - `getUserActivities($userId, $filters)`
  - `getModuleActivities($module, $filters)`
  - `getAllActivities($filters)`
  - `getStatistics($filters)`

---

## 5. Middleware

### Tersedia:
| Middleware | Fungsi |
|------------|--------|
| `auth` | Cek user terautentikasi |
| `role:[role_name]` | Cek role spesifik |
| `permission:[permission]` | Cek permission spesifik |
| `module.access:[module]` | Cek akses ke modul |

### Contoh Penggunaan:
```php
// Hanya super_admin
Route::middleware(['auth', 'role:super_admin'])->group(...);

// Admin atau pengurus keuangan
Route::middleware(['auth', 'module.access:keuangan'])->group(...);

// Permission spesifik
Route::middleware(['auth', 'permission:keuangan.create'])->group(...);
```

---

## 6. Authentication Features

### Fitur:
- ✅ Registrasi (auto-assign role `jamaah`)
- ✅ Login dengan email/username + password
- ✅ Logout
- ✅ Password Reset via Email
- ✅ Remember Me
- ✅ Account Lock setelah 5 failed attempts (30 menit)
- ✅ Session Management

### Security:
- Password di-hash dengan bcrypt
- CSRF Protection aktif
- Input validation
- XSS Protection

---

## 7. File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── UserManagementController.php
│   │   └── ActivityLogController.php
│   └── Middleware/
│       ├── CheckRole.php
│       ├── CheckPermission.php
│       ├── CheckModuleAccess.php
│       └── LogActivity.php
├── Models/
│   ├── User.php (with Spatie HasRoles trait)
│   └── ActivityLog.php
└── Services/
    ├── AuthService.php
    ├── RoleService.php
    └── ActivityLogService.php

resources/views/
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── activity-logs/
│   ├── index.blade.php         # Super Admin - Semua Log
│   ├── module.blade.php        # Admin Modul - Log per Modul
│   ├── my-logs.blade.php       # User - Log Sendiri
│   └── recent.blade.php        # Widget Dashboard
└── users/
    └── (management views)
```

---

## 8. Routes

### Authentication:
```
GET  /login              - Form login
POST /login              - Proses login
GET  /register           - Form registrasi
POST /register           - Proses registrasi
POST /logout             - Logout
GET  /forgot-password    - Form reset password
```

### Activity Logs (Super Admin):
```
GET /activity-logs           - Semua log
GET /activity-logs/recent    - Log terbaru
GET /activity-logs/export    - Export CSV
```

### User Management:
```
GET  /users                        - Daftar user
GET  /users/{id}                   - Detail user
GET  /users/promote/{module}       - Form promosi
POST /users/promote/{module}       - Proses promosi
DELETE /users/demote/{module}/{id} - Demosi ke jamaah
```

---

## 9. API/Service Layer

### AuthService:
```php
$authService->getCurrentUser();
$authService->hasRole($role);
$authService->hasPermission($permission);
$authService->hasAnyRole($roles);
$authService->canAccessModule($module);
$authService->isSuperAdmin();
```

### RoleService:
```php
$roleService->assignRole($user, $role, $assignedBy);
$roleService->removeRole($user, $role, $removedBy);
$roleService->getUsersByRole($role);
$roleService->promoteToOfficer($user, $module, $assignedBy);
$roleService->demoteToJamaah($user, $module, $removedBy);
$roleService->getPromotableUsers($module);
```

---

## 10. Cara Penggunaan

### Cek Akses di Controller:
```php
// Cek super admin
if (auth()->user()->isSuperAdmin()) { ... }

// Cek akses modul
if (auth()->user()->canAccessModule('keuangan')) { ... }

// Cek role
if (auth()->user()->hasRole('admin_keuangan')) { ... }

// Cek permission
if (auth()->user()->hasPermissionTo('keuangan.create')) { ... }
```

### Cek Akses di Blade:
```blade
@role('super_admin')
    <p>Super Admin Content</p>
@endrole

@can('keuangan.create')
    <button>Tambah Transaksi</button>
@endcan
```

---

## Success Criteria ✅

| Kriteria | Status |
|----------|--------|
| Super admin dapat melihat semua modul (read-only) | ✅ |
| Admin modul memiliki kontrol penuh atas modulnya | ✅ |
| Admin modul dapat mempromosikan jamaah menjadi pengurus | ✅ |
| Semua aksi tercatat dan bisa dilihat super admin | ✅ |
| Autentikasi aman dan reliable | ✅ |
| Developer lain bisa mengintegrasikan dengan mudah | ✅ |
| Sistem scalable untuk modul baru | ✅ |
