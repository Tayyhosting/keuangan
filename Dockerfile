FROM php:8.2-apache

# Install ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev zip git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html
COPY . .

# Beri izin folder agar bisa ditulis (wajib untuk Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# FIX: Matikan mpm_event dan aktifkan mpm_prefork agar tidak bentrok
RUN a2dismod mpm_event && a2enmod mpm_prefork

# FIX: Setup port Apache agar otomatis mengikuti variabel PORT dari Railway
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Aktifkan mod_rewrite Apache (wajib agar routing Laravel jalan)
RUN a2enmod rewrite

# Ubah document root ke folder 'public'
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
