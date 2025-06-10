# Gymni Fitness App - Installation Verification Script
Write-Host "🔍 Verifying PHP and Composer Installation..." -ForegroundColor Green
Write-Host "================================================" -ForegroundColor Green

# Check PHP
Write-Host "`n📦 Checking PHP installation..." -ForegroundColor Yellow
try {
    $phpVersion = php --version 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ PHP is installed!" -ForegroundColor Green
        Write-Host "   Version: $($phpVersion[0])" -ForegroundColor Cyan
    } else {
        Write-Host "❌ PHP is not installed or not in PATH" -ForegroundColor Red
        Write-Host "   Please install XAMPP or PHP manually" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ PHP is not installed or not in PATH" -ForegroundColor Red
    Write-Host "   Please install XAMPP or PHP manually" -ForegroundColor Red
}

# Check Composer
Write-Host "`n🎼 Checking Composer installation..." -ForegroundColor Yellow
try {
    $composerVersion = composer --version 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Composer is installed!" -ForegroundColor Green
        Write-Host "   Version: $composerVersion" -ForegroundColor Cyan
    } else {
        Write-Host "❌ Composer is not installed or not in PATH" -ForegroundColor Red
        Write-Host "   Please install Composer from https://getcomposer.org/" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Composer is not installed or not in PATH" -ForegroundColor Red
    Write-Host "   Please install Composer from https://getcomposer.org/" -ForegroundColor Red
}

# Check if we're in the Laravel project directory
Write-Host "`n🏗️  Checking Laravel project..." -ForegroundColor Yellow
if (Test-Path "artisan") {
    Write-Host "✅ Laravel project found!" -ForegroundColor Green
    
    # Check Laravel version
    try {
        $laravelVersion = php artisan --version 2>&1
        if ($LASTEXITCODE -eq 0) {
            Write-Host "   $laravelVersion" -ForegroundColor Cyan
        }
    } catch {
        Write-Host "   Could not determine Laravel version" -ForegroundColor Yellow
    }
    
    # Check if .env exists
    if (Test-Path ".env") {
        Write-Host "✅ .env file exists" -ForegroundColor Green
    } else {
        Write-Host "⚠️  .env file not found - you may need to copy .env.example" -ForegroundColor Yellow
    }
    
    # Check if vendor directory exists
    if (Test-Path "vendor") {
        Write-Host "✅ Composer dependencies installed" -ForegroundColor Green
    } else {
        Write-Host "⚠️  Composer dependencies not installed - run 'composer install'" -ForegroundColor Yellow
    }
    
} else {
    Write-Host "❌ Laravel project not found in current directory" -ForegroundColor Red
    Write-Host "   Make sure you're in the Laravel project root directory" -ForegroundColor Red
}

# Check Node.js (for frontend)
Write-Host "`n📱 Checking Node.js (for React Native frontend)..." -ForegroundColor Yellow
try {
    $nodeVersion = node --version 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Node.js is installed!" -ForegroundColor Green
        Write-Host "   Version: $nodeVersion" -ForegroundColor Cyan
    } else {
        Write-Host "❌ Node.js is not installed" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Node.js is not installed" -ForegroundColor Red
}

# Check npm
Write-Host "`n📦 Checking npm..." -ForegroundColor Yellow
try {
    $npmVersion = npm --version 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ npm is installed!" -ForegroundColor Green
        Write-Host "   Version: $npmVersion" -ForegroundColor Cyan
    } else {
        Write-Host "❌ npm is not installed" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ npm is not installed" -ForegroundColor Red
}

# Summary and next steps
Write-Host "`n🎯 Summary and Next Steps:" -ForegroundColor Green
Write-Host "==========================" -ForegroundColor Green

Write-Host "`n📋 If all checks passed, you can now:" -ForegroundColor Cyan
Write-Host "1. Run 'composer install' to install Laravel dependencies" -ForegroundColor White
Write-Host "2. Run 'php artisan key:generate' to generate application key" -ForegroundColor White
Write-Host "3. Configure your .env file with database credentials" -ForegroundColor White
Write-Host "4. Run 'php artisan migrate' to set up the database" -ForegroundColor White
Write-Host "5. Run 'php artisan serve' to start the Laravel development server" -ForegroundColor White
Write-Host "6. Navigate to 'a0-project (2)/a0-project' and run 'npm install'" -ForegroundColor White
Write-Host "7. Run 'expo start' to start the React Native development server" -ForegroundColor White

Write-Host "`n📖 For detailed instructions, see:" -ForegroundColor Cyan
Write-Host "   - PHP_INSTALLATION_GUIDE.md" -ForegroundColor White
Write-Host "   - ENVIRONMENT_SETUP.md" -ForegroundColor White
Write-Host "   - DEPLOYMENT_GUIDE.md" -ForegroundColor White

Write-Host "`n✨ Happy coding!" -ForegroundColor Green 