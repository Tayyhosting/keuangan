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

# Beri izin folder
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# FIX: Matikan event mpm dan aktifkan prefork mpm agar tidak bentrok (More than one MPM loaded)
RUN a2dismod mpm_event && a2enmod mpm_prefork

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Ubah document root ke folder 'public'
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
