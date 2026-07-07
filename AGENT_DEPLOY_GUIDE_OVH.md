# Guide de Déploiement - OVH Hébergement Mutualisé

Ce guide décrit la procédure pour déployer et mettre à jour le site **APSI BTP** sur l'hébergement mutualisé OVHcloud.

---

## 1. Informations d'Hébergement (OVH)

* **Serveur SSH & SFTP** : `ssh.cluster126.hosting.ovh.net`
* **Serveur FTP** : `ftp.cluster126.hosting.ovh.net`
* **Port SSH / SFTP** : `22`
* **Port FTP** : `21`
* **Identifiant principal** : `apsibtl`
* **Chemin racine du site** : `/home/apsibtl/www`

---

## 2. Option de Déploiement A : Transfert direct via SFTP (Recommandée & Sécurisée)

Puisque les fichiers d'images de production (`www/pic/`) et les configurations `.env` ne doivent pas être écrasés ou supprimés, le transfert ciblé de fichiers via SFTP est la méthode la plus sûre et la plus rapide.

### Transfert manuel :
Vous pouvez utiliser un client SFTP comme **FileZilla** ou **Cyberduck** :
1. Connectez-vous à `ssh.cluster126.hosting.ovh.net` sur le port `22` avec l'identifiant `apsibtl`.
2. Accédez au dossier distant `/home/apsibtl/www`.
3. Glissez-déposez les fichiers modifiés depuis votre dossier local vers le serveur.

### Transfert automatisé (Script Python) :
Vous pouvez utiliser le script Python `deploy.py` disponible dans le dossier de secours ou le recréer localement pour automatiser l'upload SSH/SFTP des fichiers modifiés en une seule commande :

```python
# deploy_ovh.py
import paramiko
import os

hostname = "ssh.cluster126.hosting.ovh.net"
username = "apsibtl"
password = "your_ssh_password"  # Remplacer par le mot de passe réel

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())

try:
    print(f"Connexion à {hostname}...")
    client.connect(hostname, username=username, password=password, timeout=15)
    sftp = client.open_sftp()
    
    # Fichiers à transférer (chemin local, chemin distant)
    files = [
        ("css/style.css", "/home/apsibtl/www/css/style.css"),
        ("professions.php", "/home/apsibtl/www/professions.php")
    ]
    
    for local, remote in files:
        if os.path.exists(local):
            print(f"Upload de {local} vers {remote}...")
            sftp.put(local, remote)
    
    sftp.close()
    print("Déploiement SFTP réussi !")
finally:
    client.close()
```

---

## 3. Option de Déploiement B : Synchronisation Git sur le serveur

Si vous préférez gérer les déploiements directement en ligne de commande via Git sur le serveur OVH, suivez ces étapes d'initialisation :

### Initialisation unique sur le serveur (via SSH) :
Connectez-vous en SSH à votre serveur :
```bash
ssh apsibtl@ssh.cluster126.hosting.ovh.net
```
Puis initialisez le dépôt Git existant sans écraser vos fichiers de production :
```bash
cd /home/apsibtl/www
git init
git remote add origin https://github.com/oriancm/APSI-WEB.git
git fetch origin
# Synchronise l'index de git avec la branche de production sans toucher aux fichiers locaux
git reset --mixed origin/codex/secure-contabo-deploy
```

### Mettre à jour le site en production (via SSH) :
Désormais, pour déployer les derniers commits poussés sur GitHub, connectez-vous au serveur et lancez :
```bash
cd /home/apsibtl/www
git pull origin codex/secure-contabo-deploy
```

---

## 4. Base de Données (OVH)

Sur l'hébergement mutualisé OVH, la base de données MariaDB/MySQL n'est pas gérée par Docker. Elle est hébergée sur un serveur de base de données OVH dédié.

* **Configuration dans `.env`** :
  Vérifiez que votre fichier `/home/apsibtl/www/.env` contient les bons accès fournis par OVH (Hôte, Nom de base, Utilisateur, Mot de passe).
* **Sauvegarde de la base de données en ligne** :
  Vous pouvez exporter la base directement en SSH :
  ```bash
  mysqldump -h <serveur_sql_ovh> -u <utilisateur> -p <nom_de_base> > backup.sql
  ```
