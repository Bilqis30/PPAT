FROM php:8.3-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Set timezone environment variable
ENV TZ=Asia/Jakarta

# Install system dependencies, Nginx, Supervisor, tzdata, and utilities
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    bash \
    tzdata \
    git \
    unzip \
    && cp /usr/share/zoneinfo/Asia/Jakarta /etc/localtime \
    && echo "Asia/Jakarta" > /etc/timezone

# Install official PHP extension installer helper
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install PHP extensions required by Laravel & SQLite
RUN install-php-extensions \
    pdo_sqlite \
    bcmath \
    opcache \
    pcntl \
    zip \
    intl \
    gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy configuration files
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Ensure executable permissions on entrypoint
RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy application source code
COPY . /var/www/html

# Install Composer dependencies (production-ready)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Create necessary directories and set correct permissions
RUN mkdir -p /var/www/html/database \
    && mkdir -p /var/www/html/storage/framework/cache/data \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Expose internal HTTP port
EXPOSE 8085

# Run entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
