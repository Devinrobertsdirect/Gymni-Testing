# PHP and Composer Installation Guide for Windows

## Method 1: Install XAMPP (Recommended)

### Step 1: Download XAMPP
1. Go to: https://www.apachefriends.org/download.html
2. Download the latest version for Windows (includes PHP, MySQL, Apache)
3. Choose the version with PHP 8.x (recommended for Laravel)

### Step 2: Install XAMPP
1. Run the downloaded installer as Administrator
2. Choose installation directory (default: `C:\xampp`)
3. Select components (make sure PHP and MySQL are selected)
4. Complete the installation

### Step 3: Add PHP to PATH
1. Open System Properties (Win + R, type `sysdm.cpl`)
2. Click "Environment Variables"
3. Under "System Variables", find "Path" and click "Edit"
4. Click "New" and add: `C:\xampp\php`
5. Click "OK" on all dialogs
6. **Restart your terminal/PowerShell**

### Step 4: Verify PHP Installation
Open a new terminal and run:
```bash
php --version
```

## Method 2: Install Composer

### Step 1: Download Composer
1. Go to: https://getcomposer.org/download/
2. Download "Composer-Setup.exe"
3. Run the installer
4. Make sure it finds your PHP installation (should auto-detect XAMPP)

### Step 2: Verify Composer Installation
```bash
composer --version
```

## Method 3: Alternative - Manual PHP Installation

If you prefer not to use XAMPP:

### Step 1: Download PHP
1. Go to: https://windows.php.net/download/
2. Download "Thread Safe" version (ZIP file)
3. Extract to `C:\php`

### Step 2: Configure PHP
1. Copy `php.ini-development` to `php.ini`
2. Edit `php.ini` and uncomment these extensions:
   ```ini
   extension=curl
   extension=fileinfo
   extension=mbstring
   extension=openssl
   extension=pdo_mysql
   extension=zip
   ```

### Step 3: Add to PATH
1. Add `C:\php` to your system PATH (same steps as XAMPP above)
2. Restart terminal

## Quick Test Commands

After installation, test with these commands:

```bash
# Test PHP
php --version

# Test Composer
composer --version

# Test Laravel (in your project directory)
php artisan --version
```

## Troubleshooting

### PHP not found after adding to PATH
- Make sure you restarted the terminal completely
- Check the exact path in XAMPP (might be `C:\xampp\php` or similar)
- Try running: `refreshenv` (if you have Chocolatey)

### Composer can't find PHP
- Make sure PHP is in your PATH
- Try running: `composer --version --verbose`

### XAMPP won't start
- Check if other services are using ports 80 or 443
- Run XAMPP Control Panel as Administrator
- Check Windows Firewall settings

## Next Steps After Installation

Once PHP and Composer are installed:

1. **Generate Laravel Key**:
   ```bash
   php artisan key:generate
   ```

2. **Install Laravel Dependencies**:
   ```bash
   composer install
   ```

3. **Run Database Migrations**:
   ```bash
   php artisan migrate
   ```

4. **Start Laravel Server**:
   ```bash
   php artisan serve
   ```

## Alternative: Use Docker (Advanced)

If you're familiar with Docker, you can also use:
```bash
# Install Docker Desktop for Windows
# Then use Laravel Sail or custom Docker setup
```

## Support

If you encounter issues:
1. Check XAMPP Control Panel for service status
2. Look at XAMPP error logs in `C:\xampp\apache\logs`
3. Verify PHP extensions are enabled in `php.ini`
4. Make sure Windows Defender/Firewall isn't blocking the services 