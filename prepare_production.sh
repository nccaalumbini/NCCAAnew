#!/bin/bash

echo "🚀 Preparing NCCAA for production deployment..."

# Remove debug and development files
echo "🗑️  Removing debug files..."
rm -f fix_admin.php
rm -f init_admin.php  
rm -f test_queries.php
rm -f admin/debug_login.php
rm -f docker-compose.yml
rm -f INSTALL.md
rm -f LOGIN_ISSUES_REPORT.md

# Create environment template
echo "📝 Creating environment template..."
cat > .env.example << 'EOF'
# Database Configuration
DB_HOST=your_db_host
DB_PORT=3306
DB_NAME=nccaa_db
DB_USER=your_db_user
DB_PASS=your_secure_password

# Application Settings
APP_ENV=production
APP_DEBUG=false
EOF

# Update database init.sql to remove default admin
echo "🔐 Updating database schema..."
sed -i "/INSERT INTO admin_users/d" database/init.sql

echo "✅ Production preparation complete!"
echo ""
echo "⚠️  IMPORTANT: Before deployment:"
echo "   1. Create .env file from .env.example"
echo "   2. Set secure database credentials"
echo "   3. Create admin user manually after deployment"
echo "   4. Test all functionality"