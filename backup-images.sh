#!/bin/bash

# Script de sauvegarde et restauration des images
# Usage: ./backup-images.sh [backup|restore]

BACKUP_DIR="./backups/images"
IMAGES_DIR="./pic"

case "$1" in
    "backup")
        echo "Sauvegarde des images..."
        mkdir -p "$BACKUP_DIR"
        if [ -d "$IMAGES_DIR" ]; then
            tar -czf "$BACKUP_DIR/images_$(date +%Y%m%d_%H%M%S).tar.gz" -C "$(dirname "$IMAGES_DIR")" "$(basename "$IMAGES_DIR")"
            echo "Sauvegarde créée dans $BACKUP_DIR"
        else
            echo "Le dossier $IMAGES_DIR n'existe pas"
        fi
        ;;
    "restore")
        echo "Restauration des images..."
        if [ -d "$BACKUP_DIR" ]; then
            LATEST_BACKUP=$(ls -t "$BACKUP_DIR"/images_*.tar.gz 2>/dev/null | head -1)
            if [ -n "$LATEST_BACKUP" ]; then
                mkdir -p "$IMAGES_DIR"
                tar -xzf "$LATEST_BACKUP" -C "$(dirname "$IMAGES_DIR")"
                echo "Images restaurées depuis $LATEST_BACKUP"
            else
                echo "Aucune sauvegarde trouvée dans $BACKUP_DIR"
            fi
        else
            echo "Le dossier de sauvegarde $BACKUP_DIR n'existe pas"
        fi
        ;;
    *)
        echo "Usage: $0 {backup|restore}"
        echo "  backup  - Sauvegarder les images actuelles"
        echo "  restore - Restaurer les images depuis la dernière sauvegarde"
        exit 1
        ;;
esac 