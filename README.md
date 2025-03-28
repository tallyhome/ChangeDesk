#     ChangeDesk v1.1.1

## À propos de ChanLog

ChanLog est une application web développée avec Laravel permettant de gérer les versions et les rapports de bugs d'un projet. Cette application offre une interface conviviale pour suivre l'évolution de votre projet et gérer les problèmes signalés par les utilisateurs.

## Prérequis

- PHP 8.2 ou supérieur
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
- expected_fix_date (date prévue de correction)!

- admin_notes (notes de l'administrateur)
- timestamps (created_at, updated_at)


### Screen 
# Aperçu du projet

| ChanLog | Todolist | Report Bug |
|---------|---------|------------|
| ![ChanLog](https://github.com/user-attachments/assets/fbb8276f-f467-4992-870d-887a50c23f07) | ![Todolist](https://github.com/user-attachments/assets/25ff9e3e-4ea2-4f6c-ba85-38990468a802) | ![Report_Bug](https://github.com/user-attachments/assets/43ef171a-d5c3-4b88-955c-a004f535a889) |

| Admin Todolist News | Admin Report Bug News | Admin Report Bug Edit |
|---------------------|----------------------|----------------------|
| ![Admin_todolist_news](https://github.com/user-attachments/assets/694afa42-30b1-4b93-90e2-50d6db63d7c1) | ![Admin_report_bug_news](https://github.com/user-attachments/assets/f0978c77-9787-422f-a5cb-a184e3f49c08) | ![Admin_report_bug_edit](https://github.com/user-attachments/assets/8221bf5e-c696-4b09-ae14-fc3e4590a8eb) |

| Admin Report Bug | Admin Profile | Admin Page |
|-----------------|---------------|------------|
| ![Admin_report_bug](https://github.com/user-attachments/assets/2fa10500-601c-4657-9583-a6da547734f2) | ![Admin_Profile](https://github.com/user-attachments/assets/62d6ce4e-c90f-4809-a8f4-6e1aeed5c189) | ![Admin_Page](https://github.com/user-attachments/assets/48e1042f-c36d-49cd-952c-3a00fbc146b9) |

| Admin ChangeLog News | Admin ChangeLog Edit | Admin ChangeLog |
|---------------------|---------------------|----------------|
| ![Admin_ChanLog_news](https://github.com/user-attachments/assets/7b59ef04-2e0d-4032-a4b9-6f7993987e17) | ![Admin_ChanLog_edit](https://github.com/user-attachments/assets/4d9f79fa-bcc4-4f99-bc92-8f0d40cb8b34) | ![Admin_ChanLog](https://github.com/user-attachments/assets/7c8eeead-3db6-424f-afa3-f663063dcafe) |

| Admin Todolist Edit | Admin Todolist |
|--------------------|---------------|
| ![Admin_todolist_edit](https://github.com/user-attachments/assets/d6709bff-cc71-43b6-9acb-19c24e7c37c2) | ![Admin_todolist](https://github.com/user-attachments/assets/2a8e2825-153b-43e7-a12e-0b916cb10f2f) |




## Licence

Ce projet est sous licence [MIT](https://opensource.org/licenses/MIT).
