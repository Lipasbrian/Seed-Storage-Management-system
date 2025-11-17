# ⚡ QUICK START - 5 Steps to Running Your App

## STEP 1: Verify Prerequisites (5 minutes)

### Open PowerShell and check:

```powershell
# Check PHP
php --version
# Should show: PHP 7.4.0 or higher

# Check PostgreSQL
psql --version
# Should show: psql (PostgreSQL) 12 or higher
```

**❌ Don't have them?**

- Download PHP: https://www.php.net/downloads
- Download PostgreSQL: https://www.postgresql.org/download/
- Then come back here

---

## STEP 2: Create & Import Database (5 minutes)

### Open pgAdmin or Command Prompt:

**Option A - Using pgAdmin (Easiest):**

```
1. Open pgAdmin (search in Start Menu)
2. Log in with your PostgreSQL password
3. Right-click Databases → Create → Database
4. Name: seed_storage_system
5. Right-click new database → Query Tool
6. Open file: seed_storage_db.sql (from your project)
7. Execute (F5)
```

**Option B - Using Command Line:**

```powershell
# Create database
psql -U postgres -c "CREATE DATABASE seed_storage_system;"

# Import schema (from project directory)
psql -U postgres -d seed_storage_system -f seed_storage_db.sql
```

✅ **Done?** Continue to Step 3

---

## STEP 3: Update Configuration (2 minutes)

### Edit: `config.php`

```php
# Find these lines (around line 6):
define('DB_PASS', 'postgres');

# Change 'postgres' to YOUR PostgreSQL password:
define('DB_PASS', 'YOUR_PASSWORD_HERE');

# Save file
```

✅ **Done?** Continue to Step 4

---

## STEP 4: Start the Server (1 minute)

### Open PowerShell:

```powershell
# Go to your project folder
cd "C:\Users\olipas\my-ksc-app"

# Start PHP server
php -S localhost:8000

# You should see:
# Development Server (http://127.0.0.1:8000)
# Press Ctrl+C to quit.
```

✅ **Done?** Continue to Step 5

---

## STEP 5: Login & Explore (1 minute)

### Open Browser:

```
1. Go to: http://localhost:8000
2. You see login page ✓
3. Username: admin
4. Password: admin123
5. Click Login
6. You see Dashboard ✓
```

🎉 **SUCCESS!** Your app is running!

---

## 🎯 What to Do Next

### Explore Features:

- [ ] Click Dashboard → See statistics
- [ ] Click Farmers → View list
- [ ] Click "Add Delivery" → Try adding data
- [ ] Click Daily Report → See reports

### When Ready for Production:

1. Read: `IMPROVEMENTS.md` (Priority 1 section)
2. Implement security fixes (3-4 hours)
3. Change default admin password
4. Follow deployment guide

---

## ❌ COMMON ISSUES

| Issue                   | Solution                                          |
| ----------------------- | ------------------------------------------------- |
| "Connection failed"     | Check PostgreSQL is running & password is correct |
| "Could not find driver" | Enable `extension=pdo_pgsql` in php.ini           |
| Blank page              | Check error logs, PHP errors might be hidden      |
| Can't login             | Verify database imported correctly                |
| Port 8000 in use        | Use different port: `php -S localhost:8001`       |

---

## 📚 NEED HELP?

- **Detailed setup:** Read `RUNNING_THE_APP.md`
- **Troubleshooting:** Read `QUICK_REFERENCE.md`
- **All questions:** Read `README.md`

---

**Total Time: ~15 minutes**

🚀 **Let's go!**
