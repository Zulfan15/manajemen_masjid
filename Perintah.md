## 🤖 PROMPT FOR AI AGENT
# CONTEXT
You are tasked to build an authentication and authorization system for a mosque management web application using Laravel and MySQL.

# PROJECT OVERVIEW
A comprehensive mosque management system with 10 modules:
1. Jamaah (Congregation) Management
2. Mosque Finance
3. Activities & Events
4. ZIS (Zakat, Infaq, Sedekah) Management
5. Qurban (Sacrifice) Management
6. Inventory Management
7. Takmir (Board) Management
8. Information & Announcements
9. Reports & Statistics
10. Authentication & User Access (YOUR PRIMARY TASK)

# YOUR SPECIFIC TASK: Module 10 - Authentication & Authorization System
For each feature, just create the navigation. There's no need to create a complete page for each feature because my job is only to create the navigation.

## REQUIREMENTS

### 1. USER HIERARCHY & ROLES
Implement a Role-Based Access Control (RBAC) system with the following roles:

**A. Super Admin (Highest Authority)**
- Role: `super_admin`
- Permissions: READ-ONLY access to ALL modules
- Can view all data, reports, and dashboards
- CANNOT create, update, or delete any data
- Primary function: Monitoring and oversight through activity logs

**B. Module-Specific Admins**
Each module has its own admin with FULL CRUD access ONLY to their designated module:
- `admin_jamaah` - Full access to Jamaah Management module only
- `admin_keuangan` - Full access to Finance module only
- `admin_kegiatan` - Full access to Activities module only
- `admin_zis` - Full access to ZIS module only
- `admin_kurban` - Full access to Qurban module only
- `admin_inventaris` - Full access to Inventory module only
- `admin_takmir` - Full access to Takmir module only
- `admin_informasi` - Full access to Information module only
- `admin_laporan` - Full access to Reports module only

**C. Jamaah (Regular Users)**
- Role: `jamaah`
- Limited access: Can only view their personal information
- Default role for new registrations

### 2. DYNAMIC ROLE ASSIGNMENT
Module admins have the ability to promote jamaah users to become officers (pengurus) in their respective modules:

**Example Flow:**
```
1. User "Ahmad" registers → Gets role: jamaah
2. admin_keuangan promotes Ahmad → Ahmad gets role: pengurus_keuangan
3. Ahmad now has CRUD access to Finance module
4. Ahmad still CANNOT access other modules
```

**Implementation Requirements:**
- Create a "Promote User" feature for each module admin
- Allow assigning module-specific officer roles (pengurus_[module_name])
- One user CAN have multiple module roles simultaneously
- Log every role assignment/removal action

### 3. PERMISSION STRUCTURE
Implement granular permissions for each module:

**Permission Naming Convention:**
```
[module].[action]

Examples:
- jamaah.view
- jamaah.create
- jamaah.update
- jamaah.delete
- keuangan.view
- keuangan.create
- keuangan.update
- keuangan.delete
```

**Permission Matrix:**
| Role | Permissions |
|------|-------------|
| super_admin | *.view (all modules read-only) |
| admin_jamaah | jamaah.* (full CRUD) |
| admin_keuangan | keuangan.* (full CRUD) |
| pengurus_keuangan | keuangan.* (full CRUD) |
| jamaah | jamaah.view (own data only) |

### 4. ACTIVITY LOGGING SYSTEM
Implement comprehensive activity logging for super admin monitoring:

**Log Requirements:**
- Track ALL user actions (login, logout, CRUD operations)
- Store: user_id, action, module, description, ip_address, user_agent, timestamp
- Provide searchable and filterable log dashboard for super_admin
- Keep logs for audit trail purposes

**What to Log:**
- Authentication events (login, logout, failed attempts)
- Data modifications (create, update, delete with before/after values)
- Role assignments/removals
- Permission changes
- Critical system actions

### 5. MIDDLEWARE & GUARDS
Create middleware to protect routes:

**Required Middleware:**
- `auth` - Ensure user is authenticated
- `role:[role_name]` - Check if user has specific role
- `permission:[permission]` - Check if user has specific permission
- `module.access:[module_name]` - Check if user can access specific module

**Example Usage:**
```php
// Only admin_keuangan or pengurus_keuangan can access
Route::middleware(['auth', 'permission:keuangan.create'])->group(function() {
    Route::post('/keuangan/transaksi', [KeuanganController::class, 'store']);
});

// Super admin monitoring dashboard
Route::middleware(['auth', 'role:super_admin'])->group(function() {
    Route::get('/monitoring/logs', [MonitoringController::class, 'logs']);
});
```

### 6. AUTHENTICATION FEATURES
Implement standard authentication features:
- User registration (auto-assign `jamaah` role)
- Login with email/username and password
- Logout
- Password reset via email
- Session management
- Remember me functionality
- Account lock after failed login attempts

### 7. DATABASE SCHEMA
Design the following tables:

**users**
- id, name, email, username, password, phone, address, photo
- email_verified_at, remember_token
- last_login_at, login_attempts, locked_until
- created_at, updated_at

**roles**
- id, name, display_name, description, module (nullable)
- created_at, updated_at

**permissions**
- id, name, display_name, description, module
- created_at, updated_at

**role_user** (pivot)
- user_id, role_id, assigned_by, assigned_at

**permission_role** (pivot)
- permission_id, role_id

**activity_logs**
- id, user_id, action, module, description, properties (JSON)
- ip_address, user_agent, created_at

### 8. API/SERVICE LAYER
Provide reusable services for other modules:

**AuthService:**
- `getCurrentUser()` - Get authenticated user with roles
- `hasRole($role)` - Check if user has specific role
- `hasPermission($permission)` - Check if user has permission
- `hasAnyRole($roles)` - Check if user has any of given roles
- `canAccessModule($module)` - Check module access

**RoleService:**
- `assignRole($user, $role, $assignedBy)` - Assign role to user
- `removeRole($user, $role)` - Remove role from user
- `getUsersByRole($role)` - Get all users with specific role
- `promoteToOfficer($user, $module, $assignedBy)` - Promote jamaah

**ActivityLogService:**
- `log($action, $module, $description, $properties)` - Log activity
- `getUserActivities($userId, $filters)` - Get user's activities
- `getModuleActivities($module, $filters)` - Get module activities
- `getRecentActivities($limit)` - Get recent system activities

## TECHNICAL SPECIFICATIONS

**Framework:** Laravel 10.x or 11.x
**Database:** MySQL 8.0+
**PHP Version:** 8.1+
**Recommended Packages:**
- `spatie/laravel-permission` - For roles and permissions
- `spatie/laravel-activitylog` - For activity logging
- Laravel Breeze/Jetstream - For authentication scaffolding

## DELIVERABLES

1. **Database Migrations**
   - All required tables with proper relationships
   - Seeders for default roles and permissions

2. **Models**
   - User model with role and permission traits
   - Role, Permission, ActivityLog models
   - Proper relationships defined

3. **Middleware**
   - Custom middleware for role and permission checks
   - Module access middleware

4. **Controllers**
   - AuthController (login, register, logout)
   - RoleController (assign/remove roles)
   - ActivityLogController (view logs)
   - UserManagementController (promote users)

5. **Services**
   - AuthService, RoleService, ActivityLogService
   - Helper functions for permission checks

6. **Routes**
   - Authentication routes
   - Protected routes with appropriate middleware
   - API routes for other modules to use

7. **Views (Basic UI)**
   - Login page
   - User management dashboard
   - Role assignment interface
   - Activity log viewer (for super_admin)

8. **Documentation**
   - Setup instructions
   - API documentation for other developers
   - Permission list for all modules
   - Role assignment guide

## CONSTRAINTS & RULES

1. **Security First:**
   - Hash all passwords
   - Protect against SQL injection
   - Implement CSRF protection
   - Validate all inputs
   - Sanitize outputs

2. **Separation of Concerns:**
   - Module admins CANNOT access other modules
   - Super admin CANNOT modify data, only view
   - Enforce permissions at both route and controller level

3. **Scalability:**
   - Design system to easily add new modules
   - Make role and permission system dynamic
   - Cache permission checks for performance

4. **Auditability:**
   - Log everything important
   - Preserve data integrity in logs
   - Make logs searchable and filterable

## SUCCESS CRITERIA

✅ Super admin can view all modules but cannot edit anything
✅ Each module admin has full control only over their module
✅ Module admins can promote jamaah users to officers
✅ All actions are properly logged and viewable by super admin
✅ Authentication is secure and reliable
✅ Other developers can easily integrate with your auth system
✅ System is scalable for future modules

## EXAMPLE USER STORIES

**Story 1: Super Admin Monitoring**
As a super admin,
I want to view activity logs of all users,
So that I can monitor system usage and detect anomalies.

**Story 2: Module Admin Assigning Officer**
As an admin_keuangan,
I want to promote a jamaah user to become pengurus_keuangan,
So that they can help manage financial transactions.

**Story 3: Protected Module Access**
As admin_kegiatan,
I should NOT be able to access the keuangan module,
So that data security and separation of duties is maintained.

## NOTES FOR IMPLEMENTATION
- Start with database schema and migrations
- Implement authentication first (login/register)
- Then add RBAC with Spatie package
- Create seeders for testing (sample users with different roles)
- Build middleware and test protection
- Implement activity logging
- Create basic UI for role management
- Document everything for other developers
Generate the complete Laravel authentication and authorization system based on these specifications.
