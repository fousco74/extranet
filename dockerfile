FROM wyveo/nginx-php-fpm:latest

# Copie des fichiers du projet dans le répertoire Nginx
COPY . /usr/share/nginx/html

# Copie de la configuration Nginx personnalisée
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Définition du répertoire de travail
WORKDIR /usr/share/nginx/html

# Installation des dépendances PHP (Composer)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installation des dépendances Node.js (pour Inertia.js et Vue.js)
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    && npm install --global npm

# Vérification de l'installation de npm
RUN node -v && npm -v

# Installation des dépendances de l'application Laravel (PHP)
RUN composer install --no-dev --optimize-autoloader && \
    npm install && \
    npm run build && \
    php artisan optimize:clear && \
    php artisan storage:link

RUN npm install    

# Installation de Tailwind CSS et autres dépendances côté client
RUN npm install -D tailwindcss postcss autoprefixer && npx tailwindcss init

# Installation de Inertia.js côté serveur pour Laravel
RUN composer require inertiajs/inertia-laravel

RUN php artisan inertia:middleware


# Installation d'Inertia.js côté client pour Vue 3
RUN npm install @inertiajs/vue3




# Lien symbolique pour le dossier public de Laravel
RUN ln -s public html

# Expose le port 8080 pour Nginx
EXPOSE 8080

# Commande par défaut pour exécuter l'application Laravel et Vue.js
CMD ["sh", "-c", "php artisan migrate --force && npm run dev & php artisan serve --host=0.0.0.0 --port=8080"]
