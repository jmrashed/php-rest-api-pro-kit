#!/bin/bash

set -e

cd /var/www/html

# Copy .env.example to .env if .env doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Install Composer dependencies
composer install --no-interaction --optimize-autoloader

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
until php -r "new PDO('mysql:host=db;dbname=test_db', 'root', 'password');" 2>/dev/null; do
    echo "MySQL is unavailable - sleeping"
    sleep 2
done
echo "MySQL is up - executing migrations"

# Run migrations
php migrate.php migrate

# Start Apache
apache2-foreground