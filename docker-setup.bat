@echo off
echo 🚀 Starting Bromo Ijen Expedition Setup...

REM Copy environment file
if not exist .env (
    copy .env.docker .env
    echo ✅ .env file created from .env.docker
)

REM Build and start containers
echo 📦 Building Docker containers...
docker-compose up -d --build

REM Wait for MySQL to be ready
echo ⏳ Waiting for MySQL to be ready...
timeout /t 15 /nobreak

REM Install composer dependencies
echo 📥 Installing Composer dependencies...
docker-compose exec -T app composer install --no-dev --optimize-autoloader

REM Generate application key
echo 🔑 Generating application key...
docker-compose exec -T app php artisan key:generate

REM Run migrations
echo 🗃️ Running database migrations...
docker-compose exec -T app php artisan migrate --force

REM Create storage link
echo 🔗 Creating storage link...
docker-compose exec -T app php artisan storage:link

REM Set permissions
echo 🔒 Setting permissions...
docker-compose exec -T app chmod -R 775 storage bootstrap/cache

REM Cache configuration
echo ⚡ Caching configuration for production...
docker-compose exec -T app php artisan config:cache
docker-compose exec -T app php artisan route:cache
docker-compose exec -T app php artisan view:cache

echo.
echo ✅ Setup Complete!
echo 🌐 Access your app at: http://localhost:8080
echo 🔐 Admin panel at: http://localhost:8080/admin
echo.
pause
