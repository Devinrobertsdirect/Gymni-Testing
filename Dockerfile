FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    nodejs \
    npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy existing application directory contents
COPY . /app/.

# Create necessary directories and set permissions
RUN mkdir -p /app/bootstrap/cache \
    && mkdir -p /app/storage/framework/cache \
    && mkdir -p /app/storage/framework/sessions \
    && mkdir -p /app/storage/framework/views \
    && mkdir -p /app/storage/logs \
    && chmod -R 775 /app/storage \
    && chmod -R 775 /app/bootstrap/cache

# Install dependencies
RUN composer install --ignore-platform-reqs --no-dev --optimize-autoloader

# Install npm dependencies (use install instead of ci to handle version mismatches)
RUN npm install --production

# Create basic .env file if it doesn't exist
RUN if [ ! -f .env ]; then \
    echo "APP_NAME=Laravel" > .env && \
    echo "APP_ENV=production" >> .env && \
    echo "APP_KEY=base64:$(openssl rand -base64 32)" >> .env && \
    echo "APP_DEBUG=false" >> .env && \
    echo "APP_URL=http://localhost" >> .env && \
    echo "LOG_CHANNEL=stack" >> .env && \
    echo "LOG_DEPRECATIONS_CHANNEL=null" >> .env && \
    echo "LOG_LEVEL=debug" >> .env && \
    echo "DB_CONNECTION=mysql" >> .env && \
    echo "DB_HOST=127.0.0.1" >> .env && \
    echo "DB_PORT=3306" >> .env && \
    echo "DB_DATABASE=laravel" >> .env && \
    echo "DB_USERNAME=root" >> .env && \
    echo "DB_PASSWORD=" >> .env && \
    echo "BROADCAST_DRIVER=log" >> .env && \
    echo "CACHE_DRIVER=file" >> .env && \
    echo "FILESYSTEM_DISK=local" >> .env && \
    echo "QUEUE_CONNECTION=sync" >> .env && \
    echo "SESSION_DRIVER=file" >> .env && \
    echo "SESSION_LIFETIME=120" >> .env; \
    fi

# Generate application key
RUN php artisan key:generate --force

# Clear and cache config
RUN php artisan config:clear && php artisan config:cache

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Create a startup script
RUN echo '#!/bin/bash\n\
# Start PHP-FPM\n\
php-fpm -D\n\
\n\
# Start nginx\n\
nginx -g "daemon off;"' > /app/start.sh && chmod +x /app/start.sh

# Expose port 80
EXPOSE 80

# Start the application
CMD ["/app/start.sh"] 