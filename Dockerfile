# =============================================================================
# Dockerfile SIM-LiBas RT 15 Pematang Sulur
# Menggunakan base image resmi PHP 8.2-FPM yang mandiri dan terstandarisasi
# =============================================================================
FROM php:8.2-fpm AS app

WORKDIR /var/www/html

# 1. Pasang dependensi sistem dan pustaka C yang dibutuhkan ekstensi PHP
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    dos2unix \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Pasang Composer resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3. Salin seluruh kode proyek SIM-LiBas (termasuk public/build hasil kompilasi Vite)
COPY . .

# 4. Salin konfigurasi PHP kustom
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# 5. Pastikan struktur folder storage dan cache tersedia
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    storage/app/public \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 6. Pasang dependensi PHP Composer produksi
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# 7. Siapkan entrypoint script & normalisasi line-ending LF
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN dos2unix /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
