# Audit de sécurité Evolora — mise en production

Les corrections de code décrites ci-dessous sont livrées en 2.8.13. Le second facteur du superadmin reste une mesure d’exploitation : mot de passe long, non réutilisé. Il n’est pas imposé dans l’application, pour ne pas bloquer la connexion sans codes de secours.

Version revue : **2.8.13**.  
Date : 9 novembre 2026  
Périmètre : code de l’application (Laravel 12, multi-tenant).  
Ce document est une revue des chemins d’attaque dans le code. Ce n’est pas un test d’intrusion contre un serveur en ligne.

## Verdict

Evolora peut être mis en production **après** deux corrections de code et la checklist de déploiement ci-dessous.

Le socle est sain : isolation des projets, sessions chiffrées, webhooks refusés sans signature, HTML public filtré, téléversements d’images bornés, installeur fermé dès qu’une clé d’application existe, connexions limitées en débit. Les failles critiques de la revue précédente (scripts dans les pages publiques, webhooks non signés, extension de fichier choisie par le visiteur, installeur rouvert, traversée de chemin des sauvegardes) sont corrigées dans la 2.8.11.

Il reste deux défauts à traiter avant d’ouvrir le service, plus des durcissements de configuration.

| Niveau | Nombre | Bloque la mise en production |
| --- | --- | --- |
| Élevé | 2 | Oui |
| Moyen | 5 | Non, à planifier |
| Faible | 4 | Non |
| Déjà corrigé | 11 | — |

## Élevé — à corriger avant ouverture

### 1. Changement de mot de passe admin inutilisable

`app/Http/Controllers/AdminProfileController.php` hache le mot de passe avec `Hash::make`, puis l’enregistre sur un modèle `User` dont le champ `password` est déjà casté `hashed`. Le mot de passe est donc haché deux fois. Après un changement depuis le profil, le compte ne se connecte plus avec le nouveau mot de passe.

Le reset superadmin et la création d’utilisateur n’ont pas ce défaut : ils confient le hachage au cast.

Correction : assigner le mot de passe en clair au champ (`$user->password = $validated['password']`) et laisser le cast hacher une seule fois. Vérifier aussi l’ancien mot de passe avec `Hash::check` avant l’assignation, ce qui est déjà fait.

### 2. Bascule de réglages sans liste blanche

`POST /admin/settings/toggle` (`SettingController::toggle`) prend le nom de clé envoyé par le client et inverse la valeur, sans la limiter aux trois interrupteurs du formulaire. Le formulaire ne l’appelle plus, mais la route reste ouverte à tout administrateur de projet connecté.

Effet : il peut créer ou inverser n’importe quel réglage **de son propre projet** (modules, liens, clés inventées). Il ne traverse pas l’isolation entre projets.

Correction : n’accepter que `external_link_enabled`, `app_store_enabled` et `play_store_enabled`, ou retirer la route.

## Moyen — à planifier, pas un blocage d’ouverture si la checklist est suivie

### 3. Restauration SQL des sauvegardes

Réservé au superadmin. Le téléchargement, la suppression et la restauration restent dans `storage/app/backups` et n’acceptent qu’un nom `.sql`. La restauration exécute ensuite le fichier avec `DB::unprepared`. L’export concatène les valeurs avec un échappement sommaire des apostrophes. Un texte déjà stocké en base peut donc produire un fichier de sauvegarde que la restauration interprète mal.

Un visiteur anonyme ne peut pas déclencher ça. Un compte superadmin compromis peut, via cette fonction, réécrire la base.

Correction : export avec des requêtes paramétrées ou un outil (`mysqldump`), et restauration seulement après confirmation explicite. Ne pas exposer cette fonction sur un hébergement partagé sans sauvegarde externe.

### 4. Point d’entrée certificats trop bavard

`GET /api/ssl/ask?domain=` répond 200 si le domaine central, un sous-domaine de projet actif, ou un domaine personnalisé vérifié est connu. C’est le contrat attendu par Caddy. Sans secret ni filtre d’adresse, n’importe qui peut tester l’existence d’un projet ou d’un domaine.

Correction : secret partagé en en-tête, ou n’accepter que l’adresse du reverse proxy.

### 5. En-têtes HTTP de sécurité absents

Aucune politique n’envoie HSTS, `X-Content-Type-Options`, `X-Frame-Options` (ou `frame-ancestors`), `Referrer-Policy`, ni une politique de contenu. Le site peut être inclus dans une frame et le navigateur ne force pas HTTPS.

Correction : les poser au reverse proxy (recommandé) ou dans un middleware, avec HSTS seulement une fois le certificat stable.

### 6. Signalements publics et captcha

Les signalements d’un projet sont listés et consultables sur le site de ce projet. C’est le fonctionnement prévu. Un visiteur qui colle un secret dans la description le publie. Le contrôle anti-robot est une addition en session, avec une limite de 8 envois par minute. Un script déterminé passe outre.

Correction : ne pas afficher l’e-mail du rapporteur (déjà le cas sur la fiche publique), avertir que le texte sera public, et remplacer l’addition par un contrôle plus solide si le spam devient un problème.

### 7. Inscription ouverte

`POST /register` crée un projet et connecte l’utilisateur sans confirmation d’e-mail. La route est limitée à 5 essais par minute. Un abus crée des projets vides, pas un accès aux autres projets.

Correction : confirmation d’e-mail, ou inscription sur invitation, si le service n’est pas ouvert au public.

### 8. Proxies de confiance non déclarés

`bootstrap/app.php` ne déclare pas les proxys. Derrière Nginx ou Cloudflare, Laravel voit l’adresse du proxy pour tout le monde. Le plafond de connexions (5 essais par e-mail et par adresse) se retrouve alors partagé, et les adresses IP des visites sont fausses.

Correction : faire confiance uniquement à l’adresse du reverse proxy, jamais à tout Internet.

## Faible

### 9. Aperçu wiki

`AdminWikiController::preview` renvoie le HTML reçu à l’administrateur qui l’a soumis. Le contenu publié, lui, passe par le filtre à l’affichage. Risque limité au navigateur de cet administrateur.

### 10. Adresses IP en clair dans l’admin

Les visites enregistrent l’adresse IP et l’admin la liste. Ce n’est pas une intrusion, c’est une donnée personnelle. Prévoir une durée de conservation et une mention dans la politique de confidentialité.

### 11. Pas de second facteur

Le superadmin n’a pas de second facteur. Un mot de passe volé ouvre la plateforme, les sauvegardes et les mises à jour.

### 12. Filtre HTML maison

`HtmlSanitizer` retire scripts, iframes, gestionnaires d’événements et liens `javascript:`. Ce n’est pas une bibliothèque de purification complète. Le risque résiduel sur un HTML très tordu est faible. Les signalements publics sont en plus passés par `strip_tags`.

## Déjà corrigé (2.8.11 et 2.8.12)

Ces points ne sont plus ouverts. Ils sont rappelés pour ne pas les retraiter.

1. Scripts stockés dans les signalements, le changelog, le wiki, l’accueil et la roadmap.
2. Webhooks Stripe et PayPal refusés sans signature valide. Sans secret, ils sont refusés (aucun plan n’est activé par un appel forgé).
3. Images enregistrées avec l’extension détectée, dossier en `0755`, scripts bloqués dans `public/uploads`.
4. Installeur fermé si `storage/app/installed` existe ou si `.env` contient une `APP_KEY`.
5. Sauvegardes : nom de fichier limité au dossier `storage/app/backups`.
6. Connexion limitée à 5 essais par minute pour le même e-mail et la même adresse.
7. Archives de mise à jour refusées si un chemin sort du dossier d’extraction. La commande lancée est passée par `escapeshellarg`.
8. Sessions chiffrées par défaut. En production, le cookie n’est envoyé qu’en HTTPS.
9. Mot de passe temporaire affiché une seule fois, haché une seule fois.
10. Jeton GitHub lu dans `GITHUB_UPDATE_TOKEN`, dépôt inchangé.
11. Impersonation : le retour exige un superadmin encore actif. L’édition de page ne peut plus changer de projet. La recherche de la page Changelog ne mélange plus les projets.

## Checklist de déploiement

À faire sur le serveur, pas dans le dépôt.

1. `APP_ENV=production`
2. `APP_DEBUG=false`
3. `APP_KEY` générée sur ce serveur, unique, jamais recopiée d’un exemple
4. `LOG_LEVEL=error` (ou `warning`)
5. HTTPS devant l’application
6. `SESSION_ENCRYPT=true`
7. `SESSION_SECURE_COOKIE=true`
8. `SESSION_DOMAIN` en `.votredomaine` si les projets sont en sous-domaines
9. Base dédiée, utilisateur SQL sans droit d’administration du serveur
10. `STRIPE_WEBHOOK_SECRET` et `PAYPAL_WEBHOOK_ID` renseignés avant d’encaisser
11. `GITHUB_UPDATE_TOKEN` seulement si le dépôt des mises à jour est privé, droit lecture seule
12. Proxys de confiance limités au reverse proxy
13. En-têtes HSTS, nosniff, frame denial, Referrer-Policy au proxy
14. `storage/` et `bootstrap/cache/` non servis par le serveur web
15. Sauvegarde externe quotidienne, en plus de l’écran superadmin
16. Compte superadmin avec un mot de passe long, non réutilisé
17. Après déploiement : `php artisan optimize:clear` puis reconnexion (les sessions chiffrées invalident les anciennes)

Ne pas copier `.env.example` tel quel en production : il est prévu pour le développement (`APP_DEBUG=true`, `APP_ENV=local`). L’installeur web écrit déjà `APP_ENV=production` et `APP_DEBUG=false`.

## Ce qui a été vérifié, et ce qui ne l’a pas été

Vérifié dans le code : routes, middleware d’isolation, authentification, webhooks, téléversements, installeur, sauvegardes, filtre HTML, sessions, mises à jour, réglages, profil, signalements publics, point certificats.

Non vérifié : le serveur d’hébergement, la configuration TLS réelle, les règles du pare-feu, les en-têtes déjà ajoutés par le proxy, ni une tentative d’intrusion sur une instance en ligne. Ces contrôles se font sur l’environnement cible, après la checklist.
