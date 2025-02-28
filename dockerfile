# Étape 1: Utiliser une image de base avec PHP 8.2 et Composer
FROM php:8.2-fpm as laravel

# Installer les dépendances système nécessaires pour PHP et Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier tous les fichiers de l'application Laravel
COPY . .

# Installer les dépendances PHP via Composer
RUN php -d memory_limit=-1 /usr/bin/composer install --no-dev --optimize-autoloader --no-interaction

# Configurer Git pour accepter le répertoire comme sûr
RUN git config --global --add safe.directory /var/www/html

# Définir les permissions pour les répertoires nécessaires à Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Étape 2: Utiliser une image de base avec Node.js pour le frontend
FROM node:20 as node

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers package.json et package-lock.json avant d'installer les dépendances
COPY --from=laravel /var/www/html /var/www/html

# Installer les dépendances Node.js via npm
RUN npm install

# Compiler les assets avec Vite
RUN npm run build

# Étape 3: Ajouter Nginx et configurer l'image finale
FROM nginx:alpine as final

# Installer openssl pour générer un certificat SSL auto-signé
RUN apk update && apk add openssl

# Générer un certificat SSL auto-signé pour Nginx
RUN mkdir -p /etc/ssl/certs /etc/ssl/private && \
    openssl req -x509 -newkey rsa:4096 -keyout /etc/ssl/private/nginx.key -out /etc/ssl/certs/nginx.crt -days 365 -nodes -subj "/CN=localhost"

# Copier la configuration Nginx
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copier les fichiers de l'application Laravel depuis l'étape précédente
COPY --from=laravel /var/www/html /var/www/html
COPY --from=node /var/www/html/public/build /var/www/html/public/build

# Définir les permissions pour les répertoires nécessaires
RUN chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer les ports HTTP et HTTPS
EXPOSE 80 443

# Démarrer Nginx et PHP-FPM
CMD ["sh", "-c", "php-fpm & nginx -g 'daemon off;'"]