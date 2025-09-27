# Use official PHP image with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions including MariaDB
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nodejs \
    npm \
    mariadb-server \
    mariadb-client \
    supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy Apache configuration
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Copy application files
COPY . .

# Create directories and set proper permissions
RUN mkdir -p bootstrap/cache storage/logs storage/framework/{cache,sessions,views} \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copy Docker environment file before composer install
RUN cp .env.example .env

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Install Node.js dependencies and build assets
RUN npm install --legacy-peer-deps && npm run build

# Copy startup script and make executable
COPY start-services.sh /usr/local/bin/start-services.sh
RUN chmod +x /usr/local/bin/start-services.sh

# Configure Apache to listen on port 80
RUN echo "Listen 80" >> /etc/apache2/ports.conf

# Expose port 80
EXPOSE 80

# Start services
CMD ["/usr/local/bin/start-services.sh"]