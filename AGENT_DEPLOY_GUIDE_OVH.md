# Déploiement OVH — la base en ligne fait autorité

Le site est hébergé dans `/home/apsibtl/www` sur `ssh.cluster126.hosting.ovh.net`, avec le compte `apsibtl` et la branche `codex/secure-contabo-deploy`.

## Procédure unique

Depuis le projet, après avoir poussé les changements de code :

```powershell
python scripts/deploy_ovh.py --check-only
python scripts/deploy_ovh.py
```

Le mot de passe SSH est demandé sans affichage, ou fourni par la variable `APSI_SSH_PASSWORD`. La clé du serveur doit déjà être vérifiée et enregistrée dans le fichier SSH `known_hosts` (connexion SSH habituelle). Le script refuse une clé inconnue ou modifiée.

## Règles de conservation

- La base en ligne est la seule source de vérité : aucun import SQL, aucune réinitialisation et aucune synchronisation depuis une base locale.
- `.env`, `admin/.env`, `secrets/` et tout le dossier `pic/` sont exclus des fichiers déployés. Les photos existantes ne sont ni remplacées ni supprimées. Les nouvelles photos sont ajoutées depuis le CRM.
- `bdd/`, les dumps SQL, les sauvegardes et les résultats d'audit ne sont pas déployés.
- Aucun `git reset --hard`, `git clean`, script d'initialisation ou changement de mot de passe administrateur pendant un déploiement.
- Une base inaccessible, le mode démo activé ou une photo référencée absente bloque le déploiement avant la mise à jour du code.
- Le code candidat est contrôlé avec les réglages de production. Sa connexion doit lire exactement les mêmes données.
- Les empreintes des données, des comptes administrateurs, des réglages et des images sont comparées avant et après déploiement.
- Si le contrôle final échoue, le code précédent est rétabli. La base n'est jamais restaurée automatiquement : une modification simultanée dans le CRM ne doit pas être écrasée.

## Sauvegardes et incidents

Chaque exécution conserve un dossier privé `/home/apsibtl/.apsi-deploy-XXXXXXXX`, hors du site public, avec une sauvegarde sérialisée de toutes les tables (schémas et lignes). Un déploiement complet y ajoute le code précédent et les résultats de validation PHP. Les fichiers contenant les données sont privés. Les sauvegardes doivent être conservées selon la politique de rétention de l'exploitant.

Le mode `--check-only` vérifie et sauvegarde la production sans modifier les fichiers applicatifs. Toute erreur renvoie un code de sortie non nul ; ne pas annoncer le déploiement comme réussi.

En cas de modification concurrente des données pendant le déploiement, le script s'arrête et remet le code précédent : vérifier la modification CRM puis relancer. Il ne faut pas réimporter les données du dépôt pour débloquer la situation.

## Développement local

En production, une base inaccessible renvoie une indisponibilité explicite (HTTP 503), jamais des références de démonstration. Le lanceur `start-local.ps1` active explicitement `APSI_ALLOW_DEMO_DATA=1` pour son processus PHP de prévisualisation : les références locales restent disponibles sans connexion à une base en ligne. `start-local.ps1 -RequireDatabase` désactive ce mode et exige une vraie base locale configurée. Cette option de démonstration doit rester désactivée en production et le déploiement refuse de continuer si elle est activée.

Les fichiers `bdd/*.sql` servent uniquement à une initialisation locale ou à une migration préparée séparément. Le déploiement OVH ne les exécute pas. Le guide `DEPLOY.md` concerne un autre hébergement, sur VPS, et ne doit pas servir à mettre à jour l'OVH actuel.
