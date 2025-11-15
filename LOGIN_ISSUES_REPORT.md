# NCCAA Admin Login Issues - Report & Fixes

## Issues Identified

### 🔴 **CRITICAL Issue #1: Password Hash Mismatch**
**Location:** Database inconsistency between files

**Problem:**
- `database/init.sql` was set to use hash for password `admin123`
- `init_admin.php` creates hash for password `admin@123`
- When `init_admin.php` runs, it overwrites the database but creates a different password
- Login page shows hint "admin / admin123" but actual password is "admin@123"
- **Result: Login fails because password doesn't match**

**Root Cause:**
```
init.sql hash:     $2y$10$92IXUNpkjO0rOQ5byMi...  (for 'admin123')
init_admin.php:    password_hash('admin@123')     (for 'admin@123')
```

**Fix Applied:**
✅ Updated `database/init.sql` to use hash for `admin@123`
✅ Updated login page hint to show correct password: `admin / admin@123`

---

### 🔴 **Issue #2: Redirect Loop in Login Logic**
**Location:** `admin/login.php`, line 17

**Problem:**
```php
header('Location: dashboard.php');  // ❌ Relative path can cause redirect issues
```

**Why It's Bad:**
- Relative redirect may not work correctly from `/admin/` folder
- Can cause redirect loop or undefined behavior depending on server configuration

**Fix Applied:**
✅ Changed to: `header('Location: /admin/dashboard.php');` (absolute path)

---

### 🟡 **Issue #3: Potential Session Validation Gap**
**Location:** `admin/dashboard.php`, line 2

**Problem:**
- Dashboard calls `requireLogin()` which checks `$_SESSION['admin_id']`
- If session data gets corrupted or lost, user is redirected to `login.php`
- But `requireLogin()` doesn't provide user feedback

**Status:** ✅ Not critical for login, but could improve UX

---

## Database Setup Instructions

### Option A: Using Docker (Recommended)
```bash
docker-compose up -d
```

### Option B: Manual Setup
```bash
# Create database and tables
mysql -u root -p < database/init.sql

# Initialize admin user
php init_admin.php
```

---

## Default Credentials (After Fix)
```
Username: admin
Password: admin@123
```

---

## Testing Checklist

- [ ] Database is created and tables exist
- [ ] Run `init_admin.php` to set up admin user
- [ ] Try login with `admin / admin@123`
- [ ] Verify redirect to dashboard works
- [ ] Check session persists when navigating between admin pages
- [ ] Verify logout works and clears session

---

## Files Modified

1. ✅ `admin/login.php` - Fixed redirect path and password hint
2. ✅ `database/init.sql` - Fixed password hash to match `admin@123`

---

## Additional Notes

- The `debug_login.php` file is helpful for troubleshooting - it shows exactly what's happening in the login process
- Consider adding a "Forgot Password" feature for production use
- Password hashes use `PASSWORD_DEFAULT` which is currently bcrypt
- All passwords should be at least 8 characters in production environments
