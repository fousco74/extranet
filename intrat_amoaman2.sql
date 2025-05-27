-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 20 jan. 2025 à 11:06
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `intrat_amoaman`
--

-- --------------------------------------------------------

--
-- Structure de la table `applications`
--

DROP TABLE IF EXISTS `applications`;
CREATE TABLE IF NOT EXISTS `applications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `logo` varchar(191) NOT NULL,
  `description` text,
  `link` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `applications`
--

INSERT INTO `applications` (`id`, `name`, `logo`, `description`, `link`, `created_at`, `updated_at`) VALUES
(1, 'GLPI', 'logos/0bMAvSlTPEPZPHvSsYTSpEUbso2nsov2zeHUPU45.png', 's: us', 'https://tma.amoaman.net/', '2024-12-02 14:34:25', '2025-01-13 16:34:40'),
(5, 'wekan', 'logos/Fu244p4rw7sl3hf1pNMWtHriUFkQuO7HfNNW5p6Z.png', 'skhbajàioiu;o;m:d:m', 'https://wekan.amoaman.net/', '2024-12-02 17:09:01', '2024-12-02 17:10:27');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:34:{i:0;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:21:\"créer un utilisateur\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:1;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:22:\"liste des utilisateurs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:2;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:17:\"créer un dossier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:3;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:18:\"liste des dossiers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:4;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:18:\"liste des fichiers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:5;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:18:\"ajouter un fichier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:6;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:25:\"ajouter un lien  onedrive\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:7;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:25:\"liste des liens onedrives\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:8;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:23:\"ajouter une application\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:9;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:22:\"liste des applications\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:10;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:22:\"liste des reservations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:11;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:24:\"envoyer une notification\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:12;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:20:\"modifier utilisateur\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:13;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:21:\"supprimer utilisateur\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:14;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:27:\"liens onedrives utilisateur\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:15;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:17:\"roles utilisateur\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:16;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:16:\"modifier dossier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:17;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:17:\"supprimer dossier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:18;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:16:\"modifier fichier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:19;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:17:\"supprimer fichier\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:20;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:17:\"modifier onedrive\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:21;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:18:\"supprimer onedrive\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:22;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:20:\"modifier application\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:23;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:21:\"supprimer application\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}i:24;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:18:\"detail reservation\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:25;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:21:\"supprimer reservation\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:26;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:17:\"voir utilisateurs\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:27;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:13:\"voir dossiers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:28;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:13:\"voir fichiers\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:29;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:20:\"voir liens onedrives\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:30;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:17:\"voir applications\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:31;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:17:\"voir reservations\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:32;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:18:\"voir Notifications\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:3;}}i:33;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:15:\"voir paramètre\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:2;}}}s:5:\"roles\";a:2:{i:0;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:9:\"direction\";s:1:\"c\";s:3:\"web\";}}}', 1737400369);

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE IF NOT EXISTS `files` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) DEFAULT NULL,
  `file_link` varchar(191) NOT NULL,
  `size` varchar(191) DEFAULT NULL,
  `extention` varchar(191) DEFAULT NULL,
  `folder_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `files_folder_id_foreign` (`folder_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `files`
--

INSERT INTO `files` (`id`, `name`, `file_link`, `size`, `extention`, `folder_id`, `created_at`, `updated_at`) VALUES
(1, 'ARCHITECTURE TECHNIQUE - LOT 1.docx', 'files/48NHWeKp84N2VPvOlW8XDSovl5uf4dYTXXEi9gzi.docx', '1120906', 'docx', 3, '2024-11-25 10:32:30', '2025-01-10 14:11:37'),
(2, 'Fondamentaux_du_Web.pdf', 'files/6WccAtAYKCmYto32j14P6FLzswQSxThL3NLHH54o.pdf', '494362', 'pdf', 1, '2024-11-25 10:32:45', '2024-12-02 12:28:45'),
(3, 'Instapay_ci_payment_api_v0.1_13032024.pdf', 'files/wuPpZ4MvM8Q86SpBIobkhfCnNGVHAgRoacv95mFw.pdf', '182679', 'pdf', 2, '2024-11-25 10:32:57', '2024-11-25 10:32:57'),
(4, 'Architecture-serveur.png', 'files/hKENGWkjYAE0LYzsdrSB2Laj9KrYC7wtHNMLeciN.png', '480799', 'png', 1, '2025-01-11 02:18:20', '2025-01-11 02:18:20');

-- --------------------------------------------------------

--
-- Structure de la table `folders`
--

DROP TABLE IF EXISTS `folders`;
CREATE TABLE IF NOT EXISTS `folders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `folders`
--

INSERT INTO `folders` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Dossier 1', '2024-11-25 10:31:49', '2024-12-09 14:55:37'),
(2, 'DOSSIER 2', '2024-11-25 10:31:57', '2024-11-25 10:31:57'),
(3, 'DOSSIER 3', '2024-11-25 10:32:04', '2024-11-25 10:32:04');

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_11_20_095420_create_one_drive_links_table', 1),
(5, '2024_11_20_095421_create_files_table', 1),
(6, '2024_11_20_095422_create_folders_table', 1),
(7, '2024_11_27_123247_create_reservations_table', 2),
(8, '2024_12_02_131045_create_applications_table', 3),
(9, '2024_12_06_122006_create_permission_tables', 4),
(10, '2024_12_08_124125_create_notifications_table', 5);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Déchargement des données de la table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(2, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 6),
(3, 'App\\Models\\User', 7),
(3, 'App\\Models\\User', 9);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(191) NOT NULL,
  `notifiable_type` varchar(191) NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Structure de la table `one_drive_links`
--

DROP TABLE IF EXISTS `one_drive_links`;
CREATE TABLE IF NOT EXISTS `one_drive_links` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `link` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `one_drive_links`
--

INSERT INTO `one_drive_links` (`id`, `name`, `link`, `created_at`, `updated_at`) VALUES
(6, 'POLE ERP', 'https://onedrive.live.com/?id=5392990CC5E6BBD0%2120353&cid=5392990CC5E6BBD0', '2025-01-10 17:48:06', '2025-01-10 17:48:06'),
(5, 'COMPTABILITE', 'https://onedrive.live.com/?id=5392990CC5E6BBD0%2120368&cid=5392990CC5E6BBD0', '2025-01-10 17:47:05', '2025-01-10 17:47:05'),
(4, 'ADMINISTRATION PROD SENEGA', 'https://onedrive.live.com/?id=5392990CC5E6BBD0%2120401&cid=5392990CC5E6BBD0', '2025-01-10 17:45:55', '2025-01-10 17:45:55'),
(7, 'FACTURATION', 'https://onedrive.live.com/?id=5392990CC5E6BBD0%2120359&cid=5392990CC5E6BBD0', '2025-01-10 17:48:23', '2025-01-10 17:48:23'),
(8, 'POLE AGENCE WEB', 'https://onedrive.live.com/?id=5392990CC5E6BBD0%2120360&cid=5392990CC5E6BBD0', '2025-01-10 17:48:46', '2025-01-10 17:48:46'),
(9, 'POLE PROJET', 'https://onedrive.live.com/?id=5392990CC5E6BBD0%2120370&cid=5392990CC5E6BBD0', '2025-01-10 17:49:09', '2025-01-10 17:49:09'),
(10, 'RH', 'https://onedrive.live.com/?id=5392990CC5E6BBD0%2120357&cid=5392990CC5E6BBD0', '2025-01-10 17:49:28', '2025-01-10 17:49:28'),
(11, 'SOURCING', 'https://onedrive.live.com/?id=5392990CC5E6BBD0%2120358&cid=5392990CC5E6BBD0', '2025-01-10 17:49:46', '2025-01-10 17:49:46'),
(12, 'SUPPORT IT', 'https://onedrive.live.com/?id=5392990CC5E6BBD0%2120352&cid=5392990CC5E6BBD0', '2025-01-10 17:50:03', '2025-01-10 17:50:03');

-- --------------------------------------------------------

--
-- Structure de la table `one_drive_link_user`
--

DROP TABLE IF EXISTS `one_drive_link_user`;
CREATE TABLE IF NOT EXISTS `one_drive_link_user` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `one_drive_link_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `one_drive_link_user_user_id_foreign` (`user_id`),
  KEY `one_drive_link_user_one_drive_link_id_foreign` (`one_drive_link_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(4, 'créer un utilisateur', 'web', '2024-12-09 10:59:33', '2024-12-09 16:00:16'),
(5, 'liste des utilisateurs', 'web', '2024-12-09 11:06:01', '2024-12-09 11:06:01'),
(6, 'créer un dossier', 'web', '2024-12-09 11:06:36', '2024-12-09 11:06:36'),
(7, 'liste des dossiers', 'web', '2024-12-09 11:06:49', '2024-12-09 11:06:49'),
(9, 'liste des fichiers', 'web', '2024-12-09 11:07:13', '2024-12-09 11:07:13'),
(10, 'ajouter un fichier', 'web', '2024-12-09 11:07:33', '2024-12-09 11:07:33'),
(12, 'ajouter un lien  onedrive', 'web', '2024-12-09 11:09:22', '2025-01-11 01:05:53'),
(13, 'liste des liens onedrives', 'web', '2024-12-09 11:09:34', '2024-12-09 11:13:42'),
(15, 'ajouter une application', 'web', '2024-12-09 11:15:41', '2024-12-09 11:15:41'),
(16, 'liste des applications', 'web', '2024-12-09 11:15:52', '2024-12-09 11:15:52'),
(18, 'liste des reservations', 'web', '2024-12-09 11:16:28', '2024-12-09 11:16:28'),
(19, 'envoyer une notification', 'web', '2024-12-09 11:16:58', '2024-12-09 11:25:42'),
(20, 'modifier utilisateur', 'web', '2024-12-09 11:27:58', '2024-12-09 11:27:58'),
(21, 'supprimer utilisateur', 'web', '2024-12-09 11:28:09', '2024-12-09 11:28:09'),
(22, 'liens onedrives utilisateur', 'web', '2024-12-09 11:28:37', '2024-12-09 11:28:37'),
(23, 'roles utilisateur', 'web', '2024-12-09 11:29:04', '2024-12-09 11:29:04'),
(24, 'modifier dossier', 'web', '2024-12-09 11:30:10', '2024-12-09 11:30:10'),
(25, 'supprimer dossier', 'web', '2024-12-09 11:31:29', '2024-12-09 11:31:29'),
(26, 'modifier fichier', 'web', '2024-12-09 11:32:08', '2024-12-09 11:32:08'),
(27, 'supprimer fichier', 'web', '2024-12-09 11:32:20', '2024-12-09 11:32:20'),
(28, 'modifier onedrive', 'web', '2024-12-09 11:34:24', '2024-12-09 11:34:24'),
(29, 'supprimer onedrive', 'web', '2024-12-09 11:34:34', '2024-12-09 11:34:34'),
(30, 'modifier application', 'web', '2024-12-09 11:37:09', '2024-12-09 11:37:09'),
(31, 'supprimer application', 'web', '2024-12-09 11:37:23', '2024-12-09 11:37:23'),
(32, 'detail reservation', 'web', '2024-12-09 11:39:04', '2024-12-09 11:39:04'),
(33, 'supprimer reservation', 'web', '2024-12-09 11:39:16', '2024-12-09 11:39:16'),
(35, 'voir utilisateurs', 'web', '2024-12-12 10:26:04', '2024-12-12 10:26:04'),
(36, 'voir dossiers', 'web', '2024-12-12 10:26:16', '2024-12-12 10:26:16'),
(37, 'voir fichiers', 'web', '2024-12-12 10:26:29', '2024-12-12 10:26:29'),
(38, 'voir liens onedrives', 'web', '2024-12-12 10:26:55', '2024-12-12 10:26:55'),
(39, 'voir applications', 'web', '2024-12-12 10:27:04', '2024-12-12 10:27:04'),
(40, 'voir reservations', 'web', '2024-12-12 10:27:18', '2024-12-12 10:27:18'),
(41, 'voir Notifications', 'web', '2024-12-12 10:27:28', '2024-12-12 10:27:28'),
(42, 'voir paramètre', 'web', '2024-12-12 10:27:46', '2024-12-12 10:27:46');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `day` varchar(191) NOT NULL,
  `month` varchar(191) NOT NULL,
  `year` varchar(191) NOT NULL,
  `startClock` varchar(191) NOT NULL,
  `endClock` varchar(191) NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `title` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `monthNumber` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `dayName` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `reservations_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(2, 'admin', 'web', '2024-12-08 11:12:25', '2024-12-08 11:39:36'),
(3, 'direction', 'web', '2024-12-08 11:13:05', '2024-12-08 11:13:05'),
(4, 'utilisateur', 'web', '2024-12-08 11:13:49', '2024-12-08 11:13:49');

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

--
-- Déchargement des données de la table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(4, 2),
(5, 2),
(6, 2),
(7, 2),
(9, 2),
(10, 2),
(12, 2),
(13, 2),
(15, 2),
(16, 2),
(18, 2),
(19, 2),
(20, 2),
(21, 2),
(22, 2),
(23, 2),
(24, 2),
(25, 2),
(26, 2),
(27, 2),
(28, 2),
(29, 2),
(30, 2),
(31, 2),
(32, 2),
(33, 2),
(35, 2),
(36, 2),
(37, 2),
(38, 2),
(39, 2),
(40, 2),
(41, 2),
(42, 2),
(5, 3),
(7, 3),
(9, 3),
(10, 3),
(12, 3),
(13, 3),
(16, 3),
(18, 3),
(19, 3),
(22, 3),
(23, 3),
(26, 3),
(27, 3),
(28, 3),
(29, 3),
(32, 3),
(33, 3),
(35, 3),
(36, 3),
(37, 3),
(38, 3),
(39, 3),
(40, 3),
(41, 3);

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('vAUDRaGSXdhGZgPqnae7S8m3llAq2CBu0kec4yrf', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoieWc4V3FWNXY0TXhOY2lrejJOZjQ2U3hJQVh5WEdWRWpUd3NreElYeCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=', 1737371161);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) NOT NULL,
  `last_name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `team` varchar(191) NOT NULL,
  `poste` varchar(191) NOT NULL,
  `phone_number` varchar(191) DEFAULT NULL,
  `profile_link` varchar(191) DEFAULT NULL,
  `linkedin_link` varchar(191) DEFAULT NULL,
  `ordre_team` varchar(191) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `team`, `poste`, `phone_number`, `profile_link`, `linkedin_link`, `ordre_team`, `remember_token`, `created_at`, `updated_at`) VALUES
(6, 'Ousman', 'SOW A.', 'asow@amoaman.com', NULL, '$2y$12$tL3gtluCswzff7a1Ta256.NHiWAF7WIS6oxxHBN9uMwrxIB9OWGCC', 'interne', 'Directeur Associé', '0574179718', NULL, NULL, '1', NULL, '2024-11-25 11:51:16', '2025-01-10 13:51:17'),
(5, 'Fousseni', 'KONE', 'fkone@amoaman.com', NULL, '$2y$12$UHDLFUpXa7xBKDs5hvBiKuZecHyUfZaeoLnesyG0onkRCEsi6/FxK', 'interne', 'Developeur Backend', '0574179718', 'profile/JlJTecxuiaSvIXj8RZPJV9GE0mK0AQZv9FAug5oN.png', NULL, '15', NULL, '2024-11-25 11:24:05', '2025-01-16 17:45:54'),
(7, 'Amoakon', 'DIHYÉ', 'damoakon@amoaman.com', NULL, '$2y$12$v2QZIufSsaB4q1BvBR./RuEYiWG6qhz8H69GqM..tqZiijVt3fzkG', 'interne', 'Fondateur', '0788652585', 'profile/IyOTouqqFgPpSpVQkpbhY6eSj5M4P678P52f0hox.png', NULL, '2', NULL, '2024-11-25 11:53:19', '2025-01-10 15:38:36'),
(8, 'Mamadou', 'SOW', 'msow@amoaman.com', NULL, '$2y$12$7RBmZ8ZM6jF.6F4jKbxMA.OhIWnSGHMj1ybCt4K/1V4WucsPIACVK', 'interne', 'Directeur Associé', NULL, NULL, NULL, '3', NULL, '2024-11-25 11:57:42', '2024-11-25 11:57:42'),
(9, 'Natacha', 'KAKOU', 'nkakou@amoaman.com', NULL, '$2y$12$rxtfBVjTTlcH3adOOdZJBePF4WnflxeyHdJraRrMIaXvuCiTMNrDW', 'interne', 'Resp. Ressources Humaines', '0707326644', 'profile/zz0jwxYNnhfilpn2R9gguEI3B2BOdHQe2VNZXBdr.png', NULL, '4', NULL, '2024-11-25 11:59:00', '2024-11-25 11:59:00'),
(10, 'Hariette', 'GHON-TAY', 'hghariette@amoaman.com', NULL, '$2y$12$0rFhbLkazvs9yoKZ4vSGie3hfM3l7fmSJN7ysV2sMpKg.5ITwQr6W', 'interne', 'Talent Acquisition Specialist', '0711211104', 'profile/CHDTBVMZjIVaJRA8E4nj7WpvoRJaFYKocgd7TXfq.png', NULL, '5', NULL, '2024-11-25 12:00:36', '2024-11-25 12:00:36'),
(11, 'Seraïa', 'ANOMA', 'eanoma@amoaman.com', NULL, '$2y$12$qrDvFcfxwCk0ncNOmsTQrusLqZI1crSRoL18uJIRYBLbgJ7BoSuey', 'interne', 'Chargé d\'Ac', '0141916817', 'profile/DEInJDhVAoxWU4ciaO8YayBZrmc1LN5pEXecJeTS.png', NULL, '6', NULL, '2024-11-25 12:02:31', '2024-11-25 12:02:31'),
(12, 'Solange', 'KOUAKOU', 'skouakou@amaoman.com', NULL, '$2y$12$cWVYgMzYBHyxjdj.EuOd0uE0.HI2azo94MWqwJp64Su9grykTe70K', 'interne', 'Resp. Facturation (ADV)', '0574887280', 'profile/F61wMQNc3Xj2vyybggVurydAyB7BhaSjyIKoBFjj.png', NULL, '7', NULL, '2024-11-25 12:04:06', '2024-11-25 12:04:06'),
(13, 'Michel', 'KOBRI', 'mkobri@amoaman.com', NULL, '$2y$12$1j.biADvdxZl2msY7VIobuKhfNK4VtpQdhVIi6kP13x6y7qx95MQW', 'interne', 'Resp. Comptable & Fiscal', '0758561358', 'profile/mf23faU7Cn2jFN43Z1ig3FRBdhg9CMfcogxUhMoy.png', NULL, '8', NULL, '2024-11-25 12:06:09', '2024-11-25 12:06:09'),
(14, 'Nouho', 'DIABAGATE', 'ndiabagate@amoaman.com', NULL, '$2y$12$n1Loy5ThdaMFCGZPm3xNx.iZixOfJyfWxb38cx1yn0b.7MJTNNSBi', 'interne', 'Assistant Comptable', '0102390244', 'profile/T6YdcocZmcWvaAHOnaYa6k0DlLHoQSVeSPAocShv.png', NULL, '9', NULL, '2024-11-25 12:10:05', '2024-11-25 12:10:05'),
(15, 'Karel', 'KONAN', 'kkonan@amoaman.com', NULL, '$2y$12$XjO1gE.dmRKF5myytV/2TOOSRzUc4.Na/WeQ7PvTjWNWMDCOmbx5O', 'interne', 'Assistante cheffe de projet IT', '0759784335', 'profile/NZgh9ovDFVKkjsXDHSTuShKKY4HdTqHxceAoco2d.png', NULL, '10', NULL, '2024-11-25 12:12:44', '2024-11-25 12:12:44'),
(16, 'Boris Dassiji', 'Siewe', 'bsiewe@amoaman.com', NULL, '$2y$12$47YZ0a5kw16vdZMVpNdI7.noi2D8kYQ4DVDo8HYWm6l8cbT.iOY2q', 'interne', 'Designer UI', '237 699 621 132', 'profile/kC1In1TcNIcFMhB99HTab1ypLUdCXUAjWAG6RhZm.png', NULL, '11', NULL, '2024-11-25 12:15:23', '2024-11-25 12:15:23'),
(17, 'Laurie-anne', 'GRANT', 'lgrant@amoaman.com', NULL, '$2y$12$bAcA9j0TlK4zD5lXvqshqeFFM4FXuGYoiaPqKEHTpcUO1axJCFRce', 'interne', 'UX/UI Designer', '0707182904', 'profile/pWN6jDT9LA5AqDlAjfxuvXeXdjqu09mN3d7tVADn.png', NULL, '12', NULL, '2024-11-25 12:17:16', '2024-11-27 10:33:49'),
(18, 'Francklin', 'KONAN', 'fkonan@amoaman.com', NULL, '$2y$12$8ElPC.QFQ5gNUL8ORiIdp.L2kPGy0CfOFHSpBcwFqxrvOYd76ZqIu', 'interne', 'Admin Réseaux & Sécurité', '0709813123', 'profile/aCdnR44ZV4Y1ZuXAcTMm81tp6L6csOVLduFZXbhR.png', NULL, '13', NULL, '2024-11-25 12:18:55', '2024-11-25 12:18:55'),
(19, 'Ange Cedrick', 'N\'ZI', 'acnzi2@amoaman.com', NULL, '$2y$12$25Be5QxtuNkLchF0tgZuOOgzuCpXP//aDVXKB/l7Viy8fdI4EGsRe', 'interne', 'Consultant DataViz', '0173304687', 'profile/4DQfy7zFo1RVXiNJkry37ZoVnMtEYX6A05CVNxTw.png', NULL, '14', NULL, '2024-11-25 12:20:32', '2024-11-25 12:20:32'),
(20, 'Micheal', 'ADOPO', 'Jadopo@amoaman.com', NULL, '$2y$12$akfww6L4GEgObkHPolToPebqMtVwUWw4OyUhhoFl/TZHrxzDaBpdq', 'interne', 'Consultant ERP', '0153858288', 'profile/XrScSKJIi0V88nB0lPLTwh6jsdQW0VChgfGelRfR.png', NULL, '16', NULL, '2024-11-25 12:22:15', '2024-11-25 12:22:15');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
