#!/bin/bash

echo "🚀 Starting Bromo Ijen Expedition Setup..."

# Copy environment file
if [ ! -f .env ]; then
    cp .env.docker .env
    echo "✅ .env file created from .env.docker"
fi

# Build and start containers
echo "📦 Building Docker containers..."
docker compose up -d --build

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
sleep 15

# Install composer dependencies
echo "📥 Installing Composer dependencies..."
docker compose exec -T app composer install --no-dev --optimize-autoloader

# Generate application key
echo "🔑 Generating application key..."
docker compose exec -T app php artisan key:generate

# Run migrations
echo "🗃️ Running database migrations..."
docker compose exec -T app php artisan migrate --force

# Run seeders (optional - uncomment if needed)
# echo "🌱 Running database seeders..."
# docker compose exec -T app php artisan db:seed --force

# Create storage link
echo "🔗 Creating storage link..."
docker compose exec -T app php artisan storage:link

# Set permissions
echo "🔒 Setting permissions..."
docker compose exec -T app chmod -R 775 storage bootstrap/cache

# Cache configuration
echo "⚡ Caching configuration for production..."
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache
docker compose exec -T app php artisan view:cache

echo ""
echo "✅ Setup Complete!"
echo "🌐 Access your app at: http://localhost:8080"
echo "🔐 Admin panel at: http://localhost:8080/admin"
echo ""
