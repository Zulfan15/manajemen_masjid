<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use App\Models\KondisiBarang;
use App\Models\TransaksiAset;
use App\Models\JadwalPerawatan;
use App\Models\ActivityLog;


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
                'jamaah',
                'keuangan',
                'kegiatan',
                'zis',
                'kurban',
                'inventaris',
                'takmir',
                'informasi',
                'laporan'
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
     * Check if user has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Super admin has all permissions (read-only)
        if ($this->hasRole('super_admin')) {
            // Super admin can only view, not create/update/delete
            if (str_contains($permission, 'create') || str_contains($permission, 'update') || str_contains($permission, 'delete')) {
                return false;
            }
            return true;
        }

        return $this->hasPermissionTo($permission);
    }

    /**
     * Activity logs relationship
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
    public function kondisiBarang()
    {
        return $this->hasMany(KondisiBarang::class, 'id_petugas', 'id');
    }

    public function transaksiAset()
    {
        return $this->hasMany(TransaksiAset::class, 'id_petugas', 'id');
    }

    public function jadwalPerawatan()
    {
        return $this->hasMany(JadwalPerawatan::class, 'id_petugas', 'id');
    }
}
