# =============================================================================
# Dockerfile SIM-LiBas RT 15 Pematang Sulur
# Menggunakan base image PHP 8.2 yang sudah terpasang modul lengkap
# =============================================================================
FROM rkm-laravel-app:latest AS app

WORKDIR /var/www/html

# Salin seluruh kode proyek SIM-LiBas (termasuk public/build hasil kompilasi Vite)
COPY . .

# Salin konfigurasi PHP kustom
COPY docker/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

# Pastikan struktur folder storage dan cache tersedia sebelum composer install dijalankan
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    storage/app/public \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Pasang dependensi PHP Composer produksi
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Siapkan entrypoint script & normalisasi line-ending LF
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN dos2unix /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
