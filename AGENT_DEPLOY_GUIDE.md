# Guide agent - deploiement APSI BTP

Ce guide permet a un agent ou developpeur de redeployer le site APSI BTP sur un VPS Contabo/Debian/Ubuntu en repartant du repo.

## Regles importantes

- Ne jamais commiter `.env`, `admin/.env`, `secrets/` ou des mots de passe.
- En local, ne pas lancer `php -S 127.0.0.1:8088` seul. Utiliser `start-local.ps1`, car les routes propres passent par `router.php`.
- En production, utiliser Docker Compose avec `docker-compose.prod.yml`.
- Le site public est servi par Caddy en HTTPS, qui reverse-proxy vers le conteneur `apsi-web`.
- Les donnees persistantes sont dans les volumes Docker `apsi_db`, `apsi_images`, `apsi_logs`, `caddy_data`, `caddy_config`.

## Test local Windows

Depuis la racine du repo :

```powershell
powershell -ExecutionPolicy Bypass -File .\start-local.ps1
```

Puis verifier :

```text
http://127.0.0.1:8088/
http://127.0.0.1:8088/aboutUs
http://127.0.0.1:8088/professions
http://127.0.0.1:8088/references
http://127.0.0.1:8088/clients
http://127.0.0.1:8088/contact
```

Si quelqu'un lance quand meme `php -S 127.0.0.1:8088` sans routeur, des dossiers de compatibilite existent pour les pages principales, mais la commande officielle reste le script.

## Preparation serveur

Sur le VPS :

```bash
sudo apt update
sudo apt install -y ca-certificates curl git openssl
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

Firewall si `ufw` est utilise :

```bash
sudo ufw allow OpenSSH
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

## Installation du repo

```bash
sudo mkdir -p /opt/apsi-web
sudo chown "$USER":"$USER" /opt/apsi-web
git clone <URL_DU_REPO> /opt/apsi-web
cd /opt/apsi-web
cp .env.example .env
mkdir -p secrets
```

Editer `.env` :

```bash
nano .env
```

Variables minimales :

```dotenv
DOMAIN=apsi-btp.fr
ACME_EMAIL=contact@apsi-btp.fr
DB_NAME=apsi
DB_USER=apsi
DB_PASSWORD=<mot_de_passe_fort>
DB_ROOT_PASSWORD=<mot_de_passe_root_fort>
ADMIN_LOGIN=admin
ADMIN_PASSWORD_HASH_FILE=./secrets/admin-password-hash
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=<adresse_smtp>
SMTP_PASSWORD=<mot_de_passe_application>
SMTP_FROM=<adresse_expediteur>
SMTP_FROM_NAME=Formulaire de contact
SMTP_TO=<adresse_destinataire>
```

Generer des mots de passe :

```bash
openssl rand -base64 32
```

Creer le hash du mot de passe admin :

```bash
docker run --rm -v "$PWD:/app" -w /app php:8.2-cli php scripts/generate-password-hash.php '<MOT_DE_PASSE_ADMIN>' > secrets/admin-password-hash
chmod 600 secrets/admin-password-hash
```

## DNS

Faire pointer le domaine vers l'IP du VPS :

```text
apsi-btp.fr      A    <IP_DU_SERVEUR>
www.apsi-btp.fr  A    <IP_DU_SERVEUR>
```

Tant que les DNS ne sont pas propages, Caddy ne pourra pas obtenir le certificat HTTPS final.

## Premier demarrage

```bash
cd /opt/apsi-web
docker compose -f docker-compose.prod.yml up -d --build
```

Verifier :

```bash
docker compose -f docker-compose.prod.yml ps
docker compose -f docker-compose.prod.yml logs -f caddy
docker compose -f docker-compose.prod.yml logs -f apsi-web
docker compose -f docker-compose.prod.yml logs -f db
```

Tester :

```bash
curl -I http://127.0.0.1
curl -I http://127.0.0.1/aboutUs
curl -I http://127.0.0.1/references
curl -I https://apsi-btp.fr
```

## Mise a jour

```bash
cd /opt/apsi-web
git pull
docker compose -f docker-compose.prod.yml up -d --build
```

## Procedure utilisee pour ce deploiement

Contexte verifie sur le VPS :

```bash
cd /opt/apsi-web
git branch --show-current
git status --short --branch
docker ps --format "table {{.Names}}\t{{.Image}}\t{{.Status}}\t{{.Ports}}"
```

Branche de production utilisee :

```text
codex/secure-contabo-deploy
```

Commandes de deploiement :

```bash
cd /opt/apsi-web
git fetch origin
git pull --ff-only origin codex/secure-contabo-deploy
docker compose -f docker-compose.prod.yml up -d --build
docker compose -f docker-compose.prod.yml ps
```

Verification HTTP apres deploiement :

```bash
curl -fsS -I http://127.0.0.1/
curl -fsS -I http://127.0.0.1/aboutUs
curl -fsS -I http://127.0.0.1/professions
curl -fsS -I http://127.0.0.1/references
curl -fsS -I http://127.0.0.1/clients
curl -fsS -I http://127.0.0.1/contact
curl -fsS -I http://127.0.0.1/reference/10001
```

Verification HTML minimale :

```bash
curl -fsS http://127.0.0.1/aboutUs | grep -q 'about-page'
curl -fsS http://127.0.0.1/professions | grep -q 'professions-page'
curl -fsS http://127.0.0.1/references | grep -q 'references-page'
curl -fsS http://127.0.0.1/clients | grep -q 'clients-page'
curl -fsS http://127.0.0.1/contact | grep -q 'contact-body'
curl -fsS http://127.0.0.1/reference/10001 | grep -q 'reference-page'
```

## Sauvegardes

Base de donnees :

```bash
cd /opt/apsi-web
source .env
docker compose -f docker-compose.prod.yml exec db mariadb-dump -u root -p"$DB_ROOT_PASSWORD" "$DB_NAME" > "backup-apsi-$(date +%F).sql"
```

Images :

```bash
docker run --rm -v apsi-web_apsi_images:/data -v "$PWD:/backup" alpine tar czf "/backup/backup-images-$(date +%F).tar.gz" -C /data .
```

## Restauration rapide

Restaurer la base :

```bash
source .env
cat backup-apsi.sql | docker compose -f docker-compose.prod.yml exec -T db mariadb -u root -p"$DB_ROOT_PASSWORD" "$DB_NAME"
```

Restaurer les images :

```bash
docker run --rm -v apsi-web_apsi_images:/data -v "$PWD:/backup" alpine sh -c 'rm -rf /data/* && tar xzf /backup/backup-images.tar.gz -C /data'
```

## Depannage routes

Symptome : `/aboutUs`, `/references`, `/clients` affichent l'accueil.

Local Windows :

```powershell
powershell -ExecutionPolicy Bypass -File .\start-local.ps1
```

Ne pas utiliser :

```powershell
php -S 127.0.0.1:8088
```

Production :

```bash
docker compose -f docker-compose.prod.yml exec apsi-web nginx -T | grep -n "rewrite\\|try_files\\|reference"
docker compose -f docker-compose.prod.yml restart apsi-web
```

Verifier que `nginx.conf` contient :

```nginx
rewrite ^/reference/([0-9]+)$ /reference.php?id=$1 last;
rewrite ^/([^/.]+)$ /$1.php last;
```

## Fichiers a connaitre

- `docker-compose.prod.yml` : orchestration prod.
- `Caddyfile` : HTTPS et reverse proxy.
- `nginx.conf` : routage interne PHP.
- `router.php` : routage local avec `php -S`.
- `start-local.ps1` : lancement local fiable.
- `.env.example` : modele de configuration.
- `bdd/apsi.sql` : import initial MariaDB.
- `scripts/generate-password-hash.php` : hash du mot de passe admin.
