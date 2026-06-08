# Deploiement VPS Contabo

Cette configuration lance le site APSI sur un VPS avec Docker Compose :

- Caddy en reverse proxy public avec HTTPS automatique
- l'application PHP/Nginx sur le reseau Docker interne
- MariaDB avec volume persistant
- un volume persistant pour les images uploadees dans `pic/`

## 1. Preparer le serveur

Pointez les DNS du domaine vers l'IP du VPS :

- `apsi-btp.fr` -> A record vers l'IP du VPS
- `www.apsi-btp.fr` -> A record vers l'IP du VPS

Installez Docker sur Ubuntu/Debian :

```bash
sudo apt update
sudo apt install -y ca-certificates curl git
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

Ouvrez les ports web si un firewall est actif :

```bash
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw allow OpenSSH
sudo ufw enable
```

## 2. Installer le projet

```bash
git clone <url-du-repo> /opt/apsi-web
cd /opt/apsi-web
cp .env.example .env
nano .env
```

Renseignez au minimum :

- `DOMAIN`
- `ACME_EMAIL`
- `DB_PASSWORD`
- `DB_ROOT_PASSWORD`
- `SMTP_USERNAME`
- `SMTP_PASSWORD`
- `SMTP_FROM`
- `SMTP_TO`

Generez des mots de passe forts :

```bash
openssl rand -base64 32
```

Pour le compte admin, choisissez un mot de passe fort puis genere son hash PHP dans un fichier local ignore par Git :

```bash
mkdir -p secrets
docker run --rm -v "$PWD:/app" -w /app php:8.2-cli php scripts/generate-password-hash.php '<mot-de-passe-admin-fort>' > secrets/admin-password-hash
chmod 600 secrets/admin-password-hash
```

Le mot de passe en clair n'est jamais stocke dans le repo. Le fichier `secrets/admin-password-hash` reste sur le serveur.

Important : le mot de passe SMTP qui etait dans `contact.php` a ete retire du code. Il faut le revoquer/regenerer cote Gmail avant la mise en prod, car il a existe dans l'ancien historique Git.

## 3. Lancer

```bash
docker compose -f docker-compose.prod.yml up -d --build
```

Verifier :

```bash
docker compose -f docker-compose.prod.yml ps
docker compose -f docker-compose.prod.yml logs -f caddy
docker compose -f docker-compose.prod.yml logs -f apsi-web
```

Le site sera disponible sur `https://$DOMAIN` quand les DNS pointeront vers le VPS et que Caddy aura obtenu le certificat.

## 4. Mise a jour

```bash
cd /opt/apsi-web
git pull
docker compose -f docker-compose.prod.yml up -d --build
```

## 5. Sauvegardes rapides

Base de donnees :

```bash
docker compose -f docker-compose.prod.yml exec db mariadb-dump -u root -p"$DB_ROOT_PASSWORD" "$DB_NAME" > backup-apsi.sql
```

Images uploadees :

```bash
docker run --rm -v apsi-web_apsi_images:/data -v "$PWD:/backup" alpine tar czf /backup/backup-images.tar.gz -C /data .
```

## 6. Notes

Le dump `bdd/apsi.sql` n'est importe automatiquement que lors de la premiere creation du volume MariaDB. Si le volume `apsi_db` existe deja, MariaDB conserve les donnees existantes et ignore les scripts d'initialisation.
