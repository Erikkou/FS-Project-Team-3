# Gebruik de officiële PHP 8.2 FPM afbeelding
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    libsodium-dev \
    unzip \
    git \
    nginx \
    && docker-php-ext-install intl pdo pdo_mysql zip sodium \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Pas PHP-instellingen aan voor grotere uploads
RUN echo "upload_max_filesize=15M" > /usr/local/etc/php/conf.d/uploads.ini
RUN echo "post_max_size=15M" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "memory_limit=128M" >> /usr/local/etc/php/conf.d/uploads.ini

# Stel de werkmap in
WORKDIR /var/www

# Kopieer de applicatiecode naar de container
COPY . .

# Kopieer omgevingsvariabelenbestand
COPY .env /var/www/.env

# Install Symfony dependencies
RUN composer install

# Set permissions for var directory
RUN mkdir -p var/cache var/log && chown -R www-data:www-data var

# Copy Nginx configuration file
COPY nginx.conf /etc/nginx/sites-available/default

# Expose port 80
EXPOSE 80

# Start Nginx and PHP-FPM
CMD mkdir -p var/cache var/log && chown -R www-data:www-data var && service nginx start && php-fpm -F