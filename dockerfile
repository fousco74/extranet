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
RUN npm install @inertiajs/vue3

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


# Compiler les assets avec Vite
RUN npm run build

# Étape 3: Utiliser une image de base pour servir l'application avec Apache
FROM php:8.2-apache

# Installer les dépendances système nécessaires pour Apache et PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Activer le module Apache rewrite
RUN a2enmod rewrite

# Ajouter la configuration pour le ServerName
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copier le fichier de configuration personnalisé d'Apache
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Copier les fichiers de l'application Laravel et les assets compilés
COPY --from=laravel /var/www/html /var/www/html
COPY --from=node /var/www/html/public/build /var/www/html/public/build

# Définir les permissions pour le stockage Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port 80
EXPOSE 80

# Définir la commande par défaut pour démarrer Apache
CMD ["apache2-foreground"]
