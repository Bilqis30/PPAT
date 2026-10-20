#!/usr/bin/env bash
set -e

echo "=== [PPAT Container Initialization] ==="

# 1. Pastikan direktori database & file SQLite tersedia dan writable
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "Creating empty database.sqlite..."
    touch /var/www/html/database/database.sqlite
fi

# 2. Pastikan direktori storage & framework cache tersedia
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/log/supervisor

# 3. Set kepemilikan dan permission agar SQLite dan Storage berjalan tanpa error
chown -R www-data:www-data /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache /var/log/supervisor
chmod -R 775 /var/www/html/database /var/www/html/storage /var/www/html/bootstrap/cache
chmod 664 /var/www/html/database/database.sqlite

# 4. Generate APP_KEY jika belum ada di .env
if [ -z "$APP_KEY" ]; then
    if grep -q "^APP_KEY=$" /var/www/html/.env 2>/dev/null || ! grep -q "^APP_KEY=" /var/www/html/.env 2>/dev/null; then
        echo "Generating application encryption key..."
        php artisan key:generate --force
    fi
fi

# 5. Jalankan Storage Symlink jika belum terpasang
if [ ! -L /var/www/html/public/storage ]; then
    echo "Creating storage symlink..."
    php artisan storage:link || true
fi

# 6. Jalankan Migrasi Database secara otomatis
echo "Running database migrations..."
php artisan migrate --force --no-interaction

# 7. Cache konfigurasi untuk performa optimal di production
if [ "$APP_ENV" = "production" ]; then
    echo "Caching configuration and routes for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

echo "=== [PPAT Container Ready: Starting Supervisord] ==="

# Jalankan supervisord sebagai proses utama
exec "$@"
