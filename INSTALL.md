# NCCAA Installation Guide

## Quick Start (Docker)

1. **Start the database:**
```bash
cd NCCAA
docker-compose up -d
```

2. **Wait 30 seconds for MySQL to initialize**

3. **Start PHP server:**
```bash
php -S localhost:8000
```

4. **Visit:** http://localhost:8000

## Manual Installation

### Step 1: Database Setup

Create MySQL database and user:
```sql
CREATE DATABASE nccaa_db;
CREATE USER 'nccaa_user'@'localhost' IDENTIFIED BY 'nccaa_pass_2024';
GRANT ALL PRIVILEGES ON nccaa_db.* TO 'nccaa_user'@'localhost';
FLUSH PRIVILEGES;
```

Import schema:
```bash
mysql -u nccaa_user -p nccaa_db < database/init.sql
```

### Step 2: Web Server Setup

**Apache:**
- Copy files to `/var/www/html/nccaa/`
- Enable mod_rewrite
- Set DocumentRoot or create virtual host

**Nginx:**
- Copy files to web directory
- Configure PHP-FPM
- Set up server block

**PHP Built-in Server (Development):**
```bash
php -S localhost:8000
```

### Step 3: Permissions

Set proper permissions:
```bash
chmod 755 public/uploads/
chmod 755 public/uploads/notices/
chmod 755 public/uploads/signatures/
```

### Step 4: Configuration

Edit `includes/config.php` if needed to match your database settings.

## Default Login

- **URL:** `/admin/login.php`
- **Username:** admin
- **Password:** admin123

## Verification

1. Visit the main site - should show captcha
2. Complete captcha - should show home page
3. Try filling a form
4. Login to admin panel
5. Check dashboard statistics

## Troubleshooting

**Database Connection Error:**
- Check MySQL is running
- Verify credentials in config.php
- Ensure database exists

**File Upload Issues:**
- Check directory permissions
- Verify PHP upload settings
- Check disk space

**Signature Pad Not Working:**
- Enable JavaScript
- Check browser console
- Verify SignaturePad library loads

## Production Deployment

1. Use proper web server (Apache/Nginx)
2. Enable HTTPS
3. Set secure database passwords
4. Configure proper file permissions
5. Enable PHP OPcache
6. Set up regular backups
7. Monitor error logs

## Security Checklist

- [ ] Change default admin password
- [ ] Use HTTPS in production
- [ ] Set proper file permissions
- [ ] Configure firewall
- [ ] Regular security updates
- [ ] Monitor access logs
- [ ] Backup database regularly

## Support

For issues or questions, check the README.md file or contact the system administrator.