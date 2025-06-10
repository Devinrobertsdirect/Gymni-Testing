# Gymni Fitness - Laravel Backend API

## Overview
This is the Laravel backend API for the Gymni Fitness mobile application. It provides authentication, user management, fitness content, social features, and challenge functionality.

## Features
- User authentication and authorization
- Profile management
- Fitness workouts and exercises
- Challenge system
- Social features (posts, comments, likes)
- Calendar and scheduling
- Subscription management
- File uploads
- Push notifications

## Requirements
- PHP 7.3+
- MySQL 5.7+
- Composer
- Laravel 8.x

## Installation

### 1. Clone the repository
```bash
git clone <your-bitbucket-repo-url>
cd gymni-fitness-backend
```

### 2. Install dependencies
```bash
composer install
```

### 3. Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure database
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gymni_fitness
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run migrations
```bash
php artisan migrate
```

### 6. Create storage link
```bash
php artisan storage:link
```

### 7. Start development server
```bash
php artisan serve
```

## API Endpoints

### Authentication
- `POST /api/login` - User login
- `POST /api/signup` - User registration
- `POST /api/forgotpassword` - Password reset
- `POST /api/change_password` - Change password

### User Management
- `POST /api/get_profile` - Get user profile
- `POST /api/update_profile` - Update user profile
- `POST /api/upload_user_profile` - Upload profile image

### Fitness Content
- `POST /api/get_fitness` - Get fitness content
- `POST /api/get_all_fitness` - Get all fitness content
- `POST /api/get_fitness_detail` - Get fitness details

### Social Features
- `POST /api/create_post` - Create social post
- `POST /api/getall_post` - Get all posts
- `POST /api/user_like` - Like a post
- `POST /api/userpost_comment` - Comment on post

### Challenges
- `POST /api/add_challenges` - Create challenge
- `POST /api/get_user_challenges` - Get user challenges

## Deployment

### Production Environment
1. Set up a web server (Apache/Nginx)
2. Configure SSL certificate
3. Set environment variables for production
4. Run `composer install --no-dev --optimize-autoloader`
5. Set proper file permissions

### Environment Variables for Production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
DB_HOST=your-production-db-host
DB_DATABASE=your-production-db
DB_USERNAME=your-production-username
DB_PASSWORD=your-production-password
```

## Security
- All API endpoints use token-based authentication
- CORS is configured for mobile app access
- File uploads are validated and secured
- Rate limiting is implemented

## Support
For issues and questions, contact the development team.

## License
This project is proprietary software for Gymni Fitness. 