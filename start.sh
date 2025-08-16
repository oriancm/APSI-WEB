#!/bin/bash

# Script de démarrage optimisé pour Coolify
echo "Démarrage de l'application APSI..."

# S'assurer que le dossier pic existe et a les bonnes permissions
# Important: Ce dossier est monté comme volume dans Coolify
echo "Configuration du dossier pic..."
mkdir -p /var/www/html/pic
chown -R www-data:www-data /var/www/html/pic
chmod -R 775 /var/www/html/pic

# Créer le dossier temporaire pour nginx s'il n'existe pas
echo "Configuration du dossier temporaire nginx..."
mkdir -p /tmp/nginx_uploads
chown -R www-data:www-data /tmp/nginx_uploads
chmod -R 755 /tmp/nginx_uploads

# Vérifier que les permissions sont correctes
echo "Vérification des permissions..."
ls -la /var/www/html/pic
ls -la /tmp/nginx_uploads

# Lancer PHP-FPM
echo "Démarrage de PHP-FPM..."
php-fpm &

# Attendre un peu que PHP-FPM démarre
sleep 2

# Lancer nginx (il écoute sur 3000 dans notre conf)
echo "Démarrage de Nginx..."
nginx -g "daemon off;"
