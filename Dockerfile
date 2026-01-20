FROM php:8.3-fpm-alpine

# Security: Create non-root user
RUN addgroup -g 1000 www-data-custom && \
    adduser -u 1000 -G www-data-custom -s /bin/sh -D www-data-custom

# Install Dependencies
RUN apk add --no-cache \
    zip \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    mysql-client

# Configure PHP Extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    zip \
    gd \
    intl \
    exif \
    bcmath

# Hardening: Disable Functions (Production)
RUN echo "disable_functions = exec,system,shell_exec,passthru,proc_open,popen,pcntl_exec,parse_ini_file,show_source" > /usr/local/etc/php/conf.d/hardening.ini

# Set Working Directory
WORKDIR /var/www/html

# Switch to non-root user
USER www-data-custom

# Expose Port
EXPOSE 9000

CMD ["php-fpm"]
