# Gunakan PHP 8.2 sebagai base image
FROM php:8.2-fpm

# Install dependencies yang dibutuhkan untuk PHP dan ekstensi lainnya
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    git \
    unzip \
    curl \
    libzip-dev \
    zlib1g-dev \
    libpq-dev \
    && apt-get clean

# ... (kode sebelumnya) ...

# 1. Konfigurasi GD dengan dukungan freetype dan jpeg
RUN docker-php-ext-configure gd --with-freetype --with-jpeg

# 2. Install GD, zip, dan ekstensi PostgreSQL
RUN docker-php-ext-install gd zip pdo pdo_pgsql pgsql

# ... (kode selanjutnya) ...
# Install Node.js v20.x dan npm
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Salin hanya package.json dan package-lock.json untuk mengoptimalkan cache Docker
COPY package*.json ./

# Install dependensi Node.js
RUN rm -rf node_modules package-lock.json && npm install

# Salin semua file proyek setelah dependensi terinstal
COPY . .

# Buat direktori storage dan cache yang mungkin di-ignore oleh .dockerignore
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Install dependensi PHP dan optimasi autoloader (HARUS sebelum npm run build agar vendor tersedia untuk Vite)
RUN composer install --no-dev --optimize-autoloader

# Build Vite assets
RUN npm run build

# Expose port yang digunakan oleh aplikasi Laravel
EXPOSE 8080

# Perintah untuk menjalankan migrasi dan menjalankan server Laravel
CMD ["sh", "-c", "php artisan migrate --force && php -S 0.0.0.0:${PORT:-8080} -t public/"]
