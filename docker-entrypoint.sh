#!/bin/bash
set -e

# Export APACHE_PORT so Apache's ${APACHE_PORT} in ports.conf and 000-default.conf picks it up
export APACHE_PORT="${PORT:-10000}"
echo "==> Render Port is set to: ${APACHE_PORT}"

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

# If using PostgreSQL, wait up to 30s for the database to become reachable
if [ -n "$DB_HOST" ] && [ "$DB_CONNECTION" = "pgsql" ]; then
    echo "==> Checking database connection at ${DB_HOST}:${DB_PORT:-5432}..."
    for i in $(seq 1 15); do
        if nc -z -w 2 "$DB_HOST" "${DB_PORT:-5432}" 2>/dev/null; then
            echo "==> Database port is reachable!"
            break
        fi
        echo "==> Database not ready yet, waiting 2s ($i/15)..."
        sleep 2
    done
fi

# Run database migrations
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    echo "==> Running database migrations..."
    php artisan migrate --force || echo "==> Notice: Migration encountered an issue, continuing..."
fi

# Optional initial seed
if [ "${SEED_DATABASE:-false}" = "true" ]; then
    echo "==> Seeding database..."
    php artisan db:seed --force || echo "==> Notice: Database seeding encountered an issue, continuing..."
fi

# Cache configuration, routes, and views
echo "==> Optimizing application configuration..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "==> Starting Apache on port ${APACHE_PORT}..."
exec apache2-foreground
