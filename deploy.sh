#!/bin/bash

# Coolify deployment script for Laravel application

echo "🚀 Starting deployment process..."

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    echo "⚠️  Warning: APP_KEY not set. Generate one in Coolify environment variables."
    echo "   Run: php artisan key:generate --show"
fi

# Set proper permissions
echo "📁 Setting permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Clear and cache Laravel configuration
echo "🔧 Configuring Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run database migrations
echo "💾 Running database migrations..."
php artisan migrate --force

# Seed database if needed (uncomment if you have seeders)
# php artisan db:seed --force

# Cache optimizations for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment completed successfully!"