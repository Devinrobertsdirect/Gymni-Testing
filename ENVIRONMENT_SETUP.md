# Environment Setup Guide for Gymni Fitness App

## Prerequisites Installation

### 1. Install PHP (Required for Laravel Backend)

#### Option A: Using XAMPP (Recommended for Windows)
1. Download XAMPP from: https://www.apachefriends.org/download.html
2. Install XAMPP (includes PHP, MySQL, Apache)
3. Add PHP to your PATH:
   - Open System Properties → Advanced → Environment Variables
   - Add `C:\xampp\php` to your PATH variable
   - Restart your terminal

#### Option B: Using Chocolatey (if you have it installed)
```powershell
choco install php
```

#### Option C: Manual Installation
1. Download PHP from: https://windows.php.net/download/
2. Extract to `C:\php`
3. Add `C:\php` to your PATH
4. Copy `php.ini-development` to `php.ini`

### 2. Install MySQL (Database)

#### Option A: Using XAMPP (includes MySQL)
- MySQL is included with XAMPP installation

#### Option B: Standalone MySQL
1. Download MySQL from: https://dev.mysql.com/downloads/mysql/
2. Install MySQL Server
3. Set root password during installation

#### Option C: Using Chocolatey
```powershell
choco install mysql
```

### 3. Install Composer (PHP Package Manager)

1. Download Composer from: https://getcomposer.org/download/
2. Run the installer
3. Verify installation: `composer --version`

## Backend Setup (Laravel)

### 1. Install PHP Dependencies
```bash
composer install
```

### 2. Environment Configuration
Update the `.env` file with your database settings:

```env
APP_NAME="Gymni Fitness"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gymni_fitness
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

### 3. Database Setup
```bash
# Create database (run in MySQL)
CREATE DATABASE gymni_fitness;

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Create storage link
php artisan storage:link
```

### 4. Start Laravel Development Server
```bash
php artisan serve
```

## Frontend Setup (React Native)

### 1. Install Dependencies
```bash
cd "a0-project (2)/a0-project"
npm install
```

### 2. Install Expo CLI
```bash
npm install -g @expo/cli
```

### 3. Start Expo Development Server
```bash
expo start
```

## Testing the Setup

### 1. Test Backend
- Open browser to: http://localhost:8000
- Should see Laravel welcome page
- Test API: http://localhost:8000/api/login (should return 405 Method Not Allowed for GET)

### 2. Test Frontend
- Expo should open in browser
- Scan QR code with Expo Go app on your phone
- Or press 'w' for web version

## Production Deployment

### Backend Deployment Options

#### Option A: Shared Hosting
1. Upload Laravel files to public_html
2. Set document root to `public` folder
3. Configure `.env` for production
4. Run migrations on production server

#### Option B: VPS/Dedicated Server
1. Install LAMP stack (Linux, Apache, MySQL, PHP)
2. Configure virtual host
3. Set up SSL certificate
4. Configure environment variables

#### Option C: Cloud Platforms
- **Heroku**: Use Heroku CLI and Procfile
- **DigitalOcean**: Use App Platform or Droplet
- **AWS**: Use EC2 or Elastic Beanstalk

### Frontend Deployment

#### Building for Production
```bash
# Android
expo build:android

# iOS
expo build:ios

# Web
expo build:web
```

#### App Store Submission
1. Create developer accounts (Apple/Google)
2. Configure app signing
3. Submit builds through Expo or EAS Build

## Troubleshooting

### Common Issues

1. **PHP not found**: Add PHP to PATH or restart terminal
2. **Composer not found**: Install Composer globally
3. **Database connection failed**: Check MySQL service and credentials
4. **CORS errors**: Configure CORS in Laravel for your domain
5. **Expo build fails**: Check Expo CLI version and dependencies

### Useful Commands

```bash
# Check PHP version
php -v

# Check Composer version
composer -V

# Check MySQL status (XAMPP)
net start mysql

# Clear Laravel cache
php artisan cache:clear
php artisan config:clear

# Check Expo CLI version
expo --version
```

## Next Steps

1. Install PHP and MySQL using one of the methods above
2. Follow the backend setup steps
3. Test the API endpoints
4. Configure the frontend to connect to your backend
5. Test the complete authentication flow
6. Prepare for production deployment 