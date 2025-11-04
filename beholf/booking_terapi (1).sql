-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2025 at 06:24 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booking_terapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_booking` varchar(255) DEFAULT NULL,
  `tanggal_booking` date DEFAULT NULL,
  `id_user` bigint(20) UNSIGNED DEFAULT NULL,
  `riwayat_penyakit` varchar(255) DEFAULT NULL,
  `kode_terapi` varchar(255) DEFAULT NULL,
  `jadwal` bigint(20) UNSIGNED DEFAULT NULL,
  `biaya_layanan` bigint(20) DEFAULT 0,
  `status` enum('pending','accepted','cancel','completed') DEFAULT NULL,
  `keluhan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `kode_booking`, `tanggal_booking`, `id_user`, `riwayat_penyakit`, `kode_terapi`, `jadwal`, `biaya_layanan`, `status`, `keluhan`, `created_at`, `created_by`) VALUES
(16, '20250601-JWUOL', '2025-06-01', 7, 'ad', '01', 1, 0, 'cancel', 'dw', '2025-05-31 21:00:05', '7'),
(17, '20250601-F6KBW', '2025-06-01', 7, 'aa', '03', 7, 12233, 'accepted', 'aa', '2025-05-31 21:14:08', '7'),
(18, '20250601-0007', '2025-06-01', 7, 'aaaaaa', '01', 9, 90000, 'accepted', 'aaaaa', NULL, NULL),
(21, '20250601-0008', '2025-06-01', 4, 'sakit mental', '09', 13, 60000, 'accepted', 'banyak coding', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jadwals`
--

CREATE TABLE `jadwals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `id_terapi` bigint(20) UNSIGNED NOT NULL,
  `biaya_jadwal` bigint(20) DEFAULT 0,
  `status` enum('available','unavailable') NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `deleted_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwals`
--

INSERT INTO `jadwals` (`id`, `jam_mulai`, `jam_selesai`, `id_terapi`, `biaya_jadwal`, `status`, `tanggal`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(1, '18:14:00', '22:14:00', 1, 65000, 'unavailable', '2025-05-01', '2025-05-31 02:13:53', '2025-05-31 21:00:05', NULL, '9', '7', NULL),
(2, '06:56:00', '09:56:00', 1, 89000, 'unavailable', '2025-05-31', '2025-05-31 09:56:53', '2025-05-31 20:53:47', NULL, '9', '7', NULL),
(3, '00:01:00', '04:01:00', 1, 45000, 'available', '2025-06-01', '2025-05-31 10:01:35', '2025-05-31 20:53:12', NULL, '9', '7', NULL),
(6, '04:42:00', '08:42:00', 3, 120000, 'available', '2025-06-05', '2025-05-31 14:42:49', '2025-05-31 20:53:07', NULL, '13', '7', NULL),
(7, '10:42:00', '13:42:00', 3, 80000, 'unavailable', '2025-06-14', '2025-05-31 14:43:14', '2025-05-31 21:14:08', NULL, '13', '7', NULL),
(8, '19:46:00', '20:43:00', 3, 90000, 'available', '2025-06-20', '2025-05-31 14:43:33', '2025-05-31 20:52:58', NULL, '13', '7', NULL),
(9, '05:50:00', '09:50:00', 1, 99000, 'unavailable', '2025-06-12', '2025-05-31 15:50:45', '2025-06-01 08:19:26', NULL, '9', '7', NULL),
(10, '11:50:00', '16:50:00', 1, 98000, 'unavailable', '2025-04-10', '2025-05-31 15:51:03', '2025-05-31 20:53:32', NULL, '9', '7', NULL),
(11, '08:51:00', '10:51:00', 1, 77000, 'available', '2025-06-24', '2025-05-31 15:51:21', '2025-05-31 20:52:40', NULL, '9', '7', NULL),
(12, '02:02:00', '06:02:00', 4, 80000, 'available', '2025-06-05', '2025-06-01 09:02:37', '2025-06-01 09:02:37', NULL, '15', NULL, NULL),
(13, '12:02:00', '17:02:00', 4, 89000, 'unavailable', '2025-06-12', '2025-06-01 09:02:54', '2025-06-01 09:10:34', NULL, '15', NULL, NULL),
(14, '02:07:00', '04:03:00', 4, 98000, 'unavailable', '2025-06-19', '2025-06-01 09:03:08', '2025-06-01 09:03:20', NULL, '15', '15', NULL);

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
(1, '2025_05_25_062322_create_users_table', 1),
(2, '2025_05_25_062358_create_terapis_table', 1),
(3, '2025_05_25_062425_create_kategoris_table', 1),
(4, '2025_05_25_074609_create_jadwals_table', 1),
(5, '2025_05_31_051649_create_bookings_table', 1),
(6, '2025_05_31_053502_create_pembayarans_table', 1);

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
-- Table structure for table `pembayarans`
--

CREATE TABLE `pembayarans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomor_pembayaran` varchar(255) NOT NULL,
  `kode_booking` varchar(255) NOT NULL,
  `grand_total` decimal(15,2) NOT NULL,
  `metode_pembayaran` enum('dana','BCA','gopay') NOT NULL,
  `foto_pembayaran` varchar(255) DEFAULT NULL,
  `status` enum('gagal','lunas','pending') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayarans`
--

INSERT INTO `pembayarans` (`id`, `nomor_pembayaran`, `kode_booking`, `grand_total`, `metode_pembayaran`, `foto_pembayaran`, `status`, `created_at`, `created_by`) VALUES
(7, '20250601-0003', '20250601-F6KBW', 92233.00, 'BCA', 'uploads/payments/20250601-0003.JPG', 'pending', NULL, '7'),
(18, '20250601-0001', '20250601-0007', 189000.00, 'gopay', 'uploads/payments/20250601-0001.JPG', 'pending', NULL, '7'),
(19, '20250601-0002', '20250601-0008', 149000.00, 'dana', 'uploads/payments/20250601-0002.JPG', 'lunas', NULL, '4');

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('5GKcJI5jpd9Q5ZRjcG3t55DYPTBvMwanz4yVMUKI', 7, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiNTdaeFJHd1pzNTJTYU1kRjZTQjBOTnF3QmM3Q2J1S080V1l1QW8wWCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czoxMToiY2FwdGNoYV9zdW0iO2k6NztzOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2phZHdhbHMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo3O30=', 1748740719),
('8dYqq4PxFWmA2kmF6VrquKBeUS2M8TIlVuvukBuC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib2pTUllkNkw5MEZrRm1uUXJabmdJMDN6M3VHOEVMOW9GdHg4VGwwMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9qYWR3YWxzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1748740881);

-- --------------------------------------------------------

--
-- Table structure for table `terapis`
--

CREATE TABLE `terapis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `spesialis` varchar(255) NOT NULL,
  `jenis_kelamin` enum('laki-laki','perempuan') NOT NULL,
  `no_hp` varchar(255) NOT NULL,
  `kode_terapi` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `deleted_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `terapis`
--

INSERT INTO `terapis` (`id`, `id_user`, `name`, `spesialis`, `jenis_kelamin`, `no_hp`, `kode_terapi`, `deskripsi`, `foto`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(1, 9, 'Lumi', 'Bipolar disorder therapist', 'perempuan', '091833', '01', 'mental health awareness', 'uploads/fotos/1748682771_▼𝙍𝙤𝙠𝙤𝙍𝙤𝙠𝙤 (@korokor59513559) on X.jpg', '2025-05-31 02:12:51', '2025-05-31 02:12:51', NULL, '8', NULL, NULL),
(3, 13, 'chloe', 'helth', 'perempuan', '0192213', '03', 'get better', 'uploads/fotos/1748727641_cf707499e8a56f52551b65765ff94463.jpg', '2025-05-31 14:40:41', '2025-05-31 14:40:41', NULL, '7', NULL, NULL),
(4, 15, 'chiii', 'aaa', 'perempuan', '0918291', '09', 'kjwkjdw', 'uploads/fotos/1748793529_fail.JPG', '2025-06-01 08:58:49', '2025-06-01 08:59:18', NULL, '7', '7', '7');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `deleted_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `level`, `foto`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`) VALUES
(4, 'sim', 'sim@gmail.com', '$2y$12$lKyHzeFY.FMeSw/mcyOXE.m2hqCwkP0kUS4gjMe9JsizrDB8ouaxG', 4, 'uploads/fotos/1748679864_5ccce873ea68d85341f466ae70ef27a3.jpg', '03jQPlM3MOhqiyZjs5zq0suouKJy5d61ShXyAGfp1PnnZd2FVmOFcy78NPiT', '2025-05-31 01:24:24', '2025-05-31 11:07:54', NULL, NULL, NULL, '7'),
(6, 'Ru', 'ru@gmail.com', '$2y$12$4QHkTYUPKo3Jr1p1G2AhXudwnX2VNWkaPQJlNzkOXe7me6spbDmQC', 2, 'uploads/fotos/1748680597_f21f4a71fcf528a8a0982c9ae9e04ebe.jpg', 'K43WBc5PvE098US2PgP5dyDyy4w1qFCL6P3bWTWJFPR8Q5dbz3uhOwzuXGWm', '2025-05-31 01:35:58', '2025-05-31 01:36:37', NULL, '4', '4', NULL),
(7, 'feifei', 'feira@gmail.com', '$2y$12$.yLDGcQp7BcaVAE2aiPuqeP7c2UvrrvKEHuEPx1hJHwB6fZyEp0Hm', 1, 'uploads/fotos/1748680804_20250110_194925.jpg', 'aMvtce7XXT00Cxhd2YdK1qUw0N88X5oP0Ia2E8PDACC6Sr2QJUNZ20y75N1E', '2025-05-31 01:40:05', '2025-05-31 01:40:05', NULL, '4', NULL, NULL),
(9, 'Lumii', 'lumi@gmail.com', '$2y$12$DaTXrWUY0DCFev0J1KLppeLIuJe4Bs10txvMGTQTSgTOXo.2ieJ/i', 3, 'uploads/fotos/1748682771_▼𝙍𝙤𝙠𝙤𝙍𝙤𝙠𝙤 (@korokor59513559) on X.jpg', 'FIia6xxLhi2UOTLOffdimZMi6hk2h5YZR3GEV2v6V4afTqOi9uphNEqVg9rv', '2025-05-31 02:12:51', '2025-05-31 11:27:34', NULL, NULL, '7', NULL),
(13, 'chloe', 'chloe@Gmail.com', '$2y$12$8Y.yVTr2BgFJlH1Nw3gKZ.rMcoZwg6ORZG8d7aF1qzxx4kiCCvzRy', 3, 'uploads/fotos/1748727641_cf707499e8a56f52551b65765ff94463.jpg', 'hfsdOuWAFFuzZUUEvUvDd5yzXuGFUDSB7JA69ebcV6RMHlJqXrIg3l73X7Le', '2025-05-31 14:40:41', '2025-05-31 14:40:41', NULL, NULL, NULL, NULL),
(15, 'chiii', 'chi@Gmail.com', '$2y$12$nlgbL9CJ8E0KHKlfzVyLS.dqe/sJe14p2Qu7z6Ujj2ZVrSyakZOti', 3, 'uploads/fotos/1748793529_fail.JPG', 'QjMCMSK4Kc0rQ4C1iraLy7zsGDk4j3pFScvKoVeqd1lUgcuPQ2x0a45HJlVG', '2025-06-01 08:58:49', '2025-06-01 08:59:18', NULL, NULL, NULL, '7');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_kode_booking_unique` (`kode_booking`),
  ADD KEY `bookings_id_user_foreign` (`id_user`),
  ADD KEY `bookings_kode_terapi_foreign` (`kode_terapi`),
  ADD KEY `bookings_jadwal_foreign` (`jadwal`);

--
-- Indexes for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwals_id_terapi_foreign` (`id_terapi`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pembayarans_nomor_pembayaran_unique` (`nomor_pembayaran`),
  ADD KEY `pembayarans_kode_booking_foreign` (`kode_booking`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `terapis`
--
ALTER TABLE `terapis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `terapis_kode_terapi_unique` (`kode_terapi`),
  ADD KEY `terapis_id_user_foreign` (`id_user`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `jadwals`
--
ALTER TABLE `jadwals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pembayarans`
--
ALTER TABLE `pembayarans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `terapis`
--
ALTER TABLE `terapis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_jadwal_foreign` FOREIGN KEY (`jadwal`) REFERENCES `jadwals` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_kode_terapi_foreign` FOREIGN KEY (`kode_terapi`) REFERENCES `terapis` (`kode_terapi`) ON DELETE CASCADE;

--
-- Constraints for table `jadwals`
--
ALTER TABLE `jadwals`
  ADD CONSTRAINT `jadwals_id_terapi_foreign` FOREIGN KEY (`id_terapi`) REFERENCES `terapis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayarans`
--
ALTER TABLE `pembayarans`
  ADD CONSTRAINT `pembayarans_kode_booking_foreign` FOREIGN KEY (`kode_booking`) REFERENCES `bookings` (`kode_booking`) ON DELETE CASCADE;

--
-- Constraints for table `terapis`
--
ALTER TABLE `terapis`
  ADD CONSTRAINT `terapis_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
