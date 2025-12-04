# 1. Use PHP with Apache
FROM php:8.2-apache

# 2. Install dependencies (PostgreSQL & others)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    zip \
    git \
    && docker-php-ext-install pdo pdo_pgsql bcmath

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set working directory
WORKDIR /var/www/html

# 5. Copy project files
COPY . .

# 6. Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# 7. Set Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Update Apache Config to point to 'public' folder
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# 9. Enable Apache mod_rewrite
RUN a2enmod rewrite

# 10. Expose Port
EXPOSE 80

# 11. Start Apache
CMD ["apache2-foreground"]
