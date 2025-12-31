-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2025 at 07:46 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `manajemen_masjid`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'created', 'App\\Models\\User', 'created', 1, NULL, NULL, '{\"attributes\":{\"name\":\"Super Administrator\",\"email\":\"superadmin@masjid.com\",\"username\":\"superadmin\",\"phone\":\"081234567890\",\"address\":null}}', NULL, '2025-11-03 06:37:38', '2025-11-03 06:37:38'),
(2, 'default', 'created', 'App\\Models\\User', 'created', 2, NULL, NULL, '{\"attributes\":{\"name\":\"Admin Jamaah\",\"email\":\"admin.jamaah@masjid.com\",\"username\":\"admin_jamaah\",\"phone\":\"081219339225\",\"address\":null}}', NULL, '2025-11-03 06:37:38', '2025-11-03 06:37:38'),
(3, 'default', 'created', 'App\\Models\\User', 'created', 3, NULL, NULL, '{\"attributes\":{\"name\":\"Admin Keuangan\",\"email\":\"admin.keuangan@masjid.com\",\"username\":\"admin_keuangan\",\"phone\":\"081240936444\",\"address\":null}}', NULL, '2025-11-03 06:37:39', '2025-11-03 06:37:39'),
(4, 'default', 'created', 'App\\Models\\User', 'created', 4, NULL, NULL, '{\"attributes\":{\"name\":\"Admin Kegiatan\",\"email\":\"admin.kegiatan@masjid.com\",\"username\":\"admin_kegiatan\",\"phone\":\"081236952701\",\"address\":null}}', NULL, '2025-11-03 06:37:39', '2025-11-03 06:37:39'),
(5, 'default', 'created', 'App\\Models\\User', 'created', 5, NULL, NULL, '{\"attributes\":{\"name\":\"Admin ZIS\",\"email\":\"admin.zis@masjid.com\",\"username\":\"admin_zis\",\"phone\":\"081271663378\",\"address\":null}}', NULL, '2025-11-03 06:37:40', '2025-11-03 06:37:40'),
(6, 'default', 'created', 'App\\Models\\User', 'created', 6, NULL, NULL, '{\"attributes\":{\"name\":\"Admin Kurban\",\"email\":\"admin.kurban@masjid.com\",\"username\":\"admin_kurban\",\"phone\":\"081253840261\",\"address\":null}}', NULL, '2025-11-03 06:37:40', '2025-11-03 06:37:40'),
(7, 'default', 'created', 'App\\Models\\User', 'created', 7, NULL, NULL, '{\"attributes\":{\"name\":\"Admin Inventaris\",\"email\":\"admin.inventaris@masjid.com\",\"username\":\"admin_inventaris\",\"phone\":\"081295005128\",\"address\":null}}', NULL, '2025-11-03 06:37:40', '2025-11-03 06:37:40'),
(8, 'default', 'created', 'App\\Models\\User', 'created', 8, NULL, NULL, '{\"attributes\":{\"name\":\"Admin Takmir\",\"email\":\"admin.takmir@masjid.com\",\"username\":\"admin_takmir\",\"phone\":\"081270630958\",\"address\":null}}', NULL, '2025-11-03 06:37:41', '2025-11-03 06:37:41'),
(9, 'default', 'created', 'App\\Models\\User', 'created', 9, NULL, NULL, '{\"attributes\":{\"name\":\"Admin Informasi\",\"email\":\"admin.informasi@masjid.com\",\"username\":\"admin_informasi\",\"phone\":\"081219291269\",\"address\":null}}', NULL, '2025-11-03 06:37:41', '2025-11-03 06:37:41'),
(10, 'default', 'created', 'App\\Models\\User', 'created', 10, NULL, NULL, '{\"attributes\":{\"name\":\"Admin Laporan\",\"email\":\"admin.laporan@masjid.com\",\"username\":\"admin_laporan\",\"phone\":\"081245632519\",\"address\":null}}', NULL, '2025-11-03 06:37:41', '2025-11-03 06:37:41'),
(11, 'default', 'created', 'App\\Models\\User', 'created', 11, NULL, NULL, '{\"attributes\":{\"name\":\"Jamaah User 1\",\"email\":\"jamaah1@example.com\",\"username\":\"jamaah1\",\"phone\":\"081343147095\",\"address\":\"Alamat Jamaah 1\"}}', NULL, '2025-11-03 06:37:41', '2025-11-03 06:37:41'),
(12, 'default', 'created', 'App\\Models\\User', 'created', 12, NULL, NULL, '{\"attributes\":{\"name\":\"Jamaah User 2\",\"email\":\"jamaah2@example.com\",\"username\":\"jamaah2\",\"phone\":\"081391236580\",\"address\":\"Alamat Jamaah 2\"}}', NULL, '2025-11-03 06:37:42', '2025-11-03 06:37:42'),
(13, 'default', 'created', 'App\\Models\\User', 'created', 13, NULL, NULL, '{\"attributes\":{\"name\":\"Jamaah User 3\",\"email\":\"jamaah3@example.com\",\"username\":\"jamaah3\",\"phone\":\"081362356932\",\"address\":\"Alamat Jamaah 3\"}}', NULL, '2025-11-03 06:37:42', '2025-11-03 06:37:42'),
(14, 'default', 'created', 'App\\Models\\User', 'created', 14, NULL, NULL, '{\"attributes\":{\"name\":\"Jamaah User 4\",\"email\":\"jamaah4@example.com\",\"username\":\"jamaah4\",\"phone\":\"081390153906\",\"address\":\"Alamat Jamaah 4\"}}', NULL, '2025-11-03 06:37:42', '2025-11-03 06:37:42'),
(15, 'default', 'created', 'App\\Models\\User', 'created', 15, NULL, NULL, '{\"attributes\":{\"name\":\"Jamaah User 5\",\"email\":\"jamaah5@example.com\",\"username\":\"jamaah5\",\"phone\":\"081365542759\",\"address\":\"Alamat Jamaah 5\"}}', NULL, '2025-11-03 06:37:43', '2025-11-03 06:37:43'),
(16, 'default', 'created', 'App\\Models\\User', 'created', 16, NULL, NULL, '{\"attributes\":{\"name\":\"Pengurus Keuangan\",\"email\":\"pengurus.keuangan@masjid.com\",\"username\":\"pengurus_keuangan\",\"phone\":\"081442120529\",\"address\":null}}', NULL, '2025-11-03 06:37:43', '2025-11-03 06:37:43'),
(17, 'default', 'created', 'App\\Models\\User', 'created', 17, NULL, NULL, '{\"attributes\":{\"name\":\"Pengurus Kegiatan\",\"email\":\"pengurus.kegiatan@masjid.com\",\"username\":\"pengurus_kegiatan\",\"phone\":\"081474214091\",\"address\":null}}', NULL, '2025-11-03 06:37:43', '2025-11-03 06:37:43'),
(18, 'default', 'created', 'App\\Models\\User', 'created', 18, NULL, NULL, '{\"attributes\":{\"name\":\"Pengurus Zis\",\"email\":\"pengurus.zis@masjid.com\",\"username\":\"pengurus_zis\",\"phone\":\"081419566265\",\"address\":null}}', NULL, '2025-11-03 06:37:43', '2025-11-03 06:37:43');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_01_01_000000_create_users_table', 1),
(2, '2024_01_01_000001_create_password_reset_tokens_table', 1),
(3, '2024_01_01_000002_create_sessions_table', 1),
(4, '2024_01_01_000003_create_activity_logs_table', 1),
(5, '2025_11_03_133609_create_permission_tables', 1),
(6, '2025_11_03_133728_create_activity_log_table', 1),
(7, '2025_11_03_133729_add_event_column_to_activity_log_table', 1),
(8, '2025_11_03_133730_add_batch_uuid_column_to_activity_log_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

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
(12, 'App\\Models\\User', 16),
(13, 'App\\Models\\User', 17),
(14, 'App\\Models\\User', 18),
(20, 'App\\Models\\User', 11),
(20, 'App\\Models\\User', 12),
(20, 'App\\Models\\User', 13),
(20, 'App\\Models\\User', 14),
(20, 'App\\Models\\User', 15);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 11),
(1, 20),
(2, 2),
(2, 11),
(3, 2),
(3, 11),
(4, 2),
(4, 11),
(5, 1),
(5, 3),
(5, 12),
(6, 3),
(6, 12),
(7, 3),
(7, 12),
(8, 3),
(8, 12),
(9, 1),
(9, 4),
(9, 13),
(10, 4),
(10, 13),
(11, 4),
(11, 13),
(12, 4),
(12, 13),
(13, 1),
(13, 5),
(13, 14),
(14, 5),
(14, 14),
(15, 5),
(15, 14),
(16, 5),
(16, 14),
(17, 1),
(17, 6),
(17, 15),
(18, 6),
(18, 15),
(19, 6),
(19, 15),
(20, 6),
(20, 15),
(21, 1),
(21, 7),
(21, 16),
(22, 7),
(22, 16),
(23, 7),
(23, 16),
(24, 7),
(24, 16),
(25, 1),
(25, 8),
(25, 17),
(26, 8),
(26, 17),
(27, 8),
(27, 17),
(28, 8),
(28, 17),
(29, 1),
(29, 9),
(29, 18),
(30, 9),
(30, 18),
(31, 9),
(31, 18),
(32, 9),
(32, 18),
(33, 1),
(33, 10),
(33, 19),
(34, 10),
(34, 19),
(35, 10),
(35, 19),
(36, 10),
(36, 19);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `login_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `phone`, `address`, `photo`, `email_verified_at`, `remember_token`, `last_login_at`, `login_attempts`, `locked_until`, `created_at`, `updated_at`) VALUES
(1, 'Super Administrator', 'superadmin@masjid.com', 'superadmin', '$2y$12$LsV2pDVVXs7RejMp9i/i3ufvMCa03kXQuwFv6b.0jVnfiaz7oYIFK', '081234567890', NULL, NULL, '2025-11-03 06:37:38', NULL, NULL, 0, NULL, '2025-11-03 06:37:38', '2025-11-03 06:37:38'),
(2, 'Admin Jamaah', 'admin.jamaah@masjid.com', 'admin_jamaah', '$2y$12$kfsm9oefBVUi1oc1ylox8eLjWG4FcGYZyTVd1l42ugTXMi4KeUD12', '081219339225', NULL, NULL, '2025-11-03 06:37:38', NULL, NULL, 0, NULL, '2025-11-03 06:37:38', '2025-11-03 06:37:38'),
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
(18, 'Pengurus Zis', 'pengurus.zis@masjid.com', 'pengurus_zis', '$2y$12$fEGov6uuF2y3Oe8w2OMNpOdzKOQvcUOjc/ZwykI8ck70WiCBrGxYS', '081419566265', NULL, NULL, '2025-11-03 06:37:43', NULL, NULL, 0, NULL, '2025-11-03 06:37:43', '2025-11-03 06:37:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_module_action_index` (`user_id`,`module`,`action`),
  ADD KEY `activity_logs_created_at_index` (`created_at`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
