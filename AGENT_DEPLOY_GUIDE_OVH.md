# Guide de Déploiement - OVH Hébergement Mutualisé (Git-Only)

Ce guide décrit la procédure pour déployer et mettre à jour le site **APSI BTP** sur l'hébergement mutualisé OVHcloud en utilisant uniquement **Git**.

---

## 1. Informations d'Hébergement & Identifiants (OVH)

* **Serveur SSH** : `ssh.cluster126.hosting.ovh.net`
* **Port SSH** : `22`
* **Identifiant principal** : `apsibtl`
* **Mot de passe SSH** : `34KAseb2sjK3Fn9`
* **Chemin racine du site** : `/home/apsibtl/www`
* **Dépôt GitHub** : `https://github.com/oriancm/APSI-WEB.git`
* **Branche de production** : `codex/secure-contabo-deploy`

---

## 2. Déploiement Ultra-Rapide via l'Agent AI Antigravity (AGY)

Si vous utilisez un agent AI Antigravity (AGY) dans une session future et souhaitez déployer, dites-lui simplement :

> *"Déploie le site en utilisant le script scripts/deploy_ovh.py"*

L'agent exécutera simplement la commande suivante en local pour automatiser la connexion SSH, le fetch Git et l'alignement des fichiers en production :

```powershell
python scripts/deploy_ovh.py
```

---

## 3. Comment déployer de nouvelles modifications (Déploiement Git Manuel)

Si vous souhaitez effectuer la mise en ligne manuellement, suivez cette procédure simple :

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
Renseignez le mot de passe `34KAseb2sjK3Fn9`, puis exécutez la commande de synchronisation Git :
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
