# 🚀 How to Run Seed Storage System - Complete Guide

## Prerequisites Check

Before starting, ensure you have:

### Required Software

```
✓ PHP 7.4 or higher
✓ PostgreSQL 12 or higher
✓ Apache or Nginx web server (or PHP built-in server)
✓ pgAdmin (optional, for database management)
```

### To Check What You Have (Windows PowerShell)

```powershell
# Check PHP
php --version

# Check PostgreSQL
psql --version

# Check Apache (if installed)
"C:\xampp\apache\bin\apache.exe" -v
```

---

## STEP 1: Set Up PostgreSQL Database

### Option A: Using pgAdmin (Easiest - GUI)

1. **Open pgAdmin**

   - Find in Start Menu or use: `pgAdmin 4`
   - Or navigate to: http://localhost:5050

2. **Connect to Server**

   - Right-click "Servers" → Register → Server
   - Name: `localhost`
   - Host: `localhost`
   - Port: `5432`
   - Username: `postgres`
   - Password: (your PostgreSQL password)
   - Click "Save"

3. **Create Database**

   - Right-click "Databases" → Create → Database
   - Name: `seed_storage_system`
   - Click "Save"

4. **Import Schema**

   - Right-click `seed_storage_system` database
   - Select "Query Tool"
   - Open file: `seed_storage_db.sql` (from your project folder)
   - Select all content (Ctrl+A)
   - Click "Execute" (F5)
   - Wait for completion

5. **Verify**
   - Expand `seed_storage_system` → Schemas → public → Tables
   - You should see 7 tables: users, farmers, bins, seed_varieties, permits, deliveries, audit_log

### Option B: Using Command Line (pgAdmin alternative)

```powershell
# 1. Open Command Prompt/PowerShell

# 2. Connect to PostgreSQL
psql -U postgres

# 3. Enter your PostgreSQL password when prompted

# 4. Run these commands:
CREATE DATABASE seed_storage_system;
\c seed_storage_system
```

Then:

```powershell
# 5. Import the schema (from project directory)
psql -U postgres -d seed_storage_system -f seed_storage_db.sql

# 6. Verify (should show 7 tables)
psql -U postgres -d seed_storage_system -c "\dt"
```

---

## STEP 2: Configure the Application

### Update Database Credentials

1. **Open file:** `config.php` in your project root
2. **Find these lines (around line 1-6):**

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_USER', 'postgres');
define('DB_PASS', 'postgres');        // ← Change this to YOUR password
define('DB_NAME', 'seed_storage_system');
```

3. **Update the password:**

```php
define('DB_PASS', 'YOUR_POSTGRESQL_PASSWORD');  // Change to your actual password
```

4. **Save the file** (Ctrl+S)

---

## STEP 3: Run the Application

### Option A: Using PHP Built-in Server (Easiest for Development)

```powershell
# 1. Open PowerShell/Command Prompt

# 2. Navigate to your project folder
cd "C:\Users\olipas\my-ksc-app"

# 3. Start PHP server
php -S localhost:8000

# 4. Output should show:
# Development Server (http://127.0.0.1:8000)
# Press Ctrl+C to quit.
```

**Access the application:**

- Open browser: http://localhost:8000
- You should see the login page

### Option B: Using XAMPP (If Installed)

1. **Copy project to XAMPP**

   ```powershell
   Copy-Item -Path "C:\Users\olipas\my-ksc-app" -Destination "C:\xampp\htdocs\seed_storage" -Recurse
   ```

2. **Start XAMPP**

   - Open XAMPP Control Panel
   - Click "Start" for Apache
   - Ensure MySQL/PostgreSQL is running

3. **Access the application**
   - Open browser: http://localhost/seed_storage
   - You should see the login page

### Option C: Using Apache (Production Setup)

1. **Copy project to web root**

   ```powershell
   Copy-Item -Path "C:\Users\olipas\my-ksc-app\*" -Destination "C:\apache\htdocs\" -Recurse
   ```

2. **Configure Apache** (httpd.conf):

   ```apache
   <Directory "C:\apache\htdocs">
       AllowOverride All
       Require all granted
   </Directory>
   ```

3. **Restart Apache** and access: http://localhost/

---

## STEP 4: Login to the Application

### Default Credentials

```
Username: admin
Password: admin123
```

**⚠️ IMPORTANT:** Change this password immediately after first login!

### First Login Steps

1. Enter username: `admin`
2. Enter password: `admin123`
3. Click "Login"
4. You should see the **Dashboard**

---

## STEP 5: Change Default Password

### Using the Web Interface

1. **Log in** with admin/admin123
2. **Go to:** Users page (sidebar → Users)
3. **Add new admin user** with strong password
4. **Go to:** PostgreSQL and delete old admin user (optional)

### Using Database Directly

```sql
-- Update admin password directly
-- First generate new bcrypt hash using:
-- https://bcrypt-generator.com/
-- or: php -r "echo password_hash('newpassword', PASSWORD_DEFAULT);"

UPDATE users
SET password = '$2y$10$YOUR_BCRYPT_HASH_HERE'
WHERE username = 'admin';
```

---

## STEP 6: Test the Application

### Test Login

- [x] Login with admin credentials
- [x] You can see the dashboard
- [x] Dashboard shows statistics

### Test Navigation

- [x] Click all sidebar links
- [x] No 404 errors
- [x] Pages load correctly

### Test Features

- [x] Navigate to Farmers → Add Farmer → fill form → Submit
- [x] Navigate to Permits → Add Permit → Select farmer, variety, etc. → Submit
- [x] Navigate to Deliveries → Add Delivery → fill form → Submit
- [x] Check Dashboard → Today's deliveries should update

### Test Search/Filter

- [x] Go to Farmers → Type a name → Click Search
- [x] Results should filter
- [x] Clear search → All farmers return

---

## COMMON ISSUES & FIXES

### ❌ "Connection failed" Error

**Cause:** PostgreSQL not running or wrong credentials

**Fix:**

```powershell
# Check if PostgreSQL is running
# Windows Services: Services.msc → Find "postgresql-x64-XX" → Check status

# Or restart PostgreSQL:
# Windows: Services.msc → postgresql-x64-XX → Restart

# Verify credentials in config.php:
# Make sure password matches your PostgreSQL setup
```

### ❌ "Could not find driver" Error

**Cause:** PHP PostgreSQL extension not enabled

**Fix:**

```powershell
# 1. Find your php.ini
php --ini

# 2. Open php.ini in a text editor

# 3. Find these lines (around line 900-920):
# ;extension=pdo_pgsql
# ;extension=pgsql

# 4. Remove the semicolons:
extension=pdo_pgsql
extension=pgsql

# 5. Save and restart Apache/PHP
```

### ❌ Blank/White Screen

**Cause:** PHP error

**Fix:**

```powershell
# Enable error display in config.php:
ini_set('display_errors', 1);
error_reporting(E_ALL);

# Or check PHP error log:
# Windows XAMPP: C:\xampp\apache\logs\error.log
```

### ❌ "Table does not exist" Error

**Cause:** Database schema not imported

**Fix:**

```powershell
# Re-import the schema:
psql -U postgres -d seed_storage_system -f seed_storage_db.sql

# Or manually using pgAdmin:
# 1. Open seed_storage_system database
# 2. Tools → Query Tool
# 3. Open seed_storage_db.sql
# 4. Execute (F5)
```

### ❌ Login page appears but won't accept credentials

**Cause:** Possibly password not hashed correctly or database connection issue

**Fix:**

```powershell
# Check if admin user exists:
psql -U postgres -d seed_storage_system -c "SELECT * FROM users WHERE username='admin';"

# If empty, re-import schema:
psql -U postgres -d seed_storage_system -f seed_storage_db.sql
```

---

## QUICK START COMMANDS (Copy & Paste)

### For Windows PowerShell

```powershell
# Navigate to project
cd "C:\Users\olipas\my-ksc-app"

# Start PHP server
php -S localhost:8000

# Then in browser, go to:
# http://localhost:8000

# Login with:
# Username: admin
# Password: admin123
```

### For Command Prompt

```batch
cd C:\Users\olipas\my-ksc-app
php -S localhost:8000
```

---

## DIRECTORY STRUCTURE AFTER SETUP

```
C:\Users\olipas\my-ksc-app\
├── config.php                 (← Update with your DB password)
├── login.php                  (← Entry point after redirect)
├── dashboard.php              (← Main page after login)
├── index.php                  (← Redirects to login)
│
├── Management Pages:
│   ├── bins.php
│   ├── farmers.php / farmer_add.php
│   ├── permits.php / permit_add.php
│   ├── deliveries.php / delivery_add.php
│   ├── varieties.php
│   ├── users.php
│   └── reports_daily.php
│
├── includes/
│   ├── header.php
│   ├── sidebar.php
│   └── footer.php
│
└── Database:
    └── seed_storage_db.sql    (← Used to create tables)
```

---

## NEXT STEPS AFTER RUNNING

### 1. Explore the System

- [x] Login and navigate around
- [x] Add sample data (farmers, permits, deliveries)
- [x] Test all features
- [x] Check reports

### 2. Read Documentation

- [ ] Read `IMPROVEMENTS.md` - Priority 1 fixes (3-4 hours)
- [ ] Implement security fixes before production
- [ ] Read `QUICK_REFERENCE.md` for dev guide

### 3. Customize (Optional)

- [ ] Change colors in `includes/header.php`
- [ ] Add your company logo
- [ ] Customize forms
- [ ] Add new fields

### 4. Deploy (When Ready)

- [ ] Follow `POSTGRESQL_INSTALLATION.md`
- [ ] Implement Priority 1 security fixes from `IMPROVEMENTS.md`
- [ ] Set up HTTPS
- [ ] Enable automated backups

---

## KEYBOARD SHORTCUTS

### In Web App

```
Tab         → Navigate form fields
Enter       → Submit form
Ctrl+A      → Select all
Ctrl+C      → Copy
Ctrl+V      → Paste
Escape      → Close modals
```

### In Database (pgAdmin/psql)

```
F5          → Execute query
Ctrl+.      → Cancel query
Ctrl+L      → Clear query editor
```

---

## VERIFY EVERYTHING IS WORKING

### Checklist

```
✓ PostgreSQL is running
  psql -U postgres -c "SELECT version();"

✓ Database exists
  psql -U postgres -c "\l" | grep seed_storage

✓ Tables created
  psql -U postgres -d seed_storage_system -c "\dt"

✓ Sample data loaded
  psql -U postgres -d seed_storage_system -c "SELECT COUNT(*) FROM users;"

✓ PHP extensions loaded
  php -m | grep pgsql

✓ config.php has correct password
  grep "DB_PASS" config.php

✓ Web server running
  Open http://localhost:8000 in browser

✓ Login works
  Use admin/admin123
```

---

## USEFUL PORTS & URLS

| Service     | URL                   | Port |
| ----------- | --------------------- | ---- |
| Application | http://localhost:8000 | 8000 |
| pgAdmin     | http://localhost:5050 | 5050 |
| Apache      | http://localhost      | 80   |
| PostgreSQL  | localhost             | 5432 |
| MySQL       | localhost             | 3306 |

---

## TROUBLESHOOTING COMMANDS

```powershell
# Check PHP version
php --version

# Check PostgreSQL connection
psql -U postgres -c "SELECT NOW();"

# List all databases
psql -U postgres -l

# List tables in seed_storage_system
psql -U postgres -d seed_storage_system -c "\dt"

# Check if admin user exists
psql -U postgres -d seed_storage_system -c "SELECT * FROM users;"

# View PHP error log (XAMPP)
Get-Content "C:\xampp\apache\logs\error.log" -Tail 50

# Test PHP PostgreSQL connection
php -r "echo extension_loaded('pgsql') ? 'OK' : 'NOT LOADED';"
```

---

## GETTING HELP

### If Something Doesn't Work

1. **Check the logs:**

   - PHP errors in browser console
   - Check `QUICK_REFERENCE.md` → Troubleshooting section

2. **Verify prerequisites:**

   - PostgreSQL running
   - PHP version 7.4+
   - PostgreSQL extensions enabled

3. **Re-import database:**

   ```powershell
   psql -U postgres -d seed_storage_system -f seed_storage_db.sql
   ```

4. **Check config.php:**
   - Database password correct
   - Database name correct
   - Username correct

---

## NEXT: READ THIS

After successfully running:

1. ⭐ Read: `START_HERE.md` - Overview
2. 📖 Read: `QUICK_REFERENCE.md` - Developer guide
3. 🔧 Read: `IMPROVEMENTS.md` - Security fixes needed

---

## YOU'RE READY!

**Time to set up:** 30-45 minutes  
**Time to run:** 1-2 minutes  
**Time to be productive:** Immediate

Now go login and explore your system! 🚀

---

**Questions?** Check `QUICK_REFERENCE.md` → Troubleshooting section
