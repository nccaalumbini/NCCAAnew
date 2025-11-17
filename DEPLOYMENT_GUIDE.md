# NCCAA Production Deployment Guide

## Pre-Deployment Cleanup

1. **Run the production preparation script:**
   ```bash
   ./prepare_production.sh
   ```

2. **Files that will be removed (unsafe for production):**
   - `fix_admin.php` - Debug admin reset script
   - `init_admin.php` - Development admin setup
   - `test_queries.php` - Database testing script
   - `admin/debug_login.php` - Debug login with password exposure
   - `docker-compose.yml` - Contains hardcoded credentials
   - Development documentation files

## AWS Deployment Steps

### 1. Environment Configuration
Create `.env` file from `.env.example`:
```bash
cp .env.example .env
```

Update with your AWS RDS credentials:
```
DB_HOST=your-rds-endpoint.amazonaws.com
DB_PORT=3306
DB_NAME=nccaa_db
DB_USER=your_secure_username
DB_PASS=your_secure_password
APP_ENV=production
```

### 2. Database Setup
- Create RDS MySQL instance
- Import `database/init.sql` (admin user removed for security)
- Update security groups to allow EC2 access only

### 3. Create Admin User
After deployment:
1. Access `create_admin.php` ONCE
2. Create your admin user with strong password
3. **DELETE `create_admin.php` immediately**

### 4. File Permissions
```bash
chmod 755 public/uploads/
chmod 755 public/uploads/notices/
chmod 755 public/uploads/signatures/
```

### 5. Security Checklist
- [ ] Environment variables configured
- [ ] Debug files removed
- [ ] Admin user created and `create_admin.php` deleted
- [ ] Database accessible only from EC2
- [ ] HTTPS enabled
- [ ] File upload permissions set correctly

## Files Safe for Production

**Core Application:**
- `admin/` (except debug files)
- `data/`
- `includes/config.php` (updated for env vars)
- `public/`
- `form.php`, `home.php`, `index.php`
- `.htaccess`

**Database:**
- `database/init.sql` (cleaned)

**Deployment:**
- `create_admin.php` (delete after use)
- `.env.example`
- This guide

## Post-Deployment
1. Test all functionality
2. Monitor error logs
3. Set up regular backups
4. Configure monitoring/alerts