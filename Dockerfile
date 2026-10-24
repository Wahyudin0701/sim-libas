# =============================================================================
# Dockerfile SIM-LiBas RT 15 Pematang Sulur
# Base image PHP 8.2-FPM Alpine — versi >= 8.2.35 (patch PHP2Shell)
# CVE-2026-109618 & CVE-2026-109619: https://nvd.nist.gov/vuln/detail/CVE-2026-109618
# =============================================================================
FROM php:8.2-fpm-alpine AS app

WORKDIR /var/www/html

# 1. Pasang alat bantu instalasi ekstensi PHP resmi dari Docker Hub
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# 2. Pasang dependensi sistem penting di Alpine
RUN apk add --no-cache \
    bash \
    curl \
    git \
    zip \
    unzip \
    dos2unix

# 3. Pasang seluruh ekstensi PHP yang dibutuhkan Laravel, Filament, DomPDF, & QRCode
RUN install-php-extensions \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    intl \
    zip \
    opcache

# 4. Pasang Composer resmi
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 5. Salin seluruh kode proyek SIM-LiBas (termasuk public/build hasil kompilasi Vite)
COPY . .

# 6. Salin konfigurasi PHP kustom
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# 7. Pastikan struktur folder storage dan cache tersedia
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    storage/app/public \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 8. Pasang dependensi PHP Composer produksi
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# 9. Siapkan entrypoint script & normalisasi line-ending LF
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN dos2unix /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
