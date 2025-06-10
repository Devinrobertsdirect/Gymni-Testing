# Gymni Fitness App - Deployment Guide

## Overview
This guide covers the complete deployment process for the Gymni Fitness app, including both the Laravel backend and React Native frontend.

## Prerequisites
- PHP 7.3+ with required extensions
- MySQL/MariaDB database
- Node.js 14+ and npm
- Expo CLI
- Composer (for PHP dependencies)

## Backend Deployment (Laravel)

### 1. Environment Configuration
Update the `.env` file with your production settings:

```bash
APP_NAME="Gymni Fitness"
APP_ENV=production
APP_KEY=your-generated-app-key
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=gymni_fitness
DB_USERNAME=your-db-username
DB_PASSWORD=your-db-password

# Mail configuration for password reset
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@gymni.com
MAIL_FROM_NAME="Gymni Fitness"

# For file uploads (if using S3)
AWS_ACCESS_KEY_ID=your-aws-key
AWS_SECRET_ACCESS_KEY=your-aws-secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
```

### 2. Database Setup
```bash
# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed the database (if you have seeders)
php artisan db:seed

# Create storage link for file uploads
php artisan storage:link
```

### 3. Authentication Setup
The app uses Laravel's built-in authentication with token-based API access. Ensure your API routes are properly protected with middleware.

### 4. Server Configuration
- Set up a web server (Apache/Nginx) pointing to the `public` directory
- Configure SSL certificate for HTTPS
- Set proper file permissions (755 for directories, 644 for files)
- Configure proper CORS headers for mobile app access

## Frontend Deployment (React Native)

### 1. Environment Configuration
Update `a0-project (2)/a0-project/config/api.ts`:
```typescript
export const BASE_API_URL = 'https://your-production-domain.com/api';
```

### 2. Build Configuration
The app is configured for Expo. For production builds:

```bash
# Install Expo CLI globally
npm install -g @expo/cli

# Navigate to frontend directory
cd "a0-project (2)/a0-project"

# Build for production
expo build:android  # For Android
expo build:ios      # For iOS
```

### 3. App Store Deployment
- Create developer accounts for Apple App Store and Google Play Store
- Configure app signing certificates
- Submit builds through Expo or build locally with EAS Build

## API Integration Points

### Authentication Endpoints
- `POST /api/login` - User login
- `POST /api/signup` - User registration
- `POST /api/forgotpassword` - Password reset
- `POST /api/change_password` - Change password (authenticated)

### Core Features
- `POST /api/get_profile` - Get user profile
- `POST /api/update_profile` - Update user profile
- `POST /api/get_fitness` - Get fitness content
- `POST /api/create_post` - Create social post
- `POST /api/getall_post` - Get all posts
- `POST /api/add_challenges` - Create challenges
- `POST /api/get_user_challenges` - Get user challenges

## Security Considerations

### Backend Security
- Enable HTTPS only
- Configure proper CORS headers
- Implement rate limiting
- Use environment variables for sensitive data
- Regular security updates

### Frontend Security
- Store tokens securely using AsyncStorage
- Implement token refresh logic
- Validate all user inputs
- Use HTTPS for all API calls

## Testing Checklist

### Backend Testing
- [ ] All API endpoints respond correctly
- [ ] Authentication works properly
- [ ] File uploads work
- [ ] Database connections are stable
- [ ] Error handling is comprehensive

### Frontend Testing
- [ ] Login/registration flow works
- [ ] All screens load properly
- [ ] API calls succeed
- [ ] Offline handling works
- [ ] App performance is acceptable

## Monitoring and Maintenance

### Backend Monitoring
- Set up error logging (Laravel Telescope, Sentry)
- Monitor database performance
- Set up automated backups
- Monitor API usage and performance

### Frontend Monitoring
- Implement crash reporting (Expo Crashlytics)
- Monitor app performance
- Track user engagement metrics

## Deployment Checklist

### Backend
- [ ] Environment variables configured
- [ ] Database migrated and seeded
- [ ] SSL certificate installed
- [ ] File permissions set correctly
- [ ] CORS configured for mobile app
- [ ] Error logging configured
- [ ] Backup system in place

### Frontend
- [ ] API URL updated for production
- [ ] App branding configured
- [ ] Build created for both platforms
- [ ] App store listings prepared
- [ ] Privacy policy and terms of service added
- [ ] App icons and splash screens updated

## Troubleshooting

### Common Issues
1. **CORS errors**: Ensure backend CORS is configured for your domain
2. **Authentication failures**: Check token format and expiration
3. **File upload issues**: Verify storage permissions and configuration
4. **Database connection**: Check credentials and network access

### Support
For deployment issues, check:
- Laravel logs: `storage/logs/laravel.log`
- Server error logs
- Expo build logs
- App store review feedback

## Next Steps
1. Set up your production environment
2. Configure domain and SSL
3. Set up database and run migrations
4. Update API URLs in frontend
5. Build and submit to app stores
6. Monitor and maintain the application 