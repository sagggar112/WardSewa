#!/bin/bash
set -e

# Default port to 10000 if not provided by Render
PORT="${PORT:-10000}"

echo "==> Configuring Apache to listen on port ${PORT}..."
sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

echo "==> Ensuring storage and cache directories exist..."
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache storage/app/public

echo "==> Setting directory permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Create storage symlink if not already created
if [ ! -L public/storage ]; then
    echo "==> Creating storage link..."
    php artisan storage:link || true
fi

# Run database migrations
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "==> Running database migrations..."
    php artisan migrate --force || true
fi

# Optional initial seed (if SEED_DATABASE is set to true)
if [ "${SEED_DATABASE:-false}" = "true" ]; then
    echo "==> Seeding database..."
    php artisan db:seed --force || true
fi

# Cache configuration, routes, and views for optimal performance
echo "==> Caching application configuration..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "==> Starting Apache..."
exec apache2-foreground
