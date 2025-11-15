# NCCAA Lumbini Province Management System

राष्ट्रिय सेवा दल पूर्व क्याडेट संघ नेपाल (NCCAA) लुम्बिनी प्रदेशको लागि वेब आधारित व्यवस्थापन प्रणाली।

## Features

- **Human Verification (Captcha)** - मानव प्रमाणीकरण
- **Notice Management** - सूचना व्यवस्थापन
- **Cadet Form Management** - क्याडेट आवेदन व्यवस्थापन
- **Digital Signature** - डिजिटल हस्ताक्षर
- **Admin Dashboard** - प्रशासकीय ड्यासबोर्ड
- **Print Functionality** - प्रिन्ट सुविधा
- **Search & Filter** - खोज र फिल्टर

## System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx Web Server
- Docker (optional)

## Installation

### Method 1: Docker Setup (Recommended)

1. Clone the repository:
```bash
git clone <repository-url>
cd NCCAA
```

2. Start the database container:
```bash
docker-compose up -d
```

3. Wait for MySQL to initialize (about 30 seconds)

4. Set up a web server to serve the PHP files or use PHP built-in server:
```bash
php -S localhost:8000
```

### Method 2: Manual Setup

1. Create MySQL database:
```sql
CREATE DATABASE nccaa_db;
CREATE USER 'nccaa_user'@'localhost' IDENTIFIED BY 'nccaa_pass_2024';
GRANT ALL PRIVILEGES ON nccaa_db.* TO 'nccaa_user'@'localhost';
FLUSH PRIVILEGES;
```

2. Import the database schema:
```bash
mysql -u nccaa_user -p nccaa_db < database/init.sql
```

3. Configure web server to point to the project directory

4. Update `includes/config.php` if needed for your database settings

## Default Admin Login

- **Username:** admin
- **Password:** admin123

## File Structure

```
NCCAA/
├── admin/                  # Admin panel files
│   ├── dashboard.php      # Admin dashboard
│   ├── forms.php          # Form management
│   ├── notices.php        # Notice management
│   ├── login.php          # Admin login
│   ├── logout.php         # Admin logout
│   ├── view_form.php      # View form details
│   ├── print_form.php     # Print form
│   └── delete_form.php    # Delete form
├── database/
│   └── init.sql           # Database schema
├── includes/
│   └── config.php         # Configuration file
├── public/
│   ├── css/
│   │   └── style.css      # Main stylesheet
│   ├── js/
│   │   └── main.js        # JavaScript functions
│   ├── images/            # Image assets
│   └── uploads/           # File uploads
│       ├── notices/       # Notice images
│       └── signatures/    # Digital signatures
├── index.php              # Captcha verification page
├── home.php               # Home page
├── form.php               # Cadet application form
└── docker-compose.yml     # Docker configuration
```

## Usage

### For Public Users

1. Visit the website
2. Complete human verification (captcha)
3. View notices on home page
4. Fill out the cadet application form
5. Submit with digital signature

### For Administrators

1. Go to `/admin/login.php`
2. Login with admin credentials
3. Manage notices and view applications
4. Print or delete applications as needed

## Database Tables

- **admin_users** - Administrator accounts
- **notices** - Public notices/announcements
- **cadet_forms** - Cadet application submissions

## Security Features

- Password hashing for admin accounts
- SQL injection prevention with prepared statements
- Input sanitization
- Session management
- File upload validation
- Human verification (captcha)

## Customization

### Adding New Admin Users

```sql
INSERT INTO admin_users (username, password, email) VALUES 
('newadmin', '$2y$10$hashedpassword', 'admin@example.com');
```

### Changing Site Configuration

Edit `includes/config.php` to modify:
- Database connection settings
- Site URL
- Upload paths

## Troubleshooting

### Database Connection Issues
- Check MySQL service is running
- Verify database credentials in `includes/config.php`
- Ensure database and user exist

### File Upload Issues
- Check directory permissions for `public/uploads/`
- Verify PHP upload settings (upload_max_filesize, post_max_size)

### Signature Pad Not Working
- Ensure JavaScript is enabled
- Check browser console for errors
- Verify SignaturePad library is loaded

## Support

For technical support or questions, please contact the system administrator.

## License

This system is developed for NCCAA Lumbini Province internal use.