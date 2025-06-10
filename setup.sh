#!/bin/bash

echo "🚀 Gymni Fitness App Setup Script"
echo "=================================="

# Backend Setup
echo "📦 Setting up Laravel Backend..."

# Generate application key
php artisan key:generate

# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate

# Create storage link
php artisan storage:link

# Set proper permissions
chmod -R 755 storage bootstrap/cache

echo "✅ Backend setup complete!"

# Frontend Setup
echo "📱 Setting up React Native Frontend..."

cd "a0-project (2)/a0-project"

# Install Node.js dependencies
npm install

# Install Expo CLI globally if not already installed
npm install -g @expo/cli

echo "✅ Frontend setup complete!"

echo ""
echo "🎉 Setup complete! Next steps:"
echo "1. Update .env file with your database credentials"
echo "2. Update config/api.ts with your backend URL"
echo "3. Run 'php artisan serve' to start the backend"
echo "4. Run 'expo start' in the frontend directory to start the app"
echo ""
echo "📖 See DEPLOYMENT_GUIDE.md for detailed deployment instructions" 