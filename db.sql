-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for manajemen_masjid
CREATE DATABASE IF NOT EXISTS `manajemen_masjid` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `manajemen_masjid`;

-- Dumping structure for table manajemen_masjid.activities
CREATE TABLE IF NOT EXISTS `activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `tanggal` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.activities: ~0 rows (approximately)
DELETE FROM `activities`;

-- Dumping structure for table manajemen_masjid.activity_log
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`),
  CONSTRAINT `activity_log_chk_1` CHECK (json_valid(`properties`))
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.activity_log: ~53 rows (approximately)
DELETE FROM `activity_log`;
INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
	(1, 'default', 'created', 'App\\Models\\User', 'created', 1, NULL, NULL, '{"attributes":{"name":"Super Administrator","email":"superadmin@masjid.com","username":"superadmin","phone":"081234567890","address":null}}', NULL, '2025-11-03 06:37:38', '2025-11-03 06:37:38'),
	(2, 'default', 'created', 'App\\Models\\User', 'created', 2, NULL, NULL, '{"attributes":{"name":"Admin Jamaah","email":"admin.jamaah@masjid.com","username":"admin_jamaah","phone":"081219339225","address":null}}', NULL, '2025-11-03 06:37:38', '2025-11-03 06:37:38'),
	(3, 'default', 'created', 'App\\Models\\User', 'created', 3, NULL, NULL, '{"attributes":{"name":"Admin Keuangan","email":"admin.keuangan@masjid.com","username":"admin_keuangan","phone":"081240936444","address":null}}', NULL, '2025-11-03 06:37:39', '2025-11-03 06:37:39'),
	(4, 'default', 'created', 'App\\Models\\User', 'created', 4, NULL, NULL, '{"attributes":{"name":"Admin Kegiatan","email":"admin.kegiatan@masjid.com","username":"admin_kegiatan","phone":"081236952701","address":null}}', NULL, '2025-11-03 06:37:39', '2025-11-03 06:37:39'),
	(5, 'default', 'created', 'App\\Models\\User', 'created', 5, NULL, NULL, '{"attributes":{"name":"Admin ZIS","email":"admin.zis@masjid.com","username":"admin_zis","phone":"081271663378","address":null}}', NULL, '2025-11-03 06:37:40', '2025-11-03 06:37:40'),
	(6, 'default', 'created', 'App\\Models\\User', 'created', 6, NULL, NULL, '{"attributes":{"name":"Admin Kurban","email":"admin.kurban@masjid.com","username":"admin_kurban","phone":"081253840261","address":null}}', NULL, '2025-11-03 06:37:40', '2025-11-03 06:37:40'),
	(7, 'default', 'created', 'App\\Models\\User', 'created', 7, NULL, NULL, '{"attributes":{"name":"Admin Inventaris","email":"admin.inventaris@masjid.com","username":"admin_inventaris","phone":"081295005128","address":null}}', NULL, '2025-11-03 06:37:40', '2025-11-03 06:37:40'),
	(8, 'default', 'created', 'App\\Models\\User', 'created', 8, NULL, NULL, '{"attributes":{"name":"Admin Takmir","email":"admin.takmir@masjid.com","username":"admin_takmir","phone":"081270630958","address":null}}', NULL, '2025-11-03 06:37:41', '2025-11-03 06:37:41'),
	(9, 'default', 'created', 'App\\Models\\User', 'created', 9, NULL, NULL, '{"attributes":{"name":"Admin Informasi","email":"admin.informasi@masjid.com","username":"admin_informasi","phone":"081219291269","address":null}}', NULL, '2025-11-03 06:37:41', '2025-11-03 06:37:41'),
	(10, 'default', 'created', 'App\\Models\\User', 'created', 10, NULL, NULL, '{"attributes":{"name":"Admin Laporan","email":"admin.laporan@masjid.com","username":"admin_laporan","phone":"081245632519","address":null}}', NULL, '2025-11-03 06:37:41', '2025-11-03 06:37:41'),
	(11, 'default', 'created', 'App\\Models\\User', 'created', 11, NULL, NULL, '{"attributes":{"name":"Jamaah User 1","email":"jamaah1@example.com","username":"jamaah1","phone":"081343147095","address":"Alamat Jamaah 1"}}', NULL, '2025-11-03 06:37:41', '2025-11-03 06:37:41'),
	(12, 'default', 'created', 'App\\Models\\User', 'created', 12, NULL, NULL, '{"attributes":{"name":"Jamaah User 2","email":"jamaah2@example.com","username":"jamaah2","phone":"081391236580","address":"Alamat Jamaah 2"}}', NULL, '2025-11-03 06:37:42', '2025-11-03 06:37:42'),
	(13, 'default', 'created', 'App\\Models\\User', 'created', 13, NULL, NULL, '{"attributes":{"name":"Jamaah User 3","email":"jamaah3@example.com","username":"jamaah3","phone":"081362356932","address":"Alamat Jamaah 3"}}', NULL, '2025-11-03 06:37:42', '2025-11-03 06:37:42'),
	(14, 'default', 'created', 'App\\Models\\User', 'created', 14, NULL, NULL, '{"attributes":{"name":"Jamaah User 4","email":"jamaah4@example.com","username":"jamaah4","phone":"081390153906","address":"Alamat Jamaah 4"}}', NULL, '2025-11-03 06:37:42', '2025-11-03 06:37:42'),
	(15, 'default', 'created', 'App\\Models\\User', 'created', 15, NULL, NULL, '{"attributes":{"name":"Jamaah User 5","email":"jamaah5@example.com","username":"jamaah5","phone":"081365542759","address":"Alamat Jamaah 5"}}', NULL, '2025-11-03 06:37:43', '2025-11-03 06:37:43'),
	(16, 'default', 'created', 'App\\Models\\User', 'created', 16, NULL, NULL, '{"attributes":{"name":"Pengurus Keuangan","email":"pengurus.keuangan@masjid.com","username":"pengurus_keuangan","phone":"081442120529","address":null}}', NULL, '2025-11-03 06:37:43', '2025-11-03 06:37:43'),
	(17, 'default', 'created', 'App\\Models\\User', 'created', 17, NULL, NULL, '{"attributes":{"name":"Pengurus Kegiatan","email":"pengurus.kegiatan@masjid.com","username":"pengurus_kegiatan","phone":"081474214091","address":null}}', NULL, '2025-11-03 06:37:43', '2025-11-03 06:37:43'),
	(18, 'default', 'created', 'App\\Models\\User', 'created', 18, NULL, NULL, '{"attributes":{"name":"Pengurus Zis","email":"pengurus.zis@masjid.com","username":"pengurus_zis","phone":"081419566265","address":null}}', NULL, '2025-11-03 06:37:43', '2025-11-03 06:37:43'),
	(19, 'default', 'created', 'App\\Models\\User', 'created', 19, NULL, NULL, '{"attributes":{"name":"jamaah aljami","email":"jami@gmail.com","username":"aljami","phone":"089671530757","address":"Jl.ph mustopha"}}', NULL, '2026-01-01 13:53:50', '2026-01-01 13:53:50'),
	(20, 'default', 'created', 'App\\Models\\User', 'created', 20, NULL, NULL, '{"attributes":{"name":"Jamaah Seeder","email":"jamaah@seed.com","username":"jamaah_seed","phone":null,"address":null}}', NULL, '2026-01-01 14:00:20', '2026-01-01 14:00:20'),
	(21, 'default', 'created', 'App\\Models\\User', 'created', 22, NULL, NULL, '{"attributes":{"name":"Jamaah Seeder","email":"jamaah@seed.com","username":"jamaah_seed","phone":null,"address":null}}', NULL, '2026-01-01 14:03:39', '2026-01-01 14:03:39'),
	(22, 'default', 'created', 'App\\Models\\User', 'created', 23, NULL, NULL, '{"attributes":{"name":"1","email":"12@gmail.com","username":"1","phone":"1","address":"1"}}', NULL, '2026-01-01 14:09:18', '2026-01-01 14:09:18'),
	(23, 'default', 'created', 'App\\Models\\User', 'created', 24, NULL, NULL, '{"attributes":{"name":"Jamaah Seeder","email":"jamaah@seed.com","username":"jamaah_seed","phone":null,"address":null}}', NULL, '2026-01-01 14:14:19', '2026-01-01 14:14:19'),
	(24, 'default', 'created', 'App\\Models\\User', 'created', 25, NULL, NULL, '{"attributes":{"name":"Jamaah Seeder","email":"jamaah@seed.com","username":"jamaah_seed","phone":null,"address":null}}', NULL, '2026-01-01 14:15:19', '2026-01-01 14:15:19'),
	(25, 'default', 'created', 'App\\Models\\User', 'created', 26, NULL, NULL, '{"attributes":{"name":"Jamaah Seeder","email":"jamaah@seed.com","username":"jamaah_seed","phone":null,"address":null}}', NULL, '2026-01-01 14:42:15', '2026-01-01 14:42:15'),
	(26, 'default', 'created', 'App\\Models\\User', 'created', 27, NULL, NULL, '{"attributes":{"name":"Jamaah Seeder","email":"jamaah@seed.com","username":"jamaah_seed","phone":null,"address":null}}', NULL, '2026-01-01 14:48:24', '2026-01-01 14:48:24'),
	(27, 'default', 'created', 'App\\Models\\User', 'created', 28, NULL, NULL, '{"attributes":{"name":"Jamaah Seeder","email":"jamaah@seed.com","username":"jamaah_seed","phone":null,"address":null}}', NULL, '2026-01-01 14:49:47', '2026-01-01 14:49:47'),
	(28, 'default', 'created', 'App\\Models\\User', 'created', 29, NULL, NULL, '{"attributes":{"name":"Jamaah Seeder","email":"jamaah@seed.com","username":"jamaah_seed","phone":null,"address":"Jl. Seeder No. 1"}}', NULL, '2026-01-01 14:51:48', '2026-01-01 14:51:48'),
	(29, 'default', 'created', 'App\\Models\\User', 'created', 30, NULL, NULL, '{"attributes":{"name":"1","email":"12@gmail.com","username":"1","phone":"089671530757","address":"Jl.ph mustopha"}}', NULL, '2026-01-01 14:56:49', '2026-01-01 14:56:49'),
	(30, 'default', 'created', 'App\\Models\\User', 'created', 31, NULL, NULL, '{"attributes":{"name":"Jabir","email":"jb@gmail.com","username":"Jabir","phone":"08977557746","address":"Jl.ph mustopha"}}', NULL, '2026-01-10 08:53:19', '2026-01-10 08:53:19'),
	(31, 'default', 'deleted', 'App\\Models\\User', 'deleted', 30, 'App\\Models\\User', 2, '{"old":{"name":"1","email":"12@gmail.com","username":"1","phone":"089671530757","address":"Jl.ph mustopha"}}', NULL, '2026-01-10 09:44:18', '2026-01-10 09:44:18'),
	(32, 'default', 'created', 'App\\Models\\User', 'created', 32, NULL, NULL, '{"attributes":{"name":"fadhil","email":"fadhil@gmail.com","username":"fadhil","phone":"0897626383","address":"jl kembar"}}', NULL, '2026-01-10 09:48:55', '2026-01-10 09:48:55'),
	(33, 'default', 'deleted', 'App\\Models\\User', 'deleted', 32, 'App\\Models\\User', 2, '{"old":{"name":"fadhil","email":"fadhil@gmail.com","username":"fadhil","phone":"0897626383","address":"jl kembar"}}', NULL, '2026-01-10 09:49:19', '2026-01-10 09:49:19'),
	(34, 'default', 'created', 'App\\Models\\User', 'created', 33, NULL, NULL, '{"attributes":{"name":"ajay","email":"ajay@gmail.com","username":"ajay","phone":"0897626383","address":"jl keke"}}', NULL, '2026-01-10 10:18:33', '2026-01-10 10:18:33'),
	(35, 'default', 'deleted', 'App\\Models\\User', 'deleted', 33, 'App\\Models\\User', 2, '{"old":{"name":"ajay","email":"ajay@gmail.com","username":"ajay","phone":"0897626383","address":"jl keke"}}', NULL, '2026-01-10 10:20:24', '2026-01-10 10:20:24'),
	(36, 'default', 'created', 'App\\Models\\User', 'created', 34, NULL, NULL, '{"attributes":{"name":"ajay","email":"ajay@gmail.com","username":"ajay","phone":"0897626383","address":"jl keke"}}', NULL, '2026-01-10 10:21:41', '2026-01-10 10:21:41'),
	(37, 'default', 'deleted', 'App\\Models\\User', 'deleted', 34, 'App\\Models\\User', 2, '{"old":{"name":"ajay","email":"ajay@gmail.com","username":"ajay","phone":"0897626383","address":"jl keke"}}', NULL, '2026-01-10 10:27:33', '2026-01-10 10:27:33'),
	(38, 'default', 'created', 'App\\Models\\User', 'created', 35, NULL, NULL, '{"attributes":{"name":"ajay","email":"ajay@gmail.com","username":"ajay","phone":"0897626383","address":"jl test"}}', NULL, '2026-01-10 10:39:28', '2026-01-10 10:39:28'),
	(39, 'default', 'deleted', 'App\\Models\\User', 'deleted', 35, 'App\\Models\\User', 2, '{"old":{"name":"ajay","email":"ajay@gmail.com","username":"ajay","phone":"0897626383","address":"jl test"}}', NULL, '2026-01-10 10:39:59', '2026-01-10 10:39:59'),
	(40, 'default', 'created', 'App\\Models\\User', 'created', 36, NULL, NULL, '{"attributes":{"name":"fadhil","email":"fadhil@gmail.com","username":"fadhil","phone":"0897626383","address":"fadhil.ld"}}', NULL, '2026-01-10 10:43:51', '2026-01-10 10:43:51'),
	(41, 'default', 'created', 'App\\Models\\User', 'created', 37, NULL, NULL, '{"attributes":{"name":"ajay","email":"ajay@gmail.com","username":"ajay","phone":"0897626383","address":"daw"}}', NULL, '2026-01-10 11:20:20', '2026-01-10 11:20:20'),
	(42, 'default', 'deleted', 'App\\Models\\User', 'deleted', 37, 'App\\Models\\User', 2, '{"old":{"name":"ajay","email":"ajay@gmail.com","username":"ajay","phone":"0897626383","address":"daw"}}', NULL, '2026-01-10 11:21:30', '2026-01-10 11:21:30'),
	(43, 'default', 'deleted', 'App\\Models\\User', 'deleted', 36, 'App\\Models\\User', 2, '{"old":{"name":"fadhil","email":"fadhil@gmail.com","username":"fadhil","phone":"0897626383","address":"fadhil.ld"}}', NULL, '2026-01-10 11:21:33', '2026-01-10 11:21:33'),
	(44, 'default', 'created', 'App\\Models\\User', 'created', 38, NULL, NULL, '{"attributes":{"name":"ajay","email":"ajay@gmail.com","username":"ajay","phone":"0897626383","address":"daa"}}', NULL, '2026-01-10 11:22:32', '2026-01-10 11:22:32'),
	(45, 'default', 'created', 'App\\Models\\User', 'created', 39, NULL, NULL, '{"attributes":{"name":"fadhil","email":"fadhil@gmail.com","username":"fadhil","phone":"0897626383","address":"awwwa"}}', NULL, '2026-01-10 11:27:14', '2026-01-10 11:27:14'),
	(46, 'default', 'deleted', 'App\\Models\\User', 'deleted', 39, 'App\\Models\\User', 2, '{"old":{"name":"fadhil","email":"fadhil@gmail.com","username":"fadhil","phone":"0897626383","address":"awwwa"}}', NULL, '2026-01-10 11:32:33', '2026-01-10 11:32:33'),
	(47, 'default', 'created', 'App\\Models\\User', 'created', 40, NULL, NULL, '{"attributes":{"name":"fadhil","email":"fadhil@gmail.com","username":"fadhil","phone":"0897626383","address":"awwwa"}}', NULL, '2026-01-10 11:32:51', '2026-01-10 11:32:51'),
	(48, 'default', 'created', 'App\\Models\\User', 'created', 41, NULL, NULL, '{"attributes":{"name":"fadhil","email":"fadhil@gmail.com","username":"fadhil","phone":"0897626383","address":"awwwa"}}', NULL, '2026-01-10 11:35:22', '2026-01-10 11:35:22'),
	(49, 'default', 'created', 'App\\Models\\User', 'created', 42, NULL, NULL, '{"attributes":{"name":"fadhil","email":"fadhil@gmail.com","username":"fadhil","phone":"0897626383","address":"awwwa"}}', NULL, '2026-01-10 11:42:06', '2026-01-10 11:42:06'),
	(50, 'default', 'deleted', 'App\\Models\\User', 'deleted', 38, 'App\\Models\\User', 2, '{"old":{"name":"ajay","email":"ajay@gmail.com","username":"ajay","phone":"0897626383","address":"daa"}}', NULL, '2026-01-10 11:42:39', '2026-01-10 11:42:39'),
	(51, 'default', 'created', 'App\\Models\\User', 'created', 43, NULL, NULL, '{"attributes":{"name":"fajar","email":"ajay@gmail.com","username":"fajar","phone":"08976261111","address":"jalan ph musdalifah"}}', NULL, '2026-01-10 11:43:25', '2026-01-10 11:43:25'),
	(52, 'default', 'deleted', 'App\\Models\\User', 'deleted', 42, 'App\\Models\\User', 2, '{"old":{"name":"fadhil","email":"fadhil@gmail.com","username":"fadhil","phone":"0897626383","address":"awwwa"}}', NULL, '2026-01-10 11:44:13', '2026-01-10 11:44:13'),
	(53, 'default', 'deleted', 'App\\Models\\User', 'deleted', 31, 'App\\Models\\User', 2, '{"old":{"name":"Jabir","email":"jb@gmail.com","username":"Jabir","phone":"08977557746","address":"Jl.ph mustopha"}}', NULL, '2026-01-10 11:44:16', '2026-01-10 11:44:16');

-- Dumping structure for table manajemen_masjid.activity_logs
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_module_action_index` (`user_id`,`module`,`action`),
  KEY `activity_logs_created_at_index` (`created_at`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `activity_logs_chk_1` CHECK (json_valid(`properties`))
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.activity_logs: ~39 rows (approximately)
DELETE FROM `activity_logs`;
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `module`, `description`, `properties`, `ip_address`, `user_agent`, `created_at`) VALUES
	(1, 2, 'logout', 'authentication', 'User Admin Jamaah logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 13:09:51'),
	(2, 2, 'logout', 'authentication', 'User Admin Jamaah logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 13:53:06'),
	(3, 19, 'register', 'authentication', 'New user jamaah aljami registered', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 13:53:50'),
	(4, 19, 'logout', 'authentication', 'User jamaah aljami logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 14:08:46'),
	(5, NULL, 'register', 'authentication', 'New user 1 registered', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 14:09:18'),
	(6, NULL, 'register', 'authentication', 'New user 1 registered', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 14:56:49'),
	(7, NULL, 'logout', 'authentication', 'User 1 logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 15:00:28'),
	(8, 2, 'logout', 'authentication', 'User Admin Jamaah logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 15:59:17'),
	(9, 2, 'role_assigned', 'user_management', 'Role \'\' assigned to user 1', '{"user_id":30,"user_name":"1","role_name":"pengurus_jamaah","role_display_name":null,"assigned_by":"Admin Jamaah","assigned_by_id":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 16:06:05'),
	(10, 2, 'role_removed', 'user_management', 'Role \'\' removed from user 1', '{"user_id":30,"user_name":"1","role_name":"pengurus_jamaah","role_display_name":null,"removed_by":"Admin Jamaah","removed_by_id":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-01 16:06:19'),
	(11, 2, 'logout', 'authentication', 'User Admin Jamaah logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 08:51:47'),
	(12, NULL, 'register', 'authentication', 'New user Jabir registered', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 08:53:19'),
	(13, NULL, 'logout', 'authentication', 'User Jabir logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 08:53:38'),
	(14, NULL, 'logout', 'authentication', 'User Jabir logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 09:48:21'),
	(15, NULL, 'register', 'authentication', 'New user fadhil registered', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 09:48:55'),
	(16, NULL, 'logout', 'authentication', 'User fadhil logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 09:49:06'),
	(17, NULL, 'register', 'authentication', 'New user ajay registered', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:18:33'),
	(18, NULL, 'register', 'authentication', 'New user ajay registered', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:21:41'),
	(19, NULL, 'logout', 'authentication', 'User ajay logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:24:04'),
	(20, NULL, 'register', 'authentication', 'New user ajay registered', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:39:28'),
	(21, NULL, 'logout', 'authentication', 'User ajay logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:39:43'),
	(22, NULL, 'register', 'authentication', 'New user fadhil registered', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:43:52'),
	(23, NULL, 'logout', 'authentication', 'User fadhil logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 10:46:42'),
	(24, NULL, 'register', 'authentication', 'New user ajay registered', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:20:20'),
	(25, NULL, 'register', 'authentication', 'New user ajay registered', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:22:32'),
	(26, NULL, 'logout', 'authentication', 'User ajay logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:26:45'),
	(27, NULL, 'register', 'authentication', 'New user fadhil registered', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:42:06'),
	(28, NULL, 'logout', 'authentication', 'User fadhil logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:42:43'),
	(29, 43, 'register', 'authentication', 'New user fajar registered', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 11:43:25'),
	(30, 2, 'logout', 'authentication', 'User Admin Jamaah logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 12:23:05'),
	(31, 43, 'logout', 'authentication', 'User fajar logged out', '[]', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 12:23:32'),
	(32, 2, 'role_assigned', 'user_management', 'Role \'\' assigned to user fajar', '{"user_id":43,"user_name":"fajar","role_name":"pengurus_jamaah","role_display_name":null,"assigned_by":"Admin Jamaah","assigned_by_id":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 12:29:43'),
	(33, 2, 'role_removed', 'user_management', 'Role \'\' removed from user fajar', '{"user_id":43,"user_name":"fajar","role_name":"pengurus_jamaah","role_display_name":null,"removed_by":"Admin Jamaah","removed_by_id":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 12:49:44'),
	(34, 2, 'role_assigned', 'user_management', 'Role \'\' assigned to user fajar', '{"user_id":43,"user_name":"fajar","role_name":"pengurus_jamaah","role_display_name":null,"assigned_by":"Admin Jamaah","assigned_by_id":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 12:53:35'),
	(35, 2, 'role_removed', 'user_management', 'Role \'\' removed from user fajar', '{"user_id":43,"user_name":"fajar","role_name":"pengurus_jamaah","role_display_name":null,"removed_by":"Admin Jamaah","removed_by_id":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 12:57:41'),
	(36, 2, 'role_assigned', 'user_management', 'Role \'\' assigned to user fajar', '{"user_id":43,"user_name":"fajar","role_name":"pengurus_jamaah","role_display_name":null,"assigned_by":"Admin Jamaah","assigned_by_id":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 13:13:32'),
	(37, 2, 'role_removed', 'user_management', 'Role \'\' removed from user fajar', '{"user_id":43,"user_name":"fajar","role_name":"pengurus_jamaah","role_display_name":null,"removed_by":"Admin Jamaah","removed_by_id":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 13:13:53'),
	(38, 2, 'role_assigned', 'user_management', 'Role \'\' assigned to user fajar', '{"user_id":43,"user_name":"fajar","role_name":"pengurus_jamaah","role_display_name":null,"assigned_by":"Admin Jamaah","assigned_by_id":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 13:32:44'),
	(39, 2, 'role_removed', 'user_management', 'Role \'\' removed from user fajar', '{"user_id":43,"user_name":"fajar","role_name":"pengurus_jamaah","role_display_name":null,"removed_by":"Admin Jamaah","removed_by_id":2}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2026-01-10 13:41:35');

-- Dumping structure for table manajemen_masjid.donations
CREATE TABLE IF NOT EXISTS `donations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jamaah_profile_id` bigint unsigned NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `donation_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `donations_jamaah_profile_id_foreign` (`jamaah_profile_id`),
  CONSTRAINT `donations_jamaah_profile_id_foreign` FOREIGN KEY (`jamaah_profile_id`) REFERENCES `jamaah_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.donations: ~0 rows (approximately)
DELETE FROM `donations`;

-- Dumping structure for table manajemen_masjid.jamaah_categories
CREATE TABLE IF NOT EXISTS `jamaah_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jamaah_categories_nama_unique` (`nama`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.jamaah_categories: ~3 rows (approximately)
DELETE FROM `jamaah_categories`;
INSERT INTO `jamaah_categories` (`id`, `nama`, `created_at`, `updated_at`) VALUES
	(1, 'Umum', NULL, NULL),
	(2, 'Pengurus', NULL, NULL),
	(3, 'Donatur', NULL, NULL);

-- Dumping structure for table manajemen_masjid.jamaah_category_jamaah
CREATE TABLE IF NOT EXISTS `jamaah_category_jamaah` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jamaah_profile_id` bigint unsigned NOT NULL,
  `jamaah_category_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jamaah_category_unique` (`jamaah_profile_id`,`jamaah_category_id`),
  KEY `jamaah_category_jamaah_jamaah_category_id_foreign` (`jamaah_category_id`),
  CONSTRAINT `jamaah_category_jamaah_jamaah_category_id_foreign` FOREIGN KEY (`jamaah_category_id`) REFERENCES `jamaah_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jamaah_category_jamaah_jamaah_profile_id_foreign` FOREIGN KEY (`jamaah_profile_id`) REFERENCES `jamaah_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.jamaah_category_jamaah: ~1 rows (approximately)
DELETE FROM `jamaah_category_jamaah`;
INSERT INTO `jamaah_category_jamaah` (`id`, `jamaah_profile_id`, `jamaah_category_id`) VALUES
	(22, 15, 1);

-- Dumping structure for table manajemen_masjid.jamaah_profiles
CREATE TABLE IF NOT EXISTS `jamaah_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `nama_lengkap` varchar(20) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `pekerjaan` varchar(100) DEFAULT NULL,
  `status_aktif` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_jamaah_user` (`user_id`),
  CONSTRAINT `fk_jamaah_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table manajemen_masjid.jamaah_profiles: ~1 rows (approximately)
DELETE FROM `jamaah_profiles`;
INSERT INTO `jamaah_profiles` (`id`, `user_id`, `nama_lengkap`, `nik`, `jenis_kelamin`, `tanggal_lahir`, `pekerjaan`, `status_aktif`, `created_at`, `updated_at`) VALUES
	(15, 43, 'fajar', '1974032798263321', 'Laki-laki', NULL, NULL, '1', '2026-01-10 11:43:25', '2026-01-10 12:22:51');

-- Dumping structure for table manajemen_masjid.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.migrations: ~14 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2024_01_01_000000_create_users_table', 1),
	(2, '2024_01_01_000001_create_password_reset_tokens_table', 1),
	(3, '2024_01_01_000002_create_sessions_table', 1),
	(4, '2024_01_01_000003_create_activity_logs_table', 1),
	(5, '2025_11_03_133609_create_permission_tables', 1),
	(6, '2025_11_03_133728_create_activity_log_table', 1),
	(7, '2025_11_03_133729_add_event_column_to_activity_log_table', 1),
	(8, '2025_11_03_133730_add_batch_uuid_column_to_activity_log_table', 1),
	(9, '2026_01_01_201441_create_jamaah_profiles_table', 2),
	(10, '2026_01_01_201536_create_jamaah_categories_table', 2),
	(11, '2026_01_01_201615_create_jamaah_category_jamaah_table', 2),
	(12, '2026_01_01_220616_create_donations_table', 3),
	(13, '2026_01_01_221143_create_activities_table', 4),
	(14, '2026_01_01_221321_create_participations_table', 5);

-- Dumping structure for table manajemen_masjid.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.model_has_permissions: ~0 rows (approximately)
DELETE FROM `model_has_permissions`;

-- Dumping structure for table manajemen_masjid.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.model_has_roles: ~29 rows (approximately)
DELETE FROM `model_has_roles`;
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 2),
	(3, 'App\\Models\\User', 3),
	(4, 'App\\Models\\User', 4),
	(5, 'App\\Models\\User', 5),
	(6, 'App\\Models\\User', 6),
	(7, 'App\\Models\\User', 7),
	(8, 'App\\Models\\User', 8),
	(9, 'App\\Models\\User', 9),
	(10, 'App\\Models\\User', 10),
	(20, 'App\\Models\\User', 11),
	(20, 'App\\Models\\User', 12),
	(20, 'App\\Models\\User', 13),
	(20, 'App\\Models\\User', 14),
	(20, 'App\\Models\\User', 15),
	(12, 'App\\Models\\User', 16),
	(13, 'App\\Models\\User', 17),
	(14, 'App\\Models\\User', 18),
	(20, 'App\\Models\\User', 19),
	(20, 'App\\Models\\User', 20),
	(20, 'App\\Models\\User', 22),
	(20, 'App\\Models\\User', 23),
	(20, 'App\\Models\\User', 24),
	(20, 'App\\Models\\User', 25),
	(20, 'App\\Models\\User', 26),
	(20, 'App\\Models\\User', 27),
	(20, 'App\\Models\\User', 28),
	(20, 'App\\Models\\User', 29),
	(20, 'App\\Models\\User', 43);

-- Dumping structure for table manajemen_masjid.participations
CREATE TABLE IF NOT EXISTS `participations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jamaah_profile_id` bigint unsigned NOT NULL,
  `activity_id` bigint unsigned NOT NULL,
  `hadir` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `participations_jamaah_profile_id_activity_id_unique` (`jamaah_profile_id`,`activity_id`),
  KEY `participations_activity_id_foreign` (`activity_id`),
  CONSTRAINT `participations_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `participations_jamaah_profile_id_foreign` FOREIGN KEY (`jamaah_profile_id`) REFERENCES `jamaah_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.participations: ~0 rows (approximately)
DELETE FROM `participations`;

-- Dumping structure for table manajemen_masjid.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table manajemen_masjid.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.permissions: ~36 rows (approximately)
DELETE FROM `permissions`;
INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'jamaah.view', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(2, 'jamaah.create', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(3, 'jamaah.update', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(4, 'jamaah.delete', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(5, 'keuangan.view', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(6, 'keuangan.create', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(7, 'keuangan.update', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(8, 'keuangan.delete', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(9, 'kegiatan.view', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(10, 'kegiatan.create', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(11, 'kegiatan.update', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(12, 'kegiatan.delete', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(13, 'zis.view', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(14, 'zis.create', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(15, 'zis.update', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(16, 'zis.delete', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(17, 'kurban.view', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(18, 'kurban.create', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(19, 'kurban.update', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(20, 'kurban.delete', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(21, 'inventaris.view', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(22, 'inventaris.create', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(23, 'inventaris.update', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(24, 'inventaris.delete', 'web', '2025-11-03 06:37:36', '2025-11-03 06:37:36'),
	(25, 'takmir.view', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(26, 'takmir.create', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(27, 'takmir.update', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(28, 'takmir.delete', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(29, 'informasi.view', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(30, 'informasi.create', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(31, 'informasi.update', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(32, 'informasi.delete', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(33, 'laporan.view', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(34, 'laporan.create', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(35, 'laporan.update', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(36, 'laporan.delete', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37');

-- Dumping structure for table manajemen_masjid.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.roles: ~20 rows (approximately)
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'super_admin', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(2, 'admin_jamaah', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(3, 'admin_keuangan', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(4, 'admin_kegiatan', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(5, 'admin_zis', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(6, 'admin_kurban', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(7, 'admin_inventaris', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(8, 'admin_takmir', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(9, 'admin_informasi', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(10, 'admin_laporan', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(11, 'pengurus_jamaah', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(12, 'pengurus_keuangan', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(13, 'pengurus_kegiatan', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(14, 'pengurus_zis', 'web', '2025-11-03 06:37:37', '2025-11-03 06:37:37'),
	(15, 'pengurus_kurban', 'web', '2025-11-03 06:37:38', '2025-11-03 06:37:38'),
	(16, 'pengurus_inventaris', 'web', '2025-11-03 06:37:38', '2025-11-03 06:37:38'),
	(17, 'pengurus_takmir', 'web', '2025-11-03 06:37:38', '2025-11-03 06:37:38'),
	(18, 'pengurus_informasi', 'web', '2025-11-03 06:37:38', '2025-11-03 06:37:38'),
	(19, 'pengurus_laporan', 'web', '2025-11-03 06:37:38', '2025-11-03 06:37:38'),
	(20, 'jamaah', 'web', '2025-11-03 06:37:38', '2025-11-03 06:37:38');

-- Dumping structure for table manajemen_masjid.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.role_has_permissions: ~82 rows (approximately)
DELETE FROM `role_has_permissions`;
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(5, 1),
	(9, 1),
	(13, 1),
	(17, 1),
	(21, 1),
	(25, 1),
	(29, 1),
	(33, 1),
	(1, 2),
	(2, 2),
	(3, 2),
	(4, 2),
	(5, 3),
	(6, 3),
	(7, 3),
	(8, 3),
	(9, 4),
	(10, 4),
	(11, 4),
	(12, 4),
	(13, 5),
	(14, 5),
	(15, 5),
	(16, 5),
	(17, 6),
	(18, 6),
	(19, 6),
	(20, 6),
	(21, 7),
	(22, 7),
	(23, 7),
	(24, 7),
	(25, 8),
	(26, 8),
	(27, 8),
	(28, 8),
	(29, 9),
	(30, 9),
	(31, 9),
	(32, 9),
	(33, 10),
	(34, 10),
	(35, 10),
	(36, 10),
	(1, 11),
	(2, 11),
	(3, 11),
	(4, 11),
	(5, 12),
	(6, 12),
	(7, 12),
	(8, 12),
	(9, 13),
	(10, 13),
	(11, 13),
	(12, 13),
	(13, 14),
	(14, 14),
	(15, 14),
	(16, 14),
	(17, 15),
	(18, 15),
	(19, 15),
	(20, 15),
	(21, 16),
	(22, 16),
	(23, 16),
	(24, 16),
	(25, 17),
	(26, 17),
	(27, 17),
	(28, 17),
	(29, 18),
	(30, 18),
	(31, 18),
	(32, 18),
	(33, 19),
	(34, 19),
	(35, 19),
	(36, 19),
	(1, 20);

-- Dumping structure for table manajemen_masjid.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.sessions: ~0 rows (approximately)
DELETE FROM `sessions`;

-- Dumping structure for table manajemen_masjid.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `login_attempts` int NOT NULL DEFAULT '0',
  `locked_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table manajemen_masjid.users: ~21 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `phone`, `address`, `photo`, `email_verified_at`, `remember_token`, `last_login_at`, `login_attempts`, `locked_until`, `created_at`, `updated_at`) VALUES
	(1, 'Super Administrator', 'superadmin@masjid.com', 'superadmin', '$2y$12$LsV2pDVVXs7RejMp9i/i3ufvMCa03kXQuwFv6b.0jVnfiaz7oYIFK', '081234567890', NULL, NULL, '2025-11-03 06:37:38', NULL, NULL, 0, NULL, '2025-11-03 06:37:38', '2025-11-03 06:37:38'),
	(2, 'Admin Jamaah', 'admin.jamaah@masjid.com', 'admin_jamaah', '$2y$12$kfsm9oefBVUi1oc1ylox8eLjWG4FcGYZyTVd1l42ugTXMi4KeUD12', '081219339225', NULL, NULL, '2025-11-03 06:37:38', 'hVRCMe96cKcCxbbfBryDNPmykd7nkNHOABGClsj5ku4wj9ZAnrefzlBF1SRd', '2026-01-10 12:23:16', 0, NULL, '2025-11-03 06:37:38', '2026-01-10 12:23:16'),
	(3, 'Admin Keuangan', 'admin.keuangan@masjid.com', 'admin_keuangan', '$2y$12$D3lhdMOT2d20GXC4U5Tdsu74/vHJ3e/V2hEfyOyZKgpOEo5TsuqIu', '081240936444', NULL, NULL, '2025-11-03 06:37:39', NULL, NULL, 0, NULL, '2025-11-03 06:37:39', '2025-11-03 06:37:39'),
	(4, 'Admin Kegiatan', 'admin.kegiatan@masjid.com', 'admin_kegiatan', '$2y$12$G6o9fBwEAElHQckmdFSSPu9ZTvdZnnx9SGANY/y2rDGVG4DSKmy8C', '081236952701', NULL, NULL, '2025-11-03 06:37:39', NULL, NULL, 0, NULL, '2025-11-03 06:37:39', '2025-11-03 06:37:39'),
	(5, 'Admin ZIS', 'admin.zis@masjid.com', 'admin_zis', '$2y$12$WxHRDGt.dK6DWC3Y3BFY2OWAvOgjjvjzPc9csdeR/NmtOTOKQkTAS', '081271663378', NULL, NULL, '2025-11-03 06:37:40', NULL, NULL, 0, NULL, '2025-11-03 06:37:40', '2025-11-03 06:37:40'),
	(6, 'Admin Kurban', 'admin.kurban@masjid.com', 'admin_kurban', '$2y$12$xjg7TzGeoLZ3Wwy/5Gv/OOp9NxS.ZU.WIgrATaKKD6FOA61cC0y0K', '081253840261', NULL, NULL, '2025-11-03 06:37:40', NULL, NULL, 0, NULL, '2025-11-03 06:37:40', '2025-11-03 06:37:40'),
	(7, 'Admin Inventaris', 'admin.inventaris@masjid.com', 'admin_inventaris', '$2y$12$dIQZFwZLFid8CzdYHLKT2.dwg1PJjNkwphS764s2D0iHecgLWTpMe', '081295005128', NULL, NULL, '2025-11-03 06:37:40', NULL, NULL, 0, NULL, '2025-11-03 06:37:40', '2025-11-03 06:37:40'),
	(8, 'Admin Takmir', 'admin.takmir@masjid.com', 'admin_takmir', '$2y$12$nLvWM8NLh8IJzCiJD7QS5et.yTO1f2qivT3d54dKv8MgHwIHfU0Ci', '081270630958', NULL, NULL, '2025-11-03 06:37:41', NULL, NULL, 0, NULL, '2025-11-03 06:37:41', '2025-11-03 06:37:41'),
	(9, 'Admin Informasi', 'admin.informasi@masjid.com', 'admin_informasi', '$2y$12$ysVl8BCsBOdzDyM8R4WW8ecOqqmFwr9.7VYfvaSG8WWJEFUj5h4gy', '081219291269', NULL, NULL, '2025-11-03 06:37:41', NULL, NULL, 0, NULL, '2025-11-03 06:37:41', '2025-11-03 06:37:41'),
	(10, 'Admin Laporan', 'admin.laporan@masjid.com', 'admin_laporan', '$2y$12$FFQjsqtPf4w6tGAw4FKMB.kEq0B/endBfiJprl.gAmF5l16gtw30i', '081245632519', NULL, NULL, '2025-11-03 06:37:41', NULL, NULL, 0, NULL, '2025-11-03 06:37:41', '2025-11-03 06:37:41'),
	(11, 'Jamaah User 1', 'jamaah1@example.com', 'jamaah1', '$2y$12$NIWW8eeASRTNESgpkV8AtuxIZOxlRLVFJXtEYSaBXg.B9da28CPti', '081343147095', 'Alamat Jamaah 1', NULL, '2025-11-03 06:37:41', NULL, NULL, 0, NULL, '2025-11-03 06:37:41', '2025-11-03 06:37:41'),
	(12, 'Jamaah User 2', 'jamaah2@example.com', 'jamaah2', '$2y$12$2aZq1ypg/Pv/hqAClv2NqOEuWa.kyXZkQLPdLPZGMb5VGhedSI2Je', '081391236580', 'Alamat Jamaah 2', NULL, '2025-11-03 06:37:42', NULL, NULL, 0, NULL, '2025-11-03 06:37:42', '2025-11-03 06:37:42'),
	(13, 'Jamaah User 3', 'jamaah3@example.com', 'jamaah3', '$2y$12$TdRoMvXkcWQUzf4Yzzr3v.sriqhKsjvUzxLoZPnE4iIs/FB17Ale2', '081362356932', 'Alamat Jamaah 3', NULL, '2025-11-03 06:37:42', NULL, NULL, 0, NULL, '2025-11-03 06:37:42', '2025-11-03 06:37:42'),
	(14, 'Jamaah User 4', 'jamaah4@example.com', 'jamaah4', '$2y$12$daxOuKXxwIYzWbLC/.tAz.19KiEOuEYDzyXu4rp4mTSALn276izoO', '081390153906', 'Alamat Jamaah 4', NULL, '2025-11-03 06:37:42', NULL, NULL, 0, NULL, '2025-11-03 06:37:42', '2025-11-03 06:37:42'),
	(15, 'Jamaah User 5', 'jamaah5@example.com', 'jamaah5', '$2y$12$Vl1RBQHTtu.97ngbKCn3SO35YR6b9fsx71/n7h1EzWNPKYxXFzh56', '081365542759', 'Alamat Jamaah 5', NULL, '2025-11-03 06:37:43', NULL, NULL, 0, NULL, '2025-11-03 06:37:43', '2025-11-03 06:37:43'),
	(16, 'Pengurus Keuangan', 'pengurus.keuangan@masjid.com', 'pengurus_keuangan', '$2y$12$IvG8.iHXVdTSCaJduncRTOwJY3ob1x8wfxpeMIHBRI72J4F3m.o.e', '081442120529', NULL, NULL, '2025-11-03 06:37:43', NULL, NULL, 0, NULL, '2025-11-03 06:37:43', '2025-11-03 06:37:43'),
	(17, 'Pengurus Kegiatan', 'pengurus.kegiatan@masjid.com', 'pengurus_kegiatan', '$2y$12$gBYQjiPfL68PIbDGFw3X.ujVwvEwk3.0mRfHhzGalm67P6s3HHbFm', '081474214091', NULL, NULL, '2025-11-03 06:37:43', NULL, NULL, 0, NULL, '2025-11-03 06:37:43', '2025-11-03 06:37:43'),
	(18, 'Pengurus Zis', 'pengurus.zis@masjid.com', 'pengurus_zis', '$2y$12$fEGov6uuF2y3Oe8w2OMNpOdzKOQvcUOjc/ZwykI8ck70WiCBrGxYS', '081419566265', NULL, NULL, '2025-11-03 06:37:43', NULL, NULL, 0, NULL, '2025-11-03 06:37:43', '2025-11-03 06:37:43'),
	(19, 'jamaah aljami', 'jami@gmail.com', 'aljami', '$2y$12$/zB5ren2tJpyHZocyugbx.2sCqB75GtAY6WlAv5ihERHJcIPD3ibq', '089671530757', 'Jl.ph mustopha', NULL, NULL, NULL, NULL, 0, NULL, '2026-01-01 13:53:50', '2026-01-01 13:53:50'),
	(29, 'Jamaah Seeder', 'jamaah@seed.com', 'jamaah_seed', '$2y$12$O8fQXFUoAi9YkSXcF.sV1.FadIQ61lI5VfAA1Vvt8H/WQwbeCOD8S', NULL, 'Jl. Seeder No. 1', NULL, NULL, NULL, NULL, 0, NULL, '2026-01-01 14:51:48', '2026-01-01 14:51:48'),
	(43, 'fajar', 'ajay@gmail.com', 'fajar', '$2y$12$i6hEVDG4zqzMq59b0Dx.fu7hVGiF2vPen6zr0YjyVPEwJAagl0MkC', '08976261111', 'jalan ph musdalifah', NULL, NULL, NULL, '2026-01-10 12:23:41', 0, NULL, '2026-01-10 11:43:25', '2026-01-10 12:23:41');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
