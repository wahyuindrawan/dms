#!/bin/sh
set -e

# Fix permissions for storage directories
# This ensures that mounted volumes have correct ownership
echo "Fixing storage permissions..."

# Create directories if they don't exist
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/app
mkdir -p /var/www/html/bootstrap/cache

# Fix ownership and permissions
# Using || true to prevent errors if already owned correctly
chown -R appuser:appgroup /var/www/html/storage || true
chown -R appuser:appgroup /var/www/html/bootstrap/cache || true
chmod -R 775 /var/www/html/storage || true
chmod -R 775 /var/www/html/bootstrap/cache || true

echo "Permissions fixed successfully"

# Execute the main command (supervisord)
exec "$@"
