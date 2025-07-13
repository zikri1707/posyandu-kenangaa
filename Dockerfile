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

# Install ekstensi PHP untuk PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Install Node.js v18.x dan npm
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www

# Salin hanya package.json dan package-lock.json untuk mengoptimalkan cache Docker
COPY package*.json ./

# Install dependensi Node.js
RUN npm install --frozen-lockfile

# Salin semua file proyek setelah dependensi terinstal
COPY . .

# Install dependensi PHP dan optimasi autoloader
RUN composer install --no-dev --optimize-autoloader

# Expose port yang digunakan oleh aplikasi Laravel
EXPOSE 8080

# Perintah untuk menjalankan Laravel dan frontend secara bersamaan di background
CMD ["bash", "-c", "php artisan serve --host=0.0.0.0 --port=8080 & npm run dev & tail -f /dev/null"]
