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

# Install npm dependencies and build assets
RUN npm ci && npm run production

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Expose port 80
EXPOSE 80

# Start nginx and php-fpm
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"] 