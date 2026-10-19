#!/bin/sh
set -e

# Pastikan folder penyimpanan dan cache tersedia
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/bootstrap/cache

# Pastikan perizinan direktori
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Buat symbolic link storage jika belum ada
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link || true
fi

# Pastikan aset Filament & Livewire tersedia
if [ ! -d "/var/www/html/public/js/filament" ]; then
    php artisan filament:assets || true
fi
if [ ! -d "/var/www/html/public/vendor/livewire" ]; then
    php artisan livewire:publish --assets || true
fi

# Jalankan perintah kontainer utama (CMD)
exec "$@"
