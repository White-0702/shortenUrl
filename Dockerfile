# 使用官方 PHP 影像作為基底影像
FROM php:8.1-fpm

# 設置工作目錄
WORKDIR /var/www

# 安裝系統依賴
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    unzip \
    git \
    vim \
    curl \
    libzip-dev \
    libonig-dev \
    libxml2-dev

# 清理緩存
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 安裝 PHP 擴展
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# 安裝 Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 複製現有應用程式代碼
COPY . /var/www

# 設置環境變數以允許 Composer 在 root 下運行
ENV COMPOSER_ALLOW_SUPERUSER=1

# 執行 Composer 安裝
RUN composer install --optimize-autoloader --no-dev

# 授權目錄權限
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# 開放端口
EXPOSE 9000

# 啟動 PHP-FPM
CMD ["php-fpm"]
