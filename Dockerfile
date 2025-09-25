# Gunakan base image PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi PHP dasar (ubah/ tambah sesuai kebutuhan aplikasi)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy source code ke dalam container
COPY . /var/www/html/

# Set permission agar bisa diakses Apache
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 (default Apache)
EXPOSE 80

# Apache otomatis jalan saat container start (sudah include di base image)
