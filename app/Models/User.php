<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'phone',
        'address',
        'photo',
        'last_login_at',
        'login_attempts',
        'locked_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Activity log configuration
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'username', 'phone', 'address'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ============================================
    // USER AUTHENTICATION & SECURITY
    // ============================================

    /**
     * Check if user is locked
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Lock user account
     */
    public function lockAccount(int $minutes = 30): void
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes),
        ]);
    }

    /**
     * Unlock user account
     */
    public function unlockAccount(): void
    {
        $this->update([
            'login_attempts' => 0,
            'locked_until' => null,
        ]);
    }

    /**
     * Increment login attempts
     */
    public function incrementLoginAttempts(): void
    {
        $this->increment('login_attempts');
        
        // Lock after 5 failed attempts
        if ($this->login_attempts >= 5) {
            $this->lockAccount();
        }
    }

    /**
     * Reset login attempts
     */
    public function resetLoginAttempts(): void
    {
        $this->update(['login_attempts' => 0]);
    }

    /**
     * Update last login
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }

    // ============================================
    // ROLE & PERMISSION METHODS
    // ============================================

    /**
     * Check if user has access to module
     */
    public function canAccessModule(string $module): bool
    {
        // Super admin can access all modules (read-only)
        if ($this->hasRole('super_admin')) {
            return true;
        }

        // Check if user has any role related to the module
        return $this->hasAnyRole([
            "admin_{$module}",
            "pengurus_{$module}",
        ]);
    }

    /**
     * Get accessible modules for user
     */
    public function getAccessibleModules(): array
    {
        if ($this->hasRole('super_admin')) {
            return [
                'jamaah', 'keuangan', 'kegiatan', 'zis', 
                'kurban', 'inventaris', 'takmir', 'informasi', 'laporan'
            ];
        }

        $modules = [];
        $roles = $this->roles->pluck('name')->toArray();

        foreach ($roles as $role) {
            if (preg_match('/^(admin|pengurus)_(.+)$/', $role, $matches)) {
                $modules[] = $matches[2];
            }
        }

        return array_unique($modules);
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Check if user is module admin
     */
    public function isModuleAdmin(string $module): bool
    {
        return $this->hasRole("admin_{$module}");
    }

    /**
     * Check if user is module officer
     */
    public function isModuleOfficer(string $module): bool
    {
        return $this->hasRole("pengurus_{$module}");
    }

    /**
     * Check if user can manage (create, update, delete) keuangan
     * Hanya admin_keuangan dan pengurus_keuangan yang bisa manage
     * Super admin hanya bisa view (read-only)
     */
    public function canManageKeuangan(): bool
    {
        return $this->hasAnyRole(['admin_keuangan', 'pengurus_keuangan']);
    }

    /**
     * Check if user can only view keuangan (read-only)
     * Super admin termasuk di sini
     */
    public function canViewKeuangan(): bool
    {
        return $this->hasRole('super_admin') || $this->canManageKeuangan();
    }

    /**
     * Generic method untuk check manage permission per module
     */
    public function canManageModule(string $module): bool
    {
        // Super admin tidak bisa manage, hanya view
        if ($this->hasRole('super_admin')) {
            return false;
        }

        return $this->hasAnyRole([
            "admin_{$module}",
            "pengurus_{$module}",
        ]);
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Activity logs relationship
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Relationship untuk transaksi pemasukan (zakat, infak, sedekah, dll)
     * Sesuaikan nama model dengan yang Anda gunakan
     */
    public function pemasukan()
    {
        return $this->hasMany(\App\Models\Pemasukan::class);
    }

    /**
     * Relationship untuk transaksi pengeluaran
     * Sesuaikan jika Anda punya model Pengeluaran
     */
    public function pengeluaran()
    {
        return $this->hasMany(\App\Models\Pengeluaran::class);
    }

    /**
     * Relationship untuk zakat
     * Sesuaikan jika Anda punya model Zakat terpisah
     */
    public function zakat()
    {
        return $this->hasMany(\App\Models\Zakat::class);
    }

    /**
     * Relationship untuk infak
     * Sesuaikan jika Anda punya model Infak terpisah
     */
    public function infak()
    {
        return $this->hasMany(\App\Models\Infak::class);
    }

    // ============================================
    // NOTIFICATION METHODS
    // ============================================

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        $this->unreadNotifications->markAsRead();
    }

    /**
     * Get latest notifications (unread first)
     */
    public function getLatestNotifications(int $limit = 10)
    {
        return $this->notifications()
            ->orderBy('read_at', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Check if user has unread notifications
     */
    public function hasUnreadNotifications(): bool
    {
        return $this->unreadNotifications()->exists();
    }

    /**
     * Get notification count by type
     */
    public function getNotificationCountByType(string $type): int
    {
        return $this->notifications()
            ->where('type', $type)
            ->count();
    }

    // ============================================
    // HELPER METHODS
    // ============================================

    /**
     * Get user full name with title (if any)
     */
    public function getFullNameAttribute(): string
    {
        return $this->name;
    }

    /**
     * Get user display name (name or username)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? $this->username;
    }

    /**
     * Get user photo URL or default avatar
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && file_exists(public_path('storage/' . $this->photo))) {
            return asset('storage/' . $this->photo);
        }
        
        // Default avatar dengan initial
        $initial = strtoupper(substr($this->name, 0, 1));
        return "https://ui-avatars.com/api/?name={$initial}&background=random";
    }

    /**
     * Get formatted phone number
     */
    public function getFormattedPhoneAttribute(): ?string
    {
        if (!$this->phone) {
            return null;
        }

        // Format: 0812-3456-7890
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        
        if (strlen($phone) >= 10) {
            return substr($phone, 0, 4) . '-' . 
                   substr($phone, 4, 4) . '-' . 
                   substr($phone, 8);
        }

        return $this->phone;
    }

    /**
     * Check if user is active (not locked)
     */
    public function isActive(): bool
    {
        return !$this->isLocked();
    }

    /**
     * Get user status text
     */
    public function getStatusTextAttribute(): string
    {
        if ($this->isLocked()) {
            return 'Terkunci';
        }

        if (!$this->email_verified_at) {
            return 'Belum Verifikasi Email';
        }

        return 'Aktif';
    }

    /**
     * Get user status badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->isLocked()) {
            return 'badge bg-danger';
        }

        if (!$this->email_verified_at) {
            return 'badge bg-warning';
        }

        return 'badge bg-success';
    }

    /**
     * Get user roles as comma separated string
     */
    public function getRolesTextAttribute(): string
    {
        return $this->roles->pluck('name')->map(function($role) {
            return ucwords(str_replace('_', ' ', $role));
        })->join(', ');
    }

    /**
     * Scope: Filter active users only
     */
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->whereNull('locked_until')
              ->orWhere('locked_until', '<', now());
        });
    }

    /**
     * Scope: Filter locked users only
     */
    public function scopeLocked($query)
    {
        return $query->where('locked_until', '>', now());
    }

    /**
     * Scope: Filter users by role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->whereHas('roles', function($q) use ($role) {
            $q->where('name', $role);
        });
    }

    /**
     * Scope: Filter users by module access
     */
    public function scopeByModule($query, string $module)
    {
        return $query->whereHas('roles', function($q) use ($module) {
            $q->where('name', 'like', "%{$module}%");
        });
    }
}