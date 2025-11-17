# Seed Storage System - PostgreSQL Installation Guide

## Prerequisites

### 1. Install XAMPP

Download and install from: https://www.apachefriends.org/

### 2. Install PostgreSQL

Download and install PostgreSQL from: https://www.postgresql.org/download/

**For Windows:**

- Download PostgreSQL 15 or 16 installer
- During installation, remember the password you set for 'postgres' user
- Default port: 5432
- Keep all default settings

**Important:** Note down your PostgreSQL password!

### 3. Install PHP PostgreSQL Extension

#### For XAMPP on Windows:

1. Open `C:\xampp\php\php.ini` in a text editor
2. Find and uncomment (remove semicolon) from these lines:
   ```
   extension=pdo_pgsql
   extension=pgsql
   ```
3. Save the file
4. Restart Apache from XAMPP Control Panel

#### Verify Extension:

Create a file `test.php` in `C:\xampp\htdocs\`:

```php
<?php
phpinfo();
?>
```

Open `http://localhost/test.php` and search for "pgsql" - you should see it listed.

## Database Setup

### Method 1: Using pgAdmin (GUI - Recommended)

1. Open pgAdmin (installed with PostgreSQL)
2. Connect to PostgreSQL server

   - Right-click "Servers" > Register > Server
   - Name: localhost
   - Host: localhost
   - Port: 5432
   - Username: postgres
   - Password: (your PostgreSQL password)

3. Create Database:

   - Right-click "Databases" > Create > Database
   - Database name: `seed_storage_system`
   - Click "Save"

4. Import Schema:
   - Right-click on `seed_storage_system` database
   - Select "Query Tool"
   - Open the `seed_storage_db.sql` file
   - Click "Execute" (F5)

### Method 2: Using Command Line (psql)

1. Open Command Prompt (Windows) or Terminal (Linux/Mac)

2. Connect to PostgreSQL:

   ```bash
   psql -U postgres
   ```

   Enter your PostgreSQL password when prompted

3. Run the SQL commands:

   ```sql
   CREATE DATABASE seed_storage_system;
   \c seed_storage_system
   ```

4. Copy and paste the entire contents of `seed_storage_db.sql` and press Enter

5. Verify tables were created:

   ```sql
   \dt
   ```

   You should see: users, farmers, seed_varieties, bins, permits, deliveries, audit_log

6. Exit:
   ```sql
   \q
   ```

## Application Setup

### 1. Copy Files

Copy all PHP files to: `C:\xampp\htdocs\seed_storage\`

### 2. Configure Database Connection

Edit `config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_USER', 'postgres');
define('DB_PASS', 'your_postgres_password_here'); // UPDATE THIS!
define('DB_NAME', 'seed_storage_system');
```

**IMPORTANT:** Replace `your_postgres_password_here` with your actual PostgreSQL password!

### 3. Test Connection

Create `test_connection.php` in `C:\xampp\htdocs\seed_storage\`:

```php
<?php
try {
    $dsn = "pgsql:host=localhost;port=5432;dbname=seed_storage_system";
    $conn = new PDO($dsn, 'postgres', 'your_password');
    echo "Connection successful!";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
```

Access: `http://localhost/seed_storage/test_connection.php`

If you see "Connection successful!", you're good to go!

### 4. Access the System

Open browser and navigate to:

```
http://localhost/seed_storage/login.php
```

**Default Login:**

- Username: `admin`
- Password: `admin123`

## Troubleshooting

### Error: "could not find driver"

**Solution:** PHP PostgreSQL extension not enabled

- Edit `php.ini`
- Uncomment `extension=pdo_pgsql` and `extension=pgsql`
- Restart Apache

### Error: "Connection refused"

**Solution:** PostgreSQL service not running

- **Windows:** Open Services, find PostgreSQL, click Start
- **Linux:** `sudo service postgresql start`

### Error: "password authentication failed"

**Solution:** Wrong password in config.php

- Verify password in pgAdmin
- Update `config.php` with correct password

### Error: "database does not exist"

**Solution:** Database not created

- Create database using pgAdmin or command line
- Import SQL schema

### Error: "relation does not exist"

**Solution:** Tables not created

- Re-run the SQL schema file
- Check pgAdmin Query Tool for errors

## PostgreSQL vs MySQL Differences

### Key Changes Made:

1. **Connection String:**

   - MySQL: `mysql:host=...`
   - PostgreSQL: `pgsql:host=...;port=5432`

2. **Auto-increment:**

   - MySQL: `AUTO_INCREMENT`
   - PostgreSQL: `SERIAL` (creates sequences automatically)

3. **Last Insert ID:**

   - MySQL: `lastInsertId()`
   - PostgreSQL: `lastInsertId('table_id_seq')`

4. **Sequences:**

   - Format: `{table_name}_id_seq`
   - Example: `users_id_seq`, `farmers_id_seq`

5. **Data Types:**
   - ENUM → VARCHAR with CHECK constraints
   - Text fields remain TEXT
   - Decimals remain DECIMAL

## Checking PostgreSQL Service Status

### Windows:

1. Press Win+R
2. Type `services.msc`
3. Find "postgresql-x64-15" (or your version)
4. Ensure Status is "Running"

### Linux:

```bash
sudo systemctl status postgresql
```

## Default PostgreSQL Settings

- **Host:** localhost
- **Port:** 5432
- **Default User:** postgres
- **Database:** seed_storage_system

## Backup and Restore

### Backup Database:

```bash
pg_dump -U postgres seed_storage_system > backup.sql
```

### Restore Database:

```bash
psql -U postgres seed_storage_system < backup.sql
```

### Using pgAdmin:

- Right-click database > Backup
- Select format and location
- Click "Backup"

## Performance Tips

1. **Increase Shared Buffers** (optional):

   - Edit `postgresql.conf`
   - Set `shared_buffers = 256MB`
   - Restart PostgreSQL

2. **Enable Connection Pooling:**

   - Install PgBouncer (advanced)

3. **Regular Maintenance:**
   ```sql
   VACUUM ANALYZE;
   ```

## Security Recommendations

1. Change PostgreSQL postgres user password:

   ```sql
   ALTER USER postgres PASSWORD 'new_strong_password';
   ```

2. Create application-specific user:

   ```sql
   CREATE USER seed_storage WITH PASSWORD 'strong_password';
   GRANT ALL PRIVILEGES ON DATABASE seed_storage_system TO seed_storage;
   ```

3. Update `config.php` with new credentials

## Support

### PostgreSQL Documentation:

https://www.postgresql.org/docs/

### pgAdmin Documentation:

https://www.pgadmin.org/docs/

### Common Commands:

- List databases: `\l`
- List tables: `\dt`
- Describe table: `\d table_name`
- Show current database: `SELECT current_database();`
- Show version: `SELECT version();`

## System is Ready!

Once configured, the system works identically to the MySQL version with all features intact:

- 48 bins management
- Automatic bin allocation
- Farmer tracking
- Permit management
- Delivery recording
- Comprehensive reporting

**Next Step:** Login and change the default admin password!
