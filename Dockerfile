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

# Copier la configuration nginx
COPY ./nginx.conf /etc/nginx/sites-available/default

# Copier le script de démarrage
COPY ./start.sh /start.sh
RUN chmod +x /start.sh

# S'assurer que le dossier pic existe et a les bonnes permissions
RUN mkdir -p /var/www/html/pic \
    && chown -R www-data:www-data /var/www/html/pic \
    && chmod 775 /var/www/html/pic

# Exposer le port 3000 (Nginx écoutera dessus)
EXPOSE 3000

# Lancer le script
CMD ["/start.sh"]
