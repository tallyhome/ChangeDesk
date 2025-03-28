-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 19 mars 2025 à 06:33
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `laravel`
--

-- --------------------------------------------------------

--
-- Structure de la table `bug_reports`
--

CREATE TABLE `bug_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `reporter_name` varchar(255) NOT NULL,
  `reporter_email` varchar(255) NOT NULL,
  `status` enum('new','in_progress','resolved','closed') NOT NULL DEFAULT 'new',
  `progress` int(11) NOT NULL DEFAULT 0,
  `color` varchar(255) NOT NULL DEFAULT 'danger',
  `severity` varchar(255) NOT NULL DEFAULT 'medium',
  `expected_fix_date` date DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bug_reports`
--

INSERT INTO `bug_reports` (`id`, `title`, `description`, `reporter_name`, `reporter_email`, `status`, `progress`, `color`, `severity`, `expected_fix_date`, `admin_notes`, `created_at`, `updated_at`) VALUES
(1, 'Erreur lors de la connexion', 'Lorsque je tente de me connecter avec des identifiants valides, je reçois parfois une erreur 500.', 'Jean Dupont', 'jean.dupont@example.com', 'resolved', 0, 'danger', 'medium', NULL, 'Problème résolu - Il s\'agissait d\'un problème de cache du serveur.', '2025-03-19 02:06:15', '2025-03-19 02:06:15'),
(2, 'Problème d\'affichage sur Firefox', 'Le menu déroulant ne s\'affiche pas correctement sur Firefox version 95.', 'Marie Martin', 'marie.martin@example.com', 'in_progress', 0, 'danger', 'medium', NULL, 'En cours d\'investigation - Problème de compatibilité CSS.', '2025-03-19 02:06:15', '2025-03-19 02:06:15'),
(3, 'Impossible de télécharger des fichiers PDF', 'Lorsque j\'essaie de télécharger un fichier PDF, rien ne se passe.', 'Pierre Leroy', 'pierre.leroy@example.com', 'new', 0, 'danger', 'medium', NULL, NULL, '2025-03-19 02:06:15', '2025-03-19 02:06:15'),
(4, 'Lenteur lors du chargement des images', 'Les images mettent beaucoup de temps à se charger sur la page d\'accueil.', 'Sophie Dubois', 'sophie.dubois@example.com', 'new', 0, 'danger', 'medium', NULL, NULL, '2025-03-19 02:06:15', '2025-03-19 02:06:15'),
(5, 'Erreur 404 sur le lien de contact', 'Le lien vers la page de contact dans le pied de page renvoie une erreur 404.', 'Thomas Bernard', 'thomas.bernard@example.com', 'resolved', 0, 'danger', 'medium', NULL, 'Corrigé - URL mal formée dans le template.', '2025-03-19 02:06:15', '2025-03-19 02:06:15');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2023_06_15_create_bug_reports_table', 1),
(5, '2023_06_15_create_todo_items_table', 1),
(6, '2024_01_10_create_pages_table', 1),
(7, '2025_03_17_232708_create_versions_table', 1),
(8, '2025_03_19_025212_add_columns_to_todo_items_table', 1),
(9, '2025_03_19_025323_add_columns_to_todo_items_table', 1),
(10, 'xxxx_xx_xx_add_columns_to_todo_items_table', 1),
(11, '2025_03_19_043649_add_status_to_todo_items_table', 2),
(12, 'xxxx_xx_xx_xxxxxx_add_status_to_todo_items_table', 2),
(13, '2025_03_19_044442_add_progress_and_color_to_todo_items_table', 3),
(14, 'xxxx_xx_xx_xxxxxx_add_progress_and_color_to_todo_items_table', 3),
(15, '2025_03_19_053014_add_progress_and_color_to_bug_reports_table', 4),
(16, 'xxxx_xx_xx_add_progress_and_color_to_bug_reports_table', 4);

-- --------------------------------------------------------

--
-- Structure de la table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Accueil', 'Bienvenue sur notre site web...', 'home', '2025-03-19 02:00:22', '2025-03-19 02:00:22'),

(3, 'Conditions d\'utilisation', 'En utilisant ce site...', 'terms', '2025-03-19 02:00:22', '2025-03-19 02:00:22'),
(4, 'Politique de confidentialité', 'Protection de vos données...', 'privacy', '2025-03-19 02:00:22', '2025-03-19 02:00:22');

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('2w8QWNwKI7xDNGPpLNuJpGMQ0j4S2tqz0ZBf6vt4', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNlBrTjg1OWlKWTUwUjlTTGhydHhQUzV6a1F0bUM2MXpEUTRHSHJzVSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1742353753),
('I2YMDXMF1uORurd4EFtD8wxSEZK9xoUampSC8MZ4', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYW9kZkFBY2h5Mkk5N25EOFFzY201S3JiMWMydGc3dmNncVZpTldnOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1742353667),
('o6mgADkhRydzykllxkd8Y95xyMIsuM6EjoSLLBrc', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36 OPR/117.0.0.0 (Edition std-1)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNFRRR0pvdlozYlFsMWM1bTV2OXZMQmZZd0VHd3hZRHBwdUZMVkV3ZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9idWctcmVwb3J0Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1742362310);

-- --------------------------------------------------------

--
-- Structure de la table `todo_items`
--

CREATE TABLE `todo_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `progress` int(11) NOT NULL DEFAULT 0,
  `color` varchar(255) NOT NULL DEFAULT 'primary',
  `expected_date` date DEFAULT NULL,
  `priority` int(11) NOT NULL DEFAULT 0,
  `completion_percentage` int(11) NOT NULL DEFAULT 0,
  `estimated_completion_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `todo_items`
--

INSERT INTO `todo_items` (`id`, `title`, `description`, `status`, `progress`, `color`, `expected_date`, `priority`, `completion_percentage`, `estimated_completion_date`, `created_at`, `updated_at`) VALUES
(1, 'Système de messagerie instantanée', 'Implémentation d\'un système de chat en temps réel entre les utilisateurs de la plateforme.', 'in_progress', 5, 'info', '2025-04-03', 5, 75, '2025-04-03', '2025-03-19 02:06:14', '2025-03-19 03:49:58'),
(2, 'Interface mobile responsive', 'Adaptation complète de l\'interface pour les appareils mobiles et tablettes.', 'completed', 100, 'danger', '2025-03-24', 4, 90, '2025-03-24', '2025-03-19 02:06:14', '2025-03-19 03:46:03'),
(3, 'Système de notifications', 'Mise en place d\'un système de notifications pour informer les utilisateurs des mises à jour et des événements importants.', 'pending', 0, 'primary', NULL, 3, 30, '2025-04-18', '2025-03-19 02:06:14', '2025-03-19 02:06:14'),
(4, 'Intégration des paiements en ligne', 'Ajout de la possibilité d\'effectuer des paiements en ligne via Stripe et PayPal.', 'pending', 0, 'primary', NULL, 4, 15, '2025-05-03', '2025-03-19 02:06:14', '2025-03-19 02:06:14'),
(5, 'Système d\'authentification avancé', 'Mise en place de l\'authentification à deux facteurs et de la connexion via réseaux sociaux.', 'pending', 0, 'primary', NULL, 5, 60, '2025-04-08', '2025-03-19 02:06:14', '2025-03-19 02:06:14'),
(16, 'v12.0.5', '<p class=\"\"><a href=\"https://infyom.com/docs/infy-vcards/releases.html#enhancements-1\" style=\"\">​</a><span style=\"background-color: var(--bs-card-bg); font-size: var(--bs-body-font-size); text-align: var(--bs-body-text-align);\">Ajouter la langue vietnamienne<br></span><span style=\"background-color: var(--bs-card-bg); font-size: var(--bs-body-font-size); text-align: var(--bs-body-text-align);\">Refactoriser l\'interface utilisateur de gestion des achats de plans d\'abonnement</span></p>', 'in_progress', 70, 'success', '2025-05-10', 0, 0, NULL, '2025-03-19 03:45:17', '2025-03-19 03:58:57'),
(17, 'v12.1.9', '<ul><li>Correction&nbsp;: le graphique des revenus ne fonctionne pas dans le super administrateur</li><li>Correction&nbsp;: le bouton d\'affichage du mot de passe de la page de connexion ne fonctionne pas</li><li>Correction&nbsp;: problème de focus dans tous les modèles vCard</li><li>Correction&nbsp;: problème de défilement sur la page produit vCard&nbsp;5</li></ul>', 'in_progress', 5, 'danger', '2025-03-25', 0, 0, NULL, '2025-03-19 03:54:37', '2025-03-19 04:00:18'),
(18, 'v13', '<ul><li>Refactoriser l\'interface utilisateur du modèle de bannière dans les modèles vCard</li><li>Augmenter la limite d\'alias d\'URL et la taille de l\'image de l\'icône de service</li><li>Ajouter un code couleur hexadécimal dans le modèle vCard dynamique</li><li>Limite de caractères de la description du service de refactorisation et limite de caractères visibles dans les modèles vCard</li><li>Ajouter un bouton radio avec le logo NFC requis dans le côté Super Admin</li></ul>', 'pending', 0, 'primary', NULL, 0, 0, NULL, '2025-03-19 03:56:54', '2025-03-19 03:56:54');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@admin.com', NULL, '$2y$12$tTaYT3f0pR2fc1adurj5eu/4vGKBp1j6lqxoYawiOOTejdJ.hQ/r6', NULL, '2025-03-19 02:00:22', '2025-03-19 02:14:30');

-- --------------------------------------------------------

--
-- Structure de la table `versions`
--

CREATE TABLE `versions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version_number` varchar(255) NOT NULL,
  `release_date` date NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `versions`
--

INSERT INTO `versions` (`id`, `version_number`, `release_date`, `content`, `created_at`, `updated_at`) VALUES
(1, '8.9.5', '2024-06-21', '<h3><strong>Corrections</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#fixes-16\"><strong>​</strong></a></h3><ul><li>Correction du problème de créneau horaire</li><li>Résoudre les problèmes de localisation</li></ul>', '2025-03-19 02:36:14', '2025-03-19 02:54:14'),
(2, '8.9.6', '2024-06-26', '<h3><strong>Corrections</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#fixes-15\"><strong>​</strong></a></h3><ul><li>Résoudre le problème de suppression d\'utilisateur</li><li>Résoudre le problème de mise à jour de la vcard</li><li>Supprimer l\'utilisateur et ajouter l\'API</li></ul>', '2025-03-19 02:47:46', '2025-03-19 02:54:23'),
(3, '8.10.0', '2024-07-01', '<h3><strong>Caractéristiques</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#features-7\"><strong>​</strong></a></h3><ul><li>Ajouter un lien social sur Front CMS au site Web</li><li>L\'e-mail existe déjà Validation en direct sur la page d\'inscription</li><li>Ajouter un module de langue</li><li>Ajouter la prise en charge multi-images NFC</li><li>Ajouter un nouveau modèle&nbsp;: Services de bricoleur</li><li>Ajouter un nouveau modèle vCard de service de transport/taxi</li><li>Ajouter un nouveau modèle de vCard de mariage</li><li>Ajouter Comment ça marche&nbsp;? Ajouter une affiliation dans Super Admin et Admin</li></ul><h3><strong>Améliorations</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#enhancements-6\"><strong>​</strong></a></h3><ul><li>Ajout de la prise en charge de la langue italienne</li><li>Ajout de Livewire datatable-3</li><li>Ajouter une section Vcard utilisateur à une nouvelle interface utilisateur</li><li>Modifications de la section Service dans All Vcard</li><li>Ajouter une nouvelle interface utilisateur à la section Feed Instagram</li><li>Ajouter une nouvelle interface utilisateur à la page Plan</li><li>Modifier l\'interface utilisateur de la bannière de support dans toutes les Vcards</li><li>Modification de l\'interface utilisateur des paramètres utilisateur</li></ul><h3><strong>Corrections</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#fixes-14\"><strong>​</strong></a></h3><ul><li>Correction du problème de support vidéo qui ne fonctionne pas sur les appareils Apple</li><li>Lorsque le statut du plan est désactivé à ce moment-là, l\'utilisateur affiche tous les plans</li></ul>', '2025-03-19 02:48:41', '2025-03-19 02:54:36'),
(4, '8.11.0', '2024-07-26', '<h3><strong>Caractéristiques</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#features-6\"><strong>​</strong></a></h3><ul><li>Créer un plan personnalisé, ajouter une option vCard et un prix</li></ul><h3><strong>Améliorations</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#enhancements-5\"><strong>​</strong></a></h3><ul><li>Ajout du support RTL pour la langue arabe</li></ul><h3><strong>Corrections</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#fixes-10\"><strong>​</strong></a></h3><ul><li>Résoudre le problème de localisation.</li><li>Résoudre le problème de téléchargement du QRcode Vcard.</li><li>Résoudre le problème de mise à jour du plan par défaut</li><li>Résoudre le problème de mise à jour de la langue par défaut</li><li>Correction du problème de saut de page Vcard 2 et 22.</li><li>Correction&nbsp;: problème d\'interface utilisateur de la page de connexion et d\'inscription.</li><li>Correction du bouton de copie de rendez-vous qui ne fonctionne pas</li><li>Correction&nbsp;: correction du problème de pagination de la Vcard</li></ul>', '2025-03-19 02:55:03', '2025-03-19 02:55:09'),
(5, '9.0.0', '2024-09-26', '<h3><strong>Caractéristiques</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#features-5\"><strong>​</strong></a></h3><ul><li>Ajout de la prise en charge de la section Liens personnalisés dans vCard</li><li>Ajouter une case à cocher Demande dans les paramètres utilisateur et l\'option Ajouter une pièce jointe dans vCard</li><li>Assistance du blog sur la page d\'accueil</li><li>Prise en charge de la configuration des informations d\'identification de messagerie dans les paramètres de super administrateur</li><li>Créer un arrière-plan virtuel personnalisé</li><li>Prise en charge de la langue hindi</li></ul><h3><strong>Améliorations</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#enhancements-4\"><strong>​</strong></a></h3><ul><li>Option de partage Snapchat dans vCard</li></ul><h3><strong>Corrections</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#fixes-7\"><strong>​</strong></a></h3><ul><li>Résoudre le problème de localisation</li></ul>', '2025-03-19 02:55:38', '2025-03-19 02:55:44'),
(6, '10.0.0', '2024-10-26', '<h3 id=\"features-4\" tabindex=\"-1\" style=\"margin: 32px 0px 0px; line-height: 28px; font-size: 20px; font-weight: 600; overflow-wrap: break-word; position: relative; outline: none; letter-spacing: -0.01em; color: rgb(60, 60, 67); font-family: Inter, ui-sans-serif, system-ui, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;;\"><font style=\"vertical-align: inherit;\">Caractéristiques</font><a class=\"header-anchor\" href=\"https://infyom.com/docs/infy-vcards/releases.html#features-4\" aria-label=\"Lien permanent vers «&nbsp;Fonctionnalités&nbsp;»\" style=\"touch-action: manipulation; text-decoration-line: none; font-weight: 500; text-underline-offset: 2px; transition: color 0.25s, opacity 0.25s; position: absolute; top: 0px; left: 0px; margin-left: -0.87em; user-select: none; opacity: 0;\"><font style=\"vertical-align: inherit;\">​</font></a></h3><ul style=\"list-style-position: initial; list-style-image: initial; margin: 16px 0px; padding: 0px 0px 0px 1.25rem; color: rgb(60, 60, 67); font-family: Inter, ui-sans-serif, system-ui, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;;\"><li style=\"overflow-wrap: break-word;\"><font style=\"vertical-align: inherit;\">Ajouter un nouveau modèle&nbsp;: architecte d\'intérieur</font></li></ul><h3 id=\"enhancements-3\" tabindex=\"-1\" style=\"margin: 32px 0px 0px; line-height: 28px; font-size: 20px; font-weight: 600; overflow-wrap: break-word; position: relative; outline: none; letter-spacing: -0.01em; color: rgb(60, 60, 67); font-family: Inter, ui-sans-serif, system-ui, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;;\"><font style=\"vertical-align: inherit;\">Améliorations</font><a class=\"header-anchor\" href=\"https://infyom.com/docs/infy-vcards/releases.html#enhancements-3\" aria-label=\"Lien permanent vers «&nbsp;Améliorations&nbsp;»\" style=\"touch-action: manipulation; text-decoration-line: none; font-weight: 500; text-underline-offset: 2px; transition: color 0.25s, opacity 0.25s; position: absolute; top: 0px; left: 0px; margin-left: -0.87em; user-select: none; opacity: 0;\"><font style=\"vertical-align: inherit;\">​</font></a></h3><ul style=\"list-style-position: initial; list-style-image: initial; margin: 16px 0px; padding: 0px 0px 0px 1.25rem; color: rgb(60, 60, 67); font-family: Inter, ui-sans-serif, system-ui, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;;\"><li style=\"overflow-wrap: break-word;\"><font style=\"vertical-align: inherit;\">Ajouter un bouton Gérer l\'abonnement dans la barre latérale de l\'utilisateur</font></li><li style=\"overflow-wrap: break-word; margin-top: 8px;\"><font style=\"vertical-align: inherit;\">Ajouter un pays avec des drapeaux dans la page de connexion vCards</font></li><li style=\"overflow-wrap: break-word; margin-top: 8px;\"><font style=\"vertical-align: inherit;\">Ajouter des coordonnées bancaires dans une section d\'affiliation</font></li><li style=\"overflow-wrap: break-word; margin-top: 8px;\"><font style=\"vertical-align: inherit;\">Ajout de la prise en charge des balises d\'intégration de la carte de localisation dans la section des détails de base de vCard</font></li></ul><h3 id=\"fixes-6\" tabindex=\"-1\" style=\"margin: 32px 0px 0px; line-height: 28px; font-size: 20px; font-weight: 600; overflow-wrap: break-word; position: relative; outline: none; letter-spacing: -0.01em; color: rgb(60, 60, 67); font-family: Inter, ui-sans-serif, system-ui, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;;\"><font style=\"vertical-align: inherit;\">Corrections</font><a class=\"header-anchor\" href=\"https://infyom.com/docs/infy-vcards/releases.html#fixes-6\" aria-label=\"Lien permanent vers «&nbsp;Corrections&nbsp;»\" style=\"touch-action: manipulation; text-decoration-line: none; font-weight: 500; text-underline-offset: 2px; transition: color 0.25s, opacity 0.25s; position: absolute; top: 0px; left: 0px; margin-left: -0.87em; user-select: none; opacity: 0;\"><font style=\"vertical-align: inherit;\">​</font></a></h3><ul style=\"list-style-position: initial; list-style-image: initial; margin: 16px 0px; padding: 0px 0px 0px 1.25rem; color: rgb(60, 60, 67); font-family: Inter, ui-sans-serif, system-ui, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;;\"><li style=\"overflow-wrap: break-word;\"><font style=\"vertical-align: inherit;\">Correction&nbsp;: toutes les images de notre section de service vCard s\'affichent correctement sur mobile</font></li><li style=\"overflow-wrap: break-word; margin-top: 8px;\"><font style=\"vertical-align: inherit;\">Correction&nbsp;: modification du montant de la commande NFC</font></li><li style=\"overflow-wrap: break-word; margin-top: 8px;\"><font style=\"vertical-align: inherit;\">Correction&nbsp;: problème de localisation</font></li></ul>', '2025-03-19 03:06:02', '2025-03-19 03:06:02'),
(7, '11.0.0', '2025-01-05', '<h3><strong>Caractéristiques</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#features-3\"><strong>​</strong></a></h3><ul><li>Ajouter un nouveau modèle&nbsp;: Musicien/Marque</li><li>Ajouter la vCard d\'un utilisateur est cloné vers un autre utilisateur dans le super administrateur</li></ul><h3><strong>Améliorations</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#enhancements-2\"><strong>​</strong></a></h3><ul><li>Ajouter un champ TimeZone dans le côté super administrateur</li><li>Ajouter un bouton pour supprimer après la valeur du point dans le super administrateur</li><li>Masquer la prise en charge des bannières dans la section Bannière de la vCard</li><li>Ajouter : Lorsque le jour du sentier est ajouté, il s\'agit alors d\'un travail de planification du sentier</li><li>Ajouter : Demander des détails avant de télécharger Ajouter un contact dans la vCard</li><li>Rendez-vous 1 jour de validation supprimer et ajouter 2 jours de validation</li><li>Envoyer un courrier de commande de produit au client et à l\'utilisateur</li></ul><h3><strong>Corrections</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#fixes-5\"><strong>​</strong></a></h3><ul><li>Correction&nbsp;: convertir tous les numéros de téléphone mobile de droite à gauche en chiffres</li><li>Correction&nbsp;: les caractères arabes et autres langues ne s\'affichent pas correctement en arrière-plan virtuel</li><li>Correction&nbsp;: problème de montant de commande du module NFC</li><li>Correction&nbsp;: problème de localisation</li></ul>', '2025-03-19 03:06:22', '2025-03-19 03:06:22'),
(8, '11.0.1', '2025-01-15', '<h3>Corrections</h3><ul><li>Correction : supprimer le turbo Livewire</li><li>Refactoriser l\'interface utilisateur du module complémentaire</li></ul>', '2025-03-19 03:07:46', '2025-03-19 03:17:02'),
(9, 'v12.0.0', '2025-02-15', '<h3><strong>Caractéristiques</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#features-2\"><strong>​</strong></a></h3><ul><li>Ajouter un nouveau modèle : Photographe</li><li>Ajouter un nouveau modèle : Agents immobiliers</li></ul><h3><strong>Améliorations</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#enhancements-1\"><strong>​</strong></a></h3><ul><li>Ajouter la langue vietnamienne</li><li>Refactoriser l\'interface utilisateur de gestion des achats de plans d\'abonnement</li><li>Ajouter un code de coupon utilisé par la liste des utilisateurs affichés dans la section Coupon</li><li>Ajouter l\'éditeur Quill dans la section FAQ</li><li>Refactoriser l\'interface modale de la newsletter dans les modèles vCard</li><li>Déclencher l\'ajout d\'une PWA à l\'écran d\'accueil lors de l\'ouverture d\'une vCard</li><li>Ajouter une liste déroulante de langue dans le tableau de bord</li><li>Refactoriser l\'interface utilisateur du modèle de bannière dans les modèles vCard</li><li>Augmenter la limite d\'alias d\'URL et la taille de l\'image de l\'icône de service</li><li>Ajouter un code couleur hexadécimal dans le modèle vCard dynamique</li><li>Limite de caractères de la description du service de refactorisation et limite de caractères visibles dans les modèles vCard</li><li>Ajouter un bouton radio avec le logo NFC requis dans le côté Super Admin</li></ul><h3><strong>Corrections</strong><a href=\"https://infyom.com/docs/infy-vcards/releases.html#fixes-2\"><strong>​</strong></a></h3><ul><li>Correction : le graphique des revenus ne fonctionne pas dans le super administrateur</li><li>Correction : le bouton d\'affichage du mot de passe de la page de connexion ne fonctionne pas</li><li>Correction : problème de focus dans tous les modèles vCard</li><li>Correction : problème de défilement sur la page produit vCard 5</li><li>Correction : le bouton radio « Ajouter au contact » ne fonctionne pas correctement</li><li>Correction : lorsque le code de pays change, il ne s\'affiche pas correctement</li><li>Correction : problème d\'interface utilisateur vCard 11</li><li>Correction : la validation du champ du logo de commande de carte NFC ne fonctionne pas</li></ul>', '2025-03-19 03:08:06', '2025-03-19 03:17:09');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `bug_reports`
--
ALTER TABLE `bug_reports`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `todo_items`
--
ALTER TABLE `todo_items`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Index pour la table `versions`
--
ALTER TABLE `versions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `bug_reports`
--
ALTER TABLE `bug_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `todo_items`
--
ALTER TABLE `todo_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `versions`
--
ALTER TABLE `versions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
