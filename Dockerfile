# Utiliser une image de base avec PHP-FPM
FROM php:8.2-fpm

# Installer Nginx et nettoyer
RUN apt-get update && apt-get install -y nginx \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copier les fichiers de l'application
COPY ./html /var/www/html
RUN chown -R www-data:www-data /var/www/html

# Copier la configuration Nginx avec les règles de réécriture
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# Configurer PHP-FPM pour écouter sur le port 9000
RUN echo "listen = 9000" >> /usr/local/etc/php-fpm.d/www.conf

# Exposer le port 80 (Nginx)
EXPOSE 80

# Script de démarrage pour lancer PHP-FPM et Nginx
COPY ./start.sh /start.sh
RUN chmod +x /start.sh
CMD ["/start.sh"]