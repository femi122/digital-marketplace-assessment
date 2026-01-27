FROM php:8.2-fpm

# 1. Install system dependencies
# We need libpq-dev for Postgres database connection
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev

# Clear cache to keep image small
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions and pdo_pgsql for PostgreSQL
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Fix Git ownership issue inside container
RUN git config --global --add safe.directory /var/www

# Set working directory
WORKDIR /var/www