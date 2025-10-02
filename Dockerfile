# Gunakan base image PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi PHP dasar (ubah/ tambah sesuai kebutuhan aplikasi)
RUN docker-php-ext-install mysqli pdo pdo_mysql pdo_sqlite

# Copy source code ke dalam container
COPY . /var/www/html/

# Buat folder data untuk JSON (jika belum ada)
RUN mkdir -p /var/www/data && \
    chown -R www-data:www-data /var/www/data && \
    chmod -R 770 /var/www/data

# Set permission agar bisa diakses Apache
RUN chown -R www-data:www-data /var/www/html

# Definisikan folder data sebagai volume agar tidak ter-replace saat redeploy
VOLUME ["/var/www/data"]

# Expose port 80 (default Apache)
EXPOSE 80

# Apache otomatis jalan saat container start (sudah include di base image)
