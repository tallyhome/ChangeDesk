# ChanLog-1.0

## À propos de ChanLog

ChanLog est une application web développée avec Laravel permettant de gérer les versions et les rapports de bugs d'un projet. Cette application offre une interface conviviale pour suivre l'évolution de votre projet et gérer les problèmes signalés par les utilisateurs.

## Prérequis

- PHP 8.1 ou supérieur
- Composer
- MySQL 5.7 ou supérieur
- Node.js et NPM (pour les assets frontend)

## Installation

1. Clonez le dépôt
   ```
   git clone <url-du-dépôt>
   cd projet_laravel
   ```

2. Installez les dépendances PHP
   ```
   composer install
   ```

3. Installez les dépendances JavaScript
   ```
   npm install
   npm run dev
   ```

4. Copiez le fichier d'environnement et générez la clé d'application
   ```
   cp .env.example .env
   php artisan key:generate
   ```

5. Configurez la base de données dans le fichier .env

## Configuration de la base de données

Les informations de connexion à la base de données sont configurées dans le fichier `.env` à la racine du projet. Voici les paramètres actuels :

```

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=Laravel

```

### Explication des paramètres

- **DB_CONNECTION** : Type de base de données (mysql, sqlite, pgsql, sqlsrv)
- **DB_HOST** : Adresse du serveur de base de données (localhost ou 127.0.0.1 pour un serveur local)
- **DB_PORT** : Port de connexion à la base de données (3306 par défaut pour MySQL)
- **DB_DATABASE** : Nom de la base de données
- **DB_USERNAME** : Nom d'utilisateur pour se connecter à la base de données
- **DB_PASSWORD** : Mot de passe pour se connecter à la base de données

## Migration et seeding

Pour créer les tables dans la base de données et les remplir avec des données de test :

```
php artisan migrate
php artisan db:seed
```

## Lancement de l'application

Pour démarrer le serveur de développement :

```
php artisan serve
```

L'application sera accessible à l'adresse http://127.0.0.1:8000

## Fonctionnalités principales

- Gestion des versions (changelog)
- Suivi des rapports de bugs
- Liste de tâches (todo)
- Gestion de pages de contenu

## Structure de la base de données

### Table versions
- id
- number (numéro de version)
- release_date (date de sortie)
- description
- changes (modifications apportées)
- image_path (chemin vers l'image associée)
- timestamps (created_at, updated_at)

### Table bug_reports
- id
- title (titre du bug)
- description
- reporter_name (nom du rapporteur)
- reporter_email (email du rapporteur)
- status (statut : new, in_progress, resolved, closed)
- progress (progression en pourcentage)
- color (couleur associée au statut)
- severity (gravité du bug)
- expected_fix_date (date prévue de correction)
- admin_notes (notes de l'administrateur)
- timestamps (created_at, updated_at)

## Licence

Ce projet est sous licence [MIT](https://opensource.org/licenses/MIT).
