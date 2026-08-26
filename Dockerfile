FROM php:8.3-apache

# Install mlocati PHP extension installer helper
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install system utilities & required PHP extensions using pre-compiled binaries / fast installer
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    && install-php-extensions pdo_mysql mysqli gd zip intl opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite & add Port 8080 support
RUN a2enmod rewrite && sed -i 's/Listen 80/Listen 80\nListen 8080/' /etc/apache2/ports.conf

# Copy Apache config pointing to /public
COPY .docker/apache-ci4.conf /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Install Composer binaries
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer manifest first to leverage Docker build layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy application source code
COPY . /var/www/html

# Optimize autoloader after app code copy
RUN composer dump-autoload --optimize --no-dev

# Set permissions for CodeIgniter 4 writable directory
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable

# Entrypoint script for migrations & permissions
COPY .docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80 8080

HEALTHCHECK --interval=10s --timeout=5s --retries=3 \
  CMD curl -f http://localhost/ || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
