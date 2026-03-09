#!/bin/bash
set -e

echo "Starting Laravel Docker container initialization..."

# Fix permissions on storage and bootstrap/cache
echo "Setting permissions on storage directories..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Wait for database to be ready
echo "Waiting for database to be ready..."
until PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USERNAME" -d "$DB_DATABASE" -c '\q' 2>/dev/null; do
  echo "  Database is unavailable - waiting..."
  sleep 2
done
echo "Database is ready!"

# Clear stale bootstrap cache (may reference dev packages not installed in container)
echo "Clearing bootstrap cache..."
rm -f /var/www/html/bootstrap/cache/packages.php /var/www/html/bootstrap/cache/services.php

# Regenerate package cache based on what's actually installed (no dev deps)
echo "Discovering packages..."
php artisan package:discover --ansi

# Run migrations if AUTO_MIGRATE is set to true
if [ "${AUTO_MIGRATE:-false}" = "true" ]; then
  echo "Running database migrations..."
  php artisan migrate --force
fi

echo "Initialization complete. Starting supervisord..."

# Execute the main command (supervisord)
exec "$@"
