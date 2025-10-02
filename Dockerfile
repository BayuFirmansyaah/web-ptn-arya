# Gunakan base image PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi PHP yang dibutuhkan
RUN docker-php-ext-install pdo pdo_sqlite

# Copy source code ke dalam container
COPY . /var/www/html/

# Buat folder data untuk JSON (di luar public)
RUN mkdir -p /var/www/data && \
    chown -R www-data:www-data /var/www/data && \
    chmod -R 770 /var/www/data

# Pastikan folder web root bisa diakses oleh Apache
RUN chown -R www-data:www-data /var/www/html

# Definisikan folder data sebagai volume untuk persistensi
VOLUME ["/var/www/data"]

# Expose port 80 (Apache)
EXPOSE 80

# Apache otomatis jalan saat container start (base image sudah include)
