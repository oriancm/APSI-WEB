FROM php:8.2-cli

# Installer les extensions nécessaires si besoin
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copier les fichiers de l'app
COPY . /var/www/html

# Se placer dans le dossier
WORKDIR /var/www/html

# Exposer le port 3000
EXPOSE 3000

# Lancer le serveur intégré de PHP
CMD ["php", "-S", "0.0.0.0:3000"]
