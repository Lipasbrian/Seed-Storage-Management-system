# 🎬 Step-by-Step Visual Guide to Running Your App

## Complete Walkthrough with Screenshots Description

---

## 📋 Pre-Check Checklist

Before you start, make sure you have:

```
☐ PHP installed (version 7.4+)
☐ PostgreSQL installed (version 12+)
☐ PostgreSQL is running
☐ You know your PostgreSQL password
☐ The project files downloaded/extracted
☐ A web browser (Chrome, Firefox, Edge, etc.)
```

---

## 🎬 DETAILED WALKTHROUGH

### Phase 1: Database Setup

#### Step 1.1: Verify PostgreSQL is Running

**Windows:**

```
Method 1 (Easy):
→ Press: Windows Key + R
→ Type: services.msc
→ Press: Enter
→ Search for: postgresql-x64-XX
→ Status should say: Running

Method 2 (Command Line):
→ Open PowerShell
→ Type: psql -U postgres
→ If it asks for password → PostgreSQL is running
→ Type: \q (to exit)
```

#### Step 1.2: Create the Database

**Using pgAdmin (Visual/Easy):**

```
1. Open pgAdmin 4
   ↓
2. Click "Servers" in left panel
   ↓
3. Right-click → "Register" → "Server"
   ↓
4. Fill in:
   Name: localhost
   Host: localhost
   Port: 5432
   Username: postgres
   Password: (your PostgreSQL password)
   ↓
5. Click "Save"
   ↓
6. Expand "Servers" → "localhost"
   ↓
7. Right-click "Databases" → "Create" → "Database"
   ↓
8. Name: seed_storage_system
   ↓
9. Click "Save"
   ↓
10. You should see new database in the list
```

#### Step 1.3: Import the Database Schema

**Using pgAdmin:**

```
1. Right-click: seed_storage_system
   ↓
2. Click: "Query Tool"
   ↓
3. Click: File icon (or Ctrl+O)
   ↓
4. Browse to: seed_storage_db.sql
   ↓
5. Select and open
   ↓
6. Click: Execute button (F5) or play icon
   ↓
7. Wait for completion
   ↓
8. Expand seed_storage_system → Schemas → public → Tables
   ↓
9. You should see: users, farmers, bins, seed_varieties, etc.
```

**Or Using Command Line:**

```powershell
# Open PowerShell in your project folder
cd "C:\Users\olipas\my-ksc-app"

# Import the schema
psql -U postgres -d seed_storage_system -f seed_storage_db.sql

# Verify (should show: 7 tables)
psql -U postgres -d seed_storage_system -c "\dt"
```

---

### Phase 2: Application Setup

#### Step 2.1: Update Configuration

```
1. Open: config.php (in your project root)

2. Find line 6: define('DB_PASS', 'postgres');

3. Change to your PostgreSQL password:
   define('DB_PASS', 'YOUR_PASSWORD_HERE');

   Example:
   define('DB_PASS', 'mypassword123');

4. Save file (Ctrl+S)
```

**File Location:** `C:\Users\olipas\my-ksc-app\config.php`

**What to look for:**

```php
// Lines 1-7 should look like:
<?php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_USER', 'postgres');
define('DB_PASS', 'postgres');           ← UPDATE THIS
define('DB_NAME', 'seed_storage_system');
```

---

### Phase 3: Start the Server

#### Step 3.1: Open PowerShell

```
Option 1 (Easy):
→ Press: Windows Key + X
→ Click: Windows PowerShell
→ (Or search for "PowerShell" in Start Menu)

Option 2:
→ Open File Explorer
→ Navigate to: C:\Users\olipas\my-ksc-app
→ Right-click empty space
→ Click: "Open PowerShell here" (or "Open Terminal here")
```

#### Step 3.2: Start PHP Server

```powershell
# Type this command:
php -S localhost:8000

# Press Enter

# You should see:
# Development Server (http://127.0.0.1:8000)
# Listening on http://127.0.0.1:8000
# Document root is C:\...
# Press Ctrl+C to quit.
```

✅ **Server is running!** Don't close this window.

---

### Phase 4: Access the Application

#### Step 4.1: Open Browser

```
1. Open your web browser (Chrome, Firefox, Edge, etc.)

2. In the address bar, type:
   http://localhost:8000

3. Press Enter

4. You should see the Login Page ✓
```

**Expected Screen:**

```
╔════════════════════════════════════╗
║     SEED STORAGE SYSTEM            ║
║     Login Page                     ║
║                                    ║
║  Username: [________________]      ║
║  Password: [________________]      ║
║                                    ║
║         [ Login Button ]           ║
╚════════════════════════════════════╝
```

#### Step 4.2: Login

```
1. Username field: type → admin
2. Password field: type → admin123
3. Click "Login" button
4. Wait for page to load
5. You should see Dashboard ✓
```

**Expected Dashboard:**

```
╔═══════════════════════════════════════╗
║  Dashboard                            ║
║  ┌─────────────────────────────────┐  ║
║  │ Empty Bins: 48/48              │  ║
║  │ Partial Bins: 0/48             │  ║
║  │ Full Bins: 0/48                │  ║
║  │ Total Stock: 0 kg              │  ║
║  └─────────────────────────────────┘  ║
║                                       ║
║  Recent Deliveries: (empty for now)   ║
╚═══════════════════════════════════════╝
```

✅ **SUCCESS!** Application is running!

---

### Phase 5: Verify Everything Works

#### Step 5.1: Test Navigation

Click each sidebar item:

```
✓ Dashboard     → Shows statistics
✓ Add Delivery  → Shows form
✓ Deliveries   → Shows empty list
✓ Bins         → Shows 48 bins
✓ Farmers      → Shows form + list
✓ Permits      → Shows form + list
✓ Varieties    → Shows maize varieties
✓ Users        → Shows user management
✓ Daily Report → Shows report interface
```

#### Step 5.2: Add Sample Data

```
1. Click "Add Delivery" in sidebar

2. Fill in form:
   - Permit: (leave for now, or create first)
   - Farmer: (leave for now, or create first)
   - Variety: Select any
   - Bin: Select any
   - Bags: 10
   - Weight: 500
   - Moisture: 12.5
   - Date: Today

3. Click "Record Delivery"

4. You should see: "Delivery recorded successfully!" ✓
```

---

## 🛠️ TROUBLESHOOTING WHILE RUNNING

### Issue: "Connection failed"

```
Causes:
1. PostgreSQL not running
2. Wrong password in config.php
3. Database doesn't exist

Solutions:
1. Check Services.msc → postgresql running?
2. Verify password matches what you set
3. Re-import database schema
```

### Issue: Page says "Could not find driver"

```
Cause: PostgreSQL extension not enabled in PHP

Solution:
1. Find php.ini:
   php --ini

2. Open php.ini in text editor

3. Search for: "pdo_pgsql" and "pgsql"

4. Remove semicolons at start:
   From: ;extension=pdo_pgsql
   To:   extension=pdo_pgsql

5. Save and restart server (Ctrl+C and restart)
```

### Issue: Login doesn't work

```
Cause: Database not set up correctly

Solution:
1. Re-import schema:
   psql -U postgres -d seed_storage_system -f seed_storage_db.sql

2. Verify admin user exists:
   psql -U postgres -d seed_storage_system -c "SELECT * FROM users;"

3. Should see one row with admin user
```

### Issue: Port 8000 already in use

```
Solution: Use different port

Instead of:
php -S localhost:8000

Use:
php -S localhost:8001
php -S localhost:8002
etc.

Then access: http://localhost:8001
```

---

## ⏱️ TIMELINE

```
Step 1: Check Prerequisites      → 2 minutes
Step 2: Create Database          → 3 minutes
Step 3: Import Schema            → 2 minutes
Step 4: Update config.php        → 1 minute
Step 5: Start Server             → 1 minute
Step 6: Open Browser & Login     → 2 minutes
                                 ─────────────
Total                             ~12 minutes
```

---

## 📍 KEY FILES & LOCATIONS

```
Project Root: C:\Users\olipas\my-ksc-app\

Critical Files:
├── config.php ..................... Database credentials
├── login.php ...................... First page you see
├── dashboard.php .................. Main page after login
├── seed_storage_db.sql ............ Database schema
└── includes/
    ├── header.php ................. Navigation bar
    ├── sidebar.php ................. Menu
    └── footer.php ................. Footer
```

---

## ✅ FINAL CHECKLIST

Before declaring success:

```
☐ PostgreSQL running
☐ Database "seed_storage_system" created
☐ Schema imported (7 tables exist)
☐ config.php updated with password
☐ PHP server started (php -S localhost:8000)
☐ Browser shows login page (http://localhost:8000)
☐ Can login with admin/admin123
☐ Dashboard displays
☐ Can navigate all menu items
☐ No error messages in browser
```

All checkboxes checked? ✅ **You're done!**

---

## 🎯 NEXT STEPS

### Immediate:

1. Explore the system
2. Add sample farmers and permits
3. Try recording deliveries
4. Check reports

### Before Production:

1. Read: `IMPROVEMENTS.md` - Security fixes
2. Implement Priority 1 fixes (3-4 hours)
3. Change admin password
4. Update database credentials
5. Enable HTTPS

### Documentation:

- Read: `QUICK_REFERENCE.md` - Developer guide
- Read: `START_HERE.md` - Full overview
- Read: `README.md` - Navigation

---

## 📞 NEED MORE HELP?

| Question                            | Answer                                      |
| ----------------------------------- | ------------------------------------------- |
| How do I stop the server?           | Press Ctrl+C in PowerShell                  |
| Can I access from another computer? | Yes, use your IP: http://YOUR_IP:8000       |
| How do I change the port?           | `php -S localhost:PORT_NUMBER`              |
| Where are the logs?                 | Browser console (F12) or Apache logs folder |
| Can I use XAMPP instead?            | Yes, copy to C:\xampp\htdocs\               |

---

## 🎉 CONGRATULATIONS!

You now have a running Seed Storage Management System!

**Next:** Read the documentation and implement the security fixes before using in production.

---

**Estimated Setup Time:** 15 minutes  
**Difficulty Level:** Beginner-friendly ⭐⭐

You've got this! 🚀
