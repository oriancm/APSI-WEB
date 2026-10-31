# Back-office APSI : tests et fonctionnement

## Exécuter les vérifications

Prérequis : PHP 8.2+ avec GD/WebP, mbstring et pdo_sqlite, Python avec `requests`, Node.js et Chrome.

```text
npm ci
python tests/admin/run.py
npm run test:admin
npm run test:deploy
```

Les tests copient le site dans un répertoire temporaire et remplacent **uniquement dans cette copie** la connexion par une base SQLite de test. Ils n'utilisent aucun identifiant ni accès de production. Les tests navigateur refusent toute adresse autre que `127.0.0.1`. Les données, images et comptes de test sont supprimés en fin d'exécution.

Les captures desktop/mobile sont produites dans `output/backoffice/`. Les tests et ce répertoire sont exclus du déploiement.

`python tests/admin/run.py --serve` ouvre une copie locale éphémère pour une vérification manuelle. L'adresse et le compte de démonstration sont affichés dans le terminal ; arrêter le processus après utilisation.

## Parcours du gérant

1. Ouvrir `/admin/` et se connecter avec son compte existant.
2. Choisir **Créer une référence**, ou rechercher un projet dans la liste.
3. Donner un nom, choisir le domaine, saisir la ville ou le code postal. La recherche officielle propose la correspondance ; si plusieurs communes ou codes postaux correspondent, choisir explicitement le bon résultat. Si la recherche est indisponible, la saisie manuelle reste possible.
4. Ajouter les photos, les réordonner en les glissant ou avec les flèches. La première est la couverture. Un retrait peut être annulé avant enregistrement.
5. Compléter les détails utiles et cliquer sur **Enregistrer et publier**. Les modifications ne deviennent publiques qu'après cet enregistrement. **Annuler les modifications** permet de revenir à la liste sans enregistrer, après confirmation ; choisir **Continuer la saisie** dans la fenêtre conserve le formulaire et ses photos. `Ctrl`/`⌘` + `Entrée` enregistre aussi le formulaire.

Les formats JPG, PNG et WebP sont décodés et réencodés en WebP avec des versions principale (1920 px), mobile (960 px) et carte (800 px), sans agrandissement. Limites : 15 Mo par photo, 60 Mo de nouveaux fichiers par enregistrement, 20 photos par référence et 20 millions de pixels par source. Les anciens fichiers et leur ordre sont conservés sans reconversion obligatoire.

Les communes historiques au format `VILLE (département)` restent inchangées lorsqu'on enregistre un projet sans modifier sa localisation. Les nouvelles localisations stockent `Ville (code postal)` dans le champ existant : aucune migration de base n'est nécessaire.

Si la session expire avant l'enregistrement, le formulaire reste ouvert avec les champs et les photos sélectionnées. Se reconnecter via le lien proposé dans un nouvel onglet, puis enregistrer à nouveau dans l'onglet d'origine. Les jetons de formulaire sont renouvelés et la protection contre les modifications concurrentes reste active.

## Protections vérifiées

- Authentification avant accès à la base pour les pages privées ; cookies HttpOnly/SameSite Strict et Secure en HTTPS ; session renouvelée à la connexion, expiration après 30 minutes d'inactivité et 8 heures maximum.
- Limitation persistante par adresse IP : 8 échecs par fenêtre de 15 minutes, partagée entre les sessions. Ne dépend pas des en-têtes fournis par le visiteur.
- Jetons CSRF sur connexion, enregistrement, suppression, changement de mot de passe et déconnexion ; aucune suppression ou déconnexion par GET.
- Mot de passe actuel exigé pour le changement, minimum 12 caractères ; invalidation des autres sessions après changement. Les mots de passe existants ne sont pas remplacés par le déploiement.
- Requêtes préparées, validation côté serveur, échappement du contenu, CSP sans script ni style inline, blocage HTTP des modules internes, absence de détails SQL dans les messages affichés.
- Validation et décodage des images, noms aléatoires, aucun fichier source brut publié, scripts interdits dans `pic/`.
- Enregistrement transactionnel ; retrait des nouvelles images en cas d'échec ; refus d'écraser une version modifiée dans un autre onglet ; protection contre les doubles soumissions.
- Une suppression de référence exige de saisir `SUPPRIMER` ; les fichiers restent sur disque pour permettre une récupération à partir de la sauvegarde privée. Un retrait de photo d'une référence supprime ses fichiers après validation de la transaction, sauf s'ils sont encore utilisés ailleurs.
- Actions importantes journalisées dans le journal PHP de l'hébergeur, sans mot de passe ni contenu des projets. Les réglages de conservation et d'accès de ces journaux relèvent de l'hébergement.

Les tests automatisés couvrent les protections applicatives ci-dessus, les transactions et les parcours réels de saisie. Ils ne constituent pas un audit d'intrusion indépendant de l'infrastructure OVH.

## Déploiement

Utiliser exclusivement `scripts/deploy_ovh.py` et le guide `AGENT_DEPLOY_GUIDE_OVH.md`. La base en ligne fait autorité. La mise en ligne ne requiert aucun SQL d'initialisation ou de migration et doit conserver les empreintes des données, comptes, réglages et photos.

Références : [API officielle des communes](https://geo.api.gouv.fr/decoupage-administratif/communes), [sécurité des sessions PHP](https://www.php.net/manual/en/features.session.security.management.php).

Les listes déroulantes du back-office proposent des options stylisées, une sélection visible et la navigation au clavier (flèches, Entrée, Échap, début/fin et recherche par lettres). Les champs natifs restent transmis et validés par le serveur. Le favicon coloré du site et du back-office est régénéré avec `python scripts/build-favicons.py` à partir du logo original.
