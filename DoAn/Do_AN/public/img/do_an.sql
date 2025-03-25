-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 16, 2025 lúc 02:35 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `do_an`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuyen_bay`
--

CREATE TABLE `chuyen_bay` (
  `id_chuyen_bay` int(11) NOT NULL,
  `ma_chuyen_bay` varchar(10) NOT NULL,
  `diem_di` varchar(100) NOT NULL,
  `diem_den` varchar(100) NOT NULL,
  `ngay_gio_khoi_hanh` datetime NOT NULL,
  `ngay_gio_den` datetime NOT NULL,
  `gia_ve_co_ban` decimal(15,2) NOT NULL,
  `so_ghe_trong` int(11) NOT NULL,
  `id_hang_bay` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chuyen_bay`
--

INSERT INTO `chuyen_bay` (`id_chuyen_bay`, `ma_chuyen_bay`, `diem_di`, `diem_den`, `ngay_gio_khoi_hanh`, `ngay_gio_den`, `gia_ve_co_ban`, `so_ghe_trong`, `id_hang_bay`) VALUES
(1, 'VN1001', 'Hà Nội', 'Hồ Chí Minh', '2025-03-10 06:30:00', '2025-03-10 08:40:00', 1550000.00, 150, 1),
(2, 'VN1002', 'Hà Nội', 'Hồ Chí Minh', '2025-03-10 09:15:00', '2025-03-10 11:25:00', 1620000.00, 120, 1),
(3, 'VN1003', 'Hà Nội', 'Hồ Chí Minh', '2025-03-10 14:45:00', '2025-03-10 16:55:00', 1480000.00, 180, 1),
(4, 'VJ1001', 'Hà Nội', 'Hồ Chí Minh', '2025-03-10 07:00:00', '2025-03-10 09:15:00', 1320000.00, 170, 2),
(5, 'VJ1002', 'Hà Nội', 'Hồ Chí Minh', '2025-03-10 12:30:00', '2025-03-10 14:45:00', 1280000.00, 160, 2),
(6, 'BA1001', 'Hà Nội', 'Hồ Chí Minh', '2025-03-10 08:15:00', '2025-03-10 10:25:00', 1580000.00, 140, 3),
(7, 'VN2001', 'Hồ Chí Minh', 'Hà Nội', '2025-03-11 07:00:00', '2025-03-11 09:10:00', 1550000.00, 150, 1),
(8, 'VN2002', 'Hồ Chí Minh', 'Hà Nội', '2025-03-11 10:30:00', '2025-03-11 12:40:00', 1620000.00, 120, 1),
(9, 'VJ2001', 'Hồ Chí Minh', 'Hà Nội', '2025-03-11 08:45:00', '2025-03-11 10:55:00', 1320000.00, 170, 2),
(10, 'BA2001', 'Hồ Chí Minh', 'Hà Nội', '2025-03-11 11:15:00', '2025-03-11 13:25:00', 1580000.00, 140, 3),
(11, 'VN3001', 'Hà Nội', 'Đà Nẵng', '2025-03-12 07:30:00', '2025-03-12 08:50:00', 1120000.00, 130, 1),
(12, 'VN3002', 'Hà Nội', 'Đà Nẵng', '2025-03-12 13:15:00', '2025-03-12 14:35:00', 1180000.00, 110, 1),
(13, 'VJ3001', 'Hà Nội', 'Đà Nẵng', '2025-03-12 09:00:00', '2025-03-12 10:20:00', 980000.00, 160, 2),
(14, 'BA3001', 'Hà Nội', 'Đà Nẵng', '2025-03-12 15:45:00', '2025-03-12 17:05:00', 1150000.00, 125, 3),
(15, 'VN4001', 'Đà Nẵng', 'Hà Nội', '2025-03-13 08:00:00', '2025-03-13 09:20:00', 1120000.00, 130, 1),
(16, 'VJ4001', 'Đà Nẵng', 'Hà Nội', '2025-03-13 11:30:00', '2025-03-13 12:50:00', 980000.00, 160, 2),
(17, 'VN5001', 'Hồ Chí Minh', 'Đà Nẵng', '2025-03-14 08:30:00', '2025-03-14 09:40:00', 1130000.00, 140, 1),
(18, 'VJ5001', 'Hồ Chí Minh', 'Đà Nẵng', '2025-03-14 10:15:00', '2025-03-14 11:25:00', 950000.00, 165, 2),
(19, 'BA5001', 'Hồ Chí Minh', 'Đà Nẵng', '2025-03-14 14:30:00', '2025-03-14 15:40:00', 1080000.00, 130, 3),
(20, 'VN6001', 'Đà Nẵng', 'Hồ Chí Minh', '2025-03-15 09:15:00', '2025-03-15 10:25:00', 1130000.00, 140, 1),
(21, 'VJ6001', 'Đà Nẵng', 'Hồ Chí Minh', '2025-03-15 12:45:00', '2025-03-15 13:55:00', 950000.00, 165, 2),
(22, 'VN7001', 'Hồ Chí Minh', 'Phú Quốc', '2025-03-16 08:00:00', '2025-03-16 09:00:00', 1110000.00, 120, 1),
(23, 'VJ7001', 'Hồ Chí Minh', 'Phú Quốc', '2025-03-16 10:30:00', '2025-03-16 11:30:00', 930000.00, 150, 2),
(24, 'VN8001', 'Hồ Chí Minh', 'Huế', '2025-03-17 07:45:00', '2025-03-17 09:00:00', 1130000.00, 120, 1),
(25, 'VJ8001', 'Hồ Chí Minh', 'Huế', '2025-03-17 11:15:00', '2025-03-17 12:30:00', 970000.00, 155, 2),
(26, 'VN1010', 'Hà Nội', 'Hồ Chí Minh', '2025-03-10 08:30:00', '2025-03-10 10:40:00', 1550000.00, 150, 1),
(27, 'VN1011', 'Hà Nội', 'Hồ Chí Minh', '2025-03-10 14:30:00', '2025-03-10 16:40:00', 1620000.00, 120, 1),
(28, 'VJ1010', 'Hà Nội', 'Hồ Chí Minh', '2025-03-10 10:00:00', '2025-03-10 12:15:00', 1320000.00, 170, 2),
(29, 'VN2010', 'Hồ Chí Minh', 'Hà Nội', '2025-03-10 09:00:00', '2025-03-10 11:10:00', 1550000.00, 150, 1),
(30, 'VJ2010', 'Hồ Chí Minh', 'Hà Nội', '2025-03-10 11:45:00', '2025-03-10 13:55:00', 1320000.00, 170, 2),
(31, 'VN3010', 'Hà Nội', 'Đà Nẵng', '2025-03-10 08:30:00', '2025-03-10 09:50:00', 1120000.00, 130, 1),
(32, 'VJ3010', 'Hà Nội', 'Đà Nẵng', '2025-03-10 12:00:00', '2025-03-10 13:20:00', 980000.00, 160, 2),
(33, 'VN1020', 'Hà Nội', 'Hồ Chí Minh', '2025-03-11 08:30:00', '2025-03-11 10:40:00', 1550000.00, 150, 1),
(34, 'VJ1020', 'Hà Nội', 'Hồ Chí Minh', '2025-03-11 10:00:00', '2025-03-11 12:15:00', 1320000.00, 170, 2),
(35, 'VN2020', 'Hồ Chí Minh', 'Hà Nội', '2025-03-11 09:00:00', '2025-03-11 11:10:00', 1550000.00, 150, 1),
(36, 'VJ2020', 'Hồ Chí Minh', 'Hà Nội', '2025-03-11 11:45:00', '2025-03-11 13:55:00', 1320000.00, 170, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
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
-- Cấu trúc bảng cho bảng `hang_bay`
--

CREATE TABLE `hang_bay` (
  `id_hang_bay` int(11) NOT NULL,
  `ten_hang_bay` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hang_bay`
--

INSERT INTO `hang_bay` (`id_hang_bay`, `ten_hang_bay`, `logo`) VALUES
(1, 'Vietnam Airlines', 'vietnam_airlines_logo.png'),
(2, 'VietJet Air', 'vietjet_logo.png'),
(3, 'Bamboo Airways', 'bamboo_logo.png'),
(4, 'Pacific Airlines', 'pacific_logo.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
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
-- Cấu trúc bảng cho bảng `job_batches`
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
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `id_nguoi_dung` int(11) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `so_dien_thoai` varchar(15) DEFAULT NULL,
  `ngay_tao` datetime DEFAULT current_timestamp(),
  `role` varchar(50) DEFAULT NULL,
  `blocked_until` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id_nguoi_dung`, `ho_ten`, `email`, `password`, `so_dien_thoai`, `ngay_tao`, `role`, `blocked_until`) VALUES
(1, 'khang', 'trongkhangdinh.2003@gmail.com', '$2y$12$Ce08zPTFsLl7EjYej92bmutRGWxuovpOW2lpLijbBayoqfU6lHfwG', '0919575638', '2025-03-07 07:52:23', 'Admin', NULL),
(2, 'trongkhang', 'dinhtrongkhang.2003@gmail.com', '$2y$12$JYBxIzB9a5SIym893VVQde5bYcyUOOYY6aFEE9jYO5lauLJObeb9W', '0919575638', '2025-03-13 05:31:49', 'user', NULL),
(3, 'trihaicot', 'trihaicot@gmail.com', '$2y$12$aozeX1BZpM94BZDSnaXuTOlTZkz1C3jyEFDE3L8G32vgCD7c62H0.', '0919575666', '2025-03-13 10:02:41', 'user', NULL),
(4, 'dangtrancuc', 'dungkcr17@gmail.com', '$2y$12$fBPQPbphD/LUbvx9PFyPyONAP5wlRR3/Z3CaYXzsbO66JnwRJArV6', '0919575665', '2025-03-13 15:05:59', 'user', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
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
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0B6zGKwAQ1nOn6SLFMb8mw5PQKb3XuFUesMeEmbq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoieVRTcGdDS09jb0Roc3pEZ1BkT25GbGdYeE5qVG9ieE1UOFFqbW83WCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NjoibG9jYWxlIjtzOjI6ImVuIjt9', 1742054870),
('0YgQq9BXv1sjgwkVXI5ycDPxiiOg4E0yrFvT9RJD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiVUV5R1FoM0VEcTBLQmM0UmpqazR1ck5JTDBldFM3cVRqSmF4bEhYUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1742053689),
('XTmJpv4DewJlmPHIvcLbHRE71HhCbJJ7qiGynYwh', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiWER1MGRlQXNmZDBFaWZmSWpVd1AyTGQwVDZCUUFRMGFxaWhuZ3VtZSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC92aWV0bmFtLWFpcmxpbmVzP3BhZ2U9MSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NjoibG9jYWxlIjtzOjI6InZpIjtzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTM6InNlYXJjaF9wYXJhbXMiO2E6NDp7czo3OiJkaWVtX2RpIjtzOjk6IkjDoCBO4buZaSI7czo4OiJkaWVtX2RlbiI7czoxNDoiSOG7kyBDaMOtIE1pbmgiO3M6NzoibmdheV9kaSI7czoxMDoiMjAyNS0wMy0xNiI7czoxMzoic29faGFuaF9raGFjaCI7czoxOiIxIjt9fQ==', 1742131769);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanh_toan`
--

CREATE TABLE `thanh_toan` (
  `id_thanh_toan` int(11) NOT NULL,
  `id_ve` int(11) DEFAULT NULL,
  `phuong_thuc` varchar(50) NOT NULL,
  `so_tien` decimal(15,2) NOT NULL,
  `ngay_thanh_toan` datetime DEFAULT current_timestamp(),
  `trang_thai` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
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

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ve_may_bay`
--

CREATE TABLE `ve_may_bay` (
  `id_ve` int(11) NOT NULL,
  `id_nguoi_dung` int(11) DEFAULT NULL,
  `id_chuyen_bay` int(11) DEFAULT NULL,
  `ma_ve` varchar(20) NOT NULL,
  `loai_ghe` varchar(50) NOT NULL,
  `gia_ve` decimal(15,2) NOT NULL,
  `ngay_dat` datetime DEFAULT current_timestamp(),
  `trang_thai` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `chuyen_bay`
--
ALTER TABLE `chuyen_bay`
  ADD PRIMARY KEY (`id_chuyen_bay`),
  ADD KEY `id_hang_bay` (`id_hang_bay`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `hang_bay`
--
ALTER TABLE `hang_bay`
  ADD PRIMARY KEY (`id_hang_bay`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`id_nguoi_dung`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD PRIMARY KEY (`id_thanh_toan`),
  ADD KEY `id_ve` (`id_ve`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Chỉ mục cho bảng `ve_may_bay`
--
ALTER TABLE `ve_may_bay`
  ADD PRIMARY KEY (`id_ve`),
  ADD UNIQUE KEY `ma_ve` (`ma_ve`),
  ADD KEY `id_nguoi_dung` (`id_nguoi_dung`),
  ADD KEY `id_chuyen_bay` (`id_chuyen_bay`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chuyen_bay`
--
ALTER TABLE `chuyen_bay`
  MODIFY `id_chuyen_bay` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hang_bay`
--
ALTER TABLE `hang_bay`
  MODIFY `id_hang_bay` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id_nguoi_dung` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  MODIFY `id_thanh_toan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `ve_may_bay`
--
ALTER TABLE `ve_may_bay`
  MODIFY `id_ve` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chuyen_bay`
--
ALTER TABLE `chuyen_bay`
  ADD CONSTRAINT `chuyen_bay_ibfk_1` FOREIGN KEY (`id_hang_bay`) REFERENCES `hang_bay` (`id_hang_bay`);

--
-- Các ràng buộc cho bảng `thanh_toan`
--
ALTER TABLE `thanh_toan`
  ADD CONSTRAINT `thanh_toan_ibfk_1` FOREIGN KEY (`id_ve`) REFERENCES `ve_may_bay` (`id_ve`);

--
-- Các ràng buộc cho bảng `ve_may_bay`
--
ALTER TABLE `ve_may_bay`
  ADD CONSTRAINT `ve_may_bay_ibfk_1` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id_nguoi_dung`),
  ADD CONSTRAINT `ve_may_bay_ibfk_2` FOREIGN KEY (`id_chuyen_bay`) REFERENCES `chuyen_bay` (`id_chuyen_bay`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
