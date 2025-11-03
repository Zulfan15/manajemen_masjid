# API DOCUMENTATION
# Sistem Manajemen Masjid

## Authentication & Authorization API

### Overview
Sistem autentikasi menggunakan Laravel session-based authentication dengan Role-Based Access Control (RBAC) menggunakan Spatie Laravel Permission.

---

## Authentication Endpoints

### POST /login
Login user

**Request Body:**
```json
{
  "username": "admin_keuangan",
  "password": "password",
  "remember": true
}
```

**Response (Success):**
```json
{
  "success": true,
  "message": "Login berhasil",
  "user": {
    "id": 1,
    "name": "Admin Keuangan",
    "email": "admin.keuangan@masjid.com",
    "roles": ["admin_keuangan"]
  }
}
```

### POST /register
Register new user (auto-assign jamaah role)

**Request Body:**
```json
{
  "name": "Ahmad Jamaah",
  "email": "ahmad@example.com",
  "username": "ahmad123",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "081234567890",
  "address": "Jl. Masjid No. 1"
}
```

### POST /logout
Logout current user

---

## Role Management API

### POST /users/promote/{module}
Promote jamaah to module officer

**Authorization:** Module Admin only

**Request Body:**
```json
{
  "user_id": 5
}
```

**Response:**
```json
{
  "success": true,
  "message": "User berhasil dipromosikan menjadi pengurus keuangan"
}
```

### DELETE /users/demote/{module}/{userId}
Demote officer back to jamaah

**Authorization:** Module Admin only

---

## Activity Log API

### GET /activity-logs
Get all activity logs (Super Admin only)

**Query Parameters:**
- `module` - Filter by module
- `action` - Filter by action
- `user_id` - Filter by user
- `start_date` - Start date (YYYY-MM-DD)
- `end_date` - End date (YYYY-MM-DD)
- `search` - Search in description
- `per_page` - Results per page (default: 20)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "user": {
        "id": 2,
        "name": "Admin Keuangan"
      },
      "action": "create_transaction",
      "module": "keuangan",
      "description": "Created transaction #123",
      "properties": {
        "transaction_id": 123,
        "amount": 1000000
      },
      "ip_address": "127.0.0.1",
      "created_at": "2024-01-01 10:00:00"
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 100
  }
}
```

### GET /{module}/logs
Get module-specific activity logs

**Authorization:** Module access required

---

## Service Layer Methods

### AuthService

#### getCurrentUser(): ?User
Get currently authenticated user with roles

```php
$user = app(AuthService::class)->getCurrentUser();
```

#### hasRole(string $role): bool
Check if current user has specific role

```php
$isAdmin = app(AuthService::class)->hasRole('super_admin');
```

#### hasPermission(string $permission): bool
Check if current user has specific permission

```php
$canCreate = app(AuthService::class)->hasPermission('keuangan.create');
```

#### canAccessModule(string $module): bool
Check if current user can access specific module

```php
$canAccess = app(AuthService::class)->canAccessModule('keuangan');
```

#### getAccessibleModules(): array
Get list of modules current user can access

```php
$modules = app(AuthService::class)->getAccessibleModules();
// Returns: ['keuangan', 'kegiatan']
```

---

### RoleService

#### assignRole(User $user, string $roleName, User $assignedBy): bool
Assign role to user

```php
$success = app(RoleService::class)->assignRole(
    $user,
    'admin_keuangan',
    $currentUser
);
```

#### removeRole(User $user, string $roleName, User $removedBy): bool
Remove role from user

```php
$success = app(RoleService::class)->removeRole(
    $user,
    'pengurus_keuangan',
    $currentUser
);
```

#### promoteToOfficer(User $user, string $module, User $assignedBy): bool
Promote jamaah to module officer

```php
$success = app(RoleService::class)->promoteToOfficer(
    $user,
    'keuangan',
    $currentUser
);
```

#### getUsersByRole(string $roleName): Collection
Get all users with specific role

```php
$admins = app(RoleService::class)->getUsersByRole('admin_keuangan');
```

#### getPromotableUsers(string $module): Collection
Get users who can be promoted in module

```php
$users = app(RoleService::class)->getPromotableUsers('keuangan');
```

---

### ActivityLogService

#### log(string $action, string $module, string $description, array $properties = []): ActivityLog
Log an activity

```php
app(ActivityLogService::class)->log(
    'create_transaction',
    'keuangan',
    'Created new transaction',
    ['transaction_id' => 123, 'amount' => 1000000]
);
```

#### logAuth(string $action, User $user = null, array $properties = []): ActivityLog
Log authentication event

```php
app(ActivityLogService::class)->logAuth('login', $user);
```

#### logCrud(string $operation, string $module, string $resourceType, $resourceId, array $changes = []): ActivityLog
Log CRUD operation

```php
app(ActivityLogService::class)->logCrud(
    'update',
    'keuangan',
    'transaction',
    123,
    ['old_amount' => 1000000, 'new_amount' => 1500000]
);
```

#### getUserActivities(int $userId, array $filters = []): LengthAwarePaginator
Get user activities with filters

```php
$logs = app(ActivityLogService::class)->getUserActivities($userId, [
    'module' => 'keuangan',
    'start_date' => '2024-01-01',
    'end_date' => '2024-01-31'
]);
```

#### getModuleActivities(string $module, array $filters = []): LengthAwarePaginator
Get module activities

```php
$logs = app(ActivityLogService::class)->getModuleActivities('keuangan', [
    'action' => 'create',
    'per_page' => 15
]);
```

---

## Middleware Usage

### Route Protection Examples

```php
// Require authentication
Route::middleware('auth')->group(function() {
    // Routes
});

// Require specific role
Route::middleware(['auth', 'role:super_admin'])->group(function() {
    // Super admin only routes
});

// Require permission
Route::middleware(['auth', 'permission:keuangan.create'])->group(function() {
    // Routes for users with keuangan.create permission
});

// Require module access
Route::middleware(['auth', 'module.access:keuangan'])->group(function() {
    // Routes for users who can access keuangan module
});
```

---

## Permission Naming Convention

Format: `{module}.{action}`

**Available Modules:**
- jamaah
- keuangan
- kegiatan
- zis
- kurban
- inventaris
- takmir
- informasi
- laporan

**Available Actions:**
- view
- create
- update
- delete

**Examples:**
- `jamaah.view` - View jamaah data
- `keuangan.create` - Create financial transaction
- `kegiatan.update` - Update activity
- `zis.delete` - Delete ZIS record

---

## Role Hierarchy

### 1. super_admin
- **Permissions:** *.view (all modules, read-only)
- **Cannot:** Create, update, or delete any data
- **Special Access:** View all activity logs

### 2. admin_{module}
- **Permissions:** {module}.* (full CRUD on assigned module)
- **Special Access:** Can promote jamaah to pengurus
- **Example:** admin_keuangan, admin_jamaah

### 3. pengurus_{module}
- **Permissions:** {module}.* (full CRUD on assigned module)
- **How to Get:** Promoted by module admin
- **Example:** pengurus_keuangan, pengurus_kegiatan

### 4. jamaah
- **Permissions:** jamaah.view (own data only)
- **Default Role:** Auto-assigned on registration

---

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "Anda tidak memiliki akses ke halaman ini."
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "username": ["The username field is required."]
  }
}
```

---

## Integration Guide for Other Modules

### Step 1: Check User Permissions

```php
use App\Services\AuthService;

class KeuanganController extends Controller
{
    protected $authService;
    
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    
    public function create()
    {
        // Check if user can create
        if (!$this->authService->hasPermission('keuangan.create')) {
            abort(403, 'Tidak memiliki izin untuk membuat transaksi');
        }
        
        // Your logic here
    }
}
```

### Step 2: Log Activities

```php
use App\Services\ActivityLogService;

$activityLogService = app(ActivityLogService::class);

// After creating record
$activityLogService->logCrud(
    'create',
    'keuangan',
    'transaction',
    $transaction->id,
    ['amount' => $transaction->amount]
);
```

### Step 3: Protect Routes

```php
// In routes/web.php
Route::middleware(['auth', 'module.access:keuangan'])->prefix('keuangan')->group(function() {
    Route::get('/', [KeuanganController::class, 'index'])->name('keuangan.index');
    
    // Only users with permission can create
    Route::middleware('permission:keuangan.create')->group(function() {
        Route::get('/create', [KeuanganController::class, 'create'])->name('keuangan.create');
        Route::post('/', [KeuanganController::class, 'store'])->name('keuangan.store');
    });
});
```

---

## Best Practices

1. **Always check permissions** at both route and controller level
2. **Log all important actions** using ActivityLogService
3. **Use service layer** for business logic
4. **Follow naming conventions** for permissions
5. **Handle errors gracefully** with appropriate HTTP status codes
6. **Validate all inputs** before processing
7. **Use transactions** for data modifications
8. **Keep middleware thin** - delegate logic to services

---

## Testing

### Example Test

```php
use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_super_admin_can_view_all_modules()
    {
        $superAdmin = User::role('super_admin')->first();
        
        $this->actingAs($superAdmin)
            ->get('/keuangan')
            ->assertStatus(200);
    }
    
    public function test_jamaah_cannot_access_keuangan()
    {
        $jamaah = User::role('jamaah')->first();
        
        $this->actingAs($jamaah)
            ->get('/keuangan')
            ->assertStatus(403);
    }
}
```

---

**For questions or support, contact the development team.**
