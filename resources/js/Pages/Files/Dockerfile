# Étape 1 : Base PHP avec Composer
FROM php:8.2-fpm

# Installe les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Installe Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définit le répertoire de travail
WORKDIR /app

# Copie les fichiers du projet
COPY . .

# Installe les dépendances backend et front-end, et construit le projet
RUN composer install --optimize-autoloader --no-dev && \
    npm install && \
    npm run build && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Expose le port utilisé par Laravel
EXPOSE 8000

# Commande par défaut pour Railway
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

