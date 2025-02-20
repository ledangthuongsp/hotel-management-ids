#!/bin/sh

# Copy code vào volume dùng chung
cp -r /app/. /var/www/html
chown -R www-data:www-data /var/www/html

# Chờ MySQL sẵn sàng
until nc -z -v -w30 mysql 3306; do
  echo "Waiting for MySQL..."
  sleep 5
done

# Cài đặt dependencies và chạy migration
cd /var/www/html
composer install --no-interaction --optimize-autoloader
php artisan migrate --force

# Khởi động PHP-FPM
exec php-fpm