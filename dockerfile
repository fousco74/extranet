# Étape 1: Utiliser une image de base avec PHP 8.2 et Composer
FROM php:8.2-fpm as laravel

# Installer les dépendances système nécessaires pour PHP et Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application Laravel
COPY . .

# Configurer Git pour accepter le répertoire comme sûr
RUN git config --global --add safe.directory /var/www/html

# Installer les dépendances PHP via Composer
RUN composer install --no-dev --optimize-autoloader
RUN composer require inertiajs/inertia-laravel
RUN php artisan inertia:middleware


# Définir les permissions pour les répertoires nécessaires
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Étape 2: Utiliser une image de base avec Node.js pour le frontend
FROM node:20 as node

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application Laravel (y compris les fichiers frontend)
COPY --from=laravel /var/www/html /var/www/html

# Installer les dépendances Node.js via npm
RUN npm install
RUN npm install @inertiajs/vue3
RUN npm install tailwindcss @tailwindcss/vite


# Compiler les assets avec Vite
RUN npm run build

# Étape 1: Utiliser une image de base avec PHP 8.2 et Composer
FROM php:8.2-fpm as laravel

# Installer les dépendances système nécessaires pour PHP et Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application Laravel
COPY . .

# Configurer Git pour accepter le répertoire comme sûr
RUN git config --global --add safe.directory /var/www/html

# Installer les dépendances PHP via Composer
RUN composer install --no-dev --optimize-autoloader
RUN composer require inertiajs/inertia-laravel
RUN php artisan inertia:middleware


# Définir les permissions pour les répertoires nécessaires
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Étape 2: Utiliser une image de base avec Node.js pour le frontend
FROM node:20 as node

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application Laravel (y compris les fichiers frontend)
COPY --from=laravel /var/www/html /var/www/html

# Installer les dépendances Node.js via npm
RUN npm install
RUN npm install @inertiajs/vue3
RUN npm install tailwindcss @tailwindcss/vite


# Compiler les assets avec Vite
RUN npm run build

# Use the official PHP 8.2 image with Apache
FROM php:8.2-apache

# Install necessary dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    openssl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && a2enmod ssl rewrite

# Generate a self-signed SSL certificate for development
RUN mkdir -p /etc/ssl/certs /etc/ssl/private && \
    openssl req -x509 -newkey rsa:4096 -keyout /etc/ssl/private/apache.key -out /etc/ssl/certs/apache.crt -days 365 -nodes -subj "/CN=localhost"

# Add Apache configuration
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
COPY ./docker/apache/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

# Enable Apache sites
RUN a2ensite 000-default.conf && a2ensite default-ssl.conf

# Copy Laravel application files and compiled assets
COPY --from=laravel /var/www/html /var/www/html
COPY --from=node /var/www/html/public/build /var/www/html/public/build

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose HTTP and HTTPS ports
EXPOSE 80
EXPOSE 443

# Start Apache
CMD ["apache2-foreground"]
