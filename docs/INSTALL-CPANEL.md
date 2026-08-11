# Installation ChanLog (cPanel / Webuzo / Plesk)

## Taille du projet (~3 Go) — pas normal pour le code seul

Le code Laravel fait typiquement **50–150 Mo** avec `vendor/`.
Si tu vois **3 Go**, cherche :

```bash
du -sh * .[^.]* 2>/dev/null | sort -hr | head -20
```

Causes fréquentes :
- `vendor/` dupliqué / corrompu
- `ChangeLog.zip` ou archives laissées sur le serveur
- `.git/` volumineux
- `node_modules/` (inutile en prod ici)
- `storage/app/backups/` ou logs énormes
- copies multiples du projet

À supprimer en prod si présents : `node_modules`, `ChangeLog.zip`, `.git` (optionnel), vieux backups.

## Méthode A — Document Root = public (recommandé)

Dans cPanel → Domaines → ton domaine :

`/home/USER/changelog-dev.myvcard.fr/public`

Puis ouvre `https://ton-domaine/`.

## Méthode B — Root = dossier Laravel + .htaccess

Le fichier **`.htaccess`** (avec le point) doit être à la racine.
Le fichier `htaccess` sans point est **ignoré** par Apache.

```bash
cd /home/fabien/changelog-dev.myvcard.fr
cp htaccess .htaccess   # ou utilise le .htaccess fourni dans le repo
# Options -Indexes empêche le listing des dossiers
```

## Auto-installeur web

1. Uploade le projet (avec `vendor/` si pas de Composer)
2. Crée la base MySQL
3. Ouvre `https://ton-domaine/install`
4. Suis : prérequis → BDD + superadmin → terminé

L’installeur écrit `.env`, lance migrate/seed, crée `storage/app/installed`.

## .env important

```env
APP_URL=https://changelog-dev.myvcard.fr
CENTRAL_DOMAIN=changelog-dev.myvcard.fr
CENTRAL_DOMAINS=changelog-dev.myvcard.fr,www.changelog-dev.myvcard.fr
```

Sans slash final sur `APP_URL`.

## Sous-domaines clients (`slug.domaine`) — cPanel pas à pas

Le site central peut marcher alors que `teste.changelog-dev.myvcard.fr` est « inaccessible ».
C’est **DNS / vhost / SSL**, pas Laravel.

### 1) DNS wildcard
Chez le registrar / Cloudflare / zone DNS du domaine parent :
- Type **A** (ou CNAME) : hôte `*` → même IP (ou cible) que `changelog-dev.myvcard.fr`
- Résultat attendu : `teste.changelog-dev.myvcard.fr` résout comme le domaine principal

Vérif :
```bash
ping teste.changelog-dev.myvcard.fr
# ou
nslookup teste.changelog-dev.myvcard.fr
```

### 2) Sous-domaine wildcard cPanel
cPanel → **Domaines** / **Subdomains** → créer :
- Subdomain : `*`
- Domain : `changelog-dev.myvcard.fr`
- Document Root : **le même** que le site Laravel (`.../changelog-dev.myvcard.fr/public` de préférence)

Ou équivalent « Wildcard Subdomain » / `ServerAlias *.changelog-dev.myvcard.fr`.

### 3) SSL wildcard
cPanel → **SSL/TLS Status** → AutoSSL, ou certificat Let’s Encrypt **wildcard**
`*.changelog-dev.myvcard.fr` (+ le domaine apex).
Sans SSL valide, Chrome peut aussi afficher une erreur d’accès.

Sans ces 3 points, le navigateur n’atteint jamais PHP.

## Taille vendor (Windows PowerShell)

`rm -rf` est une commande Linux. Sous PowerShell :

```powershell
Remove-Item -Recurse -Force vendor
composer install --no-dev --prefer-dist --optimize-autoloader
```

Sous Linux / SSH cPanel :
```bash
rm -rf vendor
composer install --no-dev --prefer-dist --optimize-autoloader
```

## Mises à jour GitHub (hors .env)

Repo + token sont dans `app/Support/GithubUpdateAuth.php` (pas le `.env`).
Pour un dépôt **privé** : PAT fine-grained Contents=Read-only sur ce repo, collé dans `TOKEN`.

Inspiré d’Obiora Panel (check robuste, cache 10 min, fallbacks releases/tags, anti-downgrade),
mais **apply en zip** (adapté cPanel) — pas de `git reset` root comme Obiora.

