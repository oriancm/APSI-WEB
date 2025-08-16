#!/bin/bash

# Script de migration des images vers le volume persistant Coolify
# À exécuter avant de redéployer avec les nouveaux volumes

echo "Migration des images vers le volume persistant..."

# Créer le dossier de destination s'il n'existe pas
mkdir -p /data/coolify/applications/cw4kw40s4g4swokgsw8ccoww/images

# Copier les images existantes vers le volume persistant
if [ -d "/var/www/html/pic" ]; then
    echo "Copie des images existantes..."
    cp -r /var/www/html/pic/* /data/coolify/applications/cw4kw40s4g4swokgsw8ccoww/images/
    echo "Migration terminée !"
    echo "Vous pouvez maintenant redéployer avec les volumes configurés."
else
    echo "Aucune image trouvée dans /var/www/html/pic"
fi 