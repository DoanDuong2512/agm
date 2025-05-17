FROM php:8.3-fpm

# Cài đặt các gói cần thiết
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip supervisor libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd opcache zip \
    && pecl install redis \
    && docker-php-ext-enable redis opcache

# Tạo thư mục supervisor
RUN mkdir -p /etc/supervisor/conf.d /var/log/supervisor

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1

# Thiết lập thư mục làm việc
WORKDIR /var/www/agm
# Copy PHP config
COPY docker/prod/php.ini /usr/local/etc/php/conf.d/custom.ini
# Copy source code vào container
COPY . /var/www/agm


RUN chown -R www-data:www-data /var/www/agm \
    && chmod -R 755 /var/www/agm \
    && chmod -R 777 /var/www/agm/storage /var/www/agm/bootstrap/cache

CMD php-fpm & supervisord -n -c /etc/supervisor/supervisord.conf
