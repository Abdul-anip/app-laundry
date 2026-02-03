-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 02, 2026 at 05:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laundry_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bundles`
--

CREATE TABLE `bundles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bundles`
--

INSERT INTO `bundles` (`id`, `name`, `description`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Paket Bed Cover', 'Cuci bed cover', 35000.00, '2026-01-28 09:55:23', '2026-01-28 09:55:23');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laundry-app-cache-anif@gmial.com|127.0.0.1', 'i:1;', 1769781814),
('laundry-app-cache-anif@gmial.com|127.0.0.1:timer', 'i:1769781814;', 1769781814);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `landing_page_settings`
--

CREATE TABLE `landing_page_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hero_title` varchar(255) NOT NULL DEFAULT 'Laundry Mudah, Hidup Lebih Santai',
  `hero_subtitle` text NOT NULL DEFAULT 'Pesan online, kami jemput & cuci, antar kembali bersih. Simple!',
  `hero_cta_primary` varchar(255) NOT NULL DEFAULT 'Pesan Sekarang',
  `hero_cta_secondary` varchar(255) NOT NULL DEFAULT 'Lacak Pesanan',
  `how_it_works_title` varchar(255) NOT NULL DEFAULT 'Cara Kerja',
  `how_it_works_subtitle` text NOT NULL DEFAULT 'Tiga langkah mudah untuk cucian bersih dan wangi',
  `services_title` varchar(255) NOT NULL DEFAULT 'Layanan Kami',
  `services_subtitle` text NOT NULL DEFAULT 'Pilih paket yang sesuai dengan kebutuhanmu',
  `why_choose_title` varchar(255) NOT NULL DEFAULT 'Kenapa Pilih Kami?',
  `why_choose_subtitle` text NOT NULL DEFAULT 'Komitmen kami untuk memberikan layanan terbaik',
  `cta_section_title` varchar(255) NOT NULL DEFAULT 'Yuk, Coba Sekarang!',
  `cta_section_text` text NOT NULL DEFAULT 'Rasakan pengalaman laundry yang mudah dan menyenangkan. Order pertamamu menanti!',
  `cta_button_text` varchar(255) NOT NULL DEFAULT 'Mulai Order →',
  `footer_description` text NOT NULL DEFAULT 'Layanan laundry online terpercaya dengan pickup & delivery untuk kemudahan hidupmu.',
  `contact_email` varchar(255) NOT NULL DEFAULT 'hello@laundryku.com',
  `contact_phone` varchar(255) NOT NULL DEFAULT '+62 812-3456-7890',
  `contact_address` varchar(255) NOT NULL DEFAULT 'Jakarta, Indonesia',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `laundry_latitude` decimal(10,8) DEFAULT NULL,
  `laundry_longitude` decimal(11,8) DEFAULT NULL,
  `laundry_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `landing_page_settings`
--

INSERT INTO `landing_page_settings` (`id`, `hero_title`, `hero_subtitle`, `hero_cta_primary`, `hero_cta_secondary`, `how_it_works_title`, `how_it_works_subtitle`, `services_title`, `services_subtitle`, `why_choose_title`, `why_choose_subtitle`, `cta_section_title`, `cta_section_text`, `cta_button_text`, `footer_description`, `contact_email`, `contact_phone`, `contact_address`, `created_at`, `updated_at`, `laundry_latitude`, `laundry_longitude`, `laundry_address`) VALUES
(1, 'Laundry Mudah, Hidup Lebih Santai coyy', 'Pesan online, kami jemput & cuci, antar kembali bersih. Simple!', 'Pesan Sekarang', 'Lacak Pesanan', 'Cara Kerja', 'Tiga langkah mudah untuk cucian bersih dan wangi', 'Layanan Kami', 'Pilih paket yang sesuai dengan kebutuhanmu', 'Kenapa Pilih Kami?', 'Komitmen kami untuk memberikan layanan terbaik', 'Yuk, Coba Sekarang!', 'Rasakan pengalaman laundry yang mudah dan menyenangkan. Order pertamamu menanti!', 'Mulai Order →', 'Layanan laundry online terpercaya dengan pickup & delivery untuk kemudahan hidupmu.', 'hello@laundryku.com', '+62 812-3456-7890', 'Jakarta, Indonesia', '2026-01-30 07:36:43', '2026-02-01 10:44:37', -0.11850670, 100.56612400, 'VHJ8+HCX, Jalan Raya, Mungka, Lima Puluh Kota Regency, West Sumatra 26253');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_28_151841_add_role_to_users_table', 1),
(5, '2026_01_28_154500_create_laundry_tables', 1),
(6, '2026_01_29_151500_add_offline_fields_to_orders_table', 2),
(7, '2026_01_30_143000_create_landing_page_settings_table', 3),
(8, '2026_01_30_152715_add_coordinates_to_orders_table', 4),
(9, '2026_02_02_160543_create_notifications_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('1e8fa5c3-a415-4d77-8522-26697b92c0cb', 'App\\Notifications\\NewOrderCreated', 'App\\Models\\User', 1, '{\"order_id\":35,\"order_code\":\"LDRY-2026-0035\",\"customer_name\":\"talingo gadang\",\"title\":\"Pesanan Baru \\ud83c\\udd95\",\"message\":\"Pesanan LDRY-2026-0035 dari talingo gadang perlu diproses.\",\"type\":\"new_order\"}', '2026-02-02 09:18:30', '2026-02-02 09:18:19', '2026-02-02 09:18:30'),
('4fbe43b6-b482-416b-9df8-377d65bf8ffe', 'App\\Notifications\\OrderFinished', 'App\\Models\\User', 10, '{\"order_id\":35,\"order_code\":\"LDRY-2026-0035\",\"title\":\"Laundry Selesai \\ud83e\\uddfa\",\"message\":\"Hore! Laundry kamu dengan kode LDRY-2026-0035 sudah selesai dicuci.\",\"type\":\"finished\"}', '2026-02-02 09:19:09', '2026-02-02 09:18:49', '2026-02-02 09:19:09'),
('a4451267-e617-42cb-8bfd-eebf289c52ca', 'App\\Notifications\\OrderFinished', 'App\\Models\\User', 10, '{\"order_id\":34,\"order_code\":\"LDRY-2026-0034\",\"title\":\"Laundry Selesai \\ud83e\\uddfa\",\"message\":\"Hore! Laundry kamu dengan kode LDRY-2026-0034 sudah selesai dicuci.\",\"type\":\"finished\"}', '2026-02-02 09:13:56', '2026-02-02 09:11:17', '2026-02-02 09:13:56'),
('e68869ba-2ac6-4192-afa9-f4696a1421e0', 'App\\Notifications\\OrderFinished', 'App\\Models\\User', 10, '{\"order_id\":33,\"order_code\":\"LDRY-2026-0033\",\"title\":\"Laundry Selesai \\ud83e\\uddfa\",\"message\":\"Hore! Laundry kamu dengan kode LDRY-2026-0033 sudah selesai dicuci.\",\"type\":\"finished\"}', '2026-02-02 09:17:52', '2026-02-02 09:08:03', '2026-02-02 09:17:52');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_code` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `customer_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_source` enum('online','offline') NOT NULL DEFAULT 'online',
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bundle_id` bigint(20) UNSIGNED DEFAULT NULL,
  `promo_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `fabric_type` varchar(255) DEFAULT NULL,
  `weight_kg` decimal(8,2) NOT NULL DEFAULT 0.00,
  `pickup_date` date NOT NULL,
  `pickup_time` time NOT NULL,
  `distance_km` decimal(8,2) NOT NULL DEFAULT 0.00,
  `pickup_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','pickup','process','finished','delivered') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `user_id`, `customer_user_id`, `order_source`, `service_id`, `bundle_id`, `promo_id`, `customer_name`, `phone`, `address`, `latitude`, `longitude`, `fabric_type`, `weight_kg`, `pickup_date`, `pickup_time`, `distance_km`, `pickup_fee`, `subtotal`, `discount`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'LDRY-2026-0001', 2, NULL, 'online', 1, NULL, NULL, 'Customer User anip', '082289235814', 'kos', NULL, NULL, NULL, 2.00, '2026-01-29', '23:56:00', 3.00, 5000.00, 14000.00, 0.00, 19000.00, 'delivered', '2026-01-28 09:57:10', '2026-01-29 06:59:22'),
(2, 'LDRY-2026-0002', 2, NULL, 'online', NULL, 1, NULL, 'nip2', '082289235814', 'domain', NULL, NULL, NULL, 0.00, '2026-01-31', '23:00:00', 5.00, 15000.00, 35000.00, 0.00, 50000.00, 'delivered', '2026-01-29 07:01:07', '2026-01-29 07:02:25'),
(3, 'LDRY-2026-0003', 2, NULL, 'online', 1, NULL, NULL, 'nuru', '082289235814', 'rumah siii', NULL, NULL, NULL, 1.00, '2026-01-31', '21:13:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'process', '2026-01-29 07:13:20', '2026-01-29 07:16:03'),
(4, 'LDRY-2026-0004', 2, NULL, 'online', 1, NULL, 1, 'dul', '082289235814', 'biasa', NULL, NULL, NULL, 1.00, '2026-01-31', '12:27:00', 1.00, 0.00, 7000.00, 1000.00, 6000.00, 'pending', '2026-01-29 07:25:07', '2026-01-29 07:25:07'),
(5, 'LDRY-2026-0005', 2, NULL, 'online', 1, NULL, NULL, 'dul_2', '082289235814', 'biasa', NULL, NULL, NULL, 10.00, '2026-01-31', '22:31:00', 3.00, 5000.00, 70000.00, 0.00, 75000.00, 'delivered', '2026-01-29 07:30:27', '2026-01-29 07:31:12'),
(6, 'LDRY-2026-0006', 2, NULL, 'online', 1, NULL, 1, 'dul_3', '082289235814', 'bisas', NULL, NULL, NULL, 10.00, '2026-01-31', '22:35:00', 1.00, 0.00, 70000.00, 1000.00, 69000.00, 'finished', '2026-01-29 07:35:10', '2026-01-29 07:35:28'),
(7, 'LDRY-2026-0007', 2, NULL, 'online', 1, NULL, 1, 'dul_4', '082289235814', 'saa', NULL, NULL, NULL, 10.00, '2026-01-31', '22:49:00', 1.00, 0.00, 70000.00, 1000.00, 69000.00, 'finished', '2026-01-29 07:50:06', '2026-01-29 07:52:00'),
(8, 'LDRY-2026-0008', 1, NULL, 'online', 1, NULL, NULL, 'dul_2', '08181818', 'Walk-in Customer (Offline)', NULL, NULL, NULL, 2.00, '2026-01-29', '15:04:23', 0.00, 0.00, 14000.00, 0.00, 14000.00, 'finished', '2026-01-29 08:04:23', '2026-01-29 08:31:36'),
(9, 'LDRY-2026-0009', 1, NULL, 'offline', 1, NULL, 1, 'nip2', '08181818', 'Walk-in Customer (Offline)', NULL, NULL, NULL, 10.00, '2026-01-29', '15:30:03', 0.00, 0.00, 70000.00, 1000.00, 69000.00, 'process', '2026-01-29 08:30:03', '2026-01-29 08:30:03'),
(10, 'LDRY-2026-0010', 2, 2, 'online', 1, NULL, 1, 'palah', '082289235814', 'kos', NULL, NULL, NULL, 10.00, '2026-01-31', '23:33:00', 1.00, 0.00, 70000.00, 1000.00, 69000.00, 'finished', '2026-01-29 08:32:46', '2026-01-29 08:33:04'),
(11, 'LDRY-2026-0011', 5, 5, 'online', 1, NULL, NULL, 'Abdul Hanif', '082289235814', 'biasa', NULL, NULL, NULL, 1.00, '2026-01-31', '22:12:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-01-30 07:12:35', '2026-01-30 07:12:35'),
(12, 'LDRY-2026-0012', 2, 2, 'online', 1, NULL, NULL, 'hanif', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 1.00, '2026-01-31', '23:33:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pickup', '2026-01-30 08:32:21', '2026-01-30 08:35:17'),
(13, 'LDRY-2026-0013', 2, 2, 'online', 1, NULL, NULL, 'ani', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 1.00, '2026-01-31', '23:42:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-01-30 08:42:15', '2026-01-30 08:42:15'),
(14, 'LDRY-2026-0014', 2, 2, 'online', 1, NULL, NULL, 'ani', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 1.00, '2026-01-31', '23:42:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-01-30 08:42:16', '2026-01-30 08:42:16'),
(15, 'LDRY-2026-0015', 2, 2, 'online', 1, NULL, NULL, 'ani', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 1.00, '2026-01-31', '23:42:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-01-30 08:42:17', '2026-01-30 08:42:17'),
(16, 'LDRY-2026-0016', 2, 2, 'online', 1, NULL, NULL, 'ani', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 1.00, '2026-01-31', '23:42:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-01-30 08:42:18', '2026-01-30 08:42:18'),
(17, 'LDRY-2026-0017', 2, 2, 'online', 1, NULL, NULL, 'ani', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 1.00, '2026-01-31', '23:42:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-01-30 08:42:18', '2026-01-30 08:42:18'),
(18, 'LDRY-2026-0018', 2, 2, 'online', 1, NULL, NULL, 'ani', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 1.00, '2026-01-31', '23:42:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-01-30 08:42:20', '2026-01-30 08:42:20'),
(19, 'LDRY-2026-0019', 2, 2, 'online', 1, NULL, NULL, 'ani', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 1.00, '2026-01-31', '23:42:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-01-30 08:42:21', '2026-01-30 08:42:21'),
(20, 'LDRY-2026-0020', 2, 2, 'online', 1, NULL, NULL, 'Customer User', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 1.00, '2026-01-31', '12:48:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-01-30 08:48:46', '2026-01-30 08:48:46'),
(21, 'LDRY-2026-0021', 6, 6, 'online', 1, NULL, NULL, 'adul', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 1.00, '2026-01-31', '22:50:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-01-30 08:50:52', '2026-01-30 08:50:52'),
(22, 'LDRY-2026-0022', 6, 6, 'online', 1, NULL, NULL, 'adul', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 1.00, '2026-01-31', '23:54:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-01-30 08:54:39', '2026-01-30 08:54:39'),
(23, 'LDRY-2026-0023', 2, 2, 'online', NULL, 1, NULL, 'Customer User', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 0.00, '2026-01-31', '12:04:00', 1.00, 0.00, 35000.00, 0.00, 35000.00, 'pending', '2026-01-30 09:04:53', '2026-01-30 09:04:53'),
(24, 'LDRY-2026-0024', 8, 8, 'online', 1, NULL, NULL, 'erlandagsya', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 1.00, '2026-02-03', '22:16:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-02-01 08:16:24', '2026-02-01 08:16:24'),
(25, 'LDRY-2026-0025', 8, 8, 'online', 1, NULL, NULL, 'erlandagsya', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 8.00, '2026-02-02', '00:17:00', 9.00, 35000.00, 56000.00, 0.00, 91000.00, 'pending', '2026-02-01 08:18:16', '2026-02-01 08:18:16'),
(26, 'LDRY-2026-0026', 10, 10, 'online', 1, NULL, NULL, 'talingo gadang', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 1.00, '2026-02-11', '12:10:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-02-01 09:10:31', '2026-02-01 09:10:31'),
(27, 'LDRY-2026-0027', 11, 11, 'online', 1, NULL, NULL, 'dul2', '082289235815', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', NULL, NULL, NULL, 1.00, '2026-02-05', '23:45:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-02-01 09:45:25', '2026-02-01 09:45:25'),
(28, 'LDRY-2026-0028', 11, 11, 'online', 1, NULL, NULL, 'dul2', '082289235815', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', -0.12947800, 100.53533200, NULL, 1.00, '2026-02-05', '23:54:00', 1.00, 0.00, 7000.00, 0.00, 7000.00, 'pending', '2026-02-01 09:54:42', '2026-02-01 09:54:42'),
(29, 'LDRY-2026-0029', 11, 11, 'online', NULL, 1, NULL, 'dul2', '082289235815', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', -0.12947800, 100.53533200, NULL, 0.00, '2026-02-07', '23:58:00', 1.00, 0.00, 35000.00, 0.00, 35000.00, 'pending', '2026-02-01 09:58:13', '2026-02-01 09:58:13'),
(30, 'LDRY-2026-0030', 11, 11, 'online', 1, NULL, NULL, 'dul2', '082289235815', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', -0.12953171, 100.53544218, NULL, 1.00, '2026-02-05', '00:45:00', 3.60, 8000.00, 7000.00, 0.00, 15000.00, 'pending', '2026-02-01 10:45:46', '2026-02-01 10:45:46'),
(31, 'LDRY-2026-0031', 10, 10, 'online', 1, NULL, NULL, 'talingo gadang', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', -0.12948366, 100.53534211, NULL, 1.00, '2026-02-05', '21:41:00', 3.60, 8000.00, 7000.00, 0.00, 15000.00, 'finished', '2026-02-02 07:42:09', '2026-02-02 07:43:49'),
(32, 'LDRY-2026-0032', 1, 10, 'offline', 1, NULL, NULL, 'talingo gadang', '082289235814', 'Walk-in Customer (Offline)', NULL, NULL, NULL, 1.00, '2026-02-02', '14:46:56', 0.00, 0.00, 7000.00, 0.00, 7000.00, 'finished', '2026-02-02 07:46:56', '2026-02-02 08:08:42'),
(33, 'LDRY-2026-0033', 10, 10, 'online', 2, NULL, NULL, 'talingo gadang', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', -0.12947800, 100.53533200, NULL, 1.00, '2026-02-05', '23:07:00', 3.60, 8000.00, 5000.00, 0.00, 13000.00, 'finished', '2026-02-02 09:07:47', '2026-02-02 09:08:01'),
(34, 'LDRY-2026-0034', 10, 10, 'online', 1, NULL, NULL, 'talingo gadang', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', -0.12948366, 100.53534211, NULL, 5.00, '2026-02-05', '12:10:00', 3.60, 8000.00, 35000.00, 0.00, 43000.00, 'finished', '2026-02-02 09:11:00', '2026-02-02 09:11:17'),
(35, 'LDRY-2026-0035', 10, 10, 'online', 1, NULL, NULL, 'talingo gadang', '082289235814', 'Guguak VIII Koto, Lima Puluh Kota, West Sumatra, Sumatra, 26255, Indonesia', -0.12948366, 100.53534211, NULL, 10.00, '2026-02-04', '12:18:00', 3.60, 8000.00, 70000.00, 0.00, 78000.00, 'finished', '2026-02-02 09:18:19', '2026-02-02 09:18:49');

-- --------------------------------------------------------

--
-- Table structure for table `order_trackings`
--

CREATE TABLE `order_trackings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_trackings`
--

INSERT INTO `order_trackings` (`id`, `order_id`, `status`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'process', 'Status updated to Process by Admin', '2026-01-29 06:57:59', '2026-01-29 06:57:59'),
(2, 1, 'finished', 'Status updated to Finished by Admin', '2026-01-29 06:58:12', '2026-01-29 06:58:12'),
(3, 1, 'delivered', 'Status updated to Delivered by Admin', '2026-01-29 06:59:22', '2026-01-29 06:59:22'),
(4, 2, 'pickup', 'Status updated to Pickup by Admin', '2026-01-29 07:01:28', '2026-01-29 07:01:28'),
(5, 2, 'process', 'Status updated to Process by Admin', '2026-01-29 07:01:38', '2026-01-29 07:01:38'),
(6, 2, 'finished', 'Status updated to Finished by Admin', '2026-01-29 07:01:50', '2026-01-29 07:01:50'),
(7, 2, 'delivered', 'Status updated to Delivered by Admin', '2026-01-29 07:02:25', '2026-01-29 07:02:25'),
(8, 3, 'pickup', 'Status updated to Pickup by Admin', '2026-01-29 07:15:48', '2026-01-29 07:15:48'),
(9, 3, 'process', 'Status updated to Process by Admin', '2026-01-29 07:16:03', '2026-01-29 07:16:03'),
(10, 5, 'pickup', 'Status updated to Pickup by Admin', '2026-01-29 07:30:58', '2026-01-29 07:30:58'),
(11, 5, 'process', 'Status updated to Process by Admin', '2026-01-29 07:31:02', '2026-01-29 07:31:02'),
(12, 5, 'finished', 'Status updated to Finished by Admin', '2026-01-29 07:31:06', '2026-01-29 07:31:06'),
(13, 5, 'delivered', 'Status updated to Delivered by Admin', '2026-01-29 07:31:12', '2026-01-29 07:31:12'),
(14, 6, 'finished', 'Status updated to Finished by Admin', '2026-01-29 07:35:28', '2026-01-29 07:35:28'),
(15, 6, 'point_added', 'Customer earned 10 points', '2026-01-29 07:35:28', '2026-01-29 07:35:28'),
(16, 7, 'delivered', 'Status updated to Delivered by Admin', '2026-01-29 07:51:49', '2026-01-29 07:51:49'),
(17, 7, 'finished', 'Status updated to Finished by Admin', '2026-01-29 07:52:00', '2026-01-29 07:52:00'),
(18, 7, 'point_added', 'Customer earned 10 points', '2026-01-29 07:52:00', '2026-01-29 07:52:00'),
(19, 8, 'process', 'Offline Order created by Admin', '2026-01-29 08:04:23', '2026-01-29 08:04:23'),
(20, 9, 'process', 'Offline Order created by Admin', '2026-01-29 08:30:03', '2026-01-29 08:30:03'),
(21, 8, 'finished', 'Status updated to Finished by Admin', '2026-01-29 08:31:36', '2026-01-29 08:31:36'),
(22, 8, 'point_added', 'Customer earned 2 points', '2026-01-29 08:31:36', '2026-01-29 08:31:36'),
(23, 10, 'finished', 'Status updated to Finished by Admin', '2026-01-29 08:33:04', '2026-01-29 08:33:04'),
(24, 10, 'point_added', 'Customer earned 10 points', '2026-01-29 08:33:04', '2026-01-29 08:33:04'),
(25, 12, 'pickup', 'Status updated to Pickup by Admin', '2026-01-30 08:35:17', '2026-01-30 08:35:17'),
(26, 31, 'process', 'Status updated to Process by Admin', '2026-02-02 07:43:21', '2026-02-02 07:43:21'),
(27, 31, 'finished', 'Status updated to Finished by Admin', '2026-02-02 07:43:49', '2026-02-02 07:43:49'),
(28, 31, 'point_added', 'Customer earned 1 points', '2026-02-02 07:43:49', '2026-02-02 07:43:49'),
(29, 32, 'process', 'Offline Order created by Admin', '2026-02-02 07:46:56', '2026-02-02 07:46:56'),
(30, 32, 'finished', 'Status updated to Finished by Admin', '2026-02-02 08:08:42', '2026-02-02 08:08:42'),
(31, 32, 'point_added', 'Customer earned 1 points', '2026-02-02 08:08:42', '2026-02-02 08:08:42'),
(32, 33, 'finished', 'Status updated to Finished by Admin', '2026-02-02 09:08:01', '2026-02-02 09:08:01'),
(33, 33, 'point_added', 'Customer earned 1 points', '2026-02-02 09:08:03', '2026-02-02 09:08:03'),
(34, 34, 'finished', 'Status updated to Finished by Admin', '2026-02-02 09:11:17', '2026-02-02 09:11:17'),
(35, 34, 'point_added', 'Customer earned 5 points', '2026-02-02 09:11:17', '2026-02-02 09:11:17'),
(36, 35, 'pending', 'Order created by customer', '2026-02-02 09:18:19', '2026-02-02 09:18:19'),
(37, 35, 'pickup', 'Status updated to Pickup by Admin', '2026-02-02 09:18:39', '2026-02-02 09:18:39'),
(38, 35, 'finished', 'Status updated to Finished by Admin', '2026-02-02 09:18:49', '2026-02-02 09:18:49'),
(39, 35, 'point_added', 'Customer earned 10 points', '2026-02-02 09:18:49', '2026-02-02 09:18:49');

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
-- Table structure for table `promos`
--

CREATE TABLE `promos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `discount_type` enum('percent','fixed') NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `expired_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promos`
--

INSERT INTO `promos` (`id`, `code`, `discount_type`, `value`, `expired_at`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'PROMO', 'fixed', 1000.00, '2026-01-30 00:00:00', 1, '2026-01-29 07:23:30', '2026-01-29 07:23:41');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `order_id`, `user_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 7, 2, 5, 'busuk', '2026-01-29 07:52:27', '2026-01-29 07:52:27');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price_per_kg` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `price_per_kg`, `created_at`, `updated_at`) VALUES
(1, 'Cuci Komplit', 7000.00, '2026-01-28 09:55:20', '2026-01-28 09:55:20'),
(2, 'Setrika Saja', 5000.00, '2026-01-28 09:55:20', '2026-01-28 09:55:20'),
(3, 'Cuci Reguler', 2000.00, '2026-01-29 09:25:05', '2026-01-29 09:25:05');

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
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `points` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `phone`, `address`, `points`) VALUES
(1, 'Admin User', 'admin@gmail.com', '2026-01-28 09:15:55', '$2y$12$Wc36br/Ldxl8.N.mNgDc2.IEFnYAfNPx4.OP52NrRaT3KlQizmTyW', 'Yjh4jlc0etohjI8p25tcJhZBYDPJOKiNSbMj30HU27NJWYC89sLnIqWcSdBH', '2026-01-28 09:15:56', '2026-02-02 08:08:42', 'admin', NULL, NULL, 3),
(2, 'Customer User', 'customer@gmail.com', '2026-01-28 09:15:56', '$2y$12$nbtnw8bjgmf4khdhxILR1.X5wN6mp/UaFGPoR5DCOrV3HWGnfjAxa', 'ggESm5RjahDMtaO4rIryhqRB9wMqHEs5LQm0wOadyFgivLYsZu13GmT4or9l', '2026-01-28 09:15:56', '2026-01-29 08:33:04', 'customer', NULL, NULL, 30),
(4, 'Abdul Hanif', 'nip@gmail.com', NULL, '$2y$12$Mmedp88m7HLZZRutEJPNLOp93aksrH5b/u.yPwaqKuCmNmvfZaUvq', NULL, '2026-01-30 06:57:58', '2026-01-30 06:57:58', 'customer', NULL, NULL, 0),
(5, 'Abdul Hanif', 'anip@gmail.com', NULL, '$2y$12$GgZUWgUpJTClrYuA7tn6/.Cjhb.lkFp1T6.fEpxnIefldhdFB.tzW', NULL, '2026-01-30 07:03:14', '2026-01-30 07:03:14', 'customer', NULL, NULL, 0),
(6, 'adul', 'dul@gmail.com', NULL, '$2y$12$4nt6zGZhFIN.EDWqk3gELuo7eFM3A1Wmi9rFd9WdQEd3zAwlIBYea', NULL, '2026-01-30 08:50:13', '2026-01-30 08:50:13', 'customer', NULL, NULL, 0),
(7, 'hanif_1', 'nif@gmail.com', NULL, '$2y$12$4lU9Gw2hI32N1Y94nvkEl.DN.ZJBpuIV5fe6tkbHtJ2AE2e/Fg4fC', NULL, '2026-02-01 08:01:57', '2026-02-01 08:01:57', 'customer', NULL, NULL, 0),
(8, 'erlandagsya', 'kontol123@gmail.com', NULL, '$2y$12$P/oKzNPBjznzzRuw46xJPeP5TRUpd0XeUXWuCoqNyvK4HL4fdkniK', NULL, '2026-02-01 08:07:31', '2026-02-01 08:07:31', 'customer', NULL, NULL, 0),
(9, 'Abdul Hanif', 'nip2@gmail.com', NULL, '$2y$12$N2IenrmoFFwj0ILDROTXFuEvmiO8j8P4juqcLkkSFYYPB70EVdiV6', NULL, '2026-02-01 08:56:37', '2026-02-01 08:56:37', 'customer', NULL, NULL, 0),
(10, 'talingo gadang', 'lingo@gmail.com', NULL, '$2y$12$2LAbH7/jLOSFYg1EvdWjbez4E6mP8EeuNN4QKcQW3gjwGOHdYtcZO', NULL, '2026-02-01 09:08:26', '2026-02-02 09:18:49', 'customer', '082289235814', NULL, 17),
(11, 'dul2', 'dul2@gmail.com', NULL, '$2y$12$2x10.uNo9Tc5.P2Z9BHreeod5PirKWoStT9sIRzrL5WfEvRgUZUpq', NULL, '2026-02-01 09:36:13', '2026-02-01 09:36:13', 'customer', '082289235815', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bundles`
--
ALTER TABLE `bundles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `landing_page_settings`
--
ALTER TABLE `landing_page_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_code_unique` (`order_code`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_service_id_foreign` (`service_id`),
  ADD KEY `orders_bundle_id_foreign` (`bundle_id`),
  ADD KEY `orders_promo_id_foreign` (`promo_id`),
  ADD KEY `orders_customer_user_id_foreign` (`customer_user_id`);

--
-- Indexes for table `order_trackings`
--
ALTER TABLE `order_trackings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_trackings_order_id_foreign` (`order_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `promos`
--
ALTER TABLE `promos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `promos_code_unique` (`code`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_order_id_foreign` (`order_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bundles`
--
ALTER TABLE `bundles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `landing_page_settings`
--
ALTER TABLE `landing_page_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `order_trackings`
--
ALTER TABLE `order_trackings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `promos`
--
ALTER TABLE `promos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_bundle_id_foreign` FOREIGN KEY (`bundle_id`) REFERENCES `bundles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_customer_user_id_foreign` FOREIGN KEY (`customer_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_promo_id_foreign` FOREIGN KEY (`promo_id`) REFERENCES `promos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_trackings`
--
ALTER TABLE `order_trackings`
  ADD CONSTRAINT `order_trackings_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
