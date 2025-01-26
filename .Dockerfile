FROM php:8.1-fpm-alpine

# نصب dependencyها
RUN apk update && apk add \
    build-base \
    curl \
    git \
    libzip-dev \
    mysql-client \
    nginx \
    supervisor \
    unzip \
    zip

# نصب PHP Extensionها
RUN docker-php-ext-install pdo_mysql zip

# نصب Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# تنظیمات Nginx و Supervisor
COPY docker/nginx /etc/nginx/conf.d
COPY docker/supervisor /etc/supervisor.d

# کپی فایل‌های پروژه
COPY . /var/www

# نصب dependencyهای پروژه
RUN composer install --optimize-autoloader --no-dev

# تنظیم مجوزها
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# پورت‌ها
EXPOSE 9000

# دستور اجرای Supervisor
CMD ["supervisord", "-n"]