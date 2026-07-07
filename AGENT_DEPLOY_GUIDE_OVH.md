# Guide de Déploiement - OVH Hébergement Mutualisé (Git-Only)

Ce guide décrit la procédure pour déployer et mettre à jour le site **APSI BTP** sur l'hébergement mutualisé OVHcloud en utilisant uniquement **Git**.

---

## 1. Informations d'Hébergement (OVH)

* **Serveur SSH** : `ssh.cluster126.hosting.ovh.net`
* **Port SSH** : `22`
* **Identifiant principal** : `apsibtl`
* **Chemin racine du site** : `/home/apsibtl/www`

---

## 2. Déploiement initial de la structure Git sur le serveur

Le serveur a été configuré avec un dépôt Git lié directement à votre dépôt GitHub : `https://github.com/oriancm/APSI-WEB.git`.

Toutes les configurations sensibles (comme `.env`) ainsi que vos fichiers d'images de production non-suivis (ex. `pic/img*.jpg`) restent intacts, car ils sont protégés par le système de fichiers et les règles d'exclusion de Git.

---

## 3. Comment déployer de nouvelles modifications (Déploiement Git)

Chaque fois que vous souhaitez pousser et mettre en ligne de nouvelles modifications, suivez cette procédure simple :

### Étape 1 : Pousser vos modifications locales sur GitHub
Depuis votre terminal local (sur votre machine de développement), poussez vos modifications vers la branche de production sur GitHub :
```bash
git push origin codex/secure-contabo-deploy
```

### Étape 2 : Déployer sur le serveur de production (via SSH)
Connectez-vous en SSH à votre serveur OVH :
```bash
ssh apsibtl@ssh.cluster126.hosting.ovh.net
```
Renseignez votre mot de passe de connexion, puis exécutez la commande de synchronisation Git :
```bash
cd /home/apsibtl/www
git fetch origin
git reset --hard origin/codex/secure-contabo-deploy
```

* **Pourquoi utiliser `git reset --hard` plutôt que `git pull` ?**
  Sur un serveur mutualisé de production, cela garantit que les fichiers locaux du serveur sont parfaitement alignés avec le code vérifié sur votre dépôt GitHub, tout en ignorant et en protégeant les fichiers non-suivis (comme vos images de production et vos variables d'environnement `.env`).

---

## 4. Base de Données (OVH)

La base de données MySQL est gérée directement sur les serveurs de base de données d'OVHcloud.

* **Variables de Connexion** :
  Assurez-vous que le fichier `/home/apsibtl/www/.env` (ignoré par Git) contient vos informations de connexion à la base de données de production OVH.
* **Sauvegarde en ligne** :
  Vous pouvez effectuer une sauvegarde rapide de votre base de données via SSH :
  ```bash
  mysqldump -h <serveur_sql_ovh> -u <utilisateur_bdd> -p <nom_de_base> > backup-db.sql
  ```
