# 👥 DAFTAR USER & ROLE
# Sistem Manajemen Masjid

## 🔐 Default Users (Hasil Seeder)

Setelah menjalankan `php artisan db:seed`, berikut adalah daftar user yang tersedia:

---

## 1. SUPER ADMIN

### Super Administrator
- **Username:** `superadmin`
- **Email:** superadmin@masjid.com
- **Password:** `password`
- **Role:** super_admin
- **Akses:** READ-ONLY semua modul
- **Fitur Khusus:**
  - Lihat semua activity logs
  - Lihat semua user
  - Assign/remove role secara manual
  - Monitoring sistem

---

## 2. MODULE ADMINS (9 Admin)

### Admin Jamaah
- **Username:** `admin_jamaah`
- **Email:** admin.jamaah@masjid.com
- **Password:** `password`
- **Role:** admin_jamaah
- **Akses:** FULL CRUD Modul Jamaah
- **Dapat:** Promosikan jamaah menjadi pengurus_jamaah

### Admin Keuangan
- **Username:** `admin_keuangan`
- **Email:** admin.keuangan@masjid.com
- **Password:** `password`
- **Role:** admin_keuangan
- **Akses:** FULL CRUD Modul Keuangan
- **Dapat:** Promosikan jamaah menjadi pengurus_keuangan

### Admin Kegiatan
- **Username:** `admin_kegiatan`
- **Email:** admin.kegiatan@masjid.com
- **Password:** `password`
- **Role:** admin_kegiatan
- **Akses:** FULL CRUD Modul Kegiatan
- **Dapat:** Promosikan jamaah menjadi pengurus_kegiatan

### Admin ZIS
- **Username:** `admin_zis`
- **Email:** admin.zis@masjid.com
- **Password:** `password`
- **Role:** admin_zis
- **Akses:** FULL CRUD Modul ZIS
- **Dapat:** Promosikan jamaah menjadi pengurus_zis

### Admin Kurban
- **Username:** `admin_kurban`
- **Email:** admin.kurban@masjid.com
- **Password:** `password`
- **Role:** admin_kurban
- **Akses:** FULL CRUD Modul Kurban
- **Dapat:** Promosikan jamaah menjadi pengurus_kurban

### Admin Inventaris
- **Username:** `admin_inventaris`
- **Email:** admin.inventaris@masjid.com
- **Password:** `password`
- **Role:** admin_inventaris
- **Akses:** FULL CRUD Modul Inventaris
- **Dapat:** Promosikan jamaah menjadi pengurus_inventaris

### Admin Takmir
- **Username:** `admin_takmir`
- **Email:** admin.takmir@masjid.com
- **Password:** `password`
- **Role:** admin_takmir
- **Akses:** FULL CRUD Modul Takmir
- **Dapat:** Promosikan jamaah menjadi pengurus_takmir

### Admin Informasi
- **Username:** `admin_informasi`
- **Email:** admin.informasi@masjid.com
- **Password:** `password`
- **Role:** admin_informasi
- **Akses:** FULL CRUD Modul Informasi
- **Dapat:** Promosikan jamaah menjadi pengurus_informasi

### Admin Laporan
- **Username:** `admin_laporan`
- **Email:** admin.laporan@masjid.com
- **Password:** `password`
- **Role:** admin_laporan
- **Akses:** FULL CRUD Modul Laporan
- **Dapat:** Promosikan jamaah menjadi pengurus_laporan

---

## 3. MODULE OFFICERS (Pengurus - 3 Sample)

### Pengurus Keuangan
- **Username:** `pengurus_keuangan`
- **Email:** pengurus.keuangan@masjid.com
- **Password:** `password`
- **Role:** pengurus_keuangan
- **Akses:** FULL CRUD Modul Keuangan
- **Cara Dapat:** Dipromosikan dari jamaah oleh admin_keuangan

### Pengurus Kegiatan
- **Username:** `pengurus_kegiatan`
- **Email:** pengurus.kegiatan@masjid.com
- **Password:** `password`
- **Role:** pengurus_kegiatan
- **Akses:** FULL CRUD Modul Kegiatan
- **Cara Dapat:** Dipromosikan dari jamaah oleh admin_kegiatan

### Pengurus ZIS
- **Username:** `pengurus_zis`
- **Email:** pengurus.zis@masjid.com
- **Password:** `password`
- **Role:** pengurus_zis
- **Akses:** FULL CRUD Modul ZIS
- **Cara Dapat:** Dipromosikan dari jamaah oleh admin_zis

---

## 4. JAMAAH (5 Sample Users)

### Jamaah User 1
- **Username:** `jamaah1`
- **Email:** jamaah1@example.com
- **Password:** `password`
- **Role:** jamaah
- **Akses:** Minimal (lihat data pribadi)

### Jamaah User 2
- **Username:** `jamaah2`
- **Email:** jamaah2@example.com
- **Password:** `password`
- **Role:** jamaah
- **Akses:** Minimal (lihat data pribadi)

### Jamaah User 3
- **Username:** `jamaah3`
- **Email:** jamaah3@example.com
- **Password:** `password`
- **Role:** jamaah
- **Akses:** Minimal (lihat data pribadi)

### Jamaah User 4
- **Username:** `jamaah4`
- **Email:** jamaah4@example.com
- **Password:** `password`
- **Role:** jamaah
- **Akses:** Minimal (lihat data pribadi)

### Jamaah User 5
- **Username:** `jamaah5`
- **Email:** jamaah5@example.com
- **Password:** `password`
- **Role:** jamaah
- **Akses:** Minimal (lihat data pribadi)

---

## 📊 Summary

| Role Type | Jumlah | Login Format |
|-----------|--------|--------------|
| Super Admin | 1 | superadmin |
| Module Admin | 9 | admin_{module} |
| Module Officer | 3 | pengurus_{module} |
| Jamaah | 5 | jamaah{1-5} |
| **TOTAL** | **18** | |

---

## 🎯 Testing Scenarios

### Scenario 1: Super Admin Flow
```
Login: superadmin / password
→ Dashboard: Lihat semua 9 modul
→ Klik modul Keuangan: Bisa lihat, tidak bisa edit
→ Sidebar: Menu "Log Aktivitas" dan "Manajemen User"
→ Activity Logs: Lihat semua aktivitas user
```

### Scenario 2: Module Admin Flow
```
Login: admin_keuangan / password
→ Dashboard: Hanya lihat modul Keuangan
→ Klik modul Keuangan: Bisa lihat dan edit
→ Sidebar: Menu "Kelola Pengurus"
→ Kelola Pengurus: Promosikan jamaah1 jadi pengurus_keuangan
→ Coba akses modul lain: Error 403
```

### Scenario 3: Module Officer Flow
```
Login: pengurus_keuangan / password
→ Dashboard: Hanya lihat modul Keuangan
→ Klik modul Keuangan: Bisa lihat dan edit (sama seperti admin)
→ Tidak ada menu "Kelola Pengurus" (hanya admin yang bisa)
→ Coba akses modul lain: Error 403
```

### Scenario 4: Jamaah Flow
```
Login: jamaah1 / password
→ Dashboard: Kosong atau minimal
→ Tidak ada akses ke modul apapun
→ Hanya bisa lihat data pribadi
→ Coba akses modul: Error 403
```

### Scenario 5: Promotion Flow
```
1. Login as admin_keuangan
2. Klik "Kelola Pengurus"
3. Lihat daftar jamaah yang bisa dipromosikan
4. Pilih jamaah2
5. Klik "Promosikan"
6. Logout
7. Login as jamaah2
8. Sekarang jamaah2 bisa akses modul Keuangan!
```

---

## 🔄 Role Matrix

| Username | Roles | jamaah | keuangan | kegiatan | zis | kurban | inventaris | takmir | informasi | laporan |
|----------|-------|--------|----------|----------|-----|--------|------------|--------|-----------|---------|
| superadmin | super_admin | View | View | View | View | View | View | View | View | View |
| admin_jamaah | admin_jamaah | CRUD | - | - | - | - | - | - | - | - |
| admin_keuangan | admin_keuangan | - | CRUD | - | - | - | - | - | - | - |
| admin_kegiatan | admin_kegiatan | - | - | CRUD | - | - | - | - | - | - |
| admin_zis | admin_zis | - | - | - | CRUD | - | - | - | - | - |
| admin_kurban | admin_kurban | - | - | - | - | CRUD | - | - | - | - |
| admin_inventaris | admin_inventaris | - | - | - | - | - | CRUD | - | - | - |
| admin_takmir | admin_takmir | - | - | - | - | - | - | CRUD | - | - |
| admin_informasi | admin_informasi | - | - | - | - | - | - | - | CRUD | - |
| admin_laporan | admin_laporan | - | - | - | - | - | - | - | - | CRUD |
| pengurus_keuangan | pengurus_keuangan | - | CRUD | - | - | - | - | - | - | - |
| pengurus_kegiatan | pengurus_kegiatan | - | - | CRUD | - | - | - | - | - | - |
| pengurus_zis | pengurus_zis | - | - | - | CRUD | - | - | - | - | - |
| jamaah1-5 | jamaah | View (own) | - | - | - | - | - | - | - | - |

**Legend:**
- **CRUD** = Create, Read, Update, Delete
- **View** = Read Only
- **-** = No Access

---

## 🔐 Permission Structure

Setiap modul memiliki 4 permissions:

```
{module}.view
{module}.create
{module}.update
{module}.delete
```

### Example Permissions:

```
jamaah.view       → View jamaah data
jamaah.create     → Create new jamaah
jamaah.update     → Update jamaah data
jamaah.delete     → Delete jamaah

keuangan.view     → View transactions
keuangan.create   → Create transactions
keuangan.update   → Update transactions
keuangan.delete   → Delete transactions
```

**Total Permissions:** 9 modules × 4 actions = **36 permissions**

---

## 🎓 Role Hierarchy

```
┌─────────────────────────┐
│     Super Admin         │ ← View All, Edit None
│   (Monitoring Only)     │
└─────────────────────────┘
            │
            ├───────────────────────────────────────────┐
            ↓                                           ↓
┌─────────────────────────┐               ┌─────────────────────────┐
│    Module Admins        │               │   Module Officers       │
│  (Full CRUD on Module)  │ ──promotes──→ │ (Full CRUD on Module)   │
│  (Can Promote Users)    │               │ (Cannot Promote)        │
└─────────────────────────┘               └─────────────────────────┘
            │                                           ↑
            └───────────promotes───────────────────────┘
            ↓
┌─────────────────────────┐
│        Jamaah           │
│   (Minimal Access)      │
│  (Can be Promoted)      │
└─────────────────────────┘
```

---

## 📝 Notes

1. **Semua password default adalah:** `password`
2. **Segera ubah password** untuk akun production
3. **User bisa memiliki multiple roles** (contoh: jamaah + pengurus_keuangan)
4. **Promotion tidak menghapus role jamaah**, hanya menambah role baru
5. **Super admin dibuat manual**, tidak bisa dipromosikan
6. **Module admin dibuat manual**, tidak bisa dipromosikan
7. **Hanya pengurus yang bisa dipromosikan/didemote** oleh module admin

---

## 🆕 Membuat User Baru

### Via Registrasi Form
1. Akses `/register`
2. Isi form
3. User baru auto dapat role **jamaah**

### Via Tinker (Manual)
```bash
php artisan tinker

$user = User::create([
    'name' => 'Nama User',
    'email' => 'user@example.com',
    'username' => 'username',
    'password' => Hash::make('password'),
]);

$user->assignRole('jamaah');
# atau
$user->assignRole('admin_keuangan');
```

### Via Seeder (Bulk)
Edit `database/seeders/UsersSeeder.php` dan tambahkan user baru, lalu:
```bash
php artisan db:seed --class=UsersSeeder
```

---

## ⚠️ Security Reminder

**PENTING untuk Production:**

1. ✅ Ubah SEMUA password default
2. ✅ Disable/hapus akun yang tidak diperlukan
3. ✅ Gunakan password yang kuat (min 12 karakter)
4. ✅ Enable 2FA jika tersedia
5. ✅ Limit failed login attempts (sudah diimplementasikan)
6. ✅ Monitor activity logs secara berkala

---

**Daftar user ini dibuat otomatis oleh seeder untuk keperluan testing dan development. Jangan gunakan di production tanpa mengubah password!**
