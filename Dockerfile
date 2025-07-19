FROM php:8.2-fpm

# Installer les extensions nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Installer nginx et outils d'optimisation
RUN apt-get update && apt-get install -y nginx \
    nginx-extras \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copier les fichiers de l'application
COPY . /var/www/html

# Définir le dossier de travail
WORKDIR /var/www/html

# Copier les configurations nginx
COPY ./nginx.conf /etc/nginx/sites-available/default
COPY ./nginx-global.conf /etc/nginx/conf.d/global.conf

# Copier le script de démarrage
COPY ./start.sh /start.sh
RUN chmod +x /start.sh

# Exposer le port 3000 (Nginx écoutera dessus)
EXPOSE 3000

# Lancer le script
CMD ["/start.sh"]
