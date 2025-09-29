#!/bin/bash

# Initialize MariaDB
echo "Initializing MariaDB..."

# Configure MariaDB to bind to all interfaces
echo "[mysqld]
bind-address = 0.0.0.0
socket = /var/run/mysqld/mysqld.sock
port = 3306
skip-grant-tables" >> /etc/mysql/mariadb.conf.d/50-server.cnf

# Create socket directory
mkdir -p /var/run/mysqld
chown mysql:mysql /var/run/mysqld

# Start MariaDB with skip-grant-tables
service mariadb start

# Wait for MariaDB to be ready
until mysqladmin ping -h "127.0.0.1" --silent; do
    echo "Waiting for MariaDB to be ready..."
    sleep 2
done

# Create database and user without authentication
mysql -h 127.0.0.1 << EOF
FLUSH PRIVILEGES;
CREATE DATABASE IF NOT EXISTS folcklore;
CREATE USER IF NOT EXISTS 'folcklore'@'%' IDENTIFIED BY 'folcklore';
CREATE USER IF NOT EXISTS 'folcklore'@'localhost' IDENTIFIED BY 'folcklore';
CREATE USER IF NOT EXISTS 'folcklore'@'127.0.0.1' IDENTIFIED BY 'folcklore';
GRANT ALL PRIVILEGES ON folcklore.* TO 'folcklore'@'%';
GRANT ALL PRIVILEGES ON folcklore.* TO 'folcklore'@'localhost';
GRANT ALL PRIVILEGES ON folcklore.* TO 'folcklore'@'127.0.0.1';
FLUSH PRIVILEGES;
EOF

# Restart MariaDB without skip-grant-tables
echo "Restarting MariaDB with authentication..."
sed -i '/skip-grant-tables/d' /etc/mysql/mariadb.conf.d/50-server.cnf
service mariadb restart

# Wait for restart
until mysqladmin ping -h "127.0.0.1" --silent; do
    echo "Waiting for MariaDB restart..."
    sleep 2
done

echo "MariaDB initialized successfully"

# Run Laravel setup
echo "Running Laravel setup..."

# Generate app key first
php artisan key:generate --force
echo "App key generated successfully"

# Test database connection before migrations
echo "Testing database connection..."
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connected successfully';"

# Run migrations and seeders
php artisan migrate --force

# Ejecutar seeders específicos para el sistema folklórico
php artisan db:seed --class=TiposGarantiaSeeder --force
php artisan db:seed --class=BasicDataSeeder --force
php artisan db:seed --class=UserSeeder --force
php artisan db:seed --class=FolkloreConjuntosSeeder --force

# Ejecutar seeder principal
php artisan db:seed --force

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

echo "Laravel setup completed"

# Start Apache in foreground
echo "Starting Apache..."
apache2-foreground