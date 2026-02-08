-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 08, 2026 at 12:41 PM
-- Server version: 10.5.29-MariaDB
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `diantar2_aktivitas`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `device_name` varchar(100) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `record_name` varchar(255) DEFAULT NULL,
  `old_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_data`)),
  `new_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_data`)),
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `device_name`, `ip_address`, `action`, `table_name`, `record_id`, `record_name`, `old_data`, `new_data`, `description`, `created_at`) VALUES
(1, 1, 'Administrator', 'Windows PC', '36.68.55.54', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 01:49:28'),
(2, 1, 'Administrator', 'Windows PC', '36.68.55.54', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 01:56:54'),
(3, 3, 'Pengurus Demo', 'Windows PC', '36.68.55.54', 'LOGIN', 'users', 3, 'Pengurus Demo', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 01:57:10'),
(4, 1, 'Administrator', 'Windows PC', '36.68.55.54', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 01:57:34'),
(5, 1, 'Administrator', 'Windows PC', '36.68.55.54', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 01:59:15'),
(6, 1, 'Administrator', 'Windows PC', '36.68.55.54', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 02:02:13'),
(7, 1, 'Administrator', 'Android Device', '182.2.82.14', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 02:04:30'),
(8, 1, 'Administrator', 'Android Device', '182.2.36.177', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 02:06:24'),
(9, 1, 'Administrator', 'Android Device', '182.2.36.177', 'DELETE', 'attendances', 66, NULL, NULL, NULL, 'Hapus absensi ke trash', '2026-01-07 02:07:08'),
(10, 1, 'Administrator', 'Android Device', '36.68.55.54', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 02:19:31'),
(11, 1, 'Administrator', 'Android Device', '36.68.55.54', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 02:52:22'),
(12, 1, 'Administrator', 'Android Device', '36.68.55.54', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 05:54:18'),
(13, 1, 'Administrator', 'Android Device', '182.2.44.117', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 06:31:21'),
(14, 1, 'Administrator', 'Android Device', '182.2.46.44', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 09:13:39'),
(15, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.54', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 09:16:45'),
(16, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.54', 'DELETE', 'users', 4, NULL, NULL, NULL, 'Hapus user ke trash', '2026-01-07 09:17:31'),
(17, 7, 'Akrom Adabi', 'Android Device', '182.2.39.132', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-07 10:54:00'),
(18, 7, 'Akrom Adabi', 'Linux PC', '182.2.45.239', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-08 07:28:25'),
(19, 7, 'Akrom Adabi', 'Linux PC', '182.2.45.239', 'CREATE', 'catatan_aktivitas', 25, 'FATAN HILMI HABIBI', NULL, '{\"nama\":\"FATAN HILMI HABIBI\",\"kategori\":\"pelanggaran\",\"judul\":\"Merokok\",\"tanggal_mulai\":\"2026-01-08T07:28\",\"tanggal_selesai\":null,\"keterangan\":\"Merokok di main jam sekolah\",\"status\":null}', 'Tambah aktivitas pelanggaran untuk FATAN HILMI HABIBI', '2026-01-08 07:29:23'),
(20, 7, 'Akrom Adabi', 'Linux PC', '182.2.45.239', 'CREATE', 'catatan_aktivitas', 26, 'MUHAMMAD RIFQI FADLI', NULL, '{\"nama\":\"MUHAMMAD RIFQI FADLI\",\"kategori\":\"pelanggaran\",\"judul\":\"Merokok\",\"tanggal_mulai\":\"2026-01-08T07:30\",\"tanggal_selesai\":null,\"keterangan\":\"Merokok di main\",\"status\":null}', 'Tambah aktivitas pelanggaran untuk MUHAMMAD RIFQI FADLI', '2026-01-08 07:30:27'),
(21, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.54', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-08 10:24:08'),
(22, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.54', 'CREATE', 'catatan_aktivitas', 27, 'MUHAMMAD ALMAS ALBANY', NULL, '{\"nama\":\"MUHAMMAD ALMAS ALBANY\",\"kategori\":\"pelanggaran\",\"judul\":\"Merokok dan PS an\",\"tanggal_mulai\":\"2026-01-08T10:26\",\"tanggal_selesai\":null,\"keterangan\":\"Merokok dan PS an malam dengan isfad\",\"status\":null}', 'Tambah aktivitas pelanggaran untuk MUHAMMAD ALMAS ALBANY', '2026-01-08 10:26:57'),
(23, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.54', 'CREATE', 'catatan_aktivitas', 28, 'MUHAMMAD RIFQI  HAFIDL', NULL, '{\"nama\":\"MUHAMMAD RIFQI  HAFIDL\",\"kategori\":\"pelanggaran\",\"judul\":\"Merokok \",\"tanggal_mulai\":\"2026-01-08T10:27\",\"tanggal_selesai\":null,\"keterangan\":\"Merokok dan bersama restu di jam sekolah\",\"status\":null}', 'Tambah aktivitas pelanggaran untuk MUHAMMAD RIFQI  HAFIDL', '2026-01-08 10:27:46'),
(24, 7, 'Akrom Adabi', 'Android Device', '36.68.55.54', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-08 10:31:12'),
(25, 7, 'Akrom Adabi', 'Android Device', '182.2.36.227', 'CREATE', 'catatan_aktivitas', 29, 'AHMAD FATKHUR MIRZAQ', NULL, '{\"nama\":\"AHMAD FATKHUR MIRZAQ\",\"kategori\":\"pelanggaran\",\"judul\":\"Merokok\",\"tanggal_mulai\":\"2026-01-08T10:35\",\"tanggal_selesai\":null,\"keterangan\":\"Merokok bareng almas\",\"status\":null}', 'Tambah aktivitas pelanggaran untuk AHMAD FATKHUR MIRZAQ', '2026-01-08 10:35:33'),
(26, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.54', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-08 13:20:36'),
(32, 1, 'Administrator', 'Android Device', '36.68.55.54', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-08 13:34:26'),
(33, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.54', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-08 14:08:20'),
(34, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.54', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-08 15:02:42'),
(35, 1, 'Administrator', 'Windows PC', '180.242.147.248', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-08 20:37:10'),
(36, 1, 'Administrator', 'Windows PC', '180.242.147.248', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-08 21:18:54'),
(37, 7, 'Akrom Adabi', 'Windows PC', '180.242.147.248', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-08 23:36:03'),
(38, 7, 'Akrom Adabi', 'Windows PC', '180.242.147.248', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 05:34:27'),
(39, 7, 'Akrom Adabi', 'Windows PC', '180.242.147.248', 'CREATE', 'catatan_aktivitas', 30, 'ANANTA ARYASATYA', NULL, '{\"nama\":\"ANANTA ARYASATYA\",\"kategori\":\"pelanggaran\",\"judul\":\"Rokokan\",\"tanggal_mulai\":\"2026-01-09T05:52\",\"tanggal_selesai\":null,\"keterangan\":\"Rokokan bareng sigit\",\"status\":null}', 'Tambah aktivitas pelanggaran untuk ANANTA ARYASATYA', '2026-01-09 05:53:05'),
(40, 7, 'Akrom Adabi', 'Windows PC', '180.242.147.248', 'UPDATE', 'catatan_aktivitas', 30, 'ANANTA ARYASATYA', '{\"nama\":\"ANANTA ARYASATYA\",\"kategori\":\"pelanggaran\",\"judul\":\"Rokokan\",\"tanggal_mulai\":\"2026-01-09 05:52:00\",\"tanggal_selesai\":null,\"keterangan\":\"Rokokan bareng sigit\",\"status\":\"Belum Diperiksa\"}', '{\"nama\":\"ANANTA ARYASATYA\",\"kategori\":\"pelanggaran\",\"judul\":\"Merokok\",\"tanggal_mulai\":\"2026-01-09T05:52\",\"tanggal_selesai\":\"\",\"keterangan\":\"Rokokan bareng sigit\",\"status\":\"Belum Diperiksa\"}', 'Ubah aktivitas pelanggaran untuk ANANTA ARYASATYA', '2026-01-09 05:53:16'),
(41, 7, 'Akrom Adabi', 'Windows PC', '180.242.147.248', 'CREATE', 'catatan_aktivitas', 31, 'SATRIO AKBAR FUADI', NULL, '{\"nama\":\"SATRIO AKBAR FUADI\",\"kategori\":\"pelanggaran\",\"judul\":\"Merokok \",\"tanggal_mulai\":\"2026-01-09T05:53\",\"tanggal_selesai\":null,\"keterangan\":\"Rokokan bareng sigit\",\"status\":null}', 'Tambah aktivitas pelanggaran untuk SATRIO AKBAR FUADI', '2026-01-09 05:53:31'),
(42, 19, 'Affan', 'Linux PC', '180.242.147.248', 'LOGIN', 'users', 19, 'Affan', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 05:59:34'),
(43, 8, 'Yusuf', 'Xiaomi', '180.242.147.248', 'LOGIN', 'users', 8, 'Yusuf', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 06:00:51'),
(44, 19, 'Affan', 'Android Device', '180.242.147.248', 'LOGIN', 'users', 19, 'Affan', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 06:08:14'),
(45, 8, 'Yusuf', 'Xiaomi', '180.242.147.248', 'CREATE', 'catatan_aktivitas', 32, 'MUHAMMAD AQIL FADHLURRHOMAN', NULL, '{\"nama\":\"MUHAMMAD AQIL FADHLURRHOMAN\",\"kategori\":\"izin_keluar\",\"judul\":\"Ke toko TB\",\"tanggal_mulai\":\"2026-01-09T13:13\",\"tanggal_selesai\":\"2026-01-09T13:13\",\"keterangan\":\"Peralatan mandi\",\"status\":null}', 'Tambah aktivitas izin_keluar untuk MUHAMMAD AQIL FADHLURRHOMAN', '2026-01-09 06:14:38'),
(46, 8, 'Yusuf', 'Xiaomi', '180.242.147.248', 'CREATE', 'catatan_aktivitas', 33, 'MUHAMMAD AQIL FADHLURRHOMAN', NULL, '{\"nama\":\"MUHAMMAD AQIL FADHLURRHOMAN\",\"kategori\":\"izin_keluar\",\"judul\":\"Ke toko TB\",\"tanggal_mulai\":\"2026-01-09T13:13\",\"tanggal_selesai\":\"2026-01-09T13:13\",\"keterangan\":\"Peralatan mandi\",\"status\":null}', 'Tambah aktivitas izin_keluar untuk MUHAMMAD AQIL FADHLURRHOMAN', '2026-01-09 06:14:40'),
(47, 8, 'Yusuf', 'Xiaomi', '180.242.147.248', 'CREATE', 'catatan_aktivitas', 34, 'MUHAMMAD AQIL FADHLURRHOMAN', NULL, '{\"nama\":\"MUHAMMAD AQIL FADHLURRHOMAN\",\"kategori\":\"izin_keluar\",\"judul\":\"Ke toko TB\",\"tanggal_mulai\":\"2026-01-09T13:13\",\"tanggal_selesai\":\"2026-01-09T13:13\",\"keterangan\":\"Peralatan mandi\",\"status\":null}', 'Tambah aktivitas izin_keluar untuk MUHAMMAD AQIL FADHLURRHOMAN', '2026-01-09 06:14:40'),
(48, 19, 'Affan', 'Android Device', '180.242.147.248', 'CREATE', 'catatan_aktivitas', 35, 'M. SYAUQI BIKA', NULL, '{\"nama\":\"M. SYAUQI BIKA\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:19\",\"tanggal_selesai\":null,\"keterangan\":\"Di Tb\",\"status\":null}', 'Tambah aktivitas izin_keluar untuk M. SYAUQI BIKA', '2026-01-09 06:20:39'),
(49, 19, 'Affan', 'Android Device', '180.242.147.248', 'CREATE', 'catatan_aktivitas', 36, 'M. SYAUQI BIKA', NULL, '{\"nama\":\"M. SYAUQI BIKA\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:19\",\"tanggal_selesai\":null,\"keterangan\":\"Di Tb\",\"status\":null}', 'Tambah aktivitas izin_keluar untuk M. SYAUQI BIKA', '2026-01-09 06:20:41'),
(50, 19, 'Affan', 'Android Device', '180.242.147.248', 'CREATE', 'catatan_aktivitas', 37, 'M. SYAUQI BIKA', NULL, '{\"nama\":\"M. SYAUQI BIKA\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:19\",\"tanggal_selesai\":null,\"keterangan\":\"Di Tb\",\"status\":null}', 'Tambah aktivitas izin_keluar untuk M. SYAUQI BIKA', '2026-01-09 06:20:42'),
(51, 19, 'Affan', 'Android Device', '180.242.147.248', 'CREATE', 'catatan_aktivitas', 38, 'ACHMAD GILANG ZHAFIF MAULA', NULL, '{\"nama\":\"ACHMAD GILANG ZHAFIF MAULA\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:21\",\"tanggal_selesai\":null,\"keterangan\":\"Di TB\",\"status\":null}', 'Tambah aktivitas izin_keluar untuk ACHMAD GILANG ZHAFIF MAULA', '2026-01-09 06:21:55'),
(52, 19, 'Affan', 'Android Device', '180.242.147.248', 'LOGIN', 'users', 19, 'Affan', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 06:24:13'),
(53, 19, 'Affan', 'Android Device', '180.242.147.248', 'CREATE', 'catatan_aktivitas', 39, 'MUHAMMAD SYAUQI BIK', NULL, '{\"nama\":\"MUHAMMAD SYAUQI BIK\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:24\",\"tanggal_selesai\":null,\"keterangan\":\"Di Alfamart\",\"status\":null}', 'Tambah aktivitas izin_keluar untuk MUHAMMAD SYAUQI BIK', '2026-01-09 06:24:49'),
(54, 19, 'Affan', 'Android Device', '180.242.147.248', 'CREATE', 'catatan_aktivitas', 40, 'RIDHO ABDUL JALIL', NULL, '{\"nama\":\"RIDHO ABDUL JALIL\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:25\",\"tanggal_selesai\":null,\"keterangan\":\"Di TB\",\"status\":null}', 'Tambah aktivitas izin_keluar untuk RIDHO ABDUL JALIL', '2026-01-09 06:26:20'),
(55, 19, 'Affan', 'Android Device', '180.242.147.248', 'CREATE', 'catatan_aktivitas', 41, 'RIDHO ABDUL JALIL', NULL, '{\"nama\":\"RIDHO ABDUL JALIL\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:25\",\"tanggal_selesai\":null,\"keterangan\":\"Di TB\",\"status\":null}', 'Tambah aktivitas izin_keluar untuk RIDHO ABDUL JALIL', '2026-01-09 06:26:21'),
(56, 19, 'Affan', 'Android Device', '180.242.147.248', 'CREATE', 'catatan_aktivitas', 42, 'RIDHO ABDUL JALIL', NULL, '{\"nama\":\"RIDHO ABDUL JALIL\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:25\",\"tanggal_selesai\":null,\"keterangan\":\"Di TB\",\"status\":null}', 'Tambah aktivitas izin_keluar untuk RIDHO ABDUL JALIL', '2026-01-09 06:26:21'),
(57, 19, 'Affan', 'Android Device', '182.2.50.15', 'LOGIN', 'users', 19, 'Affan', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 07:37:12'),
(58, 19, 'Affan', 'Android Device', '182.2.50.15', 'UPDATE', 'catatan_aktivitas', 36, 'M. SYAUQI BIKA', '{\"nama\":\"M. SYAUQI BIKA\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09 06:19:00\",\"tanggal_selesai\":null,\"keterangan\":\"Di Tb\",\"status\":\"Belum Diperiksa\"}', '{\"nama\":\"M. SYAUQI BIKA\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:19\",\"tanggal_selesai\":\"2026-01-09T14:13\",\"keterangan\":\"Di Tb\",\"status\":\"Belum Diperiksa\"}', 'Ubah aktivitas izin_keluar untuk M. SYAUQI BIKA', '2026-01-09 07:38:13'),
(59, 19, 'Affan', 'Android Device', '182.2.50.15', 'UPDATE', 'catatan_aktivitas', 36, 'M. SYAUQI BIKA', '{\"nama\":\"M. SYAUQI BIKA\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09 06:19:00\",\"tanggal_selesai\":\"2026-01-09 14:13:00\",\"keterangan\":\"Di Tb\",\"status\":\"Belum Diperiksa\"}', '{\"nama\":\"M. SYAUQI BIKA\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:19\",\"tanggal_selesai\":\"2026-01-09T14:13\",\"keterangan\":\"Di Tb\",\"status\":\"Belum Diperiksa\"}', 'Ubah aktivitas izin_keluar untuk M. SYAUQI BIKA', '2026-01-09 07:38:24'),
(60, 19, 'Affan', 'Android Device', '182.2.50.15', 'UPDATE', 'catatan_aktivitas', 35, 'M. SYAUQI BIKA', '{\"nama\":\"M. SYAUQI BIKA\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09 06:19:00\",\"tanggal_selesai\":null,\"keterangan\":\"Di Tb\",\"status\":\"Belum Diperiksa\"}', '{\"nama\":\"M. SYAUQI BIKA\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:19\",\"tanggal_selesai\":\"2026-01-09T14:14\",\"keterangan\":\"Di Tb\",\"status\":\"Belum Diperiksa\"}', 'Ubah aktivitas izin_keluar untuk M. SYAUQI BIKA', '2026-01-09 07:38:38'),
(61, 19, 'Affan', 'Android Device', '182.2.50.15', 'UPDATE', 'catatan_aktivitas', 38, 'ACHMAD GILANG ZHAFIF MAULA', '{\"nama\":\"ACHMAD GILANG ZHAFIF MAULA\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09 06:21:00\",\"tanggal_selesai\":null,\"keterangan\":\"Di TB\",\"status\":\"Belum Diperiksa\"}', '{\"nama\":\"ACHMAD GILANG ZHAFIF MAULA\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:21\",\"tanggal_selesai\":\"2026-01-09T14:14\",\"keterangan\":\"Di TB\",\"status\":\"Belum Diperiksa\"}', 'Ubah aktivitas izin_keluar untuk ACHMAD GILANG ZHAFIF MAULA', '2026-01-09 07:38:53'),
(62, 19, 'Affan', 'Android Device', '182.2.50.15', 'UPDATE', 'catatan_aktivitas', 39, 'MUHAMMAD SYAUQI BIK', '{\"nama\":\"MUHAMMAD SYAUQI BIK\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09 06:24:00\",\"tanggal_selesai\":null,\"keterangan\":\"Di Alfamart\",\"status\":\"Belum Diperiksa\"}', '{\"nama\":\"MUHAMMAD SYAUQI BIK\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:24\",\"tanggal_selesai\":\"2026-01-09T14:22\",\"keterangan\":\"Di Alfamart\",\"status\":\"Belum Diperiksa\"}', 'Ubah aktivitas izin_keluar untuk MUHAMMAD SYAUQI BIK', '2026-01-09 07:39:11'),
(63, 19, 'Affan', 'Android Device', '182.2.50.15', 'UPDATE', 'catatan_aktivitas', 42, 'RIDHO ABDUL JALIL', '{\"nama\":\"RIDHO ABDUL JALIL\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09 06:25:00\",\"tanggal_selesai\":null,\"keterangan\":\"Di TB\",\"status\":\"Belum Diperiksa\"}', '{\"nama\":\"RIDHO ABDUL JALIL\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:25\",\"tanggal_selesai\":\"2026-01-09T14:22\",\"keterangan\":\"Di TB\",\"status\":\"Belum Diperiksa\"}', 'Ubah aktivitas izin_keluar untuk RIDHO ABDUL JALIL', '2026-01-09 07:39:30'),
(64, 19, 'Affan', 'Android Device', '182.2.50.15', 'UPDATE', 'catatan_aktivitas', 41, 'RIDHO ABDUL JALIL', '{\"nama\":\"RIDHO ABDUL JALIL\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09 06:25:00\",\"tanggal_selesai\":null,\"keterangan\":\"Di TB\",\"status\":\"Belum Diperiksa\"}', '{\"nama\":\"RIDHO ABDUL JALIL\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:25\",\"tanggal_selesai\":\"2026-01-09T14:22\",\"keterangan\":\"Di TB\",\"status\":\"Belum Diperiksa\"}', 'Ubah aktivitas izin_keluar untuk RIDHO ABDUL JALIL', '2026-01-09 07:39:43'),
(65, 19, 'Affan', 'Android Device', '182.2.50.15', 'UPDATE', 'catatan_aktivitas', 40, 'RIDHO ABDUL JALIL', '{\"nama\":\"RIDHO ABDUL JALIL\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09 06:25:00\",\"tanggal_selesai\":null,\"keterangan\":\"Di TB\",\"status\":\"Belum Diperiksa\"}', '{\"nama\":\"RIDHO ABDUL JALIL\",\"kategori\":\"izin_keluar\",\"judul\":\"Beli peralatan mandi\",\"tanggal_mulai\":\"2026-01-09T06:25\",\"tanggal_selesai\":\"2026-01-09T14:22\",\"keterangan\":\"Di TB\",\"status\":\"Belum Diperiksa\"}', 'Ubah aktivitas izin_keluar untuk RIDHO ABDUL JALIL', '2026-01-09 07:39:54'),
(66, 7, 'Akrom Adabi', 'Windows PC', '180.244.223.250', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 11:08:58'),
(67, 7, 'Akrom Adabi', 'Windows PC', '180.244.223.250', 'UPDATE', 'catatan_aktivitas', 32, 'MUHAMMAD AQIL FADHLURRHOMAN', '{\"nama\":\"MUHAMMAD AQIL FADHLURRHOMAN\",\"kategori\":\"izin_keluar\",\"judul\":\"Ke toko TB\",\"tanggal_mulai\":\"2026-01-09 13:13:00\",\"tanggal_selesai\":\"2026-01-09 13:13:00\",\"keterangan\":\"Peralatan mandi\",\"status\":\"Belum Diperiksa\"}', '{\"nama\":\"MUHAMMAD AQIL FADHLURRHOMAN\",\"kategori\":\"izin_keluar\",\"judul\":\"Ke toko TB\",\"tanggal_mulai\":\"2026-01-09T13:13\",\"tanggal_selesai\":\"2026-01-09T18:10\",\"keterangan\":\"Peralatan mandi\",\"status\":\"Belum Diperiksa\"}', 'Ubah aktivitas izin_keluar untuk MUHAMMAD AQIL FADHLURRHOMAN', '2026-01-09 11:10:24'),
(68, 1, 'Administrator', 'Android Device', '180.244.223.250', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 11:46:22'),
(69, 1, 'Administrator', 'Android Device', '180.244.223.250', 'CREATE', 'catatan_aktivitas', 43, 'M. FAHMI SIROJUL MUNIR', NULL, '{\"nama\":\"M. FAHMI SIROJUL MUNIR\",\"kategori\":\"izin_keluar\",\"judul\":\"tes\",\"tanggal_mulai\":\"2026-01-09T11:47\",\"tanggal_selesai\":null,\"keterangan\":\"tes\",\"status\":null}', 'Tambah aktivitas izin_keluar untuk M. FAHMI SIROJUL MUNIR', '2026-01-09 11:47:53'),
(70, 1, 'Administrator', 'Android Device', '180.244.223.250', 'DELETE', 'catatan_aktivitas', NULL, NULL, NULL, NULL, 'Hapus 1 data aktivitas ke trash', '2026-01-09 11:48:07'),
(71, 1, 'Administrator', 'Android Device', '180.244.223.250', 'UPDATE', 'catatan_aktivitas', 33, 'MUHAMMAD AQIL FADHLURRHOMAN', '{\"nama\":\"MUHAMMAD AQIL FADHLURRHOMAN\",\"kategori\":\"izin_keluar\",\"judul\":\"Ke toko TB\",\"tanggal_mulai\":\"2026-01-09 13:13:00\",\"tanggal_selesai\":\"2026-01-09 13:13:00\",\"keterangan\":\"Peralatan mandi\",\"status\":\"Belum Diperiksa\"}', '{\"nama\":\"MUHAMMAD AQIL FADHLURRHOMAN\",\"kategori\":\"izin_keluar\",\"judul\":\"Ke toko TB\",\"tanggal_mulai\":\"2026-01-09T13:13\",\"tanggal_selesai\":\"\",\"keterangan\":\"Peralatan mandi\",\"status\":\"Belum Diperiksa\"}', 'Ubah aktivitas izin_keluar untuk MUHAMMAD AQIL FADHLURRHOMAN', '2026-01-09 11:48:24'),
(72, 1, 'Administrator', 'Android Device', '180.244.223.250', 'DELETE', 'catatan_aktivitas', NULL, NULL, NULL, NULL, 'Hapus 1 data aktivitas ke trash', '2026-01-09 11:48:39'),
(73, 1, 'Administrator', 'Android Device', '180.244.223.250', 'CREATE', 'catatan_aktivitas', 44, 'M. FAHMI SIROJUL MUNIR', NULL, '{\"nama\":\"M. FAHMI SIROJUL MUNIR\",\"kategori\":\"izin_keluar\",\"judul\":\"tes\",\"tanggal_mulai\":\"2026-01-09T12:01\",\"tanggal_selesai\":null,\"keterangan\":\"tes\",\"status\":null}', 'Tambah aktivitas izin_keluar untuk M. FAHMI SIROJUL MUNIR', '2026-01-09 12:02:14'),
(74, 1, 'Administrator', 'Android Device', '180.244.223.250', 'DELETE', 'catatan_aktivitas', NULL, NULL, NULL, NULL, 'Hapus 1 data aktivitas ke trash', '2026-01-09 12:02:24'),
(75, 7, 'Akrom Adabi', 'Windows PC', '180.244.223.250', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 12:37:55'),
(76, 1, 'Administrator', 'Windows PC', '180.244.223.250', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 13:30:32'),
(77, 1, 'Administrator', 'Android Device', '180.244.223.250', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 13:31:08'),
(78, 1, 'Administrator', 'Android Device', '180.244.223.250', 'CREATE', 'catatan_aktivitas', 45, 'M. FAHMI SIROJUL MUNIR', NULL, '{\"nama\":\"M. FAHMI SIROJUL MUNIR\",\"kategori\":\"sakit\",\"judul\":\"tes\",\"tanggal_mulai\":\"2026-01-09T13:32\",\"tanggal_selesai\":null,\"keterangan\":\"tes\",\"status\":null}', 'Tambah aktivitas sakit untuk M. FAHMI SIROJUL MUNIR', '2026-01-09 13:32:17'),
(79, 1, 'Administrator', 'Android Device', '180.244.223.250', 'CREATE', 'catatan_aktivitas', 46, 'M. FAHMI SIROJUL MUNIR', NULL, '{\"nama\":\"M. FAHMI SIROJUL MUNIR\",\"kategori\":\"sakit\",\"judul\":\"tes\",\"tanggal_mulai\":\"2026-01-09T13:32\",\"tanggal_selesai\":null,\"keterangan\":\"tes\",\"status\":null}', 'Tambah aktivitas sakit untuk M. FAHMI SIROJUL MUNIR', '2026-01-09 13:32:24'),
(80, 1, 'Administrator', 'Android Device', '180.244.223.250', 'DELETE', 'catatan_aktivitas', NULL, NULL, NULL, NULL, 'Hapus 1 data aktivitas ke trash', '2026-01-09 13:32:28'),
(81, 1, 'Administrator', 'Android Device', '180.244.223.250', 'CREATE', 'print_izin_history', 1, '001/SKA.001/PPMH/I/2026', NULL, '{\"kategori\":\"sakit\",\"santri\":[\"M. FAHMI SIROJUL MUNIR\"]}', 'Print surat izin sekolah', '2026-01-09 13:32:46'),
(82, 1, 'Administrator', 'Android Device', '180.244.223.250', 'CREATE', 'print_izin_history', 2, '002/SKA.001/PPMH/I/2026', NULL, '{\"kategori\":\"sakit\",\"santri\":[\"M. FAHMI SIROJUL MUNIR\"]}', 'Print surat izin sekolah', '2026-01-09 13:33:31'),
(83, 1, 'Administrator', 'Android Device', '180.244.223.250', 'CREATE', 'print_izin_history', 3, '003/SKA.001/PPMH/I/2026', NULL, '{\"kategori\":\"sakit\",\"santri\":[\"M. FAHMI SIROJUL MUNIR\"]}', 'Print surat izin sekolah', '2026-01-09 13:37:29'),
(84, 1, 'Administrator', 'Android Device', '180.244.223.250', 'CREATE', 'print_izin_history', 4, '004/SKA.001/PPMH/I/2026', NULL, '{\"kategori\":\"sakit\",\"santri\":[\"M. FAHMI SIROJUL MUNIR\"]}', 'Print surat izin sekolah', '2026-01-09 13:38:45'),
(85, 1, 'Administrator', 'Android Device', '180.244.223.250', 'CREATE', 'print_izin_history', 5, '005/SKA.001/PPMH/I/2026', NULL, '{\"kategori\":\"sakit\",\"santri\":[\"M. FAHMI SIROJUL MUNIR\"]}', 'Print surat izin sekolah', '2026-01-09 13:40:12'),
(86, 1, 'Administrator', 'Android Device', '180.244.223.250', 'CREATE', 'print_izin_history', 6, '006/SKA.001/PPMH/I/2026', NULL, '{\"kategori\":\"sakit\",\"santri\":[\"M. FAHMI SIROJUL MUNIR\"]}', 'Print surat izin sekolah', '2026-01-09 13:42:37'),
(87, 1, 'Administrator', 'Android Device', '180.244.223.250', 'CREATE', 'print_izin_history', 7, '007/SKA.001/PPMH/I/2026', NULL, '{\"kategori\":\"sakit\",\"santri\":[\"M. FAHMI SIROJUL MUNIR\"]}', 'Print surat izin sekolah', '2026-01-09 13:43:14'),
(88, 1, 'Administrator', 'Android Device', '180.244.223.250', 'CREATE', 'print_izin_history', 8, '008/SKA.001/PPMH/I/2026', NULL, '{\"kategori\":\"sakit\",\"santri\":[\"M. FAHMI SIROJUL MUNIR\"]}', 'Print surat izin sekolah', '2026-01-09 13:47:49'),
(89, 7, 'Akrom Adabi', 'Windows PC', '180.244.223.250', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 13:49:38'),
(90, 1, 'Administrator', 'Android Device', '180.244.223.250', 'CREATE', 'print_izin_history', 9, '009/SKA.001/PPMH/I/2026', NULL, '{\"kategori\":\"sakit\",\"santri\":[\"M. FAHMI SIROJUL MUNIR\"]}', 'Print surat izin sekolah', '2026-01-09 14:23:18'),
(91, 1, 'Administrator', 'Windows PC', '180.244.223.250', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 14:49:00'),
(92, 1, 'Administrator', 'Android Device', '180.244.223.250', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 16:07:04'),
(93, 1, 'Administrator', 'Android Device', '180.244.223.250', 'CREATE', 'print_izin_history', 10, '010/SKA.001/PPMH/I/2026', NULL, '{\"kategori\":\"sakit\",\"santri\":[\"M. FAHMI SIROJUL MUNIR\"]}', 'Print surat izin sekolah', '2026-01-09 16:07:19'),
(94, 19, 'Affan', 'Android Device', '180.244.223.250', 'LOGIN', 'users', 19, 'Affan', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-09 23:21:11'),
(95, 19, 'Affan', 'Android Device', '180.244.223.250', 'CREATE', 'catatan_aktivitas', 47, 'SABIL BAROKAH', NULL, '{\"nama\":\"SABIL BAROKAH\",\"kategori\":\"sakit\",\"judul\":\"Sakit \",\"tanggal_mulai\":\"2026-01-09T23:21\",\"tanggal_selesai\":null,\"keterangan\":\"Wudun di mata\",\"status\":null}', 'Tambah aktivitas sakit untuk SABIL BAROKAH', '2026-01-09 23:24:55'),
(96, 7, 'Akrom Adabi', 'Android Device', '182.2.46.92', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-10 03:57:30'),
(97, 9, 'Kowi', 'Android Device', '112.215.168.43', 'LOGIN', 'users', 9, 'Kowi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-10 05:58:37'),
(98, 9, 'Kowi', 'Android Device', '112.215.168.43', 'CREATE', 'catatan_aktivitas', 48, 'MUHAMMAD FADIL ARIFIN', NULL, '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10T05:59\",\"tanggal_selesai\":null,\"keterangan\":\"\",\"status\":null}', 'Tambah aktivitas paket untuk MUHAMMAD FADIL ARIFIN', '2026-01-10 05:59:44'),
(99, 9, 'Kowi', 'Android Device', '112.215.168.43', 'CREATE', 'catatan_aktivitas', 49, 'MUHAMMAD FADIL ARIFIN', NULL, '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10T05:59\",\"tanggal_selesai\":null,\"keterangan\":\"\",\"status\":null}', 'Tambah aktivitas paket untuk MUHAMMAD FADIL ARIFIN', '2026-01-10 05:59:53'),
(100, 9, 'Kowi', 'Android Device', '112.215.168.43', 'CREATE', 'catatan_aktivitas', 50, 'MUHAMMAD FADIL ARIFIN', NULL, '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10T05:59\",\"tanggal_selesai\":null,\"keterangan\":\"\",\"status\":null}', 'Tambah aktivitas paket untuk MUHAMMAD FADIL ARIFIN', '2026-01-10 05:59:54'),
(101, 9, 'Kowi', 'Android Device', '112.215.168.43', 'CREATE', 'catatan_aktivitas', 51, 'MUHAMMAD FADIL ARIFIN', NULL, '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10T05:59\",\"tanggal_selesai\":null,\"keterangan\":\"\",\"status\":null}', 'Tambah aktivitas paket untuk MUHAMMAD FADIL ARIFIN', '2026-01-10 05:59:56'),
(102, 9, 'Kowi', 'Android Device', '112.215.168.43', 'CREATE', 'catatan_aktivitas', 52, 'MUHAMMAD FADIL ARIFIN', NULL, '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10T05:59\",\"tanggal_selesai\":null,\"keterangan\":\"\",\"status\":null}', 'Tambah aktivitas paket untuk MUHAMMAD FADIL ARIFIN', '2026-01-10 05:59:58'),
(103, 9, 'Kowi', 'Android Device', '112.215.168.43', 'CREATE', 'catatan_aktivitas', 53, 'MUHAMMAD FADIL ARIFIN', NULL, '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10T05:59\",\"tanggal_selesai\":null,\"keterangan\":\"\",\"status\":null}', 'Tambah aktivitas paket untuk MUHAMMAD FADIL ARIFIN', '2026-01-10 05:59:58'),
(104, 9, 'Kowi', 'Android Device', '112.215.168.43', 'CREATE', 'catatan_aktivitas', 54, 'MUHAMMAD FADIL ARIFIN', NULL, '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10T05:59\",\"tanggal_selesai\":null,\"keterangan\":\"\",\"status\":null}', 'Tambah aktivitas paket untuk MUHAMMAD FADIL ARIFIN', '2026-01-10 05:59:59'),
(105, 9, 'Kowi', 'Android Device', '112.215.168.43', 'UPDATE', 'catatan_aktivitas', 54, 'MUHAMMAD FADIL ARIFIN', '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10 05:59:00\",\"tanggal_selesai\":null,\"keterangan\":\"\",\"status\":\"Belum Diterima\"}', '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10T05:59\",\"tanggal_selesai\":\"\",\"keterangan\":\"\",\"status\":\"Belum Diterima\"}', 'Ubah aktivitas paket untuk MUHAMMAD FADIL ARIFIN', '2026-01-10 06:00:48'),
(106, 7, 'Akrom Adabi', 'Android Device', '180.244.223.250', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-10 06:01:31'),
(107, 7, 'Akrom Adabi', 'Android Device', '180.244.223.250', 'DELETE', 'catatan_aktivitas', NULL, NULL, NULL, NULL, 'Hapus 6 data aktivitas ke trash', '2026-01-10 06:01:53'),
(108, 7, 'Akrom Adabi', 'Android Device', '180.244.223.250', 'UPDATE', 'catatan_aktivitas', 48, 'MUHAMMAD FADIL ARIFIN', '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10 05:59:00\",\"tanggal_selesai\":null,\"keterangan\":\"\",\"status\":\"Belum Diterima\"}', '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10T05:59\",\"tanggal_selesai\":\"\",\"keterangan\":\"\",\"status\":\"Belum Diterima\"}', 'Ubah aktivitas paket untuk MUHAMMAD FADIL ARIFIN', '2026-01-10 06:02:26'),
(109, 7, 'Akrom Adabi', 'Android Device', '180.244.223.250', 'UPDATE', 'catatan_aktivitas', 48, 'MUHAMMAD FADIL ARIFIN', '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10 05:59:00\",\"tanggal_selesai\":null,\"keterangan\":\"\",\"status\":\"Belum Diterima\"}', '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10T05:59\",\"tanggal_selesai\":\"\",\"keterangan\":\"\",\"status\":\"Belum Diterima\"}', 'Ubah aktivitas paket untuk MUHAMMAD FADIL ARIFIN', '2026-01-10 06:02:48'),
(110, 7, 'Akrom Adabi', 'Android Device', '180.244.223.250', 'DELETE', 'catatan_aktivitas', NULL, NULL, NULL, NULL, 'Hapus 3 data aktivitas ke trash', '2026-01-10 06:03:17'),
(111, 7, 'Akrom Adabi', 'Android Device', '180.244.223.250', 'DELETE', 'catatan_aktivitas', NULL, NULL, NULL, NULL, 'Hapus 2 data aktivitas ke trash', '2026-01-10 06:03:24'),
(112, 1, 'Administrator', 'Android Device', '36.68.55.95', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-11 03:27:41'),
(113, 1, 'Administrator', 'Windows PC', '36.68.55.95', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-11 03:28:48'),
(114, 1, 'Administrator', 'Windows PC', '36.68.55.95', 'DELETE', 'catatan_aktivitas', NULL, NULL, NULL, NULL, 'Hapus 1 data aktivitas ke trash', '2026-01-11 04:04:28'),
(115, 1, 'Administrator', 'Windows PC', '36.68.55.95', 'CREATE', 'catatan_aktivitas', 55, 'M. FAHMI SIROJUL MUNIR', NULL, '{\"nama\":\"M. FAHMI SIROJUL MUNIR\",\"kategori\":\"sakit\",\"judul\":\"tes\",\"tanggal_mulai\":\"2026-01-11T04:04\",\"tanggal_selesai\":null,\"keterangan\":\"tes\",\"status\":null}', 'Tambah aktivitas sakit untuk M. FAHMI SIROJUL MUNIR', '2026-01-11 04:04:44'),
(116, 1, 'Administrator', 'Windows PC', '36.68.55.95', 'DELETE', 'catatan_aktivitas', NULL, NULL, NULL, NULL, 'Hapus 1 data aktivitas ke trash', '2026-01-11 04:04:53'),
(117, 1, 'Administrator', 'Android Device', '36.68.55.95', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-11 04:05:25'),
(118, 1, 'Administrator', 'Android Device', '36.68.55.95', 'CREATE', 'catatan_aktivitas', 56, 'M. FAHMI SIROJUL MUNIR', NULL, '{\"nama\":\"M. FAHMI SIROJUL MUNIR\",\"kategori\":\"sakit\",\"judul\":\"tes\",\"tanggal_mulai\":\"2026-01-11T04:05\",\"tanggal_selesai\":null,\"keterangan\":\"tes\",\"status\":null}', 'Tambah aktivitas sakit untuk M. FAHMI SIROJUL MUNIR', '2026-01-11 04:05:42'),
(119, 1, 'Administrator', 'Android Device', '36.68.55.95', 'DELETE', 'catatan_aktivitas', NULL, NULL, NULL, NULL, 'Hapus 1 data aktivitas ke trash', '2026-01-11 04:05:47'),
(120, 19, 'Affan', 'Android Device', '182.2.68.19', 'LOGIN', 'users', 19, 'Affan', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-11 12:15:03'),
(121, 19, 'Affan', 'Android Device', '182.2.68.19', 'CREATE', 'catatan_aktivitas', 57, 'MUHAMMAD RIFQI  HAFIDL', NULL, '{\"nama\":\"MUHAMMAD RIFQI  HAFIDL\",\"kategori\":\"izin_pulang\",\"judul\":\"Periksa wudun\",\"tanggal_mulai\":\"2026-01-11T12:15\",\"tanggal_selesai\":null,\"keterangan\":\"dirumah\",\"status\":null}', 'Tambah aktivitas izin_pulang untuk MUHAMMAD RIFQI  HAFIDL', '2026-01-11 12:16:30'),
(122, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.95', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-11 23:07:42'),
(123, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.95', 'CREATE', 'catatan_aktivitas', 58, 'MUHAMAD FAIZZUN', NULL, '{\"nama\":\"MUHAMAD FAIZZUN\",\"kategori\":\"pelanggaran\",\"judul\":\"PSan\",\"tanggal_mulai\":\"2026-01-07T23:09\",\"tanggal_selesai\":null,\"keterangan\":\"PS an di jrebeng pulang jam 2\",\"status\":null}', 'Tambah aktivitas pelanggaran untuk MUHAMAD FAIZZUN', '2026-01-11 23:09:55'),
(124, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.95', 'CREATE', 'catatan_aktivitas', 59, 'MUHAMAD FAIZZUN', NULL, '{\"nama\":\"MUHAMAD FAIZZUN\",\"kategori\":\"pelanggaran\",\"judul\":\"PSan\",\"tanggal_mulai\":\"2026-01-10T23:09\",\"tanggal_selesai\":null,\"keterangan\":\"PS an lagi di jrebeng dengan fatan pulang jam 12\",\"status\":null}', 'Tambah aktivitas pelanggaran untuk MUHAMAD FAIZZUN', '2026-01-11 23:10:17'),
(125, 19, 'Affan', 'Android Device', '182.2.38.157', 'LOGIN', 'users', 19, 'Affan', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-12 00:57:06'),
(126, 19, 'Affan', 'Android Device', '182.2.38.157', 'CREATE', 'catatan_aktivitas', 60, 'CLARA VANESHA MAHARANI', NULL, '{\"nama\":\"CLARA VANESHA MAHARANI\",\"kategori\":\"izin_pulang\",\"judul\":\"Sakit nanah gatel\\\"\",\"tanggal_mulai\":\"2026-01-12T00:57\",\"tanggal_selesai\":null,\"keterangan\":\"Pulang dirumah\",\"status\":null}', 'Tambah aktivitas izin_pulang untuk CLARA VANESHA MAHARANI', '2026-01-12 00:59:06'),
(127, 19, 'Affan', 'Android Device', '182.2.38.157', 'CREATE', 'catatan_aktivitas', 61, 'CLARA VANESHA MAHARANI', NULL, '{\"nama\":\"CLARA VANESHA MAHARANI\",\"kategori\":\"sakit\",\"judul\":\"Nanah gatel\\\"\",\"tanggal_mulai\":\"2026-01-12T00:59\",\"tanggal_selesai\":null,\"keterangan\":\"pulang di rumah\",\"status\":null}', 'Tambah aktivitas sakit untuk CLARA VANESHA MAHARANI', '2026-01-12 01:00:18'),
(128, 19, 'Affan', 'Android Device', '182.2.38.157', 'UPDATE', 'catatan_aktivitas', 47, 'SABIL BAROKAH', '{\"nama\":\"SABIL BAROKAH\",\"kategori\":\"sakit\",\"judul\":\"Sakit \",\"tanggal_mulai\":\"2026-01-09 23:21:00\",\"tanggal_selesai\":null,\"keterangan\":\"Wudun di mata\",\"status\":\"Belum Diperiksa\"}', '{\"nama\":\"SABIL BAROKAH\",\"kategori\":\"sakit\",\"judul\":\"Sakit \",\"tanggal_mulai\":\"2026-01-09T23:21\",\"tanggal_selesai\":\"2026-01-11T08:00\",\"keterangan\":\"Wudun di mata\",\"status\":\"Belum Diperiksa\"}', 'Ubah aktivitas sakit untuk SABIL BAROKAH', '2026-01-12 01:01:04'),
(129, 1, 'Administrator', 'Windows PC', '36.68.55.95', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-12 02:38:10'),
(130, 1, 'Administrator', 'Windows PC', '36.68.55.95', 'EMPTY_TRASH', NULL, NULL, NULL, NULL, NULL, 'Dikosongkan trash: 24 data dihapus permanen', '2026-01-12 02:39:07'),
(131, 1, 'Administrator', 'Windows PC', '36.68.55.95', 'UPDATE', 'catatan_aktivitas', 48, 'MUHAMMAD FADIL ARIFIN', '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10 05:59:00\",\"tanggal_selesai\":null,\"keterangan\":\"\",\"status\":\"Belum Diterima\"}', '{\"nama\":\"MUHAMMAD FADIL ARIFIN\",\"kategori\":\"paket\",\"judul\":\"Jajan\",\"tanggal_mulai\":\"2026-01-10T05:59\",\"tanggal_selesai\":\"\",\"keterangan\":\"\",\"status\":\"Belum Diterima\"}', 'Ubah aktivitas paket untuk MUHAMMAD FADIL ARIFIN', '2026-01-12 02:40:06'),
(132, 7, 'Akrom Adabi', 'Android Device', '36.68.55.95', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-12 15:11:11'),
(133, 1, 'Administrator', 'Android Device', '36.68.55.95', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-12 15:36:19'),
(134, 14, 'Surya', 'Android Device', '114.10.127.170', 'LOGIN', 'users', 14, 'Surya', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-12 20:28:21'),
(135, 1, 'Administrator', 'Windows PC', '36.68.55.95', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-13 01:33:56'),
(136, 1, 'Administrator', 'Android Device', '36.68.55.95', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-13 02:48:21'),
(137, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.95', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-13 05:23:43'),
(138, 7, 'Akrom Adabi', 'Windows PC', '36.68.53.208', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-13 13:20:48'),
(139, 1, 'Administrator', 'Windows PC', '36.68.53.208', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-13 22:48:28'),
(140, 1, 'Administrator', NULL, '36.68.53.208', 'delete', NULL, NULL, NULL, NULL, NULL, 'Hapus absensi ke trash', '2026-01-13 23:16:49'),
(141, 1, 'Administrator', 'Android Device', '182.2.85.65', 'LOGOUT', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna keluar dari sistem', '2026-01-14 03:54:45'),
(142, 14, 'Surya', 'Android Device', '182.2.85.65', 'LOGIN', 'users', 14, 'Surya', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-14 03:54:58'),
(143, 14, 'Surya', 'Android Device', '182.2.85.65', 'LOGOUT', 'users', 14, 'Surya', NULL, NULL, 'Pengguna keluar dari sistem', '2026-01-14 03:55:22'),
(144, 1, 'Administrator', 'Android Device', '182.2.85.65', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-14 03:56:03'),
(145, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.229', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-16 04:16:34'),
(146, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 62, 'APRILIA USWATUN KHASANAH', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Nyadrran\"}', 'Tambah aktivitas izin_keluar', '2026-01-16 04:19:04'),
(147, 1, 'Administrator', 'Windows PC', '36.68.55.229', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-16 06:15:47'),
(148, 9, 'Kowi', 'Android Device', '140.213.173.138', 'LOGIN', 'users', 9, 'Kowi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-16 06:24:19'),
(149, 9, 'Kowi', 'Android Device', '140.213.173.138', 'CREATE', 'catatan_aktivitas', 63, 'MUHAMMAD DAKHROSUL HAMID', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Cukur\"}', 'Tambah aktivitas izin_keluar', '2026-01-16 06:25:25'),
(150, 9, 'Kowi', 'Android Device', '140.213.173.138', 'CREATE', 'catatan_aktivitas', 64, 'SATRIO AKBAR FUADI', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Cukur\"}', 'Tambah aktivitas izin_keluar', '2026-01-16 06:26:25'),
(151, 9, 'Kowi', 'Android Device', '140.213.173.138', 'CREATE', 'catatan_aktivitas', 65, 'BAGUS AMIRUL AKBAR', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Fc akidah\"}', 'Tambah aktivitas izin_keluar', '2026-01-16 06:26:51'),
(152, 9, 'Kowi', 'Android Device', '140.213.173.138', 'CREATE', 'catatan_aktivitas', 66, 'MIFTAKHUL KIROM', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Cukur\"}', 'Tambah aktivitas izin_keluar', '2026-01-16 06:27:24'),
(153, 9, 'Kowi', 'Android Device', '140.213.173.138', 'CREATE', 'catatan_aktivitas', 67, 'M. ARFAUR RIKZA', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Cukur\"}', 'Tambah aktivitas izin_keluar', '2026-01-16 06:27:47'),
(154, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Adil', '2026-01-16 06:35:08'),
(155, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Adil', '2026-01-16 06:35:11'),
(156, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Adil', '2026-01-16 06:35:52'),
(157, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Affan', '2026-01-16 06:36:02'),
(158, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Bidin', '2026-01-16 06:36:13'),
(159, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Ifaza', '2026-01-16 06:36:21'),
(160, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Ilmi', '2026-01-16 06:36:31'),
(161, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Irham', '2026-01-16 06:36:39'),
(162, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Kowi', '2026-01-16 06:36:46'),
(163, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Nayla', '2026-01-16 06:36:52'),
(164, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Oki', '2026-01-16 06:37:00'),
(165, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Rino', '2026-01-16 06:37:06'),
(166, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Surya', '2026-01-16 06:37:21'),
(167, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Surya', '2026-01-16 06:37:21'),
(168, 1, 'Administrator', NULL, '36.68.55.229', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Yusuf', '2026-01-16 06:37:27'),
(169, 9, 'Kowi', 'Linux PC', '140.213.163.160', 'CREATE', 'catatan_aktivitas', 68, 'TRI MAULANA AZZAM', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Cukur\"}', 'Tambah aktivitas izin_keluar', '2026-01-16 06:46:05'),
(170, 9, 'Kowi', 'Linux PC', '140.213.163.160', 'CREATE', 'catatan_aktivitas', 69, 'MUHAMMAD AS\'AD AL MUGHNI', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Cukur\"}', 'Tambah aktivitas izin_keluar', '2026-01-16 06:46:26'),
(171, 1, 'Administrator', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 70, 'FARHAN SYAH PUTRA', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Potong Rambut\"}', 'Tambah aktivitas izin_keluar', '2026-01-16 07:21:58'),
(172, 9, 'Kowi', 'Linux PC', '140.213.163.160', 'CREATE', 'catatan_aktivitas', 71, 'FARIS VERDIYANTO', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Cukur\"}', 'Tambah aktivitas izin_keluar', '2026-01-16 07:33:46'),
(173, 9, 'Kowi', 'Linux PC', '140.213.163.160', 'CREATE', 'catatan_aktivitas', 72, 'M.AZKA RIFIYANSYAH', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Cukur\"}', 'Tambah aktivitas izin_keluar', '2026-01-16 07:34:11'),
(174, 9, 'Kowi', 'Linux PC', '140.213.163.160', 'CREATE', 'catatan_aktivitas', 73, 'MUHAMMAD RIFIANSYAH', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Cukur\"}', 'Tambah aktivitas izin_keluar', '2026-01-16 07:34:39'),
(175, 1, 'Administrator', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 74, 'M. FAHMI SIROJUL MUNIR', NULL, '{\"kategori\":\"sakit\",\"judul\":\"demam\"}', 'Tambah aktivitas sakit', '2026-01-17 04:42:12'),
(176, 1, 'Administrator', 'Windows PC', '36.68.55.229', 'DELETE', 'catatan_aktivitas', NULL, NULL, NULL, NULL, 'Hapus 1 data aktivitas ke trash', '2026-01-17 04:43:20'),
(178, 7, 'Akrom Adabi', 'Android Device', '36.68.55.229', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-21 00:46:52'),
(179, 7, 'Akrom Adabi', 'Android Device', '36.68.55.229', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-21 09:11:24'),
(180, 7, 'Akrom Adabi', 'Android Device', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 75, 'FARAS MEISABILA', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Periksa Gigi\"}', 'Tambah aktivitas izin_keluar', '2026-01-21 09:12:04'),
(181, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.229', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-21 15:17:53'),
(182, 7, 'Akrom Adabi', 'Windows PC', '182.2.50.103', 'UPDATE', 'catatan_aktivitas', 75, 'FARAS MEISABILA', NULL, NULL, NULL, '2026-01-21 15:44:45'),
(183, 9, 'Kowi', 'Android Device', '112.215.168.124', 'LOGIN', 'users', 9, 'Kowi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-21 15:47:25'),
(184, 15, 'Adil', 'Android Device', '182.2.68.56', 'LOGIN', 'users', 15, 'Adil', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-21 15:48:47'),
(185, 14, 'Surya', 'Android Device', '182.2.68.56', 'LOGIN', 'users', 14, 'Surya', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-21 15:48:48'),
(186, 19, 'Affan', 'Android Device', '182.2.77.175', 'LOGIN', 'users', 19, 'Affan', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-21 15:49:54'),
(187, 11, 'Nayla', 'Android Device', '112.215.168.200', 'LOGIN', 'users', 11, 'Nayla', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-21 15:50:40'),
(188, 7, 'Akrom Adabi', NULL, '182.2.52.173', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Administrator', '2026-01-21 16:09:09'),
(189, 1, 'Administrator', 'Android Device', '182.2.76.133', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-21 16:09:37'),
(190, 15, 'Adil', 'Android Device', '182.2.68.56', 'LOGOUT', 'users', 15, 'Adil', NULL, NULL, 'Pengguna keluar dari sistem', '2026-01-21 16:18:00'),
(191, 15, 'Adil', 'Android Device', '182.2.68.56', 'LOGIN', 'users', 15, 'Adil', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-21 16:18:18'),
(192, 1, 'Administrator', NULL, '182.2.76.133', 'create', NULL, NULL, NULL, NULL, NULL, 'Menambahkan user: nova', '2026-01-21 16:30:31'),
(193, 1, 'Administrator', NULL, '182.2.76.133', 'update', NULL, NULL, NULL, NULL, NULL, 'Mengubah user: Nova', '2026-01-21 16:30:44'),
(194, 20, 'Nova', 'Android Device', '36.68.55.229', 'LOGIN', 'users', 20, 'Nova', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-21 22:15:22'),
(195, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.229', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-21 23:17:12'),
(196, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 76, 'MUHAMMAD RAFI ADITYA', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"Sakit\"}', 'Tambah aktivitas izin_pulang', '2026-01-21 23:17:51'),
(197, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.229', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-22 02:02:48'),
(198, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 77, 'MUH HASYIM MAKMUR', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"periksa telinga\"}', 'Tambah aktivitas izin_keluar', '2026-01-22 02:03:58'),
(199, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.229', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-22 07:14:22'),
(200, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 78, 'M. FILZA AZIDAN FAZA', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"PERIKSA MATA\"}', 'Tambah aktivitas izin_pulang', '2026-01-22 07:15:01'),
(201, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.229', 'UPDATE', 'catatan_aktivitas', 78, 'M. FILZA AZIDAN FAZA', NULL, NULL, NULL, '2026-01-22 07:16:55'),
(202, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.229', 'UPDATE', 'catatan_aktivitas', 78, 'M. FILZA AZIDAN FAZA', NULL, NULL, NULL, '2026-01-22 07:17:24'),
(203, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.229', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-22 22:53:35'),
(204, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'LOGIN', 'users', 11, 'Nayla', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-23 01:13:14'),
(205, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 79, 'ALFA KAMELIA PUTRI', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"pijet\"}', 'Tambah aktivitas izin_pulang', '2026-01-23 01:26:27'),
(206, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 80, 'NAYVELIN CELSILIA TJANDRAWAN .', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"keluar\"}', 'Tambah aktivitas izin_keluar', '2026-01-23 01:27:59'),
(207, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'LOGIN', 'users', 11, 'Nayla', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-23 02:54:16');
INSERT INTO `activity_logs` (`id`, `user_id`, `user_name`, `device_name`, `ip_address`, `action`, `table_name`, `record_id`, `record_name`, `old_data`, `new_data`, `description`, `created_at`) VALUES
(208, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 81, 'ANGGI FARADILA SUGIONO', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"bikin ktp\"}', 'Tambah aktivitas izin_pulang', '2026-01-23 03:30:43'),
(209, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 82, 'AUDIYYA ROHMATIL WAFA', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"jalan jalan jumat\"}', 'Tambah aktivitas izin_keluar', '2026-01-23 03:37:17'),
(210, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 83, 'NUR AFNI', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"ja\"}', 'Tambah aktivitas izin_keluar', '2026-01-23 03:38:14'),
(211, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'UPDATE', 'catatan_aktivitas', 83, 'NUR AFNI', NULL, NULL, NULL, '2026-01-23 03:38:32'),
(212, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 84, 'ZULFA MARATUS SOLIHAH', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"jalan jalan jumat\"}', 'Tambah aktivitas izin_keluar', '2026-01-23 03:39:30'),
(213, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 85, 'RIZQI KAMILA PUTRI', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"jalan jalan jumat\"}', 'Tambah aktivitas izin_keluar', '2026-01-23 03:40:12'),
(214, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 86, 'DEWI MARSHA OCTAVIANA', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"daftar sekolah\"}', 'Tambah aktivitas izin_pulang', '2026-01-23 03:42:22'),
(215, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 87, 'MAY LAVA SEVENTEEN', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"jalan jalan jumat\"}', 'Tambah aktivitas izin_keluar', '2026-01-23 03:43:28'),
(216, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 88, 'FATIMATUS ZAHRO', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"jalan jalan jumat\"}', 'Tambah aktivitas izin_keluar', '2026-01-23 03:44:20'),
(217, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 89, 'LUK LU\'UL MA\'SUMAH', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"jalan jalan jumat\"}', 'Tambah aktivitas izin_keluar', '2026-01-23 03:56:52'),
(218, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 90, 'AFIKA RIZKI WULANDARI', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"jalan jalan jumat\"}', 'Tambah aktivitas izin_keluar', '2026-01-23 04:01:35'),
(219, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 91, 'KEISHA NADIA ANINDHITA', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"jalan jalan jumat\"}', 'Tambah aktivitas izin_keluar', '2026-01-23 04:02:45'),
(220, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'CREATE', 'catatan_aktivitas', 92, 'AMIRA RIZQI RAMADHANI', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"jalan jalan jumat\"}', 'Tambah aktivitas izin_keluar', '2026-01-23 04:03:37'),
(221, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'UPDATE', 'catatan_aktivitas', 83, 'NUR AFNI', NULL, NULL, NULL, '2026-01-23 04:17:28'),
(222, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'UPDATE', 'catatan_aktivitas', 83, 'NUR AFNI', NULL, NULL, NULL, '2026-01-23 04:18:04'),
(223, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'UPDATE', 'catatan_aktivitas', 85, 'RIZQI KAMILA PUTRI', NULL, NULL, NULL, '2026-01-23 04:18:41'),
(224, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'UPDATE', 'catatan_aktivitas', 91, 'KEISHA NADIA ANINDHITA', NULL, NULL, NULL, '2026-01-23 04:19:07'),
(225, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'UPDATE', 'catatan_aktivitas', 92, 'AMIRA RIZQI RAMADHANI', NULL, NULL, NULL, '2026-01-23 04:19:35'),
(226, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'UPDATE', 'catatan_aktivitas', 82, 'AUDIYYA ROHMATIL WAFA', NULL, NULL, NULL, '2026-01-23 04:19:52'),
(227, 11, 'Nayla', 'Windows PC', '36.68.55.229', 'UPDATE', 'catatan_aktivitas', 84, 'ZULFA MARATUS SOLIHAH', NULL, NULL, NULL, '2026-01-23 04:20:11'),
(228, 19, 'Affan', 'Android Device', '36.68.53.131', 'LOGIN', 'users', 19, 'Affan', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-24 00:05:46'),
(229, 19, 'Affan', 'Android Device', '36.68.53.131', 'CREATE', 'catatan_aktivitas', 93, 'MUHAMMAD RIFQI  HAFIDL', NULL, '{\"kategori\":\"sakit\",\"judul\":\"Panas\"}', 'Tambah aktivitas sakit', '2026-01-24 00:08:11'),
(230, 19, 'Affan', 'Android Device', '36.68.53.131', 'CREATE', 'catatan_aktivitas', 94, 'MUHAMMAD ALIFFATURRIZKI', NULL, '{\"kategori\":\"sakit\",\"judul\":\"Panas\"}', 'Tambah aktivitas sakit', '2026-01-24 00:08:44'),
(231, 7, 'Akrom Adabi', 'Android Device', '182.2.40.218', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-24 05:28:58'),
(232, 7, 'Akrom Adabi', 'Windows PC', '36.68.53.131', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-24 08:33:17'),
(233, 7, 'Akrom Adabi', 'Windows PC', '36.68.53.131', 'CREATE', 'catatan_aktivitas', 95, 'GALANG WAHYU SAPUTRA', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"pulang tanpa izin\"}', 'Tambah aktivitas pelanggaran', '2026-01-24 09:02:12'),
(234, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'LOGIN', 'users', 11, 'Nayla', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-24 12:22:19'),
(235, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'CREATE', 'catatan_aktivitas', 96, 'DIAN LATHIFATUL IZZAH', NULL, '{\"kategori\":\"sakit\",\"judul\":\"mata ikan, bengkak kaki\"}', 'Tambah aktivitas sakit', '2026-01-24 12:27:13'),
(236, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'CREATE', 'catatan_aktivitas', 97, 'ALFI RIZQIHANA', NULL, '{\"kategori\":\"sakit\",\"judul\":\"magh\"}', 'Tambah aktivitas sakit', '2026-01-24 12:28:14'),
(237, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'CREATE', 'catatan_aktivitas', 98, 'ALFI RIZQIHANA', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"sakit\"}', 'Tambah aktivitas izin_pulang', '2026-01-24 12:30:33'),
(238, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'UPDATE', 'catatan_aktivitas', 79, 'ALFA KAMELIA PUTRI', NULL, NULL, NULL, '2026-01-24 12:31:04'),
(239, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'UPDATE', 'catatan_aktivitas', 86, 'DEWI MARSHA OCTAVIANA', NULL, NULL, NULL, '2026-01-24 12:31:28'),
(240, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'UPDATE', 'catatan_aktivitas', 81, 'ANGGI FARADILA SUGIONO', NULL, NULL, NULL, '2026-01-24 12:31:49'),
(241, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'UPDATE', 'catatan_aktivitas', 60, 'CLARA VANESHA MAHARANI', NULL, NULL, NULL, '2026-01-24 12:32:24'),
(242, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'UPDATE', 'catatan_aktivitas', 61, 'CLARA VANESHA MAHARANI', NULL, NULL, NULL, '2026-01-24 12:33:47'),
(243, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'DELETE', 'catatan_aktivitas', NULL, NULL, NULL, NULL, 'Hapus 1 data aktivitas ke trash', '2026-01-24 12:34:21'),
(244, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'DELETE', 'catatan_aktivitas', NULL, NULL, NULL, NULL, 'Hapus 1 data aktivitas ke trash', '2026-01-24 12:34:53'),
(245, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'CREATE', 'catatan_aktivitas', 99, 'DIAN LATHIFATUL IZZAH', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"sakit\"}', 'Tambah aktivitas izin_pulang', '2026-01-24 12:35:52'),
(246, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'CREATE', 'catatan_aktivitas', 100, 'AYU SULISTIA', NULL, '{\"kategori\":\"sakit\",\"judul\":\"mata ikan, bengkak kaki\"}', 'Tambah aktivitas sakit', '2026-01-24 12:36:31'),
(247, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'CREATE', 'catatan_aktivitas', 101, 'SAFIRA AMANDA PUTRI', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"ziarah keluarga GEDE\"}', 'Tambah aktivitas izin_pulang', '2026-01-24 12:37:34'),
(248, 11, 'Nayla', 'Windows PC', '36.68.53.131', 'UPDATE', 'catatan_aktivitas', 101, 'SAFIRA AMANDA PUTRI', NULL, NULL, NULL, '2026-01-24 12:37:47'),
(249, 19, 'Affan', 'Android Device', '36.68.53.131', 'LOGIN', 'users', 19, 'Affan', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-25 06:55:54'),
(250, 19, 'Affan', 'Android Device', '36.68.53.131', 'CREATE', 'catatan_aktivitas', 102, 'MUHAMMAD ALAIKA FAZA', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Beli sapu kamar\"}', 'Tambah aktivitas izin_keluar', '2026-01-25 06:58:02'),
(251, 19, 'Affan', 'Android Device', '36.68.53.131', 'CREATE', 'catatan_aktivitas', 103, 'FARIS VERDIYANTO', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Beli sapu kamar\"}', 'Tambah aktivitas izin_keluar', '2026-01-25 06:58:33'),
(252, 7, 'Akrom Adabi', 'Windows PC', '36.68.53.131', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-25 22:56:38'),
(253, 9, 'Kowi', 'Android Device', '140.213.173.182', 'CREATE', 'catatan_aktivitas', 104, 'MUHAMAD FAIZZUN', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"Minggat\"}', 'Tambah aktivitas pelanggaran', '2026-01-26 11:41:11'),
(254, 7, 'Akrom Adabi', 'Windows PC', '36.68.53.131', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-26 12:09:12'),
(255, 1, 'Administrator', 'Android Device', '182.2.69.0', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-27 04:03:51'),
(256, 7, 'Akrom Adabi', 'Windows PC', '36.68.53.131', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-27 06:00:58'),
(257, 7, 'Akrom Adabi', 'Windows PC', '36.68.53.131', 'CREATE', 'catatan_aktivitas', 105, 'M. ZAKI UBAIDILLAH', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"sakit demam\"}', 'Tambah aktivitas izin_pulang', '2026-01-27 06:02:53'),
(258, 7, 'Akrom Adabi', 'Windows PC', '36.68.53.131', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-27 09:22:35'),
(259, 7, 'Akrom Adabi', 'Windows PC', '36.68.53.131', 'UPDATE', 'catatan_aktivitas', 95, 'GALANG WAHYU SAPUTRA', NULL, NULL, NULL, '2026-01-27 09:22:47'),
(260, 7, 'Akrom Adabi', 'Windows PC', '36.68.53.131', 'UPDATE', 'catatan_aktivitas', 95, 'GALANG WAHYU SAPUTRA', NULL, NULL, NULL, '2026-01-27 09:22:57'),
(261, 7, 'Akrom Adabi', 'Windows PC', '36.68.53.131', 'CREATE', 'catatan_aktivitas', 106, 'MUHAMMAD IRFAN ZIDNI', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"sakit\"}', 'Tambah aktivitas izin_pulang', '2026-01-27 09:36:49'),
(262, 7, 'Akrom Adabi', 'Windows PC', '36.68.53.131', 'UPDATE', 'catatan_aktivitas', 78, 'M. FILZA AZIDAN FAZA', NULL, NULL, NULL, '2026-01-27 09:37:09'),
(263, 7, 'Akrom Adabi', 'Windows PC', '36.68.53.131', 'UPDATE', 'catatan_aktivitas', 76, 'MUHAMMAD RAFI ADITYA', NULL, NULL, NULL, '2026-01-27 09:37:29'),
(264, 7, 'Akrom Adabi', 'Windows PC', '36.68.53.131', 'UPDATE', 'catatan_aktivitas', 76, 'MUHAMMAD RAFI ADITYA', NULL, NULL, NULL, '2026-01-27 09:37:43'),
(265, 9, 'Kowi', 'Android Device', '140.213.171.184', 'CREATE', 'catatan_aktivitas', 107, 'ACHMAD GILANG ZHAFIF MAULA', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"Acara keluarga\"}', 'Tambah aktivitas izin_pulang', '2026-01-27 17:00:25'),
(266, 9, 'Kowi', 'Android Device', '140.213.171.184', 'UPDATE', 'catatan_aktivitas', 107, 'ACHMAD GILANG ZHAFIF MAULA', NULL, NULL, NULL, '2026-01-27 17:00:44'),
(267, 9, 'Kowi', 'Android Device', '140.213.171.184', 'DELETE', 'catatan_aktivitas', NULL, NULL, NULL, NULL, 'Hapus 1 data aktivitas ke trash', '2026-01-27 17:01:27'),
(268, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-28 00:21:50'),
(269, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-28 10:41:33'),
(270, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'CREATE', 'catatan_aktivitas', 108, 'HANA SHAFITRI INDRIANI', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"Periksa Sakit\"}', 'Tambah aktivitas izin_pulang', '2026-01-28 10:42:24'),
(271, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-28 14:17:59'),
(272, 1, 'Administrator', 'Windows PC', '118.96.70.104', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-28 18:52:58'),
(273, 1, 'Administrator', 'Windows PC', '118.96.70.104', 'CREATE', 'catatan_aktivitas', 109, 'FIRMAN MAULANA RISKY', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"Keluar malam\"}', 'Tambah aktivitas pelanggaran', '2026-01-28 18:53:38'),
(274, 19, 'Affan', 'Android Device', '182.2.40.117', 'LOGIN', 'users', 19, 'Affan', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-29 11:22:42'),
(275, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-29 15:29:54'),
(276, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'CREATE', 'catatan_aktivitas', 110, 'M.AZKA RIFIYANSYAH', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"Periksa\"}', 'Tambah aktivitas izin_pulang', '2026-01-29 15:30:40'),
(277, 11, 'Nayla', 'Windows PC', '118.96.70.104', 'LOGIN', 'users', 11, 'Nayla', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-30 03:17:09'),
(278, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-30 03:33:16'),
(279, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'CREATE', 'catatan_aktivitas', 111, 'MUHAMAD HUSAIN MUSYAFI', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"Periksa\"}', 'Tambah aktivitas izin_pulang', '2026-01-30 03:33:53'),
(280, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'CREATE', 'catatan_aktivitas', 112, 'MUHAMMAD RAFI ADITYA', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Pijat\"}', 'Tambah aktivitas izin_keluar', '2026-01-30 03:40:23'),
(281, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'CREATE', 'catatan_aktivitas', 113, 'MUHAMMAD ZAKI ZHAFIR', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"acara keluarga\"}', 'Tambah aktivitas izin_pulang', '2026-01-30 04:28:34'),
(282, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'CREATE', 'catatan_aktivitas', 114, 'KHAMID AWALUDIN', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"Sakit\"}', 'Tambah aktivitas izin_pulang', '2026-01-30 04:46:47'),
(283, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'CREATE', 'catatan_aktivitas', 115, 'MUHAMMAD FADIL ARIFIN', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"Sakit\"}', 'Tambah aktivitas izin_pulang', '2026-01-30 04:47:25'),
(284, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'CREATE', 'catatan_aktivitas', 116, 'MUHAMAD ALIF MAULANA', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"Sakit\"}', 'Tambah aktivitas izin_pulang', '2026-01-30 04:48:02'),
(285, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'CREATE', 'catatan_aktivitas', 117, 'ZULFA MARATUS SOLIHAH', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"beli perlatan\"}', 'Tambah aktivitas izin_keluar', '2026-01-30 06:14:59'),
(286, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'CREATE', 'catatan_aktivitas', 118, 'NAVITA ALFANIAH', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"beli perlatan\"}', 'Tambah aktivitas izin_keluar', '2026-01-30 06:15:11'),
(287, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-30 10:15:46'),
(288, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'CREATE', 'catatan_aktivitas', 119, 'MUHAMMAD ALIFFATURRIZKI', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"pijat kesleo\"}', 'Tambah aktivitas izin_pulang', '2026-01-30 10:16:49'),
(289, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'CREATE', 'catatan_aktivitas', 120, 'ANANTA ARYASATYA', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"demam\"}', 'Tambah aktivitas izin_pulang', '2026-01-30 10:17:23'),
(290, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'UPDATE', 'catatan_aktivitas', 118, 'NAVITA ALFANIAH', NULL, NULL, NULL, '2026-01-30 10:17:59'),
(291, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'UPDATE', 'catatan_aktivitas', 117, 'ZULFA MARATUS SOLIHAH', NULL, NULL, NULL, '2026-01-30 10:18:25'),
(292, 7, 'Akrom Adabi', 'Windows PC', '118.96.70.104', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-30 14:31:19'),
(293, 9, 'Kowi', 'Android Device', '112.215.145.236', 'LOGIN', 'users', 9, 'Kowi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-30 15:09:14'),
(294, 9, 'Kowi', 'Linux PC', '112.215.165.195', 'UPDATE', 'catatan_aktivitas', 113, 'MUHAMMAD ZAKI ZHAFIR', NULL, NULL, NULL, '2026-01-30 15:24:44'),
(295, 9, 'Kowi', 'Linux PC', '118.96.70.104', 'UPDATE', 'catatan_aktivitas', 113, 'MUHAMMAD ZAKI ZHAFIR', NULL, NULL, NULL, '2026-01-30 15:25:06'),
(296, 9, 'Kowi', 'Linux PC', '118.96.70.104', 'UPDATE', 'catatan_aktivitas', 115, 'MUHAMMAD FADIL ARIFIN', NULL, NULL, NULL, '2026-01-30 15:25:26'),
(297, 9, 'Kowi', 'Linux PC', '118.96.70.104', 'UPDATE', 'catatan_aktivitas', 115, 'MUHAMMAD FADIL ARIFIN', NULL, NULL, NULL, '2026-01-30 15:25:43'),
(298, 9, 'Kowi', 'Linux PC', '118.96.70.104', 'UPDATE', 'catatan_aktivitas', 115, 'MUHAMMAD FADIL ARIFIN', NULL, NULL, NULL, '2026-01-30 15:25:53'),
(299, 1, 'Administrator', 'Windows PC', '118.96.70.104', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-30 15:26:08'),
(300, 1, 'Administrator', 'Windows PC', '118.96.70.104', 'UPDATE', 'catatan_aktivitas', 115, 'MUHAMMAD FADIL ARIFIN', NULL, NULL, NULL, '2026-01-30 15:27:24'),
(301, 7, 'Akrom Adabi', 'Android Device', '118.96.70.104', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-31 07:27:30'),
(302, 7, 'Akrom Adabi', 'Android Device', '118.96.70.104', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-31 07:27:30'),
(303, 7, 'Akrom Adabi', 'Android Device', '118.96.70.104', 'CREATE', 'catatan_aktivitas', 121, 'MUHAMMAD KEVIN SANTOSO', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Fotocopy tugas\"}', 'Tambah aktivitas izin_keluar', '2026-01-31 07:27:54'),
(304, 1, 'Administrator', 'Android Device', '182.2.39.119', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-01-31 08:47:37'),
(305, 1, 'Administrator', 'Android Device', '182.2.39.119', 'UPDATE', 'catatan_aktivitas', 121, 'MUHAMMAD KEVIN SANTOSO', NULL, NULL, NULL, '2026-01-31 08:47:54'),
(306, 9, 'Kowi', 'Android Device', '36.78.52.103', 'LOGIN', 'users', 9, 'Kowi', NULL, NULL, 'Pengguna berhasil masuk', '2026-02-01 01:16:41'),
(307, 9, 'Kowi', 'Android Device', '36.78.52.103', 'CREATE', 'catatan_aktivitas', 122, 'GALANG WAHYU SAPUTRA', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"Pulang tanpa izin\"}', 'Tambah aktivitas pelanggaran', '2026-02-01 01:25:09'),
(308, 9, 'Kowi', 'Android Device', '36.78.52.103', 'CREATE', 'catatan_aktivitas', 123, 'SYAHRUL HANAFI', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"Pulang tanpa izin\"}', 'Tambah aktivitas pelanggaran', '2026-02-01 01:25:29'),
(309, 9, 'Kowi', 'Android Device', '36.78.52.103', 'CREATE', 'catatan_aktivitas', 124, 'MUHAMMAD ALIFFATURRIZKI', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"Pulang tanpa izin\"}', 'Tambah aktivitas pelanggaran', '2026-02-01 01:25:46'),
(310, 9, 'Kowi', 'Android Device', '36.68.55.224', 'CREATE', 'catatan_aktivitas', 125, 'MUHAMMAD IKMAL MAULANA', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"Keluar malam & rokokan di main\"}', 'Tambah aktivitas pelanggaran', '2026-02-03 04:53:32'),
(311, 9, 'Kowi', 'Android Device', '36.68.55.224', 'CREATE', 'catatan_aktivitas', 126, 'MUHAMMAD GUSTHOHA', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"Keluar malam & rokokan di main\"}', 'Tambah aktivitas pelanggaran', '2026-02-03 04:54:19'),
(312, 9, 'Kowi', 'Android Device', '36.68.55.224', 'CREATE', 'catatan_aktivitas', 127, 'MUHAMMAD RIFQI FADLI', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"Keluar malam & rokokan di main\"}', 'Tambah aktivitas pelanggaran', '2026-02-03 04:54:50'),
(313, 9, 'Kowi', 'Android Device', '36.68.55.224', 'CREATE', 'catatan_aktivitas', 128, 'SABIL BAROKAH', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"Keluar malam & rokokan di main\"}', 'Tambah aktivitas pelanggaran', '2026-02-03 04:55:35'),
(314, 9, 'Kowi', 'Android Device', '36.68.55.224', 'CREATE', 'catatan_aktivitas', 129, 'MUHAMMAD TAUFIQ MAULANA', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"Keluar malam & rokokan di main\"}', 'Tambah aktivitas pelanggaran', '2026-02-03 04:56:01'),
(315, 9, 'Kowi', 'Android Device', '36.68.55.224', 'CREATE', 'catatan_aktivitas', 130, 'M. EVAN SADINA FARIZQI', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"Keluar malam, bawa hp & rokokan di main\"}', 'Tambah aktivitas pelanggaran', '2026-02-03 04:56:48'),
(316, 9, 'Kowi', 'Android Device', '36.68.55.224', 'UPDATE', 'catatan_aktivitas', 130, 'M. EVAN SADINA FARIZQI', NULL, NULL, NULL, '2026-02-03 04:57:33'),
(317, 9, 'Kowi', 'Android Device', '36.68.55.224', 'CREATE', 'catatan_aktivitas', 131, 'RAFA ABDULLAH FAQIH', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"Keluar malam ,bawah & rokokan di main\"}', 'Tambah aktivitas pelanggaran', '2026-02-03 04:58:15'),
(318, 9, 'Kowi', 'Android Device', '36.68.55.224', 'CREATE', 'catatan_aktivitas', 132, 'M.RAYAN DANADYAKSA', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"Keluar malam di main\"}', 'Tambah aktivitas pelanggaran', '2026-02-03 04:59:06'),
(319, 9, 'Kowi', 'Android Device', '36.68.55.224', 'CREATE', 'catatan_aktivitas', 133, 'M.REZKY PUTRA PRATAMA', NULL, '{\"kategori\":\"pelanggaran\",\"judul\":\"Keluar malam & rokokan di main\"}', 'Tambah aktivitas pelanggaran', '2026-02-03 04:59:36'),
(320, 10, 'Oki', 'Android Device', '36.68.55.128', 'LOGIN', 'users', 10, 'Oki', NULL, NULL, 'Pengguna berhasil masuk', '2026-02-03 16:11:19'),
(321, 10, 'Oki', 'Android Device', '36.68.55.128', 'CREATE', 'catatan_aktivitas', 134, 'MUHAMMAD SYAUQI BIK', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"Sakit\"}', 'Tambah aktivitas izin_pulang', '2026-02-03 16:13:23'),
(322, 9, 'Kowi', 'Android Device', '140.213.165.55', 'LOGOUT', 'users', 9, 'Kowi', NULL, NULL, 'Pengguna keluar dari sistem', '2026-02-03 21:22:45'),
(323, 9, 'Kowi', 'Android Device', '140.213.165.55', 'LOGIN', 'users', 9, 'Kowi', NULL, NULL, 'Pengguna berhasil masuk', '2026-02-03 21:24:30'),
(324, 10, 'Oki', 'Android Device', '36.68.55.128', 'CREATE', 'catatan_aktivitas', 135, 'M NABIL RIZIQ', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"Sakit priksa\"}', 'Tambah aktivitas izin_pulang', '2026-02-03 23:34:39'),
(325, 7, 'Akrom Adabi', 'Android Device', '36.68.55.128', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-02-04 06:58:27'),
(326, 7, 'Akrom Adabi', 'Android Device', '36.68.55.128', 'CREATE', 'catatan_aktivitas', 136, 'ZULFA MARATUS SOLIHAH', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Fotocopy\"}', 'Tambah aktivitas izin_keluar', '2026-02-04 06:59:12'),
(327, 7, 'Akrom Adabi', 'Android Device', '36.68.55.128', 'CREATE', 'catatan_aktivitas', 137, 'NAJWA AISYA PUTRI', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Fotocopy\"}', 'Tambah aktivitas izin_keluar', '2026-02-04 06:59:40'),
(328, 9, 'Kowi', 'Android Device', '36.68.55.128', 'CREATE', 'catatan_aktivitas', 138, 'ACHMAD GILANG ZHAFIF MAULA', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"Acara keluarga\"}', 'Tambah aktivitas izin_pulang', '2026-02-05 08:02:37'),
(329, 9, 'Kowi', 'Android Device', '36.68.55.128', 'CREATE', 'catatan_aktivitas', 139, 'MUHAMMAD ARDI FIRMANSYAH', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"Izin periksa mata\"}', 'Tambah aktivitas izin_pulang', '2026-02-05 09:33:49'),
(330, 10, 'Oki', 'Android Device', '36.68.55.128', 'CREATE', 'catatan_aktivitas', 140, 'MUHAMMAD TAUFIQ MAULANA', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Priksa ke dokter gigi\"}', 'Tambah aktivitas izin_keluar', '2026-02-06 03:08:15'),
(331, 10, 'Oki', 'Android Device', '36.68.55.128', 'LOGIN', 'users', 10, 'Oki', NULL, NULL, 'Pengguna berhasil masuk', '2026-02-06 03:11:01'),
(332, 10, 'Oki', 'Android Device', '36.68.55.128', 'CREATE', 'catatan_aktivitas', 141, 'M. ARFAUR RIKZA', NULL, '{\"kategori\":\"sakit\",\"judul\":\"sakit panas\"}', 'Tambah aktivitas sakit', '2026-02-06 03:58:49'),
(333, 10, 'Oki', 'Android Device', '36.68.55.128', 'CREATE', 'catatan_aktivitas', 142, 'GALANG WAHYU SAPUTRA', NULL, '{\"kategori\":\"izin_keluar\",\"judul\":\"Beli keperluan\"}', 'Tambah aktivitas izin_keluar', '2026-02-06 04:00:42'),
(334, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.128', 'LOGIN', 'users', 7, 'Akrom Adabi', NULL, NULL, 'Pengguna berhasil masuk', '2026-02-06 15:20:13'),
(335, 7, 'Akrom Adabi', 'Windows PC', '36.68.55.128', 'UPDATE', 'catatan_aktivitas', 134, 'MUHAMMAD SYAUQI BIK', NULL, NULL, NULL, '2026-02-06 15:20:50'),
(336, 1, 'Administrator', 'Android Device', '36.68.55.128', 'LOGIN', 'users', 1, 'Administrator', NULL, NULL, 'Pengguna berhasil masuk', '2026-02-07 09:09:22'),
(337, 9, 'Kowi', 'Android Device', '36.68.55.128', 'CREATE', 'catatan_aktivitas', 143, 'FARRAS REYNAR RAFIF', NULL, '{\"kategori\":\"izin_pulang\",\"judul\":\"Kontrol gigi\"}', 'Tambah aktivitas izin_pulang', '2026-02-07 09:54:59');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `jadwal_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('clock_in','clock_out') DEFAULT 'clock_in',
  `attendance_date` date NOT NULL,
  `attendance_time` time NOT NULL,
  `status` enum('hadir','terlambat','absen','izin','sakit','pulang') DEFAULT 'hadir',
  `minutes_late` int(11) DEFAULT 0,
  `notes` text DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `user_id`, `jadwal_id`, `type`, `attendance_date`, `attendance_time`, `status`, `minutes_late`, `notes`, `latitude`, `longitude`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(67, 184, 1, 'clock_in', '2026-01-14', '06:16:00', 'hadir', 0, NULL, NULL, NULL, '2026-01-13 23:16:38', '2026-01-13 23:16:49', '2026-01-13 23:16:49', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `catatan_aktivitas`
--

CREATE TABLE `catatan_aktivitas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status_sambangan` varchar(50) DEFAULT NULL,
  `status_kegiatan` varchar(50) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `batas_waktu` datetime DEFAULT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `foto_dokumen_1` varchar(255) DEFAULT NULL,
  `foto_dokumen_2` varchar(255) DEFAULT NULL,
  `dibuat_oleh` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `catatan_aktivitas`
--

INSERT INTO `catatan_aktivitas` (`id`, `siswa_id`, `kategori`, `judul`, `keterangan`, `status_sambangan`, `status_kegiatan`, `tanggal`, `batas_waktu`, `tanggal_selesai`, `foto_dokumen_1`, `foto_dokumen_2`, `dibuat_oleh`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(25, 76, 'pelanggaran', 'Merokok', 'Merokok di main jam sekolah', '', 'Belum Diperiksa', '2026-01-08 07:28:00', NULL, NULL, NULL, NULL, 7, '2026-01-08 07:29:22', '2026-01-08 07:29:22', NULL, NULL),
(26, 104, 'pelanggaran', 'Merokok', 'Merokok di main', '', 'Belum Diperiksa', '2026-01-08 07:30:00', NULL, NULL, NULL, NULL, 7, '2026-01-08 07:30:27', '2026-01-08 07:30:27', NULL, NULL),
(27, 146, 'pelanggaran', 'Merokok dan PS an', 'Merokok dan PS an malam dengan isfad', '', 'Belum Diperiksa', '2026-01-08 10:26:00', NULL, NULL, NULL, NULL, 7, '2026-01-08 10:26:57', '2026-01-08 10:26:57', NULL, NULL),
(28, 237, 'pelanggaran', 'Merokok ', 'Merokok dan bersama restu di jam sekolah', '', 'Belum Diperiksa', '2026-01-08 10:27:00', NULL, NULL, NULL, NULL, 7, '2026-01-08 10:27:45', '2026-01-08 10:27:45', NULL, NULL),
(29, 149, 'pelanggaran', 'Merokok', 'Merokok bareng almas', '', 'Belum Diperiksa', '2026-01-08 10:35:00', NULL, NULL, NULL, NULL, 7, '2026-01-08 10:35:33', '2026-01-08 10:35:33', NULL, NULL),
(30, 207, 'pelanggaran', 'Merokok', 'Rokokan bareng sigit', '', 'Belum Diperiksa', '2026-01-09 05:52:00', NULL, NULL, NULL, NULL, 7, '2026-01-09 05:53:05', '2026-01-09 05:53:16', NULL, NULL),
(31, 217, 'pelanggaran', 'Merokok ', 'Rokokan bareng sigit', '', 'Belum Diperiksa', '2026-01-09 05:53:00', NULL, NULL, NULL, NULL, 7, '2026-01-09 05:53:31', '2026-01-09 05:53:31', NULL, NULL),
(32, 196, 'izin_keluar', 'Ke toko TB', 'Peralatan mandi', '', 'Belum Diperiksa', '2026-01-09 13:13:00', '2026-01-09 14:12:00', '2026-01-09 18:10:00', NULL, NULL, 8, '2026-01-09 06:14:37', '2026-01-09 11:10:24', NULL, NULL),
(35, 198, 'izin_keluar', 'Beli peralatan mandi', 'Di Tb', '', 'Belum Diperiksa', '2026-01-09 06:19:00', '2026-01-09 15:00:00', '2026-01-09 14:14:00', NULL, NULL, 19, '2026-01-09 06:20:39', '2026-01-09 07:38:38', NULL, NULL),
(38, 184, 'izin_keluar', 'Beli peralatan mandi', 'Di TB', '', 'Belum Diperiksa', '2026-01-09 06:21:00', '2026-01-09 15:05:00', '2026-01-09 14:14:00', NULL, NULL, 19, '2026-01-09 06:21:54', '2026-01-09 07:38:52', NULL, NULL),
(39, 202, 'izin_keluar', 'Beli peralatan mandi', 'Di Alfamart', '', 'Belum Diperiksa', '2026-01-09 06:24:00', '2026-01-09 15:05:00', '2026-01-09 14:22:00', NULL, NULL, 19, '2026-01-09 06:24:49', '2026-01-09 07:39:11', NULL, NULL),
(40, 182, 'izin_keluar', 'Beli peralatan mandi', 'Di TB', '', 'Belum Diperiksa', '2026-01-09 06:25:00', '2026-01-09 15:05:00', '2026-01-09 14:22:00', NULL, NULL, 19, '2026-01-09 06:26:18', '2026-01-09 07:39:54', NULL, NULL),
(47, 92, 'sakit', 'Sakit ', 'Wudun di mata', '', 'Belum Diperiksa', '2026-01-09 23:21:00', NULL, '2026-01-11 08:00:00', NULL, NULL, 19, '2026-01-09 23:24:55', '2026-01-12 01:01:03', NULL, NULL),
(48, 84, 'paket', 'Jajan', '', '', 'Belum Diterima', '2026-01-10 05:59:00', NULL, NULL, NULL, NULL, 9, '2026-01-10 05:59:44', '2026-01-12 02:40:06', NULL, NULL),
(57, 237, 'izin_pulang', 'Periksa wudun', 'dirumah', '', 'Belum Diperiksa', '2026-01-11 12:15:00', '2026-01-12 17:30:00', NULL, NULL, NULL, 19, '2026-01-11 12:16:29', '2026-01-11 12:16:29', NULL, NULL),
(58, 79, 'pelanggaran', 'PSan', 'PS an di jrebeng pulang jam 2', '', 'Belum Diperiksa', '2026-01-07 23:09:00', NULL, NULL, NULL, NULL, 7, '2026-01-11 23:09:55', '2026-01-11 23:09:55', NULL, NULL),
(59, 79, 'pelanggaran', 'PSan', 'PS an lagi di jrebeng dengan fatan pulang jam 12', '', 'Belum Diperiksa', '2026-01-10 23:09:00', NULL, NULL, NULL, NULL, 7, '2026-01-11 23:10:17', '2026-01-11 23:10:17', NULL, NULL),
(60, 226, 'izin_pulang', 'Sakit nanah gatel\"', 'Pulang dirumah', '', 'Belum Diperiksa', '2026-01-12 00:57:00', '2026-01-14 17:00:00', '2026-01-20 19:32:00', NULL, NULL, 19, '2026-01-12 00:59:06', '2026-01-24 12:32:24', NULL, NULL),
(61, 226, 'sakit', 'Nanah gatel\"', 'pulang di rumah', '', 'Sudah Diperiksa', '2026-01-12 00:59:00', NULL, '2026-01-21 19:33:00', NULL, NULL, 19, '2026-01-12 01:00:17', '2026-01-24 12:33:45', NULL, NULL),
(62, 82, 'izin_keluar', 'Nyadrran', 'Langsung kembali', NULL, 'Belum Diperiksa', '2026-01-16 11:16:00', '2026-01-16 17:16:00', NULL, NULL, NULL, 7, '2026-01-16 04:19:04', '2026-01-16 04:19:04', NULL, NULL),
(63, 190, 'izin_keluar', 'Cukur', NULL, NULL, 'Belum Diperiksa', '2026-01-16 13:24:00', '2026-01-16 14:25:00', NULL, NULL, NULL, 9, '2026-01-16 06:25:24', '2026-01-16 06:25:24', NULL, NULL),
(64, 217, 'izin_keluar', 'Cukur', NULL, NULL, 'Belum Diperiksa', '2026-01-16 13:26:00', '2026-01-16 13:26:00', NULL, NULL, NULL, 9, '2026-01-16 06:26:25', '2026-01-16 06:26:25', NULL, NULL),
(65, 56, 'izin_keluar', 'Fc akidah', NULL, NULL, 'Belum Diperiksa', '2026-01-16 13:26:00', '2026-01-16 13:26:00', NULL, NULL, NULL, 9, '2026-01-16 06:26:51', '2026-01-16 06:26:51', NULL, NULL),
(66, 51, 'izin_keluar', 'Cukur', NULL, NULL, 'Belum Diperiksa', '2026-01-16 13:27:00', '2026-01-16 13:27:00', NULL, NULL, NULL, 9, '2026-01-16 06:27:23', '2026-01-16 06:27:23', NULL, NULL),
(67, 54, 'izin_keluar', 'Cukur', NULL, NULL, 'Belum Diperiksa', '2026-01-16 13:27:00', '2026-01-16 13:27:00', NULL, NULL, NULL, 9, '2026-01-16 06:27:47', '2026-01-16 06:27:47', NULL, NULL),
(68, 140, 'izin_keluar', 'Cukur', NULL, NULL, 'Belum Diperiksa', '2026-01-16 13:45:00', '2026-01-16 13:45:00', NULL, NULL, NULL, 9, '2026-01-16 06:46:05', '2026-01-16 06:46:05', NULL, NULL),
(69, 136, 'izin_keluar', 'Cukur', NULL, NULL, 'Belum Diperiksa', '2026-01-16 13:46:00', '2026-01-16 13:46:00', NULL, NULL, NULL, 9, '2026-01-16 06:46:26', '2026-01-16 06:46:26', NULL, NULL),
(70, 170, 'izin_keluar', 'Potong Rambut', NULL, NULL, 'Belum Diperiksa', '2026-01-16 14:21:00', '2026-01-16 15:00:00', NULL, NULL, NULL, 1, '2026-01-16 07:21:57', '2026-01-16 07:21:57', NULL, NULL),
(71, 137, 'izin_keluar', 'Cukur', NULL, NULL, 'Belum Diperiksa', '2026-01-16 14:33:00', '2026-01-16 14:33:00', NULL, NULL, NULL, 9, '2026-01-16 07:33:46', '2026-01-16 07:33:46', NULL, NULL),
(72, 119, 'izin_keluar', 'Cukur', NULL, NULL, 'Belum Diperiksa', '2026-01-16 14:33:00', '2026-01-16 14:34:00', NULL, NULL, NULL, 9, '2026-01-16 07:34:11', '2026-01-16 07:34:11', NULL, NULL),
(73, 153, 'izin_keluar', 'Cukur', NULL, NULL, 'Belum Diperiksa', '2026-01-16 14:34:00', '2026-01-16 14:34:00', NULL, NULL, NULL, 9, '2026-01-16 07:34:39', '2026-01-16 07:34:39', NULL, NULL),
(74, 1, 'sakit', 'demam', NULL, NULL, 'Belum Diperiksa', '2026-01-17 11:42:00', NULL, NULL, NULL, NULL, 1, '2026-01-17 04:42:12', '2026-01-17 04:43:20', '2026-01-17 04:43:20', 1),
(75, 42, 'izin_keluar', 'Periksa Gigi', NULL, NULL, 'Belum Diperiksa', '2026-01-21 16:11:00', '2026-01-21 19:11:00', '2026-01-21 22:44:00', NULL, NULL, 7, '2026-01-21 09:12:04', '2026-01-21 15:44:45', NULL, NULL),
(76, 37, 'izin_pulang', 'Sakit', NULL, NULL, 'Belum Diperiksa', '2026-01-22 06:17:00', '2026-01-23 06:17:00', '2026-01-26 16:37:00', NULL, NULL, 7, '2026-01-21 23:17:51', '2026-01-27 09:37:43', NULL, NULL),
(77, 180, 'izin_keluar', 'periksa telinga', NULL, NULL, 'Belum Diperiksa', '2026-01-22 09:03:00', '2026-01-22 09:03:00', NULL, NULL, NULL, 7, '2026-01-22 02:03:55', '2026-01-22 02:03:55', NULL, NULL),
(78, 224, 'izin_pulang', 'PERIKSA MATA', 'DI KLINIK HERMAWAN', NULL, 'Belum Diperiksa', '2026-01-22 14:14:00', '2026-01-22 14:14:00', '2026-01-23 14:14:00', NULL, NULL, 7, '2026-01-22 07:15:00', '2026-01-22 07:17:24', NULL, NULL),
(79, 145, 'izin_pulang', 'pijet', 'pijat booking jadi membutuhkan waktu beberapahari', NULL, 'Belum Diperiksa', '2026-01-22 08:24:00', '2026-01-24 08:25:00', '2026-01-24 17:30:00', NULL, NULL, 11, '2026-01-23 01:26:27', '2026-01-24 12:31:03', NULL, NULL),
(80, 111, 'izin_keluar', 'keluar', 'menjenguk kakak operasi mata kaki', NULL, 'Belum Diperiksa', '2026-01-22 08:26:00', '2026-01-22 08:27:00', NULL, NULL, NULL, 11, '2026-01-23 01:27:59', '2026-01-23 01:27:59', NULL, NULL),
(81, 172, 'izin_pulang', 'bikin ktp', NULL, NULL, 'Belum Diperiksa', '2026-01-23 09:32:00', '2026-01-23 15:00:00', '2026-01-23 16:31:00', NULL, NULL, 11, '2026-01-23 03:30:41', '2026-01-24 12:31:49', NULL, NULL),
(82, 47, 'izin_keluar', 'jalan jalan jumat', NULL, NULL, 'Belum Diperiksa', '2026-01-23 09:31:00', '2026-01-23 11:00:00', '2026-01-23 11:19:00', NULL, NULL, 11, '2026-01-23 03:37:17', '2026-01-23 04:19:52', NULL, NULL),
(83, 72, 'izin_keluar', 'jalan jalan jumat', NULL, NULL, 'Belum Diperiksa', '2026-01-23 09:30:00', '2026-01-23 11:00:00', '2026-01-23 11:17:00', NULL, NULL, 11, '2026-01-23 03:38:14', '2026-01-23 04:17:28', NULL, NULL),
(84, 95, 'izin_keluar', 'jalan jalan jumat', NULL, NULL, 'Belum Diperiksa', '2026-01-23 09:31:00', '2026-01-23 11:01:00', '2026-01-23 11:20:00', NULL, NULL, 11, '2026-01-23 03:39:30', '2026-01-23 04:20:10', NULL, NULL),
(85, 83, 'izin_keluar', 'jalan jalan jumat', NULL, NULL, 'Belum Diperiksa', '2026-01-23 09:30:00', '2026-01-23 11:00:00', '2026-01-23 11:18:00', NULL, NULL, 11, '2026-01-23 03:40:12', '2026-01-23 04:18:41', NULL, NULL),
(86, 100, 'izin_pulang', 'daftar sekolah', NULL, NULL, 'Belum Diperiksa', '2026-01-23 09:35:00', '2026-01-23 15:00:00', '2026-01-23 18:17:00', NULL, NULL, 11, '2026-01-23 03:42:22', '2026-01-24 12:31:28', NULL, NULL),
(87, 10, 'izin_keluar', 'jalan jalan jumat', NULL, NULL, 'Belum Diperiksa', '2026-01-23 09:36:00', '2026-01-23 11:00:00', NULL, NULL, NULL, 11, '2026-01-23 03:43:28', '2026-01-23 03:43:28', NULL, NULL),
(88, 11, 'izin_keluar', 'jalan jalan jumat', NULL, NULL, 'Belum Diperiksa', '2026-01-23 09:36:00', '2026-01-23 11:00:00', NULL, NULL, NULL, 11, '2026-01-23 03:44:20', '2026-01-23 03:44:20', NULL, NULL),
(89, 205, 'izin_keluar', 'jalan jalan jumat', NULL, NULL, 'Belum Diperiksa', '2026-01-23 09:36:00', '2026-01-23 11:00:00', NULL, NULL, NULL, 11, '2026-01-23 03:56:51', '2026-01-23 03:56:51', NULL, NULL),
(90, 210, 'izin_keluar', 'jalan jalan jumat', NULL, NULL, 'Belum Diperiksa', '2026-01-23 10:00:00', '2026-01-23 11:20:00', NULL, NULL, NULL, 11, '2026-01-23 04:01:35', '2026-01-23 04:01:35', NULL, NULL),
(91, 115, 'izin_keluar', 'jalan jalan jumat', NULL, NULL, 'Belum Diperiksa', '2026-01-23 10:00:00', '2026-01-23 11:20:00', '2026-01-23 11:18:00', NULL, NULL, 11, '2026-01-23 04:02:44', '2026-01-23 04:19:07', NULL, NULL),
(92, 157, 'izin_keluar', 'jalan jalan jumat', NULL, NULL, 'Belum Diperiksa', '2026-01-23 10:00:00', '2026-01-23 10:00:00', '2026-01-23 11:19:00', NULL, NULL, 11, '2026-01-23 04:03:37', '2026-01-23 04:19:35', NULL, NULL),
(93, 237, 'sakit', 'Panas', 'dikamar', NULL, 'Belum Diperiksa', '2026-01-24 07:06:00', NULL, NULL, NULL, NULL, 19, '2026-01-24 00:08:11', '2026-01-24 00:08:11', NULL, NULL),
(94, 126, 'sakit', 'Panas', 'Dikamar', NULL, 'Belum Diperiksa', '2026-01-24 07:08:00', NULL, NULL, NULL, NULL, 19, '2026-01-24 00:08:43', '2026-01-24 00:08:43', NULL, NULL),
(95, 151, 'pelanggaran', 'pulang tanpa izin', NULL, NULL, 'Belum Diperiksa', '2026-01-24 16:02:00', NULL, NULL, NULL, NULL, 7, '2026-01-24 09:02:12', '2026-01-24 09:02:12', NULL, NULL),
(96, 91, 'sakit', 'mata ikan, bengkak kaki', NULL, NULL, 'Belum Diperiksa', '2026-01-24 06:26:00', NULL, NULL, NULL, NULL, 11, '2026-01-24 12:27:12', '2026-01-24 12:34:53', '2026-01-24 12:34:53', 11),
(97, 62, 'sakit', 'magh', NULL, NULL, 'Belum Diperiksa', '2026-01-24 18:27:00', NULL, NULL, NULL, NULL, 11, '2026-01-24 12:28:14', '2026-01-24 12:28:14', NULL, NULL),
(98, 62, 'izin_pulang', 'sakit', 'tipes kambuh', NULL, 'Belum Diperiksa', '2026-01-24 14:28:00', '2026-01-25 19:30:00', NULL, NULL, NULL, 11, '2026-01-24 12:30:33', '2026-01-24 12:34:21', '2026-01-24 12:34:21', 11),
(99, 91, 'izin_pulang', 'sakit', 'tipes kambuh', NULL, 'Belum Diperiksa', '2026-01-24 13:35:00', '2026-01-25 19:35:00', NULL, NULL, NULL, 11, '2026-01-24 12:35:52', '2026-01-24 12:35:52', NULL, NULL),
(100, 61, 'sakit', 'mata ikan, bengkak kaki', NULL, NULL, 'Belum Diperiksa', '2026-01-24 07:36:00', NULL, NULL, NULL, NULL, 11, '2026-01-24 12:36:31', '2026-01-24 12:36:31', NULL, NULL),
(101, 174, 'izin_pulang', 'ziarah keluarga', NULL, NULL, 'Belum Diperiksa', '2026-01-23 18:36:00', '2026-01-26 19:36:00', NULL, NULL, NULL, 11, '2026-01-24 12:37:34', '2026-01-24 12:37:47', NULL, NULL),
(102, 138, 'izin_keluar', 'Beli sapu kamar', 'Jrebeng', NULL, 'Belum Diperiksa', '2026-01-25 13:56:00', '2026-01-25 13:56:00', '2026-01-25 14:50:00', NULL, NULL, 19, '2026-01-25 06:58:02', '2026-01-25 06:58:02', NULL, NULL),
(103, 137, 'izin_keluar', 'Beli sapu kamar', 'Jrebeng', NULL, 'Belum Diperiksa', '2026-01-25 13:58:00', '2026-01-25 13:58:00', '2026-01-25 14:50:00', NULL, NULL, 19, '2026-01-25 06:58:33', '2026-01-25 06:58:33', NULL, NULL),
(104, 79, 'pelanggaran', 'Minggat', 'Tangane kesleo', NULL, 'Belum Diperiksa', '2026-01-24 18:40:00', NULL, NULL, NULL, NULL, 9, '2026-01-26 11:41:11', '2026-01-26 11:41:11', NULL, NULL),
(105, 175, 'izin_pulang', 'sakit demam', 'izin periksa', NULL, 'Belum Diperiksa', '2026-01-27 13:02:00', '2026-01-28 13:02:00', NULL, NULL, NULL, 7, '2026-01-27 06:02:53', '2026-01-27 06:02:53', NULL, NULL),
(106, 130, 'izin_pulang', 'sakit', NULL, NULL, 'Belum Diperiksa', '2026-01-27 16:36:00', '2026-01-28 16:36:00', NULL, NULL, NULL, 7, '2026-01-27 09:36:48', '2026-01-27 09:36:48', NULL, NULL),
(107, 184, 'izin_pulang', 'Acara keluarga', NULL, NULL, 'Belum Diperiksa', '2026-01-27 23:59:00', '2026-01-28 23:59:00', '2026-01-28 00:00:00', NULL, NULL, 9, '2026-01-27 17:00:24', '2026-01-27 17:01:27', '2026-01-27 17:01:27', 9),
(108, 133, 'izin_pulang', 'Periksa Sakit', NULL, NULL, 'Belum Diperiksa', '2026-01-28 17:42:00', '2026-01-29 17:42:00', NULL, NULL, NULL, 7, '2026-01-28 10:42:24', '2026-01-28 10:42:24', NULL, NULL),
(109, 71, 'pelanggaran', 'Keluar malam', 'Beli nasi goreng', NULL, 'Belum Diperiksa', '2026-01-29 01:53:00', NULL, NULL, NULL, NULL, 1, '2026-01-28 18:53:38', '2026-01-28 18:53:38', NULL, NULL),
(110, 119, 'izin_pulang', 'Periksa', NULL, NULL, 'Belum Diperiksa', '2026-01-29 22:30:00', '2026-01-30 22:30:00', NULL, NULL, NULL, 7, '2026-01-29 15:30:40', '2026-01-29 15:30:40', NULL, NULL),
(111, 102, 'izin_pulang', 'Periksa', NULL, NULL, 'Belum Diperiksa', '2026-01-30 10:33:00', '2026-01-31 10:33:00', NULL, NULL, NULL, 7, '2026-01-30 03:33:53', '2026-01-30 03:33:53', NULL, NULL),
(112, 37, 'izin_keluar', 'Pijat', NULL, NULL, 'Belum Diperiksa', '2026-01-30 10:40:00', '2026-01-30 02:40:00', NULL, NULL, NULL, 7, '2026-01-30 03:40:23', '2026-01-30 03:40:23', NULL, NULL),
(113, 187, 'izin_pulang', 'acara keluarga', NULL, NULL, 'Belum Diperiksa', '2026-01-30 11:27:00', '2026-01-30 11:28:00', '2026-01-30 11:28:00', NULL, NULL, 7, '2026-01-30 04:28:33', '2026-01-30 04:28:33', NULL, NULL),
(114, 222, 'izin_pulang', 'Sakit', NULL, NULL, 'Belum Diperiksa', '2026-01-29 11:46:00', '2026-01-30 11:46:00', NULL, NULL, NULL, 7, '2026-01-30 04:46:47', '2026-01-30 04:46:47', NULL, NULL),
(115, 84, 'izin_pulang', 'Sakit', NULL, NULL, 'Belum Diperiksa', '2026-01-30 11:47:00', '2026-01-30 11:47:00', '2026-01-31 22:25:00', NULL, NULL, 7, '2026-01-30 04:47:23', '2026-01-30 15:25:26', NULL, NULL),
(116, 193, 'izin_pulang', 'Sakit', NULL, NULL, 'Belum Diperiksa', '2026-01-29 11:47:00', '2026-01-30 11:47:00', NULL, NULL, NULL, 7, '2026-01-30 04:48:01', '2026-01-30 04:48:01', NULL, NULL),
(117, 95, 'izin_keluar', 'beli perlatan', NULL, NULL, 'Belum Diperiksa', '2026-01-30 13:14:00', '2026-01-30 15:14:00', '2026-01-30 17:18:00', NULL, NULL, 7, '2026-01-30 06:14:58', '2026-01-30 10:18:25', NULL, NULL),
(118, 93, 'izin_keluar', 'beli perlatan', NULL, NULL, 'Belum Diperiksa', '2026-01-30 13:15:00', '2026-01-30 15:15:00', '2026-01-30 17:17:00', NULL, NULL, 7, '2026-01-30 06:15:11', '2026-01-30 10:17:59', NULL, NULL),
(119, 126, 'izin_pulang', 'pijat kesleo', NULL, NULL, 'Belum Diperiksa', '2026-01-30 17:16:00', '2026-02-01 17:16:00', NULL, NULL, NULL, 7, '2026-01-30 10:16:49', '2026-01-30 10:16:49', NULL, NULL),
(120, 207, 'izin_pulang', 'demam', NULL, NULL, 'Belum Diperiksa', '2026-01-30 17:17:00', '2026-02-01 17:17:00', NULL, NULL, NULL, 7, '2026-01-30 10:17:23', '2026-01-30 10:17:23', NULL, NULL),
(121, 225, 'izin_keluar', 'Fotocopy tugas', NULL, NULL, 'Belum Diperiksa', '2026-01-31 14:27:00', '2026-01-31 15:27:00', '2026-01-31 15:47:00', NULL, NULL, 7, '2026-01-31 07:27:54', '2026-01-31 08:47:54', NULL, NULL),
(122, 151, 'pelanggaran', 'Pulang tanpa izin', NULL, NULL, 'Belum Diperiksa', '2026-02-01 08:24:00', NULL, NULL, NULL, NULL, 9, '2026-02-01 01:25:09', '2026-02-01 01:25:09', NULL, NULL),
(123, 139, 'pelanggaran', 'Pulang tanpa izin', NULL, NULL, 'Belum Diperiksa', '2026-02-01 08:25:00', NULL, NULL, NULL, NULL, 9, '2026-02-01 01:25:29', '2026-02-01 01:25:29', NULL, NULL),
(124, 126, 'pelanggaran', 'Pulang tanpa izin', NULL, NULL, 'Belum Diperiksa', '2026-02-01 08:25:00', NULL, NULL, NULL, NULL, 9, '2026-02-01 01:25:46', '2026-02-01 01:25:46', NULL, NULL),
(125, 132, 'pelanggaran', 'Keluar malam & rokokan di main', 'Denda semen 1 sak', NULL, 'Belum Diperiksa', '2026-02-03 11:52:00', NULL, NULL, NULL, NULL, 9, '2026-02-03 04:53:32', '2026-02-03 04:53:32', NULL, NULL),
(126, 77, 'pelanggaran', 'Keluar malam & rokokan di main', 'Denda semen 1 sak', NULL, 'Belum Diperiksa', '2026-02-03 11:54:00', NULL, NULL, NULL, NULL, 9, '2026-02-03 04:54:19', '2026-02-03 04:54:19', NULL, NULL),
(127, 104, 'pelanggaran', 'Keluar malam & rokokan di main', 'Denda semen 1 sak', NULL, 'Belum Diperiksa', '2026-02-03 11:54:00', NULL, NULL, NULL, NULL, 9, '2026-02-03 04:54:50', '2026-02-03 04:54:50', NULL, NULL),
(128, 92, 'pelanggaran', 'Keluar malam & rokokan di main', 'Denda semen 1 sak', NULL, 'Belum Diperiksa', '2026-02-03 11:55:00', NULL, NULL, NULL, NULL, 9, '2026-02-03 04:55:35', '2026-02-03 04:55:35', NULL, NULL),
(129, 55, 'pelanggaran', 'Keluar malam & rokokan di main', 'Denda semen 1 sak', NULL, 'Belum Diperiksa', '2026-02-03 11:55:00', NULL, NULL, NULL, NULL, 9, '2026-02-03 04:56:01', '2026-02-03 04:56:01', NULL, NULL),
(130, 58, 'pelanggaran', 'Keluar malam, & rokokan di main', 'Denda semen 1 sak dan tadarus sampai subuh', NULL, 'Belum Diperiksa', '2026-02-03 11:56:00', NULL, NULL, NULL, NULL, 9, '2026-02-03 04:56:48', '2026-02-03 04:57:33', NULL, NULL),
(131, 89, 'pelanggaran', 'Keluar malam ,bawah & rokokan di main', 'Denda semen 1 sak\r\nHp ditahan', NULL, 'Belum Diperiksa', '2026-02-03 11:57:00', NULL, NULL, NULL, NULL, 9, '2026-02-03 04:58:15', '2026-02-03 04:58:15', NULL, NULL),
(132, 41, 'pelanggaran', 'Keluar malam di main', 'Beli surat izin 20k', NULL, 'Belum Diperiksa', '2026-02-03 11:58:00', NULL, NULL, NULL, NULL, 9, '2026-02-03 04:59:06', '2026-02-03 04:59:06', NULL, NULL),
(133, 52, 'pelanggaran', 'Keluar malam & rokokan di main', 'Denda semen 1 sak', NULL, 'Belum Diperiksa', '2026-02-03 11:59:00', NULL, NULL, NULL, NULL, 9, '2026-02-03 04:59:36', '2026-02-03 04:59:36', NULL, NULL),
(134, 202, 'izin_pulang', 'Sakit', NULL, NULL, 'Belum Diperiksa', '2026-02-03 23:11:00', '2026-02-06 14:00:00', '2026-02-06 22:20:00', NULL, NULL, 10, '2026-02-03 16:13:23', '2026-02-06 15:20:50', NULL, NULL),
(135, 233, 'izin_pulang', 'Sakit priksa', NULL, NULL, 'Belum Diperiksa', '2026-02-04 06:33:00', '2026-02-06 16:00:00', NULL, NULL, NULL, 10, '2026-02-03 23:34:38', '2026-02-03 23:34:38', NULL, NULL),
(136, 95, 'izin_keluar', 'Fotocopy', NULL, NULL, 'Belum Diperiksa', '2026-02-04 13:58:00', '2026-02-04 14:58:00', NULL, NULL, NULL, 7, '2026-02-04 06:59:12', '2026-02-04 06:59:12', NULL, NULL),
(137, 97, 'izin_keluar', 'Fotocopy', NULL, NULL, 'Belum Diperiksa', '2026-02-04 13:59:00', '2026-02-04 14:59:00', NULL, NULL, NULL, 7, '2026-02-04 06:59:40', '2026-02-04 06:59:40', NULL, NULL),
(138, 184, 'izin_pulang', 'Acara keluarga', NULL, NULL, 'Belum Diperiksa', '2026-02-05 15:02:00', '2026-02-05 15:02:00', NULL, NULL, NULL, 9, '2026-02-05 08:02:37', '2026-02-05 08:02:37', NULL, NULL),
(139, 35, 'izin_pulang', 'Izin periksa mata', NULL, NULL, 'Belum Diperiksa', '2026-02-05 16:33:00', '2026-02-05 16:33:00', NULL, NULL, NULL, 9, '2026-02-05 09:33:49', '2026-02-05 09:33:49', NULL, NULL),
(140, 55, 'izin_keluar', 'Priksa ke dokter gigi', NULL, NULL, 'Belum Diperiksa', '2026-02-06 10:07:00', '2026-02-06 10:08:00', '2026-02-06 16:07:00', NULL, NULL, 10, '2026-02-06 03:08:14', '2026-02-06 03:08:14', NULL, NULL),
(141, 54, 'sakit', 'sakit panas', NULL, NULL, 'Belum Diperiksa', '2026-02-06 10:57:00', NULL, '2026-02-08 06:00:00', NULL, NULL, 10, '2026-02-06 03:58:47', '2026-02-06 03:58:47', NULL, NULL),
(142, 151, 'izin_keluar', 'Beli keperluan', NULL, NULL, 'Belum Diperiksa', '2026-02-06 11:00:00', '2026-02-06 15:00:00', NULL, NULL, NULL, 10, '2026-02-06 04:00:41', '2026-02-06 04:00:41', NULL, NULL),
(143, 162, 'izin_pulang', 'Kontrol gigi', NULL, NULL, 'Belum Diperiksa', '2026-02-07 16:54:00', '2026-02-07 16:54:00', '2026-02-08 16:54:00', NULL, NULL, 9, '2026-02-07 09:54:59', '2026-02-07 09:54:59', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `data_induk`
--

CREATE TABLE `data_induk` (
  `id` int(11) NOT NULL,
  `no_urut` int(11) DEFAULT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `quran` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `nisn` varchar(50) DEFAULT NULL,
  `lembaga_sekolah` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'AKTIF',
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `jumlah_saudara` int(11) DEFAULT 0,
  `nomor_kk` varchar(30) DEFAULT NULL,
  `nik` varchar(30) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kabupaten` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `asal_sekolah` varchar(255) DEFAULT NULL,
  `status_mukim` varchar(50) DEFAULT NULL,
  `nama_ayah` varchar(255) DEFAULT NULL,
  `tempat_lahir_ayah` varchar(100) DEFAULT NULL,
  `tanggal_lahir_ayah` date DEFAULT NULL,
  `nik_ayah` varchar(30) DEFAULT NULL,
  `pekerjaan_ayah` varchar(100) DEFAULT NULL,
  `penghasilan_ayah` varchar(50) DEFAULT NULL,
  `nama_ibu` varchar(255) DEFAULT NULL,
  `tempat_lahir_ibu` varchar(100) DEFAULT NULL,
  `tanggal_lahir_ibu` date DEFAULT NULL,
  `nik_ibu` varchar(30) DEFAULT NULL,
  `pekerjaan_ibu` varchar(100) DEFAULT NULL,
  `penghasilan_ibu` varchar(50) DEFAULT NULL,
  `no_wa_wali` varchar(20) DEFAULT NULL,
  `nomor_rfid` varchar(50) DEFAULT NULL,
  `sidik_jari` blob DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dokumen_kk` varchar(255) DEFAULT NULL COMMENT 'Path to KK file',
  `dokumen_akte` varchar(255) DEFAULT NULL COMMENT 'Path to Akte file',
  `dokumen_ktp` varchar(255) DEFAULT NULL COMMENT 'Path to KTP file',
  `dokumen_ijazah` varchar(255) DEFAULT NULL COMMENT 'Path to Ijazah file',
  `dokumen_sertifikat` varchar(255) DEFAULT NULL COMMENT 'Path to Sertifikat file (optional)',
  `foto_santri` varchar(255) DEFAULT NULL COMMENT 'Path to student photo',
  `nomor_pip` varchar(50) DEFAULT NULL,
  `sumber_info` varchar(100) DEFAULT NULL,
  `prestasi` varchar(255) DEFAULT NULL,
  `tingkat_prestasi` varchar(50) DEFAULT NULL,
  `juara_prestasi` varchar(50) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `data_induk`
--

INSERT INTO `data_induk` (`id`, `no_urut`, `nama_lengkap`, `kelas`, `quran`, `kategori`, `nisn`, `lembaga_sekolah`, `status`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `jumlah_saudara`, `nomor_kk`, `nik`, `kecamatan`, `kabupaten`, `alamat`, `asal_sekolah`, `status_mukim`, `nama_ayah`, `tempat_lahir_ayah`, `tanggal_lahir_ayah`, `nik_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nama_ibu`, `tempat_lahir_ibu`, `tanggal_lahir_ibu`, `nik_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `no_wa_wali`, `nomor_rfid`, `sidik_jari`, `created_at`, `updated_at`, `dokumen_kk`, `dokumen_akte`, `dokumen_ktp`, `dokumen_ijazah`, `dokumen_sertifikat`, `foto_santri`, `nomor_pip`, `sumber_info`, `prestasi`, `tingkat_prestasi`, `juara_prestasi`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 'M. FAHMI SIROJUL MUNIR', 'VII', NULL, 'Pengurus', '510033260058169984', 'ITS', 'AKTIF', 'PEKALONGAN', '2005-09-27', 'L', 2, NULL, NULL, NULL, NULL, 'Langkap Kedungwuni Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '085183878466', '0786534098', NULL, '2026-01-05 23:36:02', '2026-01-07 01:58:11', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 2, 'MUHAMMAD KOWI', 'VII', NULL, 'Pengurus', '510033260058169984', 'ITS', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Dk. Gutoko Ds. Kebonagung Kajen Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 3, 'MUHAMMAD YUSUF', 'VII', NULL, 'Pengurus', '510033260058169984', 'ITS', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Karangjompo Tirto Kota Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 4, 'M.CHOIRUS SYAFI', 'VII', NULL, 'Pengurus', '510033260058179968', 'ITS', 'TIDAK AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Desa Pabean, Kec. Pekalongan Utara, Kota Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 5, 'M.KHOTIBUL UMAM', 'VII', NULL, 'Pengurus', '510033260058179968', 'ITS', 'TIDAK AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Desa Paweden, Kec.Buaran, Kab. Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 12, 'NAILA HANA AZZAHRO', 'VII', NULL, 'Pengurus', '510033260058179968', 'ITS', 'AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, 'Pakumbulan Buaran Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62856407429690', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 17, 'IKA DINA LUTFIANA', 'VII', NULL, 'Umum', '510033260058190016', 'MA ALHIKAM', 'TIDAK AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, 'Ujungnegoro Kandeman Batang', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62853267852380', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 22, 'SUCI MAULIDA', 'VII', NULL, 'Umum', '510033260058190016', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, 'Desa Karanggayam Tangkil Kulon,Kec Kedungwuni,Kab Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62857419708590', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 24, 'NUR IFAZA KEMALA', 'VII', NULL, 'Umum', '510033260058190016', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, 'Rt.02 Rw.01 Jl.Sumbawa Pedurungan Kec.Taman Kab.Pemalang', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62882386037580', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 25, 'MAY LAVA SEVENTEEN', 'VI', 'Majlis', 'Khusus', '510033260058200000', 'MA ALHIKAM', 'AKTIF', 'Pekalongan', '2006-05-17', 'P', NULL, '3326133004180015.0', '3326135705060002', 'Kedungwuni', 'Pekalongan', 'Podo Kedungwuni Pekalongan', 'SMP NU BP Pajomblangan', 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, 'Hartutik', 'Pekalongan', '1985-03-17', '3326135703840001', NULL, NULL, '62877942367470', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 26, 'FATIMATUS ZAHRO', 'VI', 'Majlis', 'Umum', '510033260058200000', 'MA ALHIKAM', 'AKTIF', 'Batang', '2009-01-06', 'P', 1, '3325112402073416.0', '3325114601090004', 'Batang', 'Batang', 'Klidang Wetan Rt04/Rw01,Batang,Batang', 'smp nu pajomblangan', 'PONDOK PP MAMBAUL HUDA', 'Ali Bahrudin', 'Batang', '1979-06-27', '3325112705790002', 'Wiraswasta', 'Di bawah Rp. 1.000.000', 'Fauzizah', 'Batang', '1982-06-18', '3325115606820001', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '628953849828570', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 29, 'HANI ZHAFIRA KHOERUNISA', 'VI', 'Majlis', 'Umum', '510033260058200000', 'MA ALHIKAM', 'AKTIF', 'Tanggerang', '2008-11-11', 'P', 1, '3327093012080033.0', '3327095111080002', 'Taman', 'Pemalang', 'Ds. Pedurungan Rt 01/Rw 02Kec. Taman Kab. Pemalang', 'SMP NU Pajomblangan', 'PONDOK PP MAMBAUL HUDA', 'Rifai Yusup', 'Pemalang', '1974-07-19', '3327091907740005', 'Pedagang', 'Di bawah Rp. 4.000.000', 'Indah Winarni', 'Pemalang', '1982-01-01', '3327094101820017', 'Pedagang', 'Di bawah Rp. 2.500.000', '62878100227750', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 30, 'DIMAS IBRAHIM ARRUHAM', 'VI', 'Majlis', 'Khusus', '510033260058200000', 'MA ALHIKAM', 'AKTIF', 'Pekalongan', '2008-04-13', 'L', 3, '3326131012110009.0', '3326131304080004', 'Kedungwuni', 'Pekalongan', 'Dk.Capgawen Utara Rt.01/02 Kedungwuni  Pekalongan', 'SMP  NU Pajomblangan', 'PONDOK PP MAMBAUL HUDA', 'Didi Madhari', 'Majalengka', '1978-07-12', '3326131207780003', 'guru', 'Di bawah Rp. 1.000.000', 'isticharoh', 'pekalongan', '1969-04-21', '3326136104690001', 'ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62815742242380', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 31, 'JELITA ALIFIA PUTRI SUTISNA', 'VI', 'II Putri', 'Umum', '510033260058200000', 'MA ALHIKAM', 'AKTIF', 'Pemalang', '2008-08-23', 'P', 2, '3327092407120008.0', '3327096308080008', 'Taman', 'Pemalang', 'Ds Kuwungan Rt 02 Rw 08,Jebed Selatan,Taman,Pemalang', 'Kedungwuni', 'PONDOK PP MAMBAUL HUDA', 'Ari sutisna', 'Pandeglang', '1982-04-11', '3327091104820028', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'Damurah', 'Pemalang', '1985-05-31', '3327097106850094', 'IRT', 'Di bawah Rp. 1.000.000', '6285681874100', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 44, 'MUHAMMAD ISKANDAR ZULKARNAIN', 'V', NULL, 'Umum', '510033260058209984', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Bligorejo Kec. Doro Kab. Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 46, 'MUHAMMAD KANZUL ILMI ALKAROMAIN', 'V', NULL, 'Umum', '510033260058209984', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Ds. Paweden Kec. Buaran Kab. Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 54, 'MUHAMMAD ARSYA DANI', 'V', NULL, 'Khusus', '510033260058209984', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Pajomblangan Kedungwuni Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 55, 'M.AQIL MAHDI', 'VII', NULL, 'Pengurus', '510033260058209984', 'ITS', 'TIDAK AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Desa Pendukuhan Kraton,Kec. Pekalongan Utara, Kota Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 62, 'MUHAMMAD AMIRIL NASTAIN', 'V', NULL, 'Umum', '510033260058209984', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Ds. Serang Kec. Petarukan Kab. Pemalang', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 63, 'AZMI AKHMAD AFFAN', 'VII', NULL, 'Pengurus', '510033260058209984', 'ITS', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Desa Banjarturi, Kec. Warureja, Kab. Tegal', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 64, 'M.MAHFUD UBAIDIL KHAKIM', 'VII', NULL, 'Pengurus', '510033260058209984', 'ITS', 'AKTIF', NULL, NULL, 'L', 0, NULL, NULL, NULL, NULL, 'Desa Paninggaran, Kec. Paninggaran, Kab. Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '081556936584', NULL, NULL, '2026-01-05 23:36:02', '2026-01-07 01:58:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 65, 'AYU HANDAYANI', 'V', NULL, 'Umum', '510033260058209984', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, 'Ketitang Kidul Bojong Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62896031129270', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 66, 'ERLINA RAHMADHANI', 'V', NULL, 'Umum', '510033260058209984', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, 'Ds. Botekan Kec. Ulujami Kab. Pemalang', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62852298965790', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 85, 'AJENG AINI MASRUROH', 'V', NULL, 'Umum', '510033260058210048', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, 'Ds. Coprayan Buaran Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62858701574250', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 89, 'YULIANA ROHMANIYAH', 'V', NULL, 'Umum', '510033260058210048', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, 'Ds. Ujungnegoro Kec. Kandeman Kab. Batang', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62812832586690', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(26, 90, 'NOVA LUTFIA RAHMA', 'VII', NULL, 'Pengurus', '510033260058210048', 'ITS', 'AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, 'Desa Dadirejo, Kec. Tirto, Kab. Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62856029089350', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 94, 'ANDHIKA SENO PRATAMA', 'VI', 'Majlis', 'Umum', '510033260058220032', 'MA ALHIKAM', 'AKTIF', 'Pemalang', '2007-12-08', 'L', 1, '3327100110110011.0', '3327100812070008', 'Petarukan', 'Pemalang', 'Desa Keboijo Kec. Petarukan Kab. Pemalang', 'SMP pajomblangan', 'PONDOK PP MAMBAUL HUDA', 'SUHARSONO', 'PEMALANG', '1984-10-13', '3327101310840025', 'Wiraswasta', 'Di atas Rp. 4.000.000', 'RIRIN RETNOWATI', 'PEMALANG', '1986-06-19', '3327105906860050', 'IBU RUMAH TANGGA', 'Di bawah Rp. 2.500.000', '62856109110710', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 104, 'MUHAMMAD AFRIZA LUTHFI MA', 'IV', NULL, 'Umum', '510033260058220032', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Donowangun Talun Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62857023051830', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 142, 'MUHAMMAD ALFAN HAMID', 'IV', NULL, 'Umum', '510033260058220032', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Bugangan/Rt 1 Rw 1/Kedungwuni/Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62856422942520', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 143, 'AHMAD IRFANSYAH', 'IV', NULL, 'Umum', '510033260058220096', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Gg Tulip Ds Ambowetan Rt5/2 Kec Ulujami Kab Pemalang', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62856019026300', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 145, 'RIZQA HIMMATUL ULYA', 'IV', 'II Putri', 'Umum', '510033260058220096', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, 'Getas Wonopringgo Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62896308712840', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 147, 'SANIA RACHMA RAMADHANI', 'IV', 'II Putri', 'Umum', '510033260058220096', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, 'Jenggot Jenggot Pekalongan Selatan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62858704976390', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 194, 'MEI FIANU TIFANA DEFA', 'IV', 'II Putri', 'Umum', '510033260058220096', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, 'Tegalmlati, Petarukan Rt 07 Rw 04, Kab. Pemalang', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62813656879040', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 206, 'ALI ASAD', 'III A', 'Kamar I', 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2010-06-16', 'L', 2, '3326192908080002.0', '3326191606100003', 'Wonokerto', 'Pekalongan', 'Pesanggrahan 04/02 Pekalongan', 'SDN PESANGGRAHAN', 'PONDOK PP MAMBAUL HUDA', 'Slamet Darminto', 'Pekalongan', '1979-01-12', '3326191201790003', 'Buruh', 'Di bawah Rp. 1.000.000', 'Nur afifah', 'Pekalongan', '1979-06-09', '3326194906790003', 'Buruh', 'Di bawah Rp. 1.000.000', '62896533613930', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 208, 'MUHAMMAD ARDI FIRMANSYAH', 'III A', NULL, 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'garut', '2011-02-17', 'L', 1, '3205112908120001.0', '3205111701120003', 'Leuwigoong', 'Garut', 'Margahayu, Leuwigoong, Garut, Jawa Barat', 'mi harjosari', 'PONDOK PP MAMBAUL HUDA', 'm arifin', 'pekalongan', '1986-03-11', '3205111403861004', 'wiraswasta', 'Di bawah Rp. 2.500.000', 'yosi rosidah', 'pekalongan', '1986-01-06', '3205114101890019', 'ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62857339892760', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 209, 'ZAGHLUL ZAIN', 'III A', 'Kamar I', 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-08-31', 'L', 0, '3326102209110015.0', '3326103108110002/', 'Sragi', 'Pekalongan', 'Dusun Kemonggoan Selatan Desa Bulaksari Rt.001/005, Pekalongan', 'SDN seragi', 'PONDOK PP MAMBAUL HUDA', 'Ciswandi', 'Pekalongan', '1981-03-22', '3326102203810002', 'Buruh harian lepas', 'Di bawah Rp. 2.500.000', 'Tulipah', 'Pekalongan', '1981-05-19', '3326105905810001', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62853289455560', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 210, 'MUHAMMAD RAFI ADITYA', 'III A', 'Kamar I', 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-07-10', 'L', 3, '3326131407170004.0', '3326131007110002', 'Kedungwuni', 'Pekalongan', 'Ds Karangdowo Kec Kedungwuni Kab Pekalongan', 'Mi', 'PONDOK PP MAMBAUL HUDA', 'Dawammadin', 'Pekalongan', '1985-02-12', '3326131202850022', 'Buruh harian lepas', 'Di bawah Rp. 1.000.000', 'Elindawati', 'Pekalongan', '1991-06-01', '3326134106910004', 'Mengurus rumah tangga', 'Di bawah Rp. 1.000.000', NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 211, 'DAFFA AL FAREZA ADITIA', 'VI', NULL, 'Umum', '510033260058230016', 'MA ALHIKAM', 'AKTIF', 'Pekalongan', '2008-02-19', 'L', 2, '3375040506070020.0', '33750419020800001', 'Pekalongan Barat', 'Pekalongan', 'Perum Pringlangu Indah No 2 Pekalongan', 'Pondok darul amanah kendl.', 'PONDOK PP MAMBAUL HUDA', 'Abdul hadi', 'Kudus', '1980-03-15', '3375041503790002', 'Wirasuasta', 'Di atas Rp. 4.000.000', 'Titin kahsanah', 'Pekalongan', '1980-10-16', '3375045610800003', 'Ibu rumah tangga', 'Di atas Rp. 4.000.000', '6281565789840', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 213, 'MUHAMMAD IRFAN MAHRUS', 'III A', 'Kamar I', 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-02-20', 'L', 1, '3326060409140003.0', '3326062002110001', 'Doro', 'Pekalongan', 'Dk. Sawangan Timur Rt. 02 Rw. 01 Sawangan Doro Pekalongan', 'SDN 1 SAWANGAN', 'PONDOK PP MAMBAUL HUDA', 'Nurohim', 'Pekalongan', '1984-01-16', '3326101601840001', 'Buruh jahit', 'Di bawah Rp. 1.000.000', 'Nasiroh', 'Pekalongan', '1985-11-14', '3326065411850001', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62858027738000', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 214, 'LUQMANUL CHAKIM', 'VI', NULL, 'Umum', '510033260058230016', 'MA ALHIKAM', 'TIDAK AKTIF', 'Pemalang', '2007-04-15', 'L', 1, '3327052709058058.0', '3327051504070003', 'Bodeh', 'Pemalang', 'Dsn. Bubak Ds. Pasir Kec. Bodeh Kab. Pemalang', 'Mts darul istiqomah', 'PONDOK PP MAMBAUL HUDA', 'Tahari', NULL, NULL, NULL, NULL, NULL, 'Warniti', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 215, 'M.RAYAN DANADYAKSA', 'III A', 'Kamar I', 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-02-05', 'L', 2, '3326131704120003.0', '3326130502110001', 'Kedungwuni', 'Pekalongan', 'Pekajangan Gg 23 Rt/Rw ,16/06 Kebutuh Kec Kedungwuni Kab Pekalongan', 'MIS WALISONGO PEKAJANGAN', 'PONDOK PP MAMBAUL HUDA', 'BISRI MUSTHOFA', 'PEKALONGAN', '1978-12-19', '3326131912780003', 'Dagang', 'Di bawah Rp. 2.500.000', 'Yuliana khoirunnisa', 'Pekalongan', '1986-07-01', '332613407860005', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62857253258870', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 217, 'FARAS MEISABILA', 'VI', 'II Putri', 'Umum', '510033260058230016', 'MA ALHIKAM', 'AKTIF', 'Pemalang', '2008-05-19', 'P', 1, '3327050509160007.0', '3327055905080005', 'Pasir', 'Pemalang', 'Dusun Bubak Rt/Rw 06.02 Ds. Pasir Kec. Bodeh Kab. Pemalang', 'MtsS Daarul Istiqomah Pasir', 'PONDOK PP MAMBAUL HUDA', 'Mohammad Azim', 'Pekalongan', '1986-11-28', '3326022711860001', 'Wiraswasta', 'Di bawah Rp. 4.000.000', 'Nita januari', 'Pemalang', '1990-01-28', '3327056801900001', 'Ibu Rumah Tangga', 'Di bawah Rp. 1.000.000', '62823224529660', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 218, 'KENSHA AULIA ARSYADA', 'V', 'II Putri', 'Umum', '510033260058230016', 'MA ALHIKAM', 'AKTIF', 'Pekalongan', '2009-08-12', 'P', 0, '3375013012110011.0', '3375015208090003', 'Kedungwuni', 'Pekalongan', 'Dk Tosaran Rt/Rw 002/002', 'Sdn Tosaran', 'PONDOK PP MAMBAUL HUDA', 'Zurikan', 'Jepara', '1979-10-09', '3375010910790011', 'Tukang Kayu', 'Di bawah Rp. 4.000.000', 'Sri Handayani', 'Mengurus Rumah Tangga', '1978-09-18', '3375015809780004', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '6281569361110', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 219, 'ADILIA DWI PUTRI', 'III B', NULL, 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2010-11-15', 'P', 3, '3326112904110001.0', '3326115511100002', 'Bojong', 'Pekalongan', 'Ds Ketitang Kidul Kec Bojong Kab Pekalongan', 'Mi', 'PONDOK PP MAMBAUL HUDA', 'Suprayitno', 'Pekalongan', '1980-03-08', '3326110803800002', 'Buruh harian lepas', 'Di bawah Rp. 2.500.000', 'Siti suryani', 'Tangerang', '1983-04-05', '3326114504830003', 'Mengurus rumah tangga', 'Di bawah Rp. 1.000.000', '62857418185540', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 221, 'FINA HIMMATUL ULYA', 'III B', NULL, 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2010-10-28', 'P', 1, '3326052812100038.0', '3326056810100001', 'Talun', 'Pekalongan', 'Dk. Kraminan Desa Jolotigo Kecamatan Talun', 'Sdn 02 Jolotigo', 'PONDOK PP MAMBAUL HUDA', 'Suhadi', 'Pekalongan', '1979-06-15', '3326056810100001', 'Wiraswasta', 'Di bawah Rp. 4.000.000', 'Caridah A Sindun', 'Pekalongan', '1991-12-10', '3326055012910003', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62823294827390', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 222, 'RIZKY MILANIKA AFIANI', 'III B', NULL, 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pemalang', '2011-07-04', 'P', 1, '3325131406190006.0', '3325134407110002', 'Kandeman', 'Batang', 'Ds. Ujung Negoro Rt. 03.Rw.02. Kec. Kandeman. Kab. Batang', 'SDN. Bakalan', 'PONDOK PP MAMBAUL HUDA', 'Karyanto', 'Pemalang', '1988-02-14', '3327111402880002', 'Karyawan swasta', 'Di atas Rp. 4.000.000', 'Srianeka', 'Batang', '1991-06-28', '33251136806910005', 'Karyawan swasta', 'Di bawah Rp. 4.000.000', '62858483684700', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 224, 'AUDIYYA ROHMATIL WAFA', 'III B', NULL, 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-08-31', 'P', 2, '3326132803070014.0', '3326137108110001', 'Kedungwuni', 'Pekalongan', 'Dk.Kemoren Desa Karangdowo Rt 12/Rw 05 Kedungwuni Pekalongan', 'MI WS KARANGDOWO 02', 'PONDOK PP MAMBAUL HUDA', 'Slamet Riyadi', 'Pekalongan', '1980-08-05', '3326130508800101', 'Wiraswasta', 'Di atas Rp. 4.000.000', 'Reni yufika', 'Pekalongan', '1988-09-03', '3326134309880062', 'Ibu rumah tangga', 'Di bawah Rp. 4.000.000', '62858786617890', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 225, 'ALLINA ZULFIANA', 'VI', 'II Putri', 'Umum', '510033260058230016', 'MA ALHIKAM', 'AKTIF', 'Pekalongan', '2008-04-21', 'P', 3, '3326120108073843.0', '3326126104080001', 'Wonopringgo', 'Pekalongan', 'Gebruk Getas Wonopringgo Rt 03 Rw 01', 'MTs Gondang', 'PONDOK PP MAMBAUL HUDA', 'M.Tibrizin', 'Pekalongan', '1973-01-13', '3326121301730003', 'Buruh harian lepas', 'Di bawah Rp. 2.500.000', 'Khoidah', 'Pekalongan', '1982-08-25', '3326126508820003', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62852276052770', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 227, 'NAILA MELFIANA', 'III B', NULL, 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'pekalongan', '2010-05-05', 'P', 1, '111111111.0', '000000000', 'Pekalongan Selatan', 'Pekalongan', 'Jenggot,Pekalongan Selatan,Kota Pekalongan', 'MIS salafiya jengot 03', 'PONDOK PP MAMBAUL HUDA', 'ana mailul', 'pekalongan', '1989-04-12', '1111111111', 'Wiraswasta', 'Di bawah Rp. 1.000.000', 'Ana Mailul', 'pekalongan', '1988-06-08', '11111111', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62858702555300', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 229, 'DEWI MARISA AULIA', 'III B', NULL, 'Khusus', '510033260058230016', 'SMP NU BP', 'AKTIF', 'pekalongan', '2011-01-14', 'P', 3, '3326315090084.0', '3326135401110002', 'Kedungwuni', 'Pekalongan', 'Pajomblangan,Kedungwuni,Pekalongan', 'SD islam ibnu kholdun pekalongan', 'PONDOK PP MAMBAUL HUDA', 'Ainur rofiq', 'pekalongan', '1972-07-02', '3326130207720001', 'Karyawan sawasta', 'Di bawah Rp. 1.000.000', 'Eni Rokhaini', 'pekalongan', '1982-05-17', '3326135705820001', 'PNS', 'Di bawah Rp. 1.000.000', NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 231, 'MIFTAKHUL KIROM', 'III A', 'Kamar I', 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pemalang', '2011-04-12', 'L', 1, '3327091501110006.0', '3327091204110001', 'Taman', 'Pemalang', 'Dusun Siber Rt 4 Rw 4 Desa Penggarit Taman Pemalang', 'SD 01 Penggarit', 'PONDOK PP MAMBAUL HUDA', 'M.zamroni', 'Pekalongan', '1978-04-20', '3327092004780014', 'Kuli bangunan', 'Di bawah Rp. 2.500.000', 'Nur khasanah', 'Pemalang', '1985-12-31', '3327100000000000', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62882251883560', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(52, 232, 'M.REZKY PUTRA PRATAMA', 'III A', 'Kamar I', 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-01-21', 'L', 2, '3326142203120007.0', '3326142101110001', 'Buaran', 'Pekalongan', 'Desa Wonoyoso Gg5 Rt.22/08 Pekalongan', 'MIS Wonoyoso 01', 'PONDOK PP MAMBAUL HUDA', 'Imam munandar', 'Pekalongan', '1986-04-15', '3326141504860002', 'Buruh', 'Di bawah Rp. 2.500.000', 'Etik kustianingsih', 'Pekalongan', '1990-01-17', '3326145701900003', 'Dagang', 'Di bawah Rp. 2.500.000', '62856414200280', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 233, 'ROBY ASYARI', 'III A', 'Kamar I', 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-04-13', 'L', 3, '3375021708070021.0', '3327502130410002', 'Pekalongan Timur', 'Pekalongan', 'Ds Setono Kec Pekalongan Timur Kab Pekalongan', 'Sd', 'PONDOK PP MAMBAUL HUDA', 'Amat chunaedi', 'Batang', '1976-06-13', '3375021306760004', 'Buruh harian lepas', 'Di bawah Rp. 1.000.000', 'Mujiyah', 'Pekalongan', '1977-08-04', '3375024405770004', 'Buruh harian lepas', 'Di bawah Rp. 1.000.000', NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 236, 'M. ARFAUR RIKZA', 'III A', 'Kamar I', 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2010-06-29', 'L', 1, '3326132109120008.0', '3326132912100002', 'Kedungwuni', 'Pekalongan', 'Ds Karangdowo Kec Kedungwuni Kab Pekalongan', 'Mi ws karangdowo', 'PONDOK PP MAMBAUL HUDA', 'Muh. Siswanto', 'Pekalongan', '1984-09-25', '3326132509840002', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'Riskiyah', 'Pekalongan', '1976-07-21', '3326136107760004', 'Ibu rumahtangga', 'Di bawah Rp. 1.000.000', NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 237, 'MUHAMMAD TAUFIQ MAULANA', 'III A', 'Kamar I', 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2010-11-24', 'L', 2, '3326131503120006.0', '3326132411100002', 'Kedungwuni', 'Pekalongan', 'Dusun Karangdowo Desa Karangdowo Rt. 011/004 Kecamatan Kedungwuni Kabupaten Pekalongan', 'MIS WALISONGO KARANGDOWO 02', 'PONDOK PP MAMBAUL HUDA', 'AHMAD RIDLWAN', 'PEKALONGAN', '1983-03-14', '3326131403830003', 'Karyawan swasta', 'Di bawah Rp. 2.500.000', 'NUR HIDAYATI', 'PEKALONGAN', '1982-02-27', '3326136702820003', 'GURU', 'Di bawah Rp. 1.000.000', '62855248229310', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 238, 'BAGUS AMIRUL AKBAR', 'III A', 'Kamar I', 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, 'Pekalongan', 'Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62882151387300', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 239, 'DIAN FARUQ AL LATIF', 'VI', NULL, 'Umum', '510033260058230016', 'MA ALHIKAM', 'AKTIF', 'Batang', '2007-05-03', 'L', 2, '3325121903120009.0', '3325120305070002', 'Warungasem', 'Batang', 'Desa Pesaren Kecamatan Warungasem Kab. Batang', 'Mts', 'PONDOK PP MAMBAUL HUDA', 'Muh Tadi', 'Pekalongan', '1979-10-28', '3325122810790002', 'Wiraswasta', 'Di bawah Rp. 4.000.000', 'Supriyani', 'Batang', '1983-05-17', '3325125705830004', 'Karyawan swasta', 'Di bawah Rp. 4.000.000', '62815755316690', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(58, 240, 'M. EVAN SADINA FARIZQI', 'III A', 'Kamar I', 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-05-24', 'L', 7, '3326150811060013.0', '332615245110003', 'Tirto', 'Pekalongan', 'Desa Karanganyar Gg.12 Rt. 05 Rw 02 Kec. Tirto Kab. Pekalongan', 'MI Salafiyah Karanganyar', 'PONDOK PP MAMBAUL HUDA', 'Kholil', 'Pekalongan', '1966-01-03', '3326150301660001', 'Buruh', 'Di bawah Rp. 1.000.000', 'NUR ROHMAH', 'Pekalongan', '1970-03-05', '3326154503700001', 'Sudah Meninggal', 'Di bawah Rp. 1.000.000', '628165781900', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(59, 241, 'MUHAMMAD KHOIRUL AFAN', 'III A', 'Kamar I', 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2010-11-07', 'L', 1, '3326050612110003.0', '3326050711100001', 'Talun', 'Pekalongan', 'Ds Talun Kec Talun Kab Pekalongan', 'Sd', 'PONDOK PP MAMBAUL HUDA', 'Fatori', 'Pekalongan', '1986-11-13', '3326071311880001', 'Tukang jahit', 'Di bawah Rp. 1.000.000', 'Saodah', 'Pekalongan', '1988-06-08', '3326084806680002', 'Mengurus rumah tangga', 'Di bawah Rp. 1.000.000', NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 242, 'MUCH NAHDI ZAMANI', 'III A', 'Kamar I', 'Umum', '510033260058230016', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-09-26', 'L', 3, '3375012502110015.0', '3376012609110003', 'Medono', 'Pekalongan', 'Jl Jaya Bakti Gg H Abdullah Medono Pekalongan Barat 006/002', 'MSI 14 medono Pekalongan', 'PONDOK PP MAMBAUL HUDA', 'Zakaria', 'Pekalongan', '1973-08-28', '3375012808730007', 'Swasta', 'Di bawah Rp. 2.500.000', 'Nazilatul mifroh', 'Pekalongan', '1978-05-26', '3375016605780002', 'Irt', 'Di bawah Rp. 2.500.000', '62813255618050', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 246, 'AYU SULISTIA', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'pekalongan', '2011-05-22', 'P', 2, '3326131310160003.0', '3326136205110004', 'Kedungwuni', 'Pekalongan', 'Ds. Kedungpatangewu Rt. 07/04', 'mi kebon tengah', 'PONDOK PP MAMBAUL HUDA', 'prasetyo sinung', 'pekalongan', '1981-04-06', '3326120604830003', 'buruh', 'Di bawah Rp. 2.500.000', 'khuriroh', 'pekalongan', '1979-10-15', '3326135510790004', 'buruh', 'Di bawah Rp. 2.500.000', '62821361400500', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(62, 247, 'ALFI RIZQIHANA', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2011-07-24', 'P', 3, '3326140606120016.0', '3326136407110002', 'Pakumbulan', 'Pekalongan', 'Dk Kentingan Pakumbulan', 'MIS PAKUMBULAN', 'PONDOK PP MAMBAUL HUDA', 'ANDI RISWANTO', 'PEKALONGAN', '1978-07-20', '3326131401780004', 'BURUH HARIAN LEPAS', 'Di bawah Rp. 1.000.000', 'ZAENAB', 'PEKALONGAN', '1982-07-20', '3326136007820025', 'IBU RUMAH TANGGA', 'Di bawah Rp. 1.000.000', '62812720887400', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 248, 'AZRA TEGAR PRATIWI', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Banjarnegara', '2011-01-06', 'P', 1, '3304182205180004.0', '3304184601110001', 'Kalibening', 'Banjar Negara', 'Plorengan Rt/Rw 01/01 Kec. Kalibening, Kab. Banjarnegara', 'SDN 1 Plorengan', 'PONDOK PP MAMBAUL HUDA', 'Takhyono', 'Banjarnegara', '1987-08-12', '3304181208870002', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', 'Nur Asih Puspita Sari', 'Banjarnegara', '1992-01-30', '3304187001920001', 'PNS', 'Di bawah Rp. 2.500.000', '62813130347440', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, 249, 'IKHDA NIMATIL MAULA', 'III B', NULL, 'Khusus', '510033260058230080', 'SMP NU BP', 'TIDAK AKTIF', 'Pekalongan', '2011-02-04', 'P', 0, '3326110103180003.0', '3326114402110002', 'Bojong', 'Pekalongan', 'Desa Duwet Rt.07 Rw.03 Kecamatan Bojong Kabupaten Pekalongan 51156', 'MI SALAFIYAH KETITANG KIDUL BOJONG', 'PONDOK PP MAMBAUL HUDA', 'MARTONO', 'Pekalongan', '1986-12-23', '3326112312860001', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'IMAMAH', 'Pekalongan', '1987-10-18', '3326175810870001', 'Ibu rumah tangga', 'Di bawah Rp. 2.500.000', '62858760604340', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 250, 'WULAN FITRI NAZHINNAIRA ISMANTO', 'VI', 'II Putri', 'Umum', '510033260058230080', 'MA ALHIKAM', 'AKTIF', 'Pemalang', '2007-10-09', 'P', 1, '3327102003070009.0', '3327104910070002', 'Petarukan', 'Pemalang', 'Ds Petaruksn Kec Petarukan Kab Pemalang', 'Mts', 'PONDOK PP MAMBAUL HUDA', 'Jumhan ismanto', 'Pekalongan', '1982-08-20', '3327102008820061', 'Karyawan BUMN', 'Di bawah Rp. 4.000.000', 'Reni raharjo', 'Pemalang', '1980-06-19', '3327105906800081', 'Karyawan BUMN', 'Di bawah Rp. 4.000.000', '628122558450', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 251, 'KANZA NILAM ARZAQINA', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2011-06-24', 'P', 1, '3375023011100006.0', '3375026406110001', 'Kauman', 'Pekalongan', 'Sampangan, Kauman, Pekalongan', 'MIS MSI 05 SAMPANGAN', 'PONDOK PP MAMBAUL HUDA', 'M ILHAM', 'PEKALONGAN', '1986-09-23', '3375022309860004', 'PEDAGANG', 'Di bawah Rp. 2.500.000', 'KHUSNUL KHOTIMAH', 'PEKALONGAN', '1988-11-10', '3375035011880005', 'BURUH', 'Di bawah Rp. 2.500.000', '62853299455420', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 253, 'TASYA AGISTYA RAMADHANI', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2010-08-23', 'P', 0, '3326092508170002.0', '3326086308100002', 'Kesesi', 'Pekalongan', 'Desa Sidomulyo Dk Semangu Rt 06/01 Sidomulyo Kesesi Kabupaten Pekalongan', 'SDN 02 KESESI', 'PONDOK PP MAMBAUL HUDA', 'Abdul rohman', 'Pekalongan', '1985-06-11', '3326081106850006', 'Pedagang', 'Di atas Rp. 4.000.000', 'Erry sriwahyuni', 'Pekalongan', '1986-06-23', '3326096307860002', 'Ibu rumah tangga', 'Di bawah Rp. 4.000.000', '62853254501000', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 256, 'M. KAMAL FIKRI', 'III A', 'Kamar I', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-04-18', 'L', 1, '332613030110082.0', '3326131804110002', 'Karangdowo', 'Pekalongan', 'Desa Karangdowo Rt 10/ Rw 04', 'MI WS Karangdowo 2', 'PONDOK PP MAMBAUL HUDA', 'Muslimin', 'Pekalongan', '1975-05-05', '3326130505750004', 'Buruh', 'Di bawah Rp. 1.000.000', 'Zufinah', 'Pekalongan', '1980-12-10', '3326135012800003', 'Ibu Rumah Tangga', 'Di bawah Rp. 1.000.000', '62815751770680', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 257, 'NAJDA NAJIDAL ARZAQ', 'III A', 'Shifir', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-03-21', 'L', 1, '3326132803160006.0', '3326132103110001', 'Kedungwuni', 'Pekalongan', 'Karangdowo,Kedungwuni,Pekalongan', 'MI WS Kedungwuni', 'PONDOK PP MAMBAUL HUDA', 'M.Imronudin', 'Pekalongan', '1976-06-22', '3326132206760002', 'Wiraswasta', 'Di bawah Rp. 1.000.000', 'Umrotunnisak', 'Pekalongan', '1981-11-15', '3326135511810001', 'karyawan swasta', 'Di bawah Rp. 1.000.000', NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 259, 'FATHONI ANANDYA', 'VI', 'II Putri', 'Umum', '510033260058230080', 'MA ALHIKAM', 'AKTIF', 'JAKARTA', '2007-10-10', 'L', 1, '3173071012141015.0', '3171071010070005', 'Palmerah', 'Jakarta Barat', 'Jl,Kemanggisan Kincir No.14 Rt 008 Rw 008,Kelurahan Palmerah, Kecamatan Palmerah, Kota Madya Jakarta Barat, Dki Jakarta', 'SMPN 101 JAKARTA', 'PONDOK PP MAMBAUL HUDA', 'MASDUKI', 'PEMALANG', '1972-09-06', '3171070609720002', 'Karyawan', 'Di bawah Rp. 4.000.000', 'TUMINI', 'SOLO', '1972-07-12', '3171075207720003', 'IBU RUMAH TANGGA', 'Di bawah Rp. 1.000.000', '62815175757320', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 260, 'FIRMAN MAULANA RISKY', 'III A', 'Shifir', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, 'Pekalongan', 'Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '856429225250', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(72, 264, 'NUR AFNI', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2010-11-25', 'P', 0, '3326110810130001.0', '3326116511100002', 'Bojong', 'Pekalongan', 'Sumurjomblangbogo Rt.003 Rw.001 Bojong', 'SDN 01 JOMBLANGBOGO', 'PONDOK PP MAMBAUL HUDA', 'Nurkhan', '00/00/0000', '2023-06-10', '0000000', '0000000', 'Di bawah Rp. 1.000.000', 'Melyana', 'Pekalongan', '1991-05-05', '3326114505910002', 'Buruh harian lepas', 'Di bawah Rp. 1.000.000', '628953839917770', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(73, 269, 'AMANDA NUR LATIFA', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'TEGAL', '2011-08-12', 'P', 1, '3171070501121008.0', '3171075208111003', NULL, 'Tegal', 'Jl.Sabeni No.10 Rt.016/012 Tegal', 'MADRASAH IBTIDAIYAH AL-ITTIHAD TANAH ABANG JAKARTA PUSAT', 'PONDOK PP MAMBAUL HUDA', 'MUHAMMAD MUJTAHID', 'JAKARTA', '1986-01-09', '3171070401860004', 'KARYAWAN SWASTA', 'Di atas Rp. 4.000.000', 'SUSIANA', 'TEGAL', '1988-08-06', '3328134608880173', 'MENGURUS RUMAH TANGGA', 'Di bawah Rp. 2.500.000', '62821122805670', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 270, 'ALIYA DWI NAWATI', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-03-14', 'P', 2, '3326141009090002.0', '3326145403120003', 'Buaran', 'Pekalongan', 'Ds Coprayan Kec Buaran Kab Pekalongan', 'Sd', 'PONDOK PP MAMBAUL HUDA', 'Sunardi', 'Pemalang', '1983-10-13', '3326141310830001', 'Buruh harian lepas', 'Di bawah Rp. 1.000.000', 'Srinawati', 'Pekalongan', '1982-08-04', '3326144408820004', 'Mengurus rumah tangga', 'Di bawah Rp. 1.000.000', '62895108981470', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 272, 'MUHAMMAD AFFAN FAILASUF', 'III A', 'Kamar I', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Batang', '2011-05-02', 'L', 1, '332608190218001.0', '3375010205110001', 'Kajen', 'Pekalongan', 'Jl. Kamboja No 7 Rt 04/12 Gandarum Kajen Kabupaten Pekalongan', 'SDN 02 PEKIRINGAN ALIT', 'PONDOK PP MAMBAUL HUDA', 'SUSATYO KURNIADI', 'PEKALONGAN', '1982-04-12', '3375011204820010', 'Karyawan swasta', 'Di bawah Rp. 2.500.000', 'NOK FAIZAH', 'BATANG', '1985-09-09', '3375014909850004', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62858789867010', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 273, 'FATAN HILMI HABIBI', 'III A', 'Kamar I', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-05-31', 'L', 1, '3.326061407110001e+16', '3326063105110003', 'Doro', 'Pekalongan', 'Dk. Rondongungak, Desa. Lemahabang Kec. Doro Kab. Pekalongan', 'MII Harjosari', 'PONDOK PP MAMBAUL HUDA', 'Ade Sulaeman', 'Cirebon', '1985-05-05', '3209160505850014', 'Wiraswasta', 'Di atas Rp. 4.000.000', 'Musiyam', 'Pekalongan', '1990-04-21', '3326066104900001', 'IRT', 'Di bawah Rp. 1.000.000', '62813917445850', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(77, 274, 'MUHAMMAD GUSTHOHA', 'III A', 'Shifir', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Cianjur', '2010-07-31', 'L', 2, '3171050710160005.0', '3171053107101003', 'Cempaka Putih', 'Jakarta Pusat', 'Kp. Jawa Rawasari Rt 05/08 Kelurahan: Rawasari Kecamatan: Cempaka Putih Jakarta Pusat', 'SDN Rawasari 05 pagi', 'PONDOK PP MAMBAUL HUDA', 'Ahmad Thohir', 'Pekalongan', '1984-04-14', '3171051404840004', 'Pedagang', 'Di bawah Rp. 2.500.000', 'Siti Rohmah', 'Cianjur', '1989-08-27', '320320270889004', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62857713964140', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(78, 276, 'ULUL AZMI AL MUBAROK', 'III A', 'Shifir', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Batang', '2011-01-16', 'L', 1, '3325022802110006.0', '3325021601110001', 'Talun', 'Pekalongan', 'Dk Bandar Talun Rt/Rw 003/002', 'Sd bandar', 'PONDOK PP MAMBAUL HUDA', 'Amat Mubin', 'Batang', '1976-03-15', '3325021504760002', 'Pedagang', 'Di bawah Rp. 2.500.000', 'Mei Silviana', 'Pekalongan', '1982-05-23', '3326136305820001', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62856406165470', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(79, 277, 'MUHAMAD FAIZZUN', 'III A', 'Kamar I', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, 'Pekalongan', 'Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80, 278, 'M. RIFQI', 'VI', NULL, 'Umum', '510033260058230080', 'MA ALHIKAM', 'AKTIF', 'Pekalongan', '2007-07-26', 'L', 3, '3326130610050016.0', '3326132907030021', 'Kedungwuni', 'Pekalongan', 'Dk. Puncisan Desa Tangkil Kulon Rt.006 Rw.002 Kec. Kedungwuni Pekalongan', 'SD N 1 Buaran', 'PONDOK PP MAMBAUL HUDA', 'Riyanto', 'Pekalongan', '1967-03-19', '3326131903670001', 'PNS', NULL, 'Nafsiyah', 'Pekalongan', '1969-11-17', '3326135711690001', 'Ibu rumah tangga', NULL, '62815755783810', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(81, 279, 'AULIA RAMADHANI', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'TIDAK AKTIF', 'Pekalongan', '2010-08-10', 'P', 0, '3326110107190002.0', '3326115008100003', 'Bojong', 'Pekalongan', 'Ds Ketitang Kidul Kec Bojong Kab Pekalongan', 'Sd', 'PONDOK PP MAMBAUL HUDA', 'Akhmad khusaeri', 'Tegal', '1985-01-25', '3328012105870006', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'Nur khasanah', 'Pekalongan', '1980-05-11', '3326115305800021', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '628953770734850', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(82, 280, 'APRILIA USWATUN KHASANAH', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'pekalongan', '2011-04-22', 'P', 0, '3326051305160002.0', '3326056204110001', 'Talun', 'Pekalongan', 'Jolotigo,Talun,Pekalongan,Rt02/Rw01', 'sd n1  talun', 'PONDOK PP MAMBAUL HUDA', 'Era Widianingsih', 'pekalongan', '1981-08-09', '3326054908810001', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', 'Era Widianingsih', 'Pekalongan', '1981-08-09', '3326054908810001', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62813817677240', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(83, 282, 'RIZQI KAMILA PUTRI', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Batang', '2011-01-11', 'P', 1, '3325122402072045.0', '3325125701110002', 'Warungasem', 'Batang', 'Dukuh Kasapan, Desa Pesaren, Kecamatan Warungasem, Kabupaten Batang', 'SD N 2 Pesaren', 'PONDOK PP MAMBAUL HUDA', 'Akhmad Rokhan', 'Batang', '1981-02-04', '3325120402810003', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'Sri Hastutik', 'Batang', '1980-01-18', '3325125801800001', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62858660143530', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(84, 284, 'MUHAMMAD FADIL ARIFIN', 'III A', 'Shifir', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-02-12', 'L', 2, '3326161405110001.0', '3326161202110001', 'Wiradesa', 'Pekalongan', 'Desa Warukidul Rt.011/003', 'Mis Walisongo Karangdowo 01', 'PONDOK PP MAMBAUL HUDA', 'Rohman', 'Pekalongan', '1975-12-08', '3326160812750001', 'Buruh', 'Di bawah Rp. 2.500.000', 'Yefi Aryanti', 'Pekalongan', '1984-10-23', '336166310840004', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62857284366530', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `data_induk` (`id`, `no_urut`, `nama_lengkap`, `kelas`, `quran`, `kategori`, `nisn`, `lembaga_sekolah`, `status`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `jumlah_saudara`, `nomor_kk`, `nik`, `kecamatan`, `kabupaten`, `alamat`, `asal_sekolah`, `status_mukim`, `nama_ayah`, `tempat_lahir_ayah`, `tanggal_lahir_ayah`, `nik_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nama_ibu`, `tempat_lahir_ibu`, `tanggal_lahir_ibu`, `nik_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `no_wa_wali`, `nomor_rfid`, `sidik_jari`, `created_at`, `updated_at`, `dokumen_kk`, `dokumen_akte`, `dokumen_ktp`, `dokumen_ijazah`, `dokumen_sertifikat`, `foto_santri`, `nomor_pip`, `sumber_info`, `prestasi`, `tingkat_prestasi`, `juara_prestasi`, `deleted_at`, `deleted_by`) VALUES
(85, 286, 'MUHAMMAD AGENG', 'VI', NULL, 'Umum', '510033260058230080', 'MA ALHIKAM', 'AKTIF', 'Pekalongan', '2008-11-20', 'L', 2, '3326141505120012.0', '33261420110800023', 'Buaran', 'Pekalongan', 'Wonoyoso Gg 5 Rt :022 Rw:008 Kecamatan Buaran Kabupaten Pekalongan', 'SMPN 1 Buaran Pekalongan', 'PONDOK PP MAMBAUL HUDA', 'EFENDI', 'Pekalongan', '1982-01-12', '3326151201820023', 'Wirausaha', 'Di bawah Rp. 4.000.000', 'SITI MUFRODAH', 'Pekalongan', '1983-06-19', '3326145906830001', 'Ibu Rumah Tangga', 'Di bawah Rp. 1.000.000', '62856404473730', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(86, 287, 'ROFIATUL HIMMATI', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'pekalongan', '2011-07-01', 'P', 4, '3375042908070041.0', '3375044107110001', 'Pekalongan Selatan', 'Pekalongan', 'Ds.Banyurip Kec Pekalongan Selatan Kota Pekalongan', 'mi 01 banyurep ageng', 'PONDOK PP MAMBAUL HUDA', 'ahmad dhuha', 'pekalongan', '1966-12-29', '3375042912660002', 'wirasuwasta', 'Di bawah Rp. 2.500.000', 'khodijah', 'pekalongan', '1972-04-28', '3375046804720003', 'pedagang', 'Di bawah Rp. 2.500.000', '62857131779910', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(87, 288, 'BALQIS SAFINATUN NAJJAH', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-07-16', 'P', 1, '3326050801100001.0', '3326055607110001', 'Kalirejo', 'Pekalongan', 'Dk. Nolo Desa Kalirejo Rt.04/02', 'SD N 01 KALIREJO', 'PONDOK PP MAMBAUL HUDA', 'DAMIRI', 'Pekalongan', '1975-05-23', '3326052305750001', 'Buruh', 'Di bawah Rp. 1.000.000', 'ROZIQOH', 'Pekalongan', '1977-07-22', '3326056207770001', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62857019151750', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(88, 289, 'ZAHWA AULIYA RAHMA', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2010-10-10', 'P', 1, '3326050309090002.0', '3326055010100001', 'Talun', 'Pekalongan', 'Dk. Jolotigo Desa Jolotigo Rt001/Rw001', 'SD NEGERI 02 JOLOTIGO', 'PONDOK PP MAMBAUL HUDA', 'SIAM SUWANDI', 'PEKALONGAN', '1982-07-01', '3326050107820034', 'PNS', 'Di bawah Rp. 4.000.000', 'SUBEKTI', 'PEKALONGAN', '1993-05-27', '3326056705930004', 'IBU RUMAH TANGGA', 'Di bawah Rp. 1.000.000', '62823234070500', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(89, 290, 'RAFA ABDULLAH FAQIH', 'III A', 'Kamar I', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Pemalang', '2010-10-11', 'L', 1, '3173051303200011.0', '3327131110100006', 'Kebon Jeruk', 'Jakarta Barat', 'Jln Daud 1 Rt : 002/ Rw : 008 No : 60H  Kel : Sukabumi Utara Kec : Kebon Jeruk  Kota : Jakarta Barat Prov : Dki Jakarta', 'SD N KEBON JERUK 06', 'PONDOK PP MAMBAUL HUDA', 'Taryono', 'Pekalongan', '1979-03-28', '3327132803790003', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'Wiyatni', 'Pemalang', '1981-08-18', '3327135808810006', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62812142896340', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(90, 291, 'AHMAD FAJRIN', 'III A', 'Kamar I', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2011-03-10', 'L', 0, '3326140501110031.0', '3326141003110002', 'Buaran', 'Pekalongan', 'Dk.Coprayan Desa Coprayan Rt.Rw 006/002 Buaran', 'SD N Coprayan', 'PONDOK PP MAMBAUL HUDA', 'SOFIAN', 'BATANG', '1974-04-19', '3326141904740004', 'KARYAWAN SWASTA', 'Di bawah Rp. 4.000.000', 'SUSANA DARMIYATI', 'PEKALONGAN', '1978-03-12', '3326145203780003', 'MENGURUS RUMAH TANGGA', 'Di bawah Rp. 1.000.000', '62856429340250', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(91, 292, 'DIAN LATHIFATUL IZZAH', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2010-06-29', 'P', 2, '3174051612090055.0', '3174056906101007', 'Kebayoran', 'Jakarta Selatan', 'Kebon Mangga Ii No 25 Rt 001 Re 003 Desa Cipulir Kec. Kebayoran Lama Kota Jakarta Selatan Provinsi Dki Jakarta', 'MIS WONOREJO', 'PONDOK PP MAMBAUL HUDA', 'Sugeng Priyanto', 'Pekalongan', '1982-11-11', '3174051111820019', 'Buruh', 'Di bawah Rp. 1.000.000', 'Masturoh', 'Pekalongan', '1984-07-22', '3174056207840005', 'Ibu Rumah Tangga', NULL, '62856409385400', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(92, 294, 'SABIL BAROKAH', 'III A', 'Shifir', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Tegal', '2010-10-07', 'L', 3, '3328062108130013.0', '3328060710100003', 'Lebaksiu', 'Tegal', 'Dk Lebakgowah', 'Sdn', 'PONDOK PP MAMBAUL HUDA', 'Muhammad Djahuri', 'Tegal', '1972-11-24', '3328063112730022', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', 'Rini', 'Tegal', '1977-04-06', '3328064606770005', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(93, 296, 'NAVITA ALFANIAH', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Banjarnegara', '2010-10-08', 'P', 1, '3304181602120007.0', '3304184810100001', 'Kalibening', 'Banjar Negara', 'Desa Plorengan , Kecamatan Kalibening , Kabupaten Banjarnegara ,Rt 1, Rw 1', 'SDN 1 Plorengan', 'PONDOK PP MAMBAUL HUDA', 'FAOZAN', 'Banjarnegara', '1985-12-13', '3304181312850001', 'Petani / pekebun', 'Di bawah Rp. 2.500.000', 'Rohmahniah', 'Banjarnegara', '1990-05-29', '3304186905900001', 'Pedagang', 'Di bawah Rp. 2.500.000', '62812261621940', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(94, 297, 'NUR FADIA AULIA', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'pekalongan', '2011-04-20', 'P', 3, '3326130304070015.0', '3326136004110001', 'Kedungwuni', 'Pekalongan', 'Ds Kedungpatangewu Kec Kedungwuni Kab Pekalongan', 'mi walisongo kebontengah', 'PONDOK PP MAMBAUL HUDA', 'ma\'mun', 'pekalongan', '1969-08-08', '3326130506690021', 'buruh harian', 'Di bawah Rp. 2.500.000', 'jazilah', 'pekalongan', '1970-07-08', '3326134807700021', 'ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62856241809770', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(95, 298, 'ZULFA MARATUS SOLIHAH', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Banjarnegara', '2009-08-21', 'P', 3, '3304181709090001.0', '3304186108090001', 'Kalibening', 'Banjar Negara', 'Desa Plorengan Rt. 01 Rw. 02 Banjar Negara', 'SD N 1 Plorengan', 'PONDOK PP MAMBAUL HUDA', 'DAHRUN AHMAD HARUNUDIN', 'BANJARNEGARA', '1983-05-13', '3304181305830003', 'pedagang', 'Di bawah Rp. 2.500.000', 'NURWATI', 'BANJARNEGARA', '1986-12-21', '3304186112860002', 'ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62852297197680', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(96, 299, 'M.MUDHOAF', 'III A', 'Kamar I', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2010-12-03', 'L', 3, '3375031106080015.0', '3375030312100001', 'Pekalongan Utara', 'Pekalongan', 'Desa Pabean Pekalongan Rt.03 Rw.14 Padukuhan Kraton Pekalongan Utara', 'MSI 17 PABEAN', 'PONDOK PP MAMBAUL HUDA', 'Mahmud', 'Pekalongan', '1964-04-06', '3375030604680003', 'Buruh', 'Di bawah Rp. 2.500.000', 'Ibah', 'Pekalongan', '1970-06-28', '3375030604680003', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '628953220786290', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(97, 300, 'NAJWA AISYA PUTRI', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, 'Pekalongan', 'Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62815484459900', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(98, 302, 'DINDA KHALIMATUS SABILAH', 'VI', 'II Putri', 'Khusus', '510033260058230080', 'MA ALHIKAM', 'AKTIF', 'Pekalongan', NULL, 'P', NULL, NULL, NULL, 'Kedungwuni', 'Pekalongan', 'Pajomblangan Kec. Kedungwuni Kab. Pekalongan', 'Smp NU pajomblangan', 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62857426861710', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(99, 303, 'NURUL AZKIA', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'pekalongan', '2011-03-21', 'P', 1, '3326122504110003.0', '3326126103110001', 'Wonopringgo', 'Pekalongan', 'Ds Wonorejop Kec Wonopringgo Kab Pekalongan', 'mi wonorejo', 'PONDOK PP MAMBAUL HUDA', 'moh.khamim', 'pekalongan', '2011-03-21', '3326121208820005', 'buruh harian lepas', 'Di bawah Rp. 2.500.000', 'yamaeroh', 'pekalongan', '1985-09-19', '3326125909850003', 'ibu rumah tangga', 'Di bawah Rp. 2.500.000', '62823222588900', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(100, 304, 'DEWI MARSHA OCTAVIANA', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2010-10-04', 'P', 1, '3326141904120002.0', '3326144410100004', 'Buaran', 'Pekalongan', 'Kertijayan Gg.6 Rt.015/006 Pekalongan', 'MIS BLIGO', 'PONDOK PP MAMBAUL HUDA', 'MUSTAJAB', 'PEKALONGAN', '1984-02-12', '3326141202840001', 'WIRASWASTA', 'Di bawah Rp. 4.000.000', 'NISRONAH', 'PEKALONGAN', '1986-07-21', '3326146107860001', 'MENGURUS RUMAH TANGGA', 'Di bawah Rp. 1.000.000', '62858460739660', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(101, 305, 'KHANZA GILDA ZHAHIRA', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Tegal', '2011-05-28', 'P', 2, '3328172210150003.0', '3328176805110006', 'Warureja', 'Tegal', 'Dk  Banjarsari Desa Banjar Turi Warurejo Tegal', 'Mi nurul ulum banjar turi', 'PONDOK PP MAMBAUL HUDA', 'Kusno', 'Tegal', '1989-05-09', '3328170305720001', 'Buruh', 'Di bawah Rp. 2.500.000', 'Raonah', 'Tegal', '1986-04-12', '3328175210830001', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62812265250470', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(102, 306, 'MUHAMAD HUSAIN MUSYAFI', 'III A', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Batang', '2010-03-23', 'L', 1, '3325122204100002.0', '3325122303100001', 'Warungasem', 'Batang', 'Jl. Raya Desa Lebo Gang 12 Belakang Balai Desa Lebo Kecamatan Warungasem Kabupaten Batang', 'SDN LEBO 02', 'PONDOK PP MAMBAUL HUDA', 'NUR MUZEN', 'BATANG', '1980-01-02', '3325120201800003', 'BURUH HARIAN LEPAS', 'Di bawah Rp. 2.500.000', 'NUR SIAMI', 'BATANG', '1984-05-25', '3325126505840002', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '62857277233260', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(103, 307, 'LULUK FAUZIYAH', 'III B', NULL, 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Batang', '2010-07-03', 'P', 2, '3325122502074520.0', '3325124307100001', 'Warungasem', 'Batang', 'Desa Lebo,Rt:003/Rw:002,Kecamatan Warungasem,Kb Batang,Provinsi Jawa Tengah', 'SD N LEBO 02', 'PONDOK PP MAMBAUL HUDA', 'TAFSiR', 'BATANG', '1979-06-06', '3325120606790002', 'Wiraswasta', 'Di bawah Rp. 4.000.000', 'ISRODHA', 'BATANG', '1983-09-12', '3325125212830004', 'BURUH HARIAN LEPAS', 'Di bawah Rp. 2.500.000', '62823250872530', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(104, 308, 'MUHAMMAD RIFQI FADLI', 'III A', 'Kamar I', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2011-02-22', 'L', 0, '3375040909200009.0', '3375042202110002', 'Pekalongan Selatan', 'Pekalongan', 'Duwet Ds. Sokoduwet Kec. Pekalongan Selatan Kota Pekalongan', 'MIS SALAFIYAH DUWET PEKALONGAN SELATAN', 'PONDOK PP MAMBAUL HUDA', 'RUSWANDI', 'BATANG', '1984-10-10', '3375045010840007', 'PEDAGANG', 'Di bawah Rp. 4.000.000', 'RISMAWATI', 'BATANG', '1984-10-10', '3375045010840007', 'IRT', 'Di bawah Rp. 2.500.000', '62852909584160', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(105, 309, 'MUHAMMAD RIZQI SETYAWAN', 'III A', 'Kamar I', 'Umum', '510033260058230080', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-12-15', 'L', 2, '3326131105070016.0', '3326131512110003', 'Kedungwuni', 'Pekalongan', 'Ds Kwayangan Kec Kedungwuni Kab Pekalongan', 'Sd', 'PONDOK PP MAMBAUL HUDA', 'Khadlirin', 'Pekalongan', '1971-01-02', '3326130201710002', 'Buruh harian lepas', 'Di bawah Rp. 1.000.000', 'Rinawati', 'Pekalongan', '1985-09-09', '3326134909850003', 'Buruh harian lepas', 'Di bawah Rp. 1.000.000', NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(106, 310, 'AHMAD AGUNG SETIYAWAN', 'IV', NULL, 'Umum', '510033260058230080', 'MA ALHIKAM', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Batang', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(107, 311, 'KHASANATUL ILMI', 'VI', 'II Putri', 'Umum', '510033260058230080', 'MA ALHIKAM', 'AKTIF', 'Pekalongan', '2007-03-12', 'P', 2, '3326181712080002.0', '3326185203070005', 'Karangdadap', 'Pekalongan', 'Dk Pangkah Desa Pangkah Kec Karangdadap', 'Smp n 1 karandadap', 'PONDOK PP MAMBAUL HUDA', 'Muhammad Warto', 'Pekalongan', '1962-12-12', '3326181212620004', 'Buruh', 'Di bawah Rp. 2.500.000', 'Umi Fadhilah', 'Pekalongan', '1975-10-18', '3326185810750001', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', '62882166838670', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(108, 314, 'PRABU ASTAGHINA', 'VI', NULL, 'Umum', '510033260058230080', 'MA ALHIKAM', 'AKTIF', 'Karawang', '2008-03-15', 'L', 5, NULL, NULL, 'Karawang Timur', 'Karawang', 'Perumahan Buana Asri Blok A15 No. 20 Palumbon Sari Lamaran Karawang', 'SMP Gontor', 'PONDOK PP MAMBAUL HUDA', 'Nanang Qosim', NULL, NULL, NULL, NULL, NULL, 'Neni Lestari', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(109, 315, 'SURYA DAFA ATSABIT', 'VII', NULL, 'Umum', '510033260058240064', 'MA ALHIKAM', 'AKTIF', 'PEKALONGAN', '2007-03-16', 'L', 1, '3375042205070007.0', '3375041603070001', 'PEKALONGAN SELATAN', 'KOTA PEKALONGAN', 'Kuripan Kidul Gg. 01', 'MTS AMSILATI', 'PONDOK PP MAMBAUL HUDA', 'MUHAMMAD SUBHAN', 'PEKALONGAN', '1974-01-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '858692946210', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(110, 317, 'RIZKY FAJAR RAMADHAN', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2011-08-01', 'L', 1, '3326062504170004.0', '3326060108110002', NULL, NULL, 'Sawangan 06/03 Doro Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', 'HENDRO IMAM SUTRISNO', 'KUDUS', '1982-06-16', '3319071606820001', 'TNI', 'Di atas Rp. 4.000.000', 'HETY PUSPITARINI', 'PEKALONGAN', '1983-08-27', '3326066708830003', 'IRT', 'Di bawah Rp. 1.000.000', '857417692950', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(111, 318, 'NAYVELIN CELSILIA TJANDRAWAN .', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Jakarta', '2011-11-29', 'P', 3, '3173012201090563.0', '3173016911111003', NULL, NULL, 'Domisili .Desa Babalanlor Lor Rt 14 Rw 04 Dukuh Patean  Kecamatan Bojong Kabupaten Pekalongan', 'SDN Babalan lor 2', 'PONDOK PP MAMBAUL HUDA', 'Andri tjandrawan', 'Jakarta', '1972-02-01', '3173010102720015', 'Wirasuasta', 'Di bawah Rp. 4.000.000', 'Mutika arifah', 'Jakarta', '1985-08-19', '3173015908850011', 'Kariawan swasta', 'Di bawah Rp. 2.500.000', '877197735900', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(112, 319, 'MOH. RIZQI RAMADANI', 'V', NULL, 'Umum', '510033260058240000', 'MA ALHIKAM', 'AKTIF', 'TEGAL', '2008-08-31', 'L', 2, '3326071402070006.0', '3326073108080002', NULL, NULL, 'Dukuh Jawab, Desa Kutosari Rt 02 Rw 01 Kecamatan Karanganyar, Kabupaten Pekalongan', 'Mts yapik kecamatan karanganyar', 'PONDOK PP MAMBAUL HUDA', 'TUTUR YASIN', 'PEKALONGAN', '1978-04-19', '3326071904780001', 'TUKANG JAHIT', 'Di bawah Rp. 2.500.000', 'KUNISAH', 'TEGAL', '1984-08-12', '3326076808770001', 'TUKANG JAHIT', 'Di bawah Rp. 2.500.000', '857264166350', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(113, 320, 'ELSYA SAGITA PUTRI', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-01-18', 'P', 1, '3325012703170001.0', '3326145801120003', NULL, NULL, 'Dk. Kampung Baru, Desa Wates, Rt. 002/001, Kec. Wonotunggal, Kab. Batang 51253', 'Mi salafiyyah pakumbulan', 'PONDOK PP MAMBAUL HUDA', 'Bagyo', 'Batang', '1983-02-18', '3325011802830002', 'Swasta', 'Di bawah Rp. 2.500.000', 'Tirohah', 'Pekalongan', '1987-08-25', '3326146508870002', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '88826095190', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(114, 321, 'IKFINA DIAN AFIKA', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-02-23', 'P', 0, '3375042707090001.0', '3375046302120001', NULL, NULL, 'Jenggot Jl. Pelita 03 Rt.02 Rw.05 Pekalongan Selatan', 'Mis jenggot 03', 'PONDOK PP MAMBAUL HUDA', 'Saifudin', 'Pekalongan', '1973-08-19', '3375041908730007', 'karyawan', 'Di bawah Rp. 2.500.000', 'Khabibah', 'Pekalongan', '1980-11-29', '3375046911800004', 'ibu rumah tangga', NULL, '856009544520', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(115, 323, 'KEISHA NADIA ANINDHITA', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2012-03-06', 'P', 1, '3326150904120002.0', '3326154603120003', NULL, NULL, 'Ngalian Tirto Pekalingan Rt 003/002', 'MIS Ngalian', 'PONDOK PP MAMBAUL HUDA', 'SLAMET SUBKHI', 'Pekalongan', '1983-07-22', '3326132207830003', 'Wiraswasta', 'Di bawah Rp. 4.000.000', 'Nur Khasanah', 'Pekalongan', '1985-01-01', '3326154101850101', 'Ibu rumah tangga', NULL, '81569614460', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(116, 324, 'KHARISMA AULIA', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2012-06-26', 'P', 0, '3326152308170011.0', '3326146606120002', NULL, NULL, 'Ngalian Rt.002/002 Kec.Tirto Kab. Pekalongan Jawa Tengah', 'MIS NGALIAN', 'PONDOK PP MAMBAUL HUDA', 'ARIS SETIAWAN', 'PEKALONGAN', '1986-12-28', '3326142812860002', 'BURUH', 'Di bawah Rp. 2.500.000', 'INDAH ISMAWATI', 'PEKALONGAN', '1988-07-19', '3326155907880001', 'IBU RUMAH TANGGA', 'Di bawah Rp. 1.000.000', '856010290950', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(117, 325, 'M AZKA HADANI ROBBY', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-05-20', 'L', 2, '3375011809070138.0', '3375012005220001', NULL, NULL, 'Jl.Karya Bakti Vii Rt/', 'Msi 8 medono pekalongan', 'PONDOK PP MAMBAUL HUDA', 'Mukharom', 'Pekalongan', '1977-01-11', '3357011101770003', 'Tukang kayu', 'Di bawah Rp. 2.500.000', 'Ismawati', 'Pekalongan', '1981-08-22', '3375016208810011', 'IRT', NULL, '857434508990', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(118, 326, 'ALFIDLO\' AFKAR', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-10-04', 'L', 2, '3326181211070008.0', '3326180410110002', NULL, NULL, 'Dk. Rowobulus Kidul Desa Kebonrowopucang Rt. 02/10', 'MIWS Kebonrowopucang', 'PONDOK PP MAMBAUL HUDA', 'Nur Rohman', 'Pekalongan', '1976-06-12', '3326181206760001', 'Supir', 'Di bawah Rp. 2.500.000', 'Sri Ghonimah', 'Pekalongan', '1981-09-03', '3326184309810002', 'Ibu Rumah Tangga', NULL, '857022564650', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(119, 327, 'M.AZKA RIFIYANSYAH', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-04-02', 'L', 1, '3326180903120001.0', '3326180204120002', NULL, NULL, 'Dek.Rowobulus Lor Desa Kebonrowopucang Rt.001/014', 'Mi Walisongo Kebonrowopucang', 'PONDOK PP MAMBAUL HUDA', 'Muntolib', 'Pekalongan', '1985-02-18', '3326181802850002', 'Wiraswasta', 'Di bawah Rp. 4.000.000', 'Umi Kholilah', 'Pekalongan', '1983-09-13', '3326185309830001', 'Wiraswasta', 'Di bawah Rp. 2.500.000', '856420370470', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(120, 328, 'MUHAMMAD KHANIF ROSYDA', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-05-02', 'L', 5, '3326140108071426.0', '3326140205110001', NULL, NULL, 'Desa Buaran, Kecamatan Buaran, Kabupaten Pekalongan', 'MIS BLIGO', 'PONDOK PP MAMBAUL HUDA', 'Kuswandi', 'Pekalongan', '1972-11-15', '3326141511720001', 'Buruh Harian Lepas', 'Di bawah Rp. 2.500.000', 'Khakimah', 'Pekalongan', '1969-12-12', '3326145212690003', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62857279989850', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(121, 329, 'M. RIZQI MAULANA', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2013-01-02', 'L', 1, '3.3261415051200012e+16', '3326140201130004', NULL, NULL, 'Desa Bligo, Kecamatan Buaran, Kabupaten Pekalongan', 'MIS BLIGO', 'PONDOK PP MAMBAUL HUDA', 'Ahmad Sairi', 'Pekalongan', '1978-07-21', '3326142107780001', 'Buruh Harian Lepas', 'Di bawah Rp. 2.500.000', 'Nur Laela', 'Pekalongan', '1985-07-05', '3326144507850002', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', '62857424843730', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(122, 330, 'PUTRI ASTSALITSA YUCHTI BARKAH', 'V', NULL, 'Umum', '510033260058240000', 'MA ALHIKAM', 'AKTIF', 'Yogyakarta', '2009-04-23', 'P', 2, '3471092510110004.0', '3471101301980001', NULL, NULL, 'Ds Panembahan Kec Kraton Kota Yogyakarta', 'Smp negri 16 yogyakarta', 'PONDOK PP MAMBAUL HUDA', 'Surono', 'Klaten', '1971-10-09', '3471100910710001', 'Wirasuwasta', 'Di bawah Rp. 1.000.000', 'Kiswati', 'Pekalongan', '1975-01-06', '3471104601750001', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '823226795150', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(123, 332, 'BIMAS MULIANTO', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2011-10-01', 'L', 1, '3326100710110002.0', '3326100110110001', NULL, NULL, 'Dk. Ringinpitu Sragi Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', 'RAUDIN', 'PEKALONGAN', '1977-08-13', '3326161308770002', 'PEDAGANG', 'Di bawah Rp. 2.500.000', 'DWI APRILAWATI', 'PEKALONGAN', '1982-04-23', '3326106304820001', 'IBU RUMAH TANGGA', 'Di bawah Rp. 1.000.000', '856436222860', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(124, 333, 'INDAH NADA PUSPITA', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-10-14', 'P', 2, '3326092010110007.0', '3326095410110001', NULL, NULL, 'Dk. Ngasem Desa Watupayung Rt 01 Rw 01 Kecamatan Kesesi Kabupaten Pekalongan', 'SD N 02 Kesesi', 'PONDOK PP MAMBAUL HUDA', 'Matyuri', 'Pekalongan', '1978-01-03', '3326090307780002', 'ASN', 'Di atas Rp. 4.000.000', 'KIswati', 'Pekalongan', '1994-04-09', '3326094904940002', 'Ibu rumah tangga', NULL, '815480415220', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(125, 335, 'RAHMA MUTIARA SABRINA', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-11-15', 'P', 3, '3375011603180013.0', '3375015511110001', NULL, NULL, 'Jl. Karya Bakti Gg. V Rt 007 Rw 004 Medono, Kec. Pekalongan Barat, Kota Pekalongan', 'MSI 14 MEDONO', 'PONDOK PP MAMBAUL HUDA', 'Suharli (Almarhum)', 'Curup', '1973-12-10', NULL, NULL, NULL, 'Hidayah', 'Pekalongan', '1970-07-12', '3375015207700008', 'Pedagang kecil (menjual sembako)', 'Di bawah Rp. 2.500.000', '852014851140', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(126, 336, 'MUHAMMAD ALIFFATURRIZKI', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'BATANG', '2012-06-02', 'L', NULL, '3325132802130001.0', '3325130206120003', NULL, NULL, 'Desa Ujungnegoro, Kecamatan Kandeman, Kabupaten Barang. Rt. 04/01', 'MI AL IKHSAN UJUGNEGORO', 'PONDOK PP MAMBAUL HUDA', 'NASRUDIN', 'BATANG, JAWA TENGAH', '1984-02-18', '3325131802840002', 'NELAYAN', NULL, 'SITI KHASANAH', 'BATANG, JAWA TENGAH', '1987-07-05', '3325104303870003', 'DAGANG', NULL, '813911723940', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(127, 338, 'AINUR ROFIQOH', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-01-11', 'P', 2, '3326142510160001.0', '3326135101120001', NULL, NULL, 'Desa Wonoyoso, Kec. Buatan, Kab. Pekalongan', 'MIS WONOYOSO 02', 'PONDOK PP MAMBAUL HUDA', 'Istighfar', 'Pekalongan', '1978-07-19', '3326131907780003', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', 'Lisviana', 'Pekalongan', '1982-10-08', '3326134810820001', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(128, 339, 'M. NUR ALIMMUDIN', 'II A', NULL, 'Khusus', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-11-09', 'L', 2, '3326131804070001.0', '3326130911110001', NULL, NULL, 'Desa Karangdowo, Kec. Kedungwuni, Kab. Pekalongan', 'SDN 1 KARANGDOWO', 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, 'Nur Khasanah', 'Pekalongan', '1977-07-01', '3326134107770102', 'Wiraswasta', 'Di bawah Rp. 2.500.000', NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(129, 340, 'FIKA LAILATUS SYARIFAH', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-08-13', 'P', 1, '3326143012110002.0', '3326145308120001', NULL, NULL, 'Desa Wonoyoso, Kec. Buatan, Kab. Pekalongan', 'MIS WONOYOSO 02', 'PONDOK PP MAMBAUL HUDA', 'Rusdiono', 'Pekalongan', '1984-10-10', '3326141010640002', 'Pedagang', 'Di bawah Rp. 2.500.000', 'Nur Rohmah', 'Pekalongan', '1981-01-15', '3326145501810001', 'Mengurus Rumah Tangga', 'Di bawah Rp. 2.500.000', NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(130, 342, 'MUHAMMAD IRFAN ZIDNI', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-03-07', 'L', 3, '3375022009110003.0', '3375020703120001', NULL, NULL, 'Noyontaan Pekalongan Timur', 'MSI 02 keputran', 'PONDOK PP MAMBAUL HUDA', 'Ali Yaumin', 'Pekalongan', '1979-08-21', '332616208790002', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', 'Chamidah Pujiati', 'Pekalongan', '1980-10-31', '3375027110800004', 'Ibu Rumah Tangga', 'Di bawah Rp. 1.000.000', '62856409263620', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(131, 343, 'MEYDA FITRIANA', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-09-02', 'P', 1, '3326050806070153.0', '3326054209110002', NULL, NULL, 'Dukuh Nolo Ds. Kalirejo Kec. Talun Rt. 4/2', '0000', 'PONDOK PP MAMBAUL HUDA', 'Nurul al abdulbari', 'Pekalongan', '1975-07-04', '3326050407750001', 'Buruh', 'Di bawah Rp. 2.500.000', 'Irwanah', 'Pekalongan', '1985-07-08', '3326054807850002', 'Mengurus rumah tangga', 'Di bawah Rp. 2.500.000', '822211871660', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(132, 346, 'MUHAMMAD IKMAL MAULANA', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-08-27', 'L', 3, '3326182604110002.0', '3326182708120001', NULL, NULL, 'Dk Kebonrowopucang Ds.Kebonrowopucang Rt/Rw 001/014', '......', 'PONDOK PP MAMBAUL HUDA', 'Fadlun', 'Pekalongan', '1972-03-07', '3326180703720002', 'Wiraswasta', 'Di bawah Rp. 4.000.000', 'Masfiyati', 'Pekalongan', '1978-12-20', '3326186012780002', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '856427379320', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(133, 347, 'HANA SHAFITRI INDRIANI', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-01-20', 'P', 1, '3326132102120022.0', '3326136001120003', NULL, NULL, 'Tangkil Kulon Rt.05/02', 'SDN Tangkil Kulon', 'PONDOK PP MAMBAUL HUDA', 'CIPTO HANDOKO', 'Pekalongan', '1984-05-14', '3326131405840081', 'Wira Usaha', 'Di atas Rp. 4.000.000', 'RATNA WIDURI', 'Pekalongan', '1986-03-20', '3326136003860081', 'Ibu rumah tangga', NULL, '857864043000', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(134, 348, 'M.FATKHURRROHMAN', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-09-04', 'L', 3, '3375012708110003.0', '3375010409110003', NULL, NULL, 'Jl.Karya Bakti Gg.7 Rt06 Rw04 Medono Pekalongan Barat 51111', 'MI S 08 medono', 'PONDOK PP MAMBAUL HUDA', 'M.yusuf Anthoni', 'Pekalongan', '1983-08-20', '3375040000000000', 'Pedagang', 'Di bawah Rp. 1.000.000', 'Tunjanah', 'Pekalongan', '1988-07-05', '3375014507880001', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '895171583020', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(135, 350, 'M FARIS SAPUTRA', 'V', NULL, 'Umum', '510033260058240000', 'MA ALHIKAM', 'AKTIF', 'Pekalongan', '2009-10-30', 'L', NULL, NULL, NULL, NULL, NULL, 'Paweden Rt.9/3 Buaran Pekalongan', 'SMP N 1 buaran', 'PONDOK PP MAMBAUL HUDA', 'Tri widadi', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '857278366510', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(136, 351, 'MUHAMMAD AS\'AD AL MUGHNI', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-04-12', 'L', 2, '3326183007070187.0', '3326181204120005', NULL, NULL, 'Desa Kebonrowopucang, Kec. Karangdadap, Kab. Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', 'Abdul Wahid', 'Pekalongan', '1976-09-12', '3326181209760001', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'Umi Hanik', 'Pekalongan', '1981-12-18', '3326185812810002', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '857862077120', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(137, 352, 'FARIS VERDIYANTO', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-09-24', 'L', 1, '3326080705050005.0', '3326082409110003', NULL, NULL, 'Desa Kebonagung, Kec. Kajen, Kab. Pekalongan', 'SD Kebonagung 2', 'PONDOK PP MAMBAUL HUDA', 'Karmen', 'Pekalongan', '1966-06-30', '3326083006660084', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', 'Wartuni', 'Pekalongan', '1971-05-14', '3326085405710002', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62816323527390', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(138, 354, 'MUHAMMAD ALAIKA FAZA', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2013-01-29', 'L', 3, '3326151509080008.0', '3326152901130001', NULL, NULL, 'Desa Pandanarum, Kec. Trito, Kab. Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', 'Makmur', 'Pekalongan', '1976-07-30', '3326150107750063', 'Buruh Harian Lepas', 'Di bawah Rp. 2.500.000', 'Nadhofah', 'Pekalongan', '1977-01-11', '3326155101770004', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', '62812265237500', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(139, 355, 'SYAHRUL HANAFI', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pemalang', '2011-08-08', 'L', 2, '3327102909060005.0', '3327100808110008', NULL, NULL, 'Desa Klareyan, Kec. Petarukan, Kab. Pemalang', NULL, 'PONDOK PP MAMBAUL HUDA', 'Tarmuji', 'Pemalang', '1981-02-10', '3327101002810081', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', 'Turyati', 'Pemalang', '1997-04-01', '3327104104790001', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62812261547260', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(140, 356, 'TRI MAULANA AZZAM', 'II A', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pemalang', '2012-06-26', 'L', 0, '3327100304180005.0', '3327102606120001', NULL, NULL, 'Desa Klareyan, Kec. Petarukan, Kab. Pemalang', NULL, 'PONDOK PP MAMBAUL HUDA', 'Deden Sumarna', 'Tegal', '1971-03-02', '3172020203710011', 'Perdagangan', 'Di bawah Rp. 2.500.000', 'Kastumi', 'Pemalang', '1974-03-17', '3172025703740012', NULL, 'Di bawah Rp. 1.000.000', '62857860020890', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(141, 357, 'CINTIA RAHMI', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-04-06', 'P', 3, '3375012905090011.0', '3375044604120001', NULL, NULL, 'Desa Jenggot, Kec. Pekalongan Selatan, Kota Pekalongan', 'MIS Jenggot 3', 'PONDOK PP MAMBAUL HUDA', 'Fathul Faizin', 'Pekalongan', '1978-06-18', '3375011806780008', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', 'Siti Masrofah', 'Pekalongan', '1981-10-12', '3375045210810002', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', '62857134114990', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(142, 361, 'NAYLA SALSABILA', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2013-01-28', 'P', 1, NULL, NULL, NULL, NULL, 'Tangkil Tengah Kec. Kedungwuni', 'MI Tangkil Tengah', 'PONDOK PP MAMBAUL HUDA', 'Subhan', NULL, NULL, NULL, NULL, 'Di bawah Rp. 4.000.000', 'Tarina', NULL, NULL, NULL, NULL, NULL, '858344774150', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(143, 364, 'JIHAN NAVELA ULYA', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2012-05-02', 'P', 3, '3326112712060008.0', '3326114205120001', NULL, NULL, 'Dukuh Totogan Desa Wiroditan Rt.02/01 Kecamatan Bojong', 'MIS NU AL UTSMANI GEJLIG KAJEN', 'PONDOK PP MAMBAUL HUDA', 'AMINU SOLEH', 'PEKALONGAN', '1975-06-30', '3326113006750241', 'WIRASWASTA', 'Di bawah Rp. 4.000.000', 'NURUL FALAH', 'PEKALONGAN', '1982-04-04', '3326114404820002', 'GURU', 'Di bawah Rp. 4.000.000', '62858668659970', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(144, 365, 'SHOFANA NADIA', 'II B', NULL, 'Umum', '510033260058240000', 'SMP NU BP', 'AKTIF', 'BANJARNEGARA', '2012-01-05', 'P', 1, '3304182801120002.0', '3304184501120004', NULL, NULL, 'Desa Plorengan', NULL, 'PONDOK PP MAMBAUL HUDA', 'WAHYUDIN', 'BANJARNEGARA', '1984-06-19', '3304181905840001', 'Petani', 'Di bawah Rp. 2.500.000', 'SULIMAH', 'BANJARNEGARA', '1991-05-09', '3304184905910001', 'Ibu rumah tangga', 'Di bawah Rp. 2.500.000', '857330595870', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(145, 371, 'ALFA KAMELIA PUTRI', 'II B', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-07-21', 'P', 1, '3326130102120008.0', '3326136107120005', NULL, NULL, 'Desa Kedung Patangewu, Kec. Kedungwuni, Kab. Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', 'ALI SHODIKIN', 'Batang', '1970-09-27', '3326122709700001', 'Wiraswasta', 'Di bawah Rp. 4.000.000', 'SRI IRNAWATI', 'Pekalongan', '1981-05-08', '3326124605810004', 'Menggurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62815750736890', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(146, 372, 'MUHAMMAD ALMAS ALBANY', 'II A', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-11-19', 'L', 3, '3326081509050005.0', '3326081911110004', NULL, NULL, 'Perumahan Griya Permata Indah Blok A2 No. 19, Desa Tanjungsari, Kec. Kajen, Kab. Pekalongan', 'MI Sullama Taufiq Kajen', 'PONDOK PP MAMBAUL HUDA', 'Moh. Zaenal Muttaqin', 'Pekalongan', '1971-11-27', '3326082711710001', 'PNS', 'Di atas Rp. 4.000.000', 'Handayaningsih', 'Batang', '1981-04-30', '3326087004810022', 'Ibu Rumah Tangga', 'Di bawah Rp. 1.000.000', '882217190300', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(147, 374, 'HIMMA SYARIFATUL AMALIYAH', 'II B', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-04-24', 'P', 1, '3375012112110008.0', '3375016404120001', NULL, NULL, 'Desa Pringrejo, Kec. Pekalongan Barat, Kota Pekalongan', 'MI Pringlangu', 'PONDOK PP MAMBAUL HUDA', 'HASSAN BISRI', 'Pekalongan', '1986-09-10', '3375011009660006', 'Guru', 'Di bawah Rp. 4.000.000', 'KHOIRUN NISA', 'Pekalongan', '1991-10-11', '3375015110910006', 'Guru', 'Di bawah Rp. 4.000.000', '62856027222450', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(148, 376, 'DWI AYU RAUDHOTUL JANNAH', 'II B', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Denpasar', '2012-06-02', 'P', 2, '3375010811080008.0', '3375014206120002', NULL, NULL, 'Desa Pasirkeratonkeramat, Kec. Pekalongan Barat, Kota Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', 'FATKHU ROÎšÎ—ÎœÎ‘Î', 'Pekalongan', '1980-12-13', '3375011312800007', 'Buruh Harian Lepas', 'Di bawah Rp. 2.500.000', 'MUNIROH', 'Pekalongan', '1984-12-12', '3375015212840009', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62878295000420', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(149, 378, 'AHMAD FATKHUR MIRZAQ', 'II A', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-04-12', 'L', 1, '3326152906120003.0', '3325151204120001', NULL, NULL, 'Desa Mulyorejo, Kec. Tirto, Kab. Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', 'RIWAN', 'Pekalongan', '1985-08-23', '3326152308850001', 'Buruh Harian Lepas', 'Di bawah Rp. 2.500.000', 'RISCIANA', 'Pekalongan', '1986-06-21', '3326136106860061', 'Burub Harian Lepas', 'Di bawah Rp. 2.500.000', '62856407248360', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(150, 379, 'CHIKA MARETA', 'V', NULL, 'Umum', '510033260058240064', 'MA ALHIKAM', 'AKTIF', 'Pekalongan', '2009-03-20', 'P', 2, '3326050402080003.0', '3326056003090001', NULL, NULL, 'Desa Donowangun, Kec. Talun, Kab. Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', 'KHADIRIN', 'Pekalongan', '1985-04-28', '3326052804850001', 'Buruh  Harian Lepas', 'Di bawah Rp. 2.500.000', 'HERMAWATI', 'Pekalongan', '1987-11-21', '3326056108870001', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62815423961310', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(151, 380, 'GALANG WAHYU SAPUTRA', 'II A', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-01-24', 'L', NULL, '3326181407070102.0', '3326182401120002', NULL, NULL, 'Desa Logandeng, Kec. Karangdadap, Kab. Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', 'UUS SUTARDI', 'Pekalongan', NULL, '3326182705740002', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', NULL, NULL, NULL, NULL, NULL, NULL, '62813864320260', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(152, 381, 'RAISA AQILA EL ZAHWA', 'II B', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pemalang', '2012-05-30', 'P', 1, '3327042311230006.0', '3327047005120002', NULL, NULL, 'Desa Majakerta, Kec. Watukumpul, Kab. Pemalang', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, 'FITRIA RICA SUSANTI', 'Pemalang', '1989-02-26', '3327046602890002', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62858900720860', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(153, 384, 'MUHAMMAD RIFIANSYAH', 'II A', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'PEMALANG', '2011-10-28', 'L', 2, '3327130612110014.0', '3327132810110004', NULL, NULL, 'Ambowetan 03/02 Ulujami Pemalang', 'SD 01 AMBOWETAN ULUJAMI', 'PONDOK PP MAMBAUL HUDA', 'MUHAMMAD MAFUL', 'PEMALANG', '1981-07-04', '3327130407810005', 'WIRASWASTA', 'Di bawah Rp. 4.000.000', 'KHOMSANA WATI', 'PEMALANG', '1985-12-12', '3327135312850002', 'IBU RUMAH TANGGA', 'Di bawah Rp. 1.000.000', '857428425010', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(154, 389, 'AUREL AZZAHROTUL HABIBAH', 'II B', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-06-05', 'P', 1, '3326081406110005.0', '3326084506110001', 'Kajen', 'Kab. Pekalongan', 'Desa Rowolaku, Kec. Kajen, Kab. Pekalongan', 'MI NU Rowolaku Kajen', 'PONDOK PP MAMBAUL HUDA', 'SYAIFUL AHMAD', 'Pekalongan', '1990-12-14', '3326081412900002', 'PEDAGANG', 'Di bawah Rp. 2.500.000', 'DWI EVIYANTI', 'Pekalongan', '1991-06-12', '3326085206910001', 'MENGURUS RUMAH TANGGA', 'Di bawah Rp. 1.000.000', '62858693180460', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(155, 390, 'AHMAD MAHDI AR RIDHO', 'II A', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Kota Pekalongan', '2011-12-28', 'L', 2, '3375010211090027.0', '3375012', NULL, NULL, 'Medono Pekalongan Barat Rt 004 /Rw 001', 'SD N Medono 04', 'PONDOK PP MAMBAUL HUDA', 'Supriadi', 'Jakarta', '1980-06-19', '3375011906800009', 'Karyawan swasta', 'Di bawah Rp. 2.500.000', 'Nailul Aliyah', 'Pekalongan', '1988-01-09', '3375014901880001', 'Mengurus rumah tangga', 'Di bawah Rp. 2.500.000', '00', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(156, 394, 'MUHAMMAD ISYFA\' LANA', 'II A', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-06-29', 'L', 0, '3326111903120023.0', '3326112906120004', NULL, NULL, 'Kec. Bohong, Kab. Pekalongan', 'MI Kemasan', 'PONDOK PP MAMBAUL HUDA', 'SUMAIRI', 'Pekalongan', '1979-07-05', '3320110507790004', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'NURUL QOMARIYAH', 'Pekalongan', '1987-11-27', '3326116711370023', 'Mengurus Rumah Tangga', 'Di bawah Rp. 2.500.000', NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(157, 395, 'AMIRA RIZQI RAMADHANI', 'II B', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-08-10', 'P', 4, '3326130601070013.0', '3325135008120002', NULL, NULL, 'Kec. Kedungwuni, Kab. Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', 'TACHARI', 'Pekalongan', '1961-06-30', '3326133006610666', 'Buruh Harian Lepas', 'Di bawah Rp. 2.500.000', 'RAHAYU', 'Pekalongan', '1971-02-20', '3326136002710021', 'Buruh Harian Lepas', 'Di bawah Rp. 2.500.000', NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(158, 398, 'M SHODIQ ASSAEROFI', 'II A', 'Shifir', 'Khusus', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-05-30', 'L', 2, '3326130205180002.0', '3326133005120004', NULL, NULL, 'Karangdowo Kedungwuni Rt/Rw 011/004', 'Mis walisongo karangdowo', 'PONDOK PP MAMBAUL HUDA', 'Kowiyatun', 'Pekalongan', '1979-07-15', '3326135507790005', 'Buruh', 'Di bawah Rp. 1.000.000', NULL, 'Pekalongan', NULL, NULL, NULL, NULL, '00', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(159, 400, 'M SALMAN AL FARIZI', 'II A', 'Shifir', 'Khusus', '510033260058240064', 'SMP NU BP', 'AKTIF', 'pekalongan', '2012-05-30', 'L', 2, '3326130205180002.0', '3326133005120005', NULL, NULL, 'Karongdowo Kedungwuni Rt/Rw 011/004', 'Mi karongdowo 01', 'PONDOK PP MAMBAUL HUDA', 'Kowiyatun', 'Pekalongan', '1979-07-15', '33261355077900005', 'Buruh', 'Di bawah Rp. 1.000.000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(160, 402, 'SALSABILA SUFIANI', 'V', NULL, 'Umum', '510033260058240064', 'MA ALHIKAM', 'AKTIF', 'Pemalang', '2009-06-19', 'P', 1, '3327130703110025.0', '3327135906090004', NULL, NULL, 'Ds. Botekan Kec. Ulujami Kab. Pemalang', 'SMP AL ISLAH PLUS AMPELGADING', 'PONDOK PP MAMBAUL HUDA', 'Suwarno', 'Pemalang', '1968-04-08', '3327130804680003', 'Wiraswasta', 'Di bawah Rp. 1.000.000', 'Karmini', 'Banyumas', '1981-04-13', '3327140000000000', 'Mengurus rumah tangga', 'Di bawah Rp. 1.000.000', '852934994250', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(161, 404, 'AHMAD FAIRUZ ZABADI', 'V', NULL, 'Umum', '510033260058240064', 'MA ALHIKAM', 'AKTIF', 'Jakarta', '2008-09-06', 'L', 1, '3173010108110002.0', '3173010906081004', NULL, NULL, 'Tanah Koja Duri Kosambi Cengkareng Jakarta Barat Dki Jakarta', NULL, 'PONDOK PP MAMBAUL HUDA', 'Syaiful Arohi', 'Jakarta', '1980-09-05', '3173010509800034', 'Swasta', 'Di bawah Rp. 2.500.000', 'Desi Permata Sari', 'Jakarta', '1985-12-02', '6281211620294', NULL, NULL, '62857110199680', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(162, 405, 'FARRAS REYNAR RAFIF', 'II A', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2012-08-11', 'L', NULL, '3326141702120019.0', '3326141108120002', NULL, NULL, 'Wonoyoso Gg. 4 Rt.08/03', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `data_induk` (`id`, `no_urut`, `nama_lengkap`, `kelas`, `quran`, `kategori`, `nisn`, `lembaga_sekolah`, `status`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `jumlah_saudara`, `nomor_kk`, `nik`, `kecamatan`, `kabupaten`, `alamat`, `asal_sekolah`, `status_mukim`, `nama_ayah`, `tempat_lahir_ayah`, `tanggal_lahir_ayah`, `nik_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `nama_ibu`, `tempat_lahir_ibu`, `tanggal_lahir_ibu`, `nik_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `no_wa_wali`, `nomor_rfid`, `sidik_jari`, `created_at`, `updated_at`, `dokumen_kk`, `dokumen_akte`, `dokumen_ktp`, `dokumen_ijazah`, `dokumen_sertifikat`, `foto_santri`, `nomor_pip`, `sumber_info`, `prestasi`, `tingkat_prestasi`, `juara_prestasi`, `deleted_at`, `deleted_by`) VALUES
(163, 407, 'MUHAMMAD LUTFI AKMAL', 'V', NULL, 'Umum', '510033260058240064', 'MA ALHIKAM', 'AKTIF', 'Pemalang', '2008-07-22', 'L', 1, '3327133010170012.0', '3327132207080002', NULL, NULL, 'Desa Tasikrejo, Kec. Ulujami, Kab. Pemalang', 'SMP Negeri 4 Ulujami', 'PONDOK PP MAMBAUL HUDA', 'MUHAMMAD MUSLICH', 'Pemalang', '1981-10-13', '3327131502820004', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'WIJAYANTI', 'Pemalang', '1983-04-05', '3327130000000000', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62857254244580', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(164, 408, 'PUTRI SANTIA', 'V', NULL, 'Umum', '510033260058240064', 'MA ALHIKAM', 'AKTIF', 'Pekalongan', '2009-02-09', 'P', 4, '3326060107090038.0', '3326064902090002', NULL, NULL, 'Desa Doro, Kec. Doro, Kab. Pekalongan', 'SMP Negeri 1 Doro', 'PONDOK PP MAMBAUL HUDA', 'SODIKIN', 'Pekalongan', '1972-05-25', '3326062005720003', 'Buruh Harian Lepas', 'Di bawah Rp. 1.000.000', 'URIPAH', 'Pekalongan', '1972-07-09', '3326064907720001', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62888069455810', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(165, 410, 'YUMNA AULIA', 'II B', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-10-13', 'P', 1, '3326143011170002.0', '3326145310120001', NULL, NULL, 'Desa Bligo, Kec. Buatan, Kab. Pekalongan', 'MIS Bligo', 'PONDOK PP MAMBAUL HUDA', 'ABDUL KODIR', 'Pekalongan', '1984-03-06', '3375010603840009', 'Buruh Harian Lepas', 'Di bawah Rp. 2.500.000', 'FATIMATUL FARIDAH', 'Pekalongan', '1987-06-06', '3326144608870004', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62856407544000', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(166, 411, 'TASYA KAMILA', 'V', NULL, 'Umum', '510033260058240064', 'MA ALHIKAM', 'AKTIF', 'PEKALONGAN', '2009-06-16', 'P', 2, '3375030309070382.0', '3375035606000002', NULL, NULL, 'Desa Padukuhan Kraton\n\n Kec Pekalongan Utara Kota Pekalongan', 'SMP ISLAM TAKHOSSUS SIMBANG KULON', 'PONDOK PP MAMBAUL HUDA', 'AFIF', 'PEKALONGAN', '1983-04-28', '3375632804830004', 'BURUH HARIAN LEPAS', 'Di bawah Rp. 1.000.000', 'NANIK HERAWATI', 'PEKALONGAN', '1985-10-10', '3375035010850007', 'BURUH HARIAN LEPAS', 'Di bawah Rp. 1.000.000', '856005503990', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(167, 419, 'MUHAMMAD IRFAN AL GHIFARI', 'II A', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Batang', '2012-05-18', 'L', 1, '3325030201130001.0', '3326031805120001', NULL, NULL, 'Desa Kambangan, Kec. Blado, Kab. Batang', NULL, 'PONDOK PP MAMBAUL HUDA', 'MOHAMAD SARYODO', 'Pekalongan', '1987-08-23', '3326172307870001', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'YUBAIDAH', 'Batang', '1991-10-11', '3325036110010002', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', NULL, NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(168, 421, 'IKHSAN DWI SEPTIAN', 'II A', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-09-30', 'L', 1, '3326161201110015.0', '3326163009110003', NULL, NULL, 'Desa Bener Kecamatan Wiradesa', NULL, 'PONDOK PP MAMBAUL HUDA', 'Khaerul Huda', 'Pekalongan', '1985-05-18', '3326161805850001', 'Buruh', 'Di bawah Rp. 1.000.000', NULL, NULL, NULL, NULL, NULL, NULL, '89766005820', NULL, NULL, '2026-01-05 23:36:02', '2026-01-05 23:36:02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(169, 425, 'M. FAZA FAUZAN ADHIMA', 'II A', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Semarang jaya', '2011-12-20', 'L', 2, '1804212308110001.0', '1804212012110001', NULL, NULL, 'Desa Semarang Jaya Kec Air Hitam Kab Lampung Barat', 'Sdn 1 sumber alam', 'PONDOK PP MAMBAUL HUDA', 'Shokhibul kahfi', 'Semarang jaya', '1985-04-02', '1804210204850002', 'Petani', 'Di bawah Rp. 2.500.000', 'Ani swatun muya syaroh', 'Semarang jaya', '1987-01-25', '1804216501870001', 'Petani', 'Di bawah Rp. 2.500.000', NULL, NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(170, 426, 'FARHAN SYAH PUTRA', 'II A', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-08-30', 'L', 2, '3373040102081069.0', '3326183007110001', NULL, NULL, 'Desa Krobokan, Kec. Semarang Barat, Kota Semarang', 'SDI Al Hikmah Krobokan', 'PONDOK PP MAMBAUL HUDA', 'DENY WICAKSONO', 'Salatiga', '1980-12-10', '3373041012800001', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'NUR FARIDA', 'Semarang', '1980-11-21', '3326186111800002', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '88166014900', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(171, 428, 'MUHAMMAD AINUR ROFIQ', 'II A', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2011-07-23', 'L', 0, '3326051611200001.0', '3326052307110001', NULL, NULL, 'Desa Jalatigo, Kec. Talun, Kab. Pekalongan', 'SD N 02 Jalatiga', 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, 'KUSMINI', 'Pekalongan', '1988-06-19', '3326055906880001', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', '88166014900', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(172, 430, 'ANGGI FARADILA SUGIONO', 'V', NULL, 'Umum', '510033260058240064', 'MA ALHIKAM', 'AKTIF', 'Pekalongan', '2008-11-30', 'P', 0, '3326131110040035.0', '3326137110080002', NULL, NULL, 'Dk. Kwayangan Desa Kwayangan Kedungwuni', NULL, 'PONDOK PP MAMBAUL HUDA', 'Muchin', 'Pekalongan', '1957-06-27', '3326130402570001', 'Pedagang kecil', 'Di bawah Rp. 2.500.000', 'Turpiyah', 'Pekalongan', '1976-03-07', '3326134703760022', 'Wiraswasta', 'Di bawah Rp. 2.500.000', '8820075629430', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(173, 432, 'NOVITA SARI', 'II B', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'Pemalang', '2011-12-28', 'P', 2, '332710110300039.0', '3327106812110006', NULL, NULL, 'Dk. Gejlig Desa Widodaren Rt 09 Rw 02 Kec. Petarukan Kab. Pemalang', 'SDN MEDONO 01', 'PONDOK PP MAMBAUL HUDA', 'Rokhani', 'Pemalang', '1981-09-09', '3327100909810104', 'Wiraswasta', 'Di bawah Rp. 4.000.000', 'Mugiati', 'Pekalongan', '1987-10-11', '3327105110870003', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', NULL, NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(174, 433, 'SAFIRA AMANDA PUTRI', 'III B', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, 'Jenggot Buaran Kota Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '856014772510', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(175, 434, 'M. ZAKI UBAIDILLAH', 'II A', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', NULL, NULL, 'L', NULL, NULL, NULL, NULL, NULL, 'Ujungnegoro Batang', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '857335157940', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(176, 436, 'SHALUNA DYAH MIRZA', 'II B', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', 'BATANG', '2011-04-05', 'P', 3, '332501504130002.0', '3325014504110004', 'WONOTUNGGAL', 'BATANG', 'Ds. Dringo Kidul Kec. Wonotunggal Kab. Pekalongan', 'SD N 1 SIWATU', 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(177, 437, 'DIAJENG RATU ASTAGINA', 'II B', NULL, 'Umum', '510033260058240064', 'SMP NU BP', 'AKTIF', NULL, NULL, 'P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(178, 438, 'MUHAMMAD AL FATIH', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-11-19', 'L', 1, '3375021207120005.0', '3375021911120003', NULL, NULL, 'Landungsari gg. 16 no. 6 rt 03/12 kelurahan Noyontaansari Kec. Pekalongan Timur Kota Pekalongan', 'MSI 02 Keputran', 'PONDOK PP MAMBAUL HUDA', 'Izzul Choirot', 'Pekalongan', '1986-01-10', '3375021001860008', 'Karyawan Swasta', 'Di bawah Rp. 4.000.000', 'Wuninggar', 'Pemalang', '1989-06-03', '3327134306890003', 'Ibu rumah tangga', NULL, '8953245887450', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(179, 439, 'SALMA HAFSHA', 'I B', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Kota pekalongan', '2013-07-07', 'P', NULL, '3375034707130002.0', '3375034707130002', NULL, NULL, 'Desa. Pabean-padukuhan kraton, RT. 03/RW.14, kec. Pekalongan Utara, kab. Pekalongan.', 'MSI 17 PABEAN', 'PONDOK PP MAMBAUL HUDA', '-', '-', NULL, '-', '-', NULL, 'Sayaroh', 'Kota pekalongan', '1979-07-05', '3375034507790001', 'Wiraswasta', 'Di bawah Rp. 2.500.000', '62856432569940', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(180, 440, 'MUH HASYIM MAKMUR', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-10-18', 'L', 2, '3171052111111011.0', '3171051810121004', NULL, NULL, 'Perumahan Griya Alya Blok C No.5 Petukangan, Wiradesa', 'SDIT Al Mubarok', 'PONDOK PP MAMBAUL HUDA', 'KHAERON B RACHMAT', 'Pekalongan', '1975-07-03', '3175090307750004', 'Pedagang', 'Di bawah Rp. 4.000.000', 'FERLIN PERMATASARI', 'Pekalongan', '1989-06-30', '3326117006890021', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62857720973660', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(181, 441, 'JIHAN ZULFA', 'I B', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Batang', '2013-06-18', 'P', 1, '3325022702072592.0', '3325025806130005', NULL, NULL, 'Desa Tambahrejo, Kecamatan Bandar, Kabupaten Batang', 'SD Tambahrejo', 'PONDOK PP MAMBAUL HUDA', 'AHMAD ROMADHON', 'Batang', '1972-06-11', '3325020611720002', 'Buruh Harian Lepas', 'Di bawah Rp. 2.500.000', 'TRI WINARSIH', 'Batang', '1982-05-14', '3325025102820001', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62858425603250', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(182, 442, 'RIDHO ABDUL JALIL', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Sukoharjo', '2013-03-07', 'L', NULL, '3311020109120005.0', '3311020703130001', NULL, NULL, 'Desa Malangan, Kec. Bulu, Kab. Sukoharjo', NULL, 'PONDOK PP MAMBAUL HUDA', 'JOKO PITOYO', 'Sukoharjo', '1982-01-05', '3311020501820004', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', 'ANDIWATI', 'Sukoharjo', '1983-06-13', '3311025306830001', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', '62853250640440', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(183, 445, 'DYAH AYU SEKAR KIRANIA', 'I B', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pemalang', '2013-01-17', 'P', 1, '3327100110110011.0', '3327105701130003', NULL, NULL, 'Kec. Petarukan, Kab. Pemalang', NULL, 'PONDOK PP MAMBAUL HUDA', 'SUHARSONO', 'Pemalang', '1984-10-13', '3327101310840026', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'RIRIN RETNOWATI', 'Pemalang', '1986-06-19', '3227110000000000', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', NULL, NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(184, 446, 'ACHMAD GILANG ZHAFIF MAULA', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2013-04-30', 'L', 1, '3375042011120003.0', '3375043004130001', NULL, NULL, 'Desa Sokoduwet, Kec. Pekalongan Selatan, Kota Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', 'YUNUS SIAM', 'Pekalongan', '1986-05-14', '3326151405860002', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', 'IFA YULIANTI', 'Batang', '1989-10-08', '3375044810890005', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', '62823130936960', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(185, 447, 'MUHAMMAD ISHAQUL HIMAM', 'IV', NULL, 'Umum', '510033260058249984', 'MA ALHIKAM', 'AKTIF', 'Pemalang', '2010-06-30', 'L', 0, '3327130910050837.0', '3327133006100002', NULL, NULL, 'Desa Tasikrejo RT004/006, kecamatan Ulujami, Pemalang', 'SMP N 4 Ulujami', 'PONDOK PP MAMBAUL HUDA', 'Surono', 'Pemalang', '1977-11-09', '3327132106730001', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'Nur Asiyah', 'Pemalang', '1976-01-16', '3327135106800002', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '8953772927920', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(186, 448, 'ALIFIA MAULIDA ROSYADA', 'I B', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-07-27', 'P', 2, '3375040000000000.0', '33750467071120002', NULL, NULL, 'Jl. Otto Iskandar Dinata, Duwet, gang 7 RT 03/RW 08', 'MI Salafiyah duwet', 'PONDOK PP MAMBAUL HUDA', 'Imron', 'Pekalongan', '1969-04-10', '3375041004690001', 'Tani', NULL, 'Yoyok Herlina', 'Batang', '1974-02-24', '337504640270001', 'Ibu Rumah tangga', NULL, '62813264443490', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(187, 449, 'MUHAMMAD ZAKI ZHAFIR', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2013-03-07', 'L', 1, '3326143008120005.0', '3326140703130001', NULL, NULL, 'Bligo RT 08 RW 03, Buaran Pekalongan', 'MIS BLIGO', 'PONDOK PP MAMBAUL HUDA', 'MUHAMAD FAQIHUDDIN', 'Pekalongan', '1987-09-30', '3326143009870001', 'Karyawan swasta', 'Di bawah Rp. 4.000.000', 'MILADA KHASANAH', 'Pekalongan', '1988-02-26', '3326146602880001', 'Pedagang', 'Di bawah Rp. 2.500.000', '857022492980', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(188, 450, 'MOHAMAD IRSYAD ZIDNY AQIL', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pemalang', '2012-11-24', 'L', 3, '3327132709070073.0', '3327132411120001', NULL, NULL, 'Desa Tasikrejo RT. 02/RW.04 Kec. Ulujami Kab. Pemalang', 'MI Hadirul Ulum Tasikrejo', 'PONDOK PP MAMBAUL HUDA', 'MUHAMAD SUBAR', 'PEMALANG', '1980-08-10', '3327131008800007', 'PENJAHIT', 'Di bawah Rp. 2.500.000', 'KISYANTI', 'PEMALANG', '1986-03-30', '3327137008860005', 'MENGURUS RUMAH TANGGA', NULL, '858666261610', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(189, 451, 'FAIQ UBAYDILLAH AISY', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'JAKARTA', '2011-11-17', 'L', 1, '3173060601098947.0', '3173061711111012', NULL, NULL, 'Desa Tasikrejo RT 02/ RW 04 Kec, Ulujami Kab, Pemalang', 'SD N 05 KALIDERES JAKARTA BARAT', 'PONDOK PP MAMBAUL HUDA', 'BEJO FARIZAL', 'JAKARTA', '1980-06-26', '3173062606800013', 'PEDAGANG', 'Di atas Rp. 4.000.000', 'TARIPAH', 'PEMALANG', '1978-12-09', '3173064912780002', 'PEDANGANG', NULL, '62878248765550', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(190, 452, 'MUHAMMAD DAKHROSUL HAMID', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pemalang', '2012-09-21', 'L', 3, '3327131010050297.0', '3327132109120002', NULL, NULL, 'Desa Tasikrejo, RT. 05/03, Kec. Ulujami, Kab. Pemalang', 'MI Hadirul Ulum Tasikrejo', 'PONDOK PP MAMBAUL HUDA', 'SUROTO', 'Pemalang', '1962-08-02', '3327130208620001', 'Wirausaha', NULL, 'Dikronah', 'Pemalang', '1970-02-20', '3327136002700001', NULL, NULL, '857013713110', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(191, 453, 'MUHAMMAD SIGIT KHOLID', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pemalang', '2013-04-02', 'L', 1, '3327133105120006.0', '3327130204130001', NULL, NULL, 'Desa Tasikrejo, RT 04/06', 'MI HADIRUL ULUM', 'PONDOK PP MAMBAUL HUDA', 'Sodikin', 'Pemalang', '1980-05-15', '3327131505800008', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'NUR INAYAH', 'Pemalang', '1989-08-08', '3327134808890013', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '831760551970', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(192, 455, 'MUHAMMAD RAIHAN FATKHUL GHIFAR', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pemalang', '2013-04-09', 'L', 2, '332713041180006.0', '3327130904130001', NULL, NULL, 'Dk keweden rt 06 rw 05 Desa Rowosari kec Ulujami kab Pemalang', 'MIS MA\'ARIF NU TERPADU ROWOSARI', 'PONDOK PP MAMBAUL HUDA', 'MOCH ARIFIN', 'PEMALANG', '1983-01-30', '3327133001830004', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'SRI WAHYUNINGSIH', 'PEKALONGAN', '1990-11-07', '3327134711900014', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '858701143250', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(193, 456, 'MUHAMAD ALIF MAULANA', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Batang', '2013-01-31', 'L', 1, '3325131802130009.0', '3325133101130001', NULL, NULL, 'Ds Ujungnegoro Rt2 Rw1 kec Kandeman kab batang', 'MI AL-IKHSAN Ujungnegoro', 'PONDOK PP MAMBAUL HUDA', 'Rasmudi', 'Batang', '1987-05-20', '3325130111860001', 'Wiraswasta', NULL, 'Titik Dwi jayanti', 'Batang', '1991-11-16', '3325135611910001', 'Ibu rumah tangga', NULL, '823255049860', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(194, 457, 'MUHAMMAD AQIL EL MUNJIBY', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2013-07-24', 'L', 0, '3375011712140002.0', '3375012407130001', NULL, NULL, 'Jalan Karya Bakti Gg7 Rt03 Rw4 Medono Pekalongan Barat', 'MSI 08 Medono', 'PONDOK PP MAMBAUL HUDA', 'Edy Syafrudin', 'Pekalongan', '1983-12-10', '3326141012830001', 'Buruh Harian Lepas', 'Di bawah Rp. 4.000.000', 'I\'anah', 'Pekalongan', '1987-09-06', '3375014609870005', 'Ibu Rumah Tangga', NULL, '81565779720', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(195, 458, 'M. MAIMUN SA\'IID', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2013-05-17', 'L', 6, '3326132108080003.0', '3326131705130001', NULL, NULL, 'Ds karngdowo rt 12 rw 05 kec kedungwuni kab pekalongan', 'Mi ws karangdowo 02', 'PONDOK PP MAMBAUL HUDA', 'Ahmad Rokhis', 'Pekalongan', '1973-08-01', '3326130108730003', 'Wirasuwasta', 'Di bawah Rp. 2.500.000', 'Fatimatuzzahro', 'Pekalongan', '1976-01-07', '3326134701760064', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '858657854180', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(196, 459, 'MUHAMMAD AQIL FADHLURRHOMAN', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan 18 Oktober 2012', '2012-10-18', 'L', 2, '3326080902120013.0', '3326081810120004', NULL, NULL, 'DK. Gutoko RT.01 RW.05 Ds. Kebonagung Kec. Kajen', 'SDN 02 KEBONAGUNG', 'PONDOK PP MAMBAUL HUDA', 'Tri Mulyo', 'Pekalongan', '1984-06-13', '3326081306840041', 'Tukang kayu', NULL, 'Gandis Lovila Roji', 'Pekalongan', '1993-07-25', '3326086507930021', 'Ibu Rumah Tangga', NULL, '855911787460', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(197, 460, 'KHALIMATUL AQILA', 'I B', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-10-25', 'P', 1, '3326111105120006.0', '332616510120003', NULL, NULL, 'Desa Ketitang Kidul Kec Bojong', NULL, 'PONDOK PP MAMBAUL HUDA', 'Nur Salim', 'Pekalongan', '1987-06-18', '3326161806870001', 'Buruh', 'Di bawah Rp. 2.500.000', 'Zulfa Naila', 'Pekalongan', '1991-07-15', '3326115707910001', 'Tukang jahit', 'Di bawah Rp. 1.000.000', '856425027020', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(198, 461, 'M. SYAUQI BIKA', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-11-14', 'L', 3, '3375011809070276.0', '337501141120003', NULL, NULL, 'Jl. Karya Bakti GG 7 No. 28 RT. 06 RW. 04 Ds. Medono Kec. Pekalongan Barat', NULL, 'PONDOK PP MAMBAUL HUDA', 'Zainal Arifin', 'Pekalongan', '1972-12-31', '3375013112720037', 'Buruh', NULL, 'Abadiyah', 'Pekalongan', '1975-05-18', '3375015808750014', 'Ibu Rumah Tangga', NULL, '8953918697080', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(199, 462, 'SIFA MUTHOHAROH', 'I B', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-12-18', 'P', NULL, '3375011309110003.0', '3375015812120001', NULL, NULL, 'Desa pasir kraton kramat kec pekalongan barat', NULL, 'PONDOK PP MAMBAUL HUDA', 'Qomar', 'Ciamis', '1975-12-07', '3375010712750004', 'Buruh', 'Di bawah Rp. 2.500.000', 'Danonah', 'Pekalongan', '1985-09-27', '3375016709850005', 'Buruh', NULL, '823224419170', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(200, 463, 'SADANAH HAYUNNISA', 'I B', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Cahayatani', '2013-06-01', 'P', 2, '1605141401190003.0', '16021341091300003', NULL, NULL, 'Desa Bangun Jaya Kec Bts Ulu kab musirawas sulsel', NULL, 'PONDOK PP MAMBAUL HUDA', 'Edi prayitno', 'Jember', '1968-09-06', '1602102130609680003', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'Siti Rohannah', 'Jember', '1977-06-06', '16021346606770004', 'Rumah tangga', 'Di bawah Rp. 1.000.000', '00', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(201, 464, 'YOSSI NOVA PRATAMA', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'TIDAK AKTIF', 'PEKALONGAN', '2012-11-20', 'L', 1, '3326131307120008.0', '3326132811120002', NULL, NULL, 'KARANGDOWO KEDUNGWUNI PEKALONGAN', 'MI WS KARANGDOWO 1', 'PONDOK PP MAMBAUL HUDA', 'SLAMET BEJO', 'PEKALONGAN', NULL, NULL, NULL, NULL, 'JEHWATI', NULL, NULL, NULL, NULL, NULL, '857417883100', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(202, 465, 'MUHAMMAD SYAUQI BIK', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2013-04-20', 'L', 1, '3375012702130006.0', '3375012004130001', NULL, NULL, 'Pringlangu Gg 3 No 21 Kel Pringrejo Kec Pekalongan Barat Kota Pekalongan', 'MI Islamiyah Pringlangu Pekalongan', 'PONDOK PP MAMBAUL HUDA', 'H. Moh Abda\'i Rathomi', 'Pekalongan', '1980-08-26', '3375012608800005', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'Dewi Madaniah', 'Pekalongan', '1987-12-08', '3375034812870001', 'Ibu rumah tangga/ Bidan', 'Di bawah Rp. 2.500.000', '882251784850', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(203, 466, 'ACHMAD ZEIN AL GHIFARI', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2013-01-19', 'L', 2, '3326100701090014.0', '3326101901130001', NULL, NULL, 'Dukuh Ringinpitu Desa Sragi RT. 03/10', 'MI MA\'ARIF NU KALIJAMBE', 'PONDOK PP MAMBAUL HUDA', 'Achmad Sugiarto, S.Pd', 'Pekalongan', '1976-11-29', '3326102911760002', 'Guru', 'Di bawah Rp. 4.000.000', 'Eva Mestonariyah, S.Pd.', 'Pekalongan', '1977-11-20', '3326106011770003', 'Guru', 'Di bawah Rp. 4.000.000', '62815484555300', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(204, 467, 'M. ZACKY RIZKI AFGANI', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-08-05', 'L', NULL, '3326132905130001.0', '3326130508120002', NULL, NULL, 'Dk. kedungwuni timur RT.001/003 Kec. kedungwuni Kab. Pekalongan', 'SD N 04 Kedungwuni', 'PONDOK PP MAMBAUL HUDA', 'SURONO', 'Pekalongan', '1991-07-16', '3326131607910021', 'Buruh', 'Di bawah Rp. 2.500.000', 'ROHMI SAVITRI', 'Ibu Rumah tangga', '1995-12-15', '3326135512950026', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '882160046350', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(205, 468, 'LUK LU\'UL MA\'SUMAH', 'I B', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2013-05-24', 'P', 1, '3326131606060005.0', '3326136405130001', NULL, NULL, 'Pekajangan Kedungwuni Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', 'SISWANTO', 'pekalongan', '1980-07-24', '3326132407800061', 'karyawan swasta', 'Di bawah Rp. 2.500.000', 'ISLACHAH', 'pekalongan', '1981-03-08', '3326134803810081', 'karyawan swasta', 'Di bawah Rp. 2.500.000', '856015268200', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(206, 469, 'M ANWAR FAQIH', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-08-06', 'L', 2, '3326160205120007.0', '3326160608120002', NULL, NULL, 'Ds Warukidul kec wiradesa', NULL, 'PONDOK PP MAMBAUL HUDA', 'Tarjo', 'Pekalongan', '1979-07-11', '3326161107790001', 'Buruh', NULL, 'Sri Hermawati', 'Pekalongan', '1984-10-26', '3326166610840001', NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(207, 470, 'ANANTA ARYASATYA', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Batang', '2013-08-04', 'L', 2, '3325130410090016.0', '3325130409130001', NULL, NULL, 'Desa ujungnegoro RT 002/ 001 kec. Kandeman kab. Batang', 'Mi Al- ikhsan ujungnegoro', 'PONDOK PP MAMBAUL HUDA', 'Ar salafudin', 'Batang', '1973-07-03', '3325130307730001', 'Karyawan', 'Di atas Rp. 4.000.000', 'Suharti', 'Batang', '1973-01-22', '3325136101730001', 'Ibu rumah tangga', NULL, '857288028910', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(208, 472, 'WALIYUDIN AHMAD ALFAQIH', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'TIDAK AKTIF', 'Pekalongan', '2013-09-28', 'L', 2, '332610701140001.0', '3326102809130003', NULL, NULL, 'Dk. Bulaktungak Desa Purwodadi RT.001/003', 'SDN Purwodadi', 'PONDOK PP MAMBAUL HUDA', 'Tugimin', 'Pekalongan', '1982-06-04', '3326100406820004', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', 'Nur laila', 'Pekalongan', '1982-04-02', '3326144204820004', 'Karyawan swasta', 'Di bawah Rp. 2.500.000', '8877719556730', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(209, 473, 'MUCHAMAD MASRUR ALI', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2013-06-17', 'L', NULL, '3326092406130002.0', '3326091706130001', NULL, NULL, 'Dk.kemukus Desa karangrejo RT.01/01 Kec. Kesesi Kab. Pekalongan', 'SD N 02 Kesesi', 'PONDOK PP MAMBAUL HUDA', 'GHUFRON', 'Pekalongan', '1988-05-12', '3326091205880002', 'Buruh', 'Di bawah Rp. 4.000.000', 'IFATUL MUSFIROH', 'Pekalongan', '2025-06-08', '3326096603910005', 'Guru TK Muslimat', 'Di bawah Rp. 1.000.000', '858692909070', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(210, 474, 'AFIKA RIZKI WULANDARI', 'I B', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Tegal', '2012-12-07', 'P', 1, '3328071604130007.0', '3328074712120001', NULL, NULL, 'Desa penyalahan blok lor rt 12 / rw 02', 'MI DARUSSALAM ,PENYALAHAN KEC,JATINEGARA ,KAB TEGAL', 'PONDOK PP MAMBAUL HUDA', 'MASHUDIN', 'Tegal', '1989-09-09', '3328070709890001', 'Pedagang', 'Di atas Rp. 4.000.000', 'KURNIATI', 'Pekalongan', '1993-08-08', '3326064808930001', 'Ibu rumah tangga', 'Di bawah Rp. 1.000.000', '818095567220', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(211, 481, 'REVAN AL DIANSYAH', 'I A', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pemalang', '2012-07-15', 'L', 1, '3327102709070156.0', '3327101507120003', NULL, NULL, 'Ds. Tegalmlati, Kec. Petarukan, Kab. Tegal', NULL, 'PONDOK PP MAMBAUL HUDA', 'SUTAWI', 'Subang', '1969-01-04', '3327100401880001', 'Petani / Pekebun', 'Di bawah Rp. 2.500.000', 'SUKEMI', 'Pemalang', '1972-12-27', '3327106712720022', 'Petani / Pekebun', 'Di bawah Rp. 2.500.000', NULL, NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(212, 482, 'AISYA ALUNA PRAMESWARI', 'I B', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'Pekalongan, 28 April 2013', NULL, 'P', 2, '3326132510180007.0', '3326156804130002', NULL, NULL, 'PEKAJANGAN, GANG 23 RT/RW 016/006', 'MIS BLIGO', 'PONDOK PP MAMBAUL HUDA', 'Fahru Rozikin', 'Pekalongan', NULL, NULL, 'Buruh', 'Di bawah Rp. 1.000.000', 'Sri yanah', 'Pekalongan', NULL, NULL, 'Irt', 'Di bawah Rp. 1.000.000', '857434663920', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(213, 488, 'TSAMARA NUR ILMI', 'I B', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2012-09-24', 'P', 3, '3173070310121005.0', '3173076409121001', NULL, NULL, 'KARANGDOWO KDW PKL', NULL, 'PONDOK PP MAMBAUL HUDA', 'SABAR RIYADI', 'PEKALONGAN', '1985-06-22', '3173072206850011', 'WIRASWASTA', 'Di bawah Rp. 4.000.000', 'RINI ANDRIYANI', 'PEKALONGAN', '1999-08-23', 'PEKALONGAN', 'MENGURUS RUMAH TANGGA', 'Di bawah Rp. 1.000.000', '856429225250', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(214, 489, 'ZIDNA ILMA AIDILLA', 'I B', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'BATANG', '2012-10-30', 'P', 1, '3325130911120001.0', '3325130709180001', NULL, NULL, 'UJUNGNEGORO KANDEMAN BATANG', 'MI AL IKHSAN KAUMAN', 'PONDOK PP MAMBAUL HUDA', 'M ABDUL HOFUR', 'BATANG', '1984-10-04', '3325130410840001', 'WIRASWASTA', 'Di bawah Rp. 4.000.000', 'MAWARTI', 'BATANG', '1984-06-29', '3325136906860001', 'WIRASWASTA', 'Di bawah Rp. 4.000.000', '852293213630', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(215, 490, 'KAILA KHOERU NISA', 'I B', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2013-05-31', 'P', 1, '3326121612080012.0', '33261127105130003', NULL, NULL, 'GETAS WONOPRINGGO', NULL, 'PONDOK PP MAMBAUL HUDA', 'CIPTO ROSO', 'PEKALONGAN', '1979-10-10', '3326121010790003', 'BURUH', 'Di bawah Rp. 2.500.000', 'SITI KOMARIYAH', 'PEKALONGAN', '1985-05-19', '3326125911850002', 'MENGURUS RUMAH TANGGA', NULL, '00', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(216, 491, 'FAKHRO SUFAIRO RIZQI', 'I B', NULL, 'Umum', '510033260058249984', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2013-03-05', 'P', NULL, '3375042308070164.0', '3375044503130002', NULL, NULL, 'BANYURIP PEKALONGAN SELATAN KOTA PEKALONGAN', 'MI ISLAMIYAH BANYURIP AGENG 01', 'PONDOK PP MAMBAUL HUDA', 'SYAEKHU', 'PEKALONGAN', '1966-03-04', '3375040403660005', 'GURU', 'Di bawah Rp. 4.000.000', 'NUR AFIYAH', 'PEKALONGAN', '1971-04-10', '3375045007710003', 'TUKANG JAHIT', 'Di bawah Rp. 4.000.000', '857420990030', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(217, 492, 'SATRIO AKBAR FUADI', 'I A', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2012-10-26', 'L', 2, '3375030309070382.0', '3375032610120001', NULL, NULL, 'PADUKUHAN KRATON PEKALONGAN UTARA KOTA PEKALONGAN', 'MIS MSI 17 PABEAN', 'PONDOK PP MAMBAUL HUDA', 'AFIF', '3375032804830004', '1983-04-28', '3375032804830004', 'BURUH', 'Di bawah Rp. 2.500.000', 'NANIK HERAWATI', 'PEKALONGAN', '1985-10-10', '3375035010850007', 'BURUH', 'Di bawah Rp. 2.500.000', '856005503990', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(218, 493, 'AFZA FEBRIANA HERMANTO', 'I B', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2013-02-20', 'P', 3, '3326130402060001.0', '3326136002130003', NULL, NULL, 'PAKIS PUTIH KEDUNGWUNI PEKALONGAN', 'SDN 02 PAKISPUTIH', 'PONDOK PP MAMBAUL HUDA', 'BAMBANG HERMANTO', 'PEKALONGAN', '1979-12-13', '3326131312790001', 'WIRASWASTA', 'Di bawah Rp. 2.500.000', 'SRI UTAMI', 'PEKALONGAN', '1985-04-26', '3326136604850001', 'MENGURUS RUMAH TANGGA', 'Di bawah Rp. 2.500.000', '857005185010', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(219, 494, 'JABAR PANGESTU', 'I A', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'TIDAK AKTIF', 'PEMALANG', '1974-05-22', 'L', 1, '3327090710220002.0', '3327090710220002', NULL, NULL, 'KEDUNGBANJAR TAMAN PEMALANG', 'SDN 03 KEDUNGBANJAR', 'PONDOK PP MAMBAUL HUDA', 'AJI SISWOYO', 'PEMALANG', '1974-05-22', '3327092205760004', 'BURUH', 'Di bawah Rp. 2.500.000', 'MASRIYAH', 'PEMALANG', '1980-09-20', '3327094404800009', 'MENGURUS RUMAH TANGGA', 'Di bawah Rp. 1.000.000', '838563181910', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(220, 495, 'ARJUNA SAPUTRA', 'I A', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'TIDAK AKTIF', 'TEGAL', '2013-09-07', 'L', 2, '3327091312080014.0', '3327607091300007', NULL, NULL, 'TAMAN PEMALANG', NULL, 'PONDOK PP MAMBAUL HUDA', 'SAKHRONI', 'PEMALANG', '1975-01-01', '3327090101000987', 'BURUH', 'Di bawah Rp. 2.500.000', 'MASRUROH', 'PEMALANG', '1977-01-23', '332709630177770006', 'RUMAH TANGGA', 'Di bawah Rp. 2.500.000', '00', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(221, 497, 'RIZKI RAMADANI', 'I A', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'Legoksayem, Wanayasa, banjarnegara', '2011-08-12', 'L', 2, '3304171404210001.0', '1502221208110001', NULL, NULL, 'Desa legoksayem RT 007/001', 'SMP Negeri 3 kalibening', 'PONDOK PP MAMBAUL HUDA', 'M Dahlan', 'Banjarnegara', '1981-08-25', '1502222508810001', 'Tani', 'Di bawah Rp. 2.500.000', 'Buroidah', 'Tanjung', '1992-11-09', '1502224911920002', 'Tqni', 'Di bawah Rp. 2.500.000', '813531757410', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(222, 498, 'KHAMID AWALUDIN', 'I A', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2012-01-31', 'L', 3, '3326061111080016.0', '3326063101120002', NULL, NULL, 'DES BLIGOREJO KEC DORO PEKALONGAN', NULL, 'PONDOK PP MAMBAUL HUDA', 'RISNO', 'PEKALONGAN', '1980-05-26', '3326062605800002', 'BURUH', 'Di bawah Rp. 2.500.000', 'LINA RUSNIATI', 'PEKALONGAN', '1982-12-27', '3326066712820002', 'BURUH', 'Di bawah Rp. 1.000.000', '00', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(223, 499, 'MASLIKHA', 'I B', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2011-09-07', 'P', NULL, '3326132510160012.0', '3326154709110003', NULL, NULL, 'DES KWAYANGAN KEC KEDUNGWUNI PEKALONGAN', NULL, 'PONDOK PP MAMBAUL HUDA', 'NUR ROKHIM', 'PEKALONGAN', '1986-06-02', '3326150206860002', 'BURUH', 'Di bawah Rp. 2.500.000', NULL, NULL, NULL, NULL, NULL, NULL, '877624060410', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(224, 500, 'M. FILZA AZIDAN FAZA', 'I A', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-01-28', 'L', 1, '3375012712110008.0', '3375012801120002', NULL, NULL, 'Ds. Pringrejo, Kec. Pekalongan Barat, Kota Pekalongan', 'MI Islamiyah Pringlangu 01', 'PONDOK PP MAMBAUL HUDA', 'ARIF SETIAWAN', 'Pekalongan', '1982-09-21', '3375012109820005', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'UMMI UMAIROH', 'Pekalongan', '1986-09-01', '3375024109860007', 'Karyawan Swasta', 'Di bawah Rp. 2.500.000', '62857279808880', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(225, 501, 'MUHAMMAD KEVIN SANTOSO', 'I A', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-05-04', 'L', NULL, '3375041901150001.0', '3375040405120003', NULL, NULL, 'Kec. Pekalongan Selatan, Kota Pekalongan', 'SDN Sidorejo Warungasem', 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, 'LINA BUDHARTI', 'Pekalongan', '1981-04-14', '3375045401810003', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62851426159210', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(226, 502, 'CLARA VANESHA MAHARANI', 'I B', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2012-03-05', 'P', 1, '3375011200001.0', '3375014503120001', NULL, NULL, 'DES TIRTO KEC PEKALONGAN BARAT PEKALONGAN', NULL, 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, 'NOVITA SULASTRI', 'PEKALONGAN', '1988-06-27', '3375016708880003', 'DIPLOMA', 'Di bawah Rp. 4.000.000', '823294648720', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(227, 503, 'ZAHRO NUR AULIYAH', 'I B', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'PEMALANG', '2012-07-15', 'P', 1, '3327091608120006.0', '3327095507120004', NULL, NULL, 'DES JEBED SELATAN KEC TAMAN PML', NULL, 'PONDOK PP MAMBAUL HUDA', 'NUR CAHYO', 'PEKALONGAN', '1980-12-26', '3327092612800014', 'WIRASWASTA', 'Di bawah Rp. 2.500.000', 'UCI RIYATUN', 'PEMALANG', '1988-04-12', '3327095204880006', NULL, NULL, '00', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(228, 504, 'M AZKA SYAFIQ', 'I A', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2013-04-15', 'L', 2, '3326132911120002.0', '3326131504130002', NULL, NULL, 'DES KARANGDOWO KEC KDW PKL', NULL, 'PONDOK PP MAMBAUL HUDA', 'RIDWAN ARIFIN', 'PEKALONGAN', '1988-07-15', '3326131507880021', 'WIRASWASTA', 'Di bawah Rp. 2.500.000', 'SITI AISYAH', 'PEKALONGAN', '1988-08-15', '3326135508880021', NULL, NULL, '858423265400', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(229, 505, 'ADHYAKSA HUSEIN RAHMATILLAH', 'I A', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'Batang', '2013-04-24', 'L', NULL, '3325111903130013.0', '3325112404130005', NULL, NULL, 'Kec. Batang, Kab. Batang', NULL, 'PONDOK PP MAMBAUL HUDA', 'PENTA ADHI WIJAYANTO', 'Batang', '1984-06-22', '3325112206840001', 'Guru', 'Di bawah Rp. 2.500.000', 'DEWI SITI KHOTIJAH', 'Pekalongan', '1989-03-24', '3326106403890001', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62856435728090', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(230, 506, 'ASHKIA MAHLIDA', 'I B', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2013-06-26', 'P', NULL, '3326132010170004.0', '3326136606130006', NULL, NULL, 'Ds. Pajomblangan, Kec. Kedungwuni, Kab. Pekalongan', 'MIS Walisongo Pajomblangan 02', 'PONDOK PP MAMBAUL HUDA', 'SUPARLAN', 'Bojonegoro', '1985-10-10', '3522160000000000', 'Buruh', 'Di bawah Rp. 2.500.000', 'NIHAYAH', 'Pekalongan', '1985-10-29', '3326136910850001', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62857419100360', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(231, 507, 'AGNY RAEESA NABILA', 'I B', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2012-02-04', 'P', 1, '3375022204130009.0', '3375024402120001', NULL, NULL, 'DES KAUMAN KEC PEKALONGAN TIMUR KOTA PKL', NULL, 'PONDOK PP MAMBAUL HUDA', 'AGUS PRIONO', 'PEKALONGAN', '1979-08-09', '3375022204130009', 'BURUH', 'Di bawah Rp. 2.500.000', 'NUR ANIAH', 'PEKALONGAN', '1987-04-01', '3375024104870005', 'BURUH', NULL, '823285023650', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(232, 508, 'DZATI ZAHRA AULIA', 'I B', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-08-22', 'P', 1, '3326181905150007.0', '3326186208120002', NULL, NULL, 'Ds. Jrebengkembang, Kec. Karangdadap, Kab. Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', 'CHAMDI', 'Pekalongan', '1989-09-05', '3326182206890001', 'Buruh Harian Lepas', 'Di bawah Rp. 2.500.000', 'ELI PURWATI', 'Pekalongan', '1993-03-12', '3326185203930001', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', NULL, NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(233, 509, 'M NABIL RIZIQ', 'IV', NULL, 'Umum', '510033260058250112', 'MA ALHIKAM', 'AKTIF', 'PEKALONGAN', '2008-03-28', 'L', 1, '3375030105090008.0', '3375032803080001', NULL, NULL, 'DES KRAPYAK KEC PEKALONGAN UTARA KOTA PKL', NULL, 'PONDOK PP MAMBAUL HUDA', 'AHMAD BESAR', NULL, NULL, '3375031303660005', 'BURUH', 'Di bawah Rp. 2.500.000', 'ISTI QOMAH', 'PEKALONGAN', '2008-03-28', '3375035506790012', NULL, NULL, '859725511530', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(234, 510, 'KHOIRUL AZMI FADHIL', 'I A', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'Pemalang', '2013-10-15', 'L', 1, '3327092707110018.0', '3327091510130005', NULL, NULL, 'Kec. Taman, Kab. Pemalang', 'SDN 03 Kedungbanjar', 'PONDOK PP MAMBAUL HUDA', 'WIRYADI', 'Pemalang', '1981-08-20', '3327090000000000', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'ZANI ARISAH', 'Pemalang', '1984-08-13', '3327095308840006', 'Mengurus Rumah Tangga', 'Di bawah Rp. 1.000.000', '62878472726730', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(235, 511, 'AHMAD FADLI RAFA ARDANA', 'I A', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'PEKALONGAN', '2012-12-05', 'L', 2, '3326060000000000.0', '3326061905120001', NULL, NULL, 'DES HARJOSARI KEC DORO PKL', NULL, 'PONDOK PP MAMBAUL HUDA', 'ULFIYADI', 'PEKALONGAN', '1973-12-03', '3326061903730003', 'PERANGKAT DESA', 'Di atas Rp. 4.000.000', 'NURWATI', 'PEKALONGAN', '1983-12-06', '3326065806830003', NULL, NULL, '813298377850', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(236, 513, 'ILYA LANA NURONA', 'I B', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'Batang', '2012-05-30', 'P', 1, '3325220904180012.0', '3325127005120002', NULL, NULL, 'Ds. Kaliwareng, Kec. Warungasem, Kab. Batang', 'SDN Kaliwareng', 'PONDOK PP MAMBAUL HUDA', NULL, NULL, NULL, NULL, NULL, NULL, 'CUCI MARTINI', 'Batang', '1988-11-15', '3325125511880001', 'Menurus Rumah Tangga', 'Di bawah Rp. 2.500.000', '62859725382150', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(237, 517, 'MUHAMMAD RIFQI  HAFIDL', 'I A', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2012-01-31', 'L', 1, '3326062802120004.0', '3326063101120001', NULL, NULL, 'Ds. Bligorejo, Kec. Doro, Kab. Pekalongan', NULL, 'PONDOK PP MAMBAUL HUDA', 'ABDULLAH', 'Pekalongan', '1984-12-24', '3326062412840001', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'MUTHOHAROH', 'Batang', '1989-05-21', '3325126105890001', 'Wiraswasta', 'Di bawah Rp. 2.500.000', '62857409215670', NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(238, 518, 'KHANZA FAUZIYAH', 'I B', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2013-07-17', 'P', 5, '3326182707070432.0', '3326185707130001', NULL, NULL, 'Ds. Pangkah, Kec. Karangdadap, Kab. Pekalongan', 'SDN Pangkah', 'PONDOK PP MAMBAUL HUDA', 'KANAPI', 'Batang', '0076-10-03', '3326180310760001', 'Wiraswasta', 'Di bawah Rp. 2.500.000', 'MUKHAYAH', 'Pekalongan', '1980-10-03', '3326184310800003', 'Mengurus Rumah Tangga', 'Di bawah Rp. 2.500.000', NULL, NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(239, 519, 'M. ABDUL MAJID HUMAM', 'I A', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'Pemalang', '2013-04-04', 'L', 3, '3327061104080036.0', '3327060404130002', NULL, NULL, 'Sumurkidang Kec. Bantar Bolang Kab. Pemalang', NULL, 'PONDOK PP MAMBAUL HUDA', 'MUHAMMAD FATCHUL HIDAYAT', 'MAGELANG', '1971-07-19', '3327061607710003', 'WIRASWASTA', NULL, 'SUSMIYARTI', 'PEMALANG', '1974-06-29', '3327066908740001', 'GURU', NULL, NULL, NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(240, 520, 'NUR ISTIQOMAH', 'II B', NULL, 'Umum', '510033260058250112', 'SMP NU BP', 'AKTIF', 'Pekalongan', '2025-10-02', 'P', 2, NULL, NULL, NULL, NULL, 'Jakarta Pusat Tamah Abang Petamburan', NULL, 'PONDOK PP MAMBAUL HUDA', 'CASMANTO', 'PEKALONGAN', NULL, NULL, NULL, NULL, 'SITI AISYAH', 'PEKALONGAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(241, 521, 'NAILATUS SANIA', 'II B', NULL, 'Umum', NULL, 'SMP NU BP', 'AKTIF', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-01-05 23:36:03', '2026-01-05 23:36:03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
-- Table structure for table `jadwal_absens`
--

CREATE TABLE `jadwal_absens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `start_time` time NOT NULL,
  `scheduled_time` time NOT NULL,
  `end_time` time NOT NULL,
  `late_tolerance_minutes` int(11) DEFAULT 15,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `jadwal_absens`
--

INSERT INTO `jadwal_absens` (`id`, `name`, `type`, `start_time`, `scheduled_time`, `end_time`, `late_tolerance_minutes`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(1, 'Absen Masuk', 'absen', '05:30:00', '06:00:00', '07:00:00', 15, '2026-01-04 20:59:39', '2026-01-05 12:38:40', NULL, NULL),
(2, 'Absen Pulang', 'absen', '15:00:00', '16:00:00', '23:59:00', 0, '2026-01-04 20:59:39', '2026-01-05 12:38:40', NULL, NULL),
(4, 'Absen Dzuhur', 'absen', '11:30:00', '12:00:00', '23:59:00', 0, '2026-01-05 13:34:24', '2026-01-05 13:34:24', NULL, NULL);

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
-- Table structure for table `print_izin_history`
--

CREATE TABLE `print_izin_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nomor_surat` varchar(50) NOT NULL,
  `kategori` enum('sakit','izin_pulang') NOT NULL,
  `santri_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`santri_ids`)),
  `santri_names` text DEFAULT NULL,
  `tujuan_guru` varchar(100) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `printed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `printed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `print_izin_history`
--

INSERT INTO `print_izin_history` (`id`, `nomor_surat`, `kategori`, `santri_ids`, `santri_names`, `tujuan_guru`, `kelas`, `tanggal`, `printed_by`, `printed_at`) VALUES
(1, '001/SKA.001/PPMH/I/2026', 'sakit', '[\"1\"]', 'M. FAHMI SIROJUL MUNIR', 'MA ALHIKAM', 'VII', '2026-01-09', 1, '2026-01-09 13:32:42'),
(2, '002/SKA.001/PPMH/I/2026', 'sakit', '[\"1\"]', 'M. FAHMI SIROJUL MUNIR', 'MA ALHIKAM', 'VII', '2026-01-09', 1, '2026-01-09 13:33:30'),
(3, '003/SKA.001/PPMH/I/2026', 'sakit', '[\"1\"]', 'M. FAHMI SIROJUL MUNIR', 'MA ALHIKAM', 'VII', '2026-01-09', 1, '2026-01-09 13:37:28'),
(4, '004/SKA.001/PPMH/I/2026', 'sakit', '[\"1\"]', 'M. FAHMI SIROJUL MUNIR', 'MA ALHIKAM', 'VII', '2026-01-09', 1, '2026-01-09 13:38:43'),
(5, '005/SKA.001/PPMH/I/2026', 'sakit', '[\"1\"]', 'M. FAHMI SIROJUL MUNIR', 'MA ALHIKAM', 'VII', '2026-01-09', 1, '2026-01-09 13:40:11'),
(6, '006/SKA.001/PPMH/I/2026', 'sakit', '[\"1\"]', 'M. FAHMI SIROJUL MUNIR', 'MA ALHIKAM', 'VII', '2026-01-09', 1, '2026-01-09 13:42:36'),
(7, '007/SKA.001/PPMH/I/2026', 'sakit', '[\"1\"]', 'M. FAHMI SIROJUL MUNIR', 'MA ALHIKAM', 'VII', '2026-01-09', 1, '2026-01-09 13:43:14'),
(8, '008/SKA.001/PPMH/I/2026', 'sakit', '[\"1\"]', 'M. FAHMI SIROJUL MUNIR', 'MA ALHIKAM', 'VII', '2026-01-09', 1, '2026-01-09 13:47:49'),
(9, '009/SKA.001/PPMH/I/2026', 'sakit', '[\"1\"]', 'M. FAHMI SIROJUL MUNIR', 'MA ALHIKAM', 'VII', '2026-01-09', 1, '2026-01-09 14:23:18'),
(10, '010/SKA.001/PPMH/I/2026', 'sakit', '[\"1\"]', 'M. FAHMI SIROJUL MUNIR', 'MA ALHIKAM', 'VII', '2026-01-09', 1, '2026-01-09 16:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `print_queue`
--

CREATE TABLE `print_queue` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_type` varchar(50) NOT NULL DEFAULT 'surat_izin',
  `job_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`job_data`)),
  `status` enum('pending','processing','completed','failed') DEFAULT 'pending',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `processed_at` timestamp NULL DEFAULT NULL,
  `error_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `print_queue`
--

INSERT INTO `print_queue` (`id`, `job_type`, `job_data`, `status`, `created_by`, `created_at`, `processed_at`, `error_message`) VALUES
(1, 'surat_izin', '{\"nomorSurat\":\"001\\/SKA.001\\/PPMH\\/I\\/2026\",\"tujuanGuru\":\"MA ALHIKAM\",\"santriNames\":[\"M. FAHMI SIROJUL MUNIR\"],\"kelas\":\"VII\",\"tanggal\":\"09\\/01\\/2026\",\"kategori\":\"sakit\"}', 'failed', 1, '2026-01-09 13:32:46', '2026-01-09 13:33:05', 'Failed to sign request'),
(2, 'surat_izin', '{\"nomorSurat\":\"002\\/SKA.001\\/PPMH\\/I\\/2026\",\"tujuanGuru\":\"MA ALHIKAM\",\"santriNames\":[\"M. FAHMI SIROJUL MUNIR\"],\"kelas\":\"VII\",\"tanggal\":\"09\\/01\\/2026\",\"kategori\":\"sakit\"}', 'failed', 1, '2026-01-09 13:33:32', '2026-01-09 13:33:35', 'Failed to sign request'),
(3, 'surat_izin', '{\"nomorSurat\":\"003\\/SKA.001\\/PPMH\\/I\\/2026\",\"tujuanGuru\":\"MA ALHIKAM\",\"santriNames\":[\"M. FAHMI SIROJUL MUNIR\"],\"kelas\":\"VII\",\"tanggal\":\"09\\/01\\/2026\",\"kategori\":\"sakit\"}', 'completed', 1, '2026-01-09 13:37:29', '2026-01-09 13:37:37', NULL),
(4, 'surat_izin', '{\"nomorSurat\":\"004\\/SKA.001\\/PPMH\\/I\\/2026\",\"tujuanGuru\":\"MA ALHIKAM\",\"santriNames\":[\"M. FAHMI SIROJUL MUNIR\"],\"kelas\":\"VII\",\"tanggal\":\"09\\/01\\/2026\",\"kategori\":\"sakit\"}', 'completed', 1, '2026-01-09 13:38:46', '2026-01-09 13:38:55', NULL),
(5, 'surat_izin', '{\"nomorSurat\":\"005\\/SKA.001\\/PPMH\\/I\\/2026\",\"tujuanGuru\":\"MA ALHIKAM\",\"santriNames\":[\"M. FAHMI SIROJUL MUNIR\"],\"kelas\":\"VII\",\"tanggal\":\"09\\/01\\/2026\",\"kategori\":\"sakit\"}', 'completed', 1, '2026-01-09 13:40:14', '2026-01-09 13:40:29', NULL),
(6, 'surat_izin', '{\"nomorSurat\":\"006\\/SKA.001\\/PPMH\\/I\\/2026\",\"tujuanGuru\":\"MA ALHIKAM\",\"santriNames\":[\"M. FAHMI SIROJUL MUNIR\"],\"kelas\":\"VII\",\"tanggal\":\"09\\/01\\/2026\",\"kategori\":\"sakit\"}', 'completed', 1, '2026-01-09 13:42:38', '2026-01-09 13:42:47', NULL),
(7, 'surat_izin', '{\"nomorSurat\":\"007\\/SKA.001\\/PPMH\\/I\\/2026\",\"tujuanGuru\":\"MA ALHIKAM\",\"santriNames\":[\"M. FAHMI SIROJUL MUNIR\"],\"kelas\":\"VII\",\"tanggal\":\"09\\/01\\/2026\",\"kategori\":\"sakit\"}', 'completed', 1, '2026-01-09 13:43:14', '2026-01-09 13:43:18', NULL),
(8, 'surat_izin', '{\"nomorSurat\":\"008\\/SKA.001\\/PPMH\\/I\\/2026\",\"tujuanGuru\":\"MA ALHIKAM\",\"santriNames\":[\"M. FAHMI SIROJUL MUNIR\"],\"kelas\":\"VII\",\"tanggal\":\"09\\/01\\/2026\",\"kategori\":\"sakit\"}', 'completed', 1, '2026-01-09 13:47:49', '2026-01-09 13:47:51', NULL),
(9, 'surat_izin', '{\"nomorSurat\":\"009\\/SKA.001\\/PPMH\\/I\\/2026\",\"tujuanGuru\":\"MA ALHIKAM\",\"santriNames\":[\"M. FAHMI SIROJUL MUNIR\"],\"kelas\":\"VII\",\"tanggal\":\"09\\/01\\/2026\",\"kategori\":\"sakit\"}', 'completed', 1, '2026-01-09 14:23:18', '2026-01-09 14:23:19', NULL),
(10, 'surat_izin', '{\"nomorSurat\":\"010\\/SKA.001\\/PPMH\\/I\\/2026\",\"tujuanGuru\":\"MA ALHIKAM\",\"santriNames\":[\"M. FAHMI SIROJUL MUNIR\"],\"kelas\":\"VII\",\"tanggal\":\"09\\/01\\/2026\",\"kategori\":\"sakit\"}', 'completed', 1, '2026-01-09 16:07:19', '2026-01-09 16:07:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `remember_tokens`
--

INSERT INTO `remember_tokens` (`id`, `user_id`, `token_hash`, `expires_at`, `created_at`) VALUES
(25, 9, '9d5ec512dcc0b9c797bfa5a2866e7be863ebf45f3ae77f191809492352de3307', '2026-03-06 04:24:30', '2026-02-03 21:24:30'),
(26, 10, '0f9dc568a173fc88e2299cfd3dfe3dc0da74fd3bd7db433803cdc414ef9731b0', '2026-03-08 10:11:02', '2026-02-06 03:11:05'),
(27, 7, 'a89d397c6be85a7cdd16ffd9293ff55efca637aff3158ec0ff3292613f255c44', '2026-03-08 22:20:13', '2026-02-06 15:20:13'),
(28, 1, 'c90487b6170171db16acd58bbd19b01738781dec200c8848c5f6f9fa237c0b02', '2026-03-09 16:09:22', '2026-02-07 09:09:22');

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
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'Laporan Santri', '2026-01-04 20:59:40', '2026-01-04 20:59:40'),
(2, 'school_name', 'Pondok Pesantren Mambaul Huda', '2026-01-04 20:59:40', '2026-01-04 20:59:40'),
(3, 'school_address', 'Jl. Pesantren No. 123, Kediri, Jawa Timur', '2026-01-04 20:59:40', '2026-01-04 20:59:40'),
(4, 'school_phone', '0354-123456', '2026-01-04 20:59:40', '2026-01-04 20:59:40'),
(5, 'wa_api_url', 'http://serverwa.hello-inv.com/send-message', '2026-01-04 20:59:40', '2026-01-04 20:59:40'),
(6, 'wa_api_key', 'VbM1epmqMKqrztVrWpd1YquAboWWFa', '2026-01-04 20:59:40', '2026-01-04 20:59:40'),
(7, 'wa_sender', '6282131871383', '2026-01-04 20:59:40', '2026-01-04 20:59:40'),
(8, 'latitude', '-7.8166', '2026-01-04 20:59:40', '2026-01-04 20:59:40'),
(9, 'longitude', '112.0148', '2026-01-04 20:59:40', '2026-01-04 20:59:40'),
(10, 'radius_meters', '100', '2026-01-04 20:59:40', '2026-01-04 20:59:40');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `description`, `updated_at`) VALUES
(1, 'auto_purge_enabled', '1', 'Enable auto-delete of trash items after X days', '2026-01-11 04:06:10'),
(2, 'auto_purge_days', '30', 'Number of days before permanent deletion', '2026-01-06 18:31:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','karyawan','pengurus','guru','keamanan','kesehatan') NOT NULL DEFAULT 'karyawan',
  `foto` varchar(255) DEFAULT 'profile.jpg',
  `phone` varchar(20) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `role`, `foto`, `phone`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`) VALUES
(1, 'Administrator', 'admin@mambaul-huda.com', '$2y$12$zyWoVLAQZC22jKs7QV7oYOp0lGLVej7DXb5ktfBeE9L66m28kDIXu', 'Kantor Pondok Pesantren', 'admin', 'user_1_1767752456.png', '085183878466', 'k2KYFK3GK7q3ZBnUDNuNzLynLT8SBbaua8vFA5C2il9jRMyyGdu9KdAnkQYc', '2026-01-04 20:59:39', '2026-01-21 16:09:09', NULL, NULL),
(7, 'Akrom Adabi', 'debi@ppmh.com', '$2y$10$OGJH7jq6BlL520FIQy9lkOrbWXa7NDdsnPkByD9O15WVP5kqa9qH2', '', 'admin', 'profile.jpg', '085641647478', 'CILiEZopTC8S0GhPdk489iwUxdWfxbtE5nS4mr8BbQUSlIRuAQzJyYnQjBSc', '2026-01-07 09:14:33', '2026-02-06 15:20:13', NULL, NULL),
(8, 'Yusuf', 'yusuf@ppmh.com', '$2y$12$uFEDiBmOeuNViNXma6zJZOuf8mTGwinTvwRYnSzycDF1EPLmfDRNG', NULL, 'pengurus', 'profile.jpg', NULL, NULL, '2026-01-07 09:17:18', '2026-01-16 06:37:27', NULL, NULL),
(9, 'Kowi', 'kowi@ppmh.com', '$2y$12$HBy7XUtrSPF0IICqZObhO.u2VjoBgApEy/vZ7X3p4SdPnGTqAhsBa', NULL, 'pengurus', 'profile.jpg', NULL, 'vDOKq9tuDXS6YX0ELTp7eDXsZQAoe942xgOvw2LiseP5ILxVOofCUciQUcPJ', '2026-01-07 09:17:59', '2026-02-03 21:22:45', NULL, NULL),
(10, 'Oki', 'oki@ppmh.com', '$2y$12$CaPW6qrwJWBKFihDXhLIxuESkTWiSn75LlrXJWfjoLzZlGaOnseBu', NULL, 'pengurus', 'profile.jpg', NULL, 'Jaz1M8NYsfNbV1Q8Ky2OPzVc7DXAavlvQDCsuOgyEr1Iuw0xemQSZWQTNyWn', '2026-01-07 09:18:23', '2026-02-03 16:11:19', NULL, NULL),
(11, 'Nayla', 'nayla@ppmh.com', '$2y$12$7XbHZmZqsXpB5Drc6LCm5.eMDT8uf8/YLDJbctWdE3ZvfEBmp6ObS', NULL, 'pengurus', 'profile.jpg', NULL, NULL, '2026-01-07 09:18:47', '2026-01-16 06:36:52', NULL, NULL),
(12, 'Ilmi', 'ilmi@ppmh.com', '$2y$12$jQ4LdFrCOgYnBQRykgVlvOkgn/RjlOnPnG9RL5Vw1O87FjiLt0hg.', NULL, 'pengurus', 'profile.jpg', NULL, NULL, '2026-01-07 09:19:55', '2026-01-16 06:36:31', NULL, NULL),
(13, 'Ifaza', 'ifaza@ppmh.com', '$2y$12$WK9482qc4Q6P8hIjHfIZoegc3HoktaxSFcGnCTMKhkJQz2RECaCtK', NULL, 'pengurus', 'profile.jpg', NULL, NULL, '2026-01-07 09:20:11', '2026-01-16 06:36:21', NULL, NULL),
(14, 'Surya', 'surya@ppmh.com', '$2y$12$eqlcBO2zj6S7unfM2xKP3.1evAI3SIEsb7/PYkgDAisEP.7.yI2KS', NULL, 'pengurus', 'profile.jpg', NULL, NULL, '2026-01-07 09:20:27', '2026-01-16 06:37:21', NULL, NULL),
(15, 'Adil', 'adil@ppmh.com', '$2y$12$/Ag07Mvn1hy9WjlfcXCy0e6sX5iz4FIYnuTA3g7mz.UFLvXbWvljG', NULL, 'pengurus', 'profile.jpg', NULL, NULL, '2026-01-07 09:20:39', '2026-01-16 06:35:52', NULL, NULL),
(16, 'Rino', 'rino@ppmh.com', '$2y$12$3Kgl6kYBd4IbjsMmd1IcXuqqb6BAjLYXgMuognKFE743V2i7z8x5q', NULL, 'pengurus', 'profile.jpg', NULL, NULL, '2026-01-08 14:09:01', '2026-01-16 06:37:06', NULL, NULL),
(17, 'Irham', 'irham@ppmh.com', '$2y$12$ZDqb2h.sl7e3LecUxvE.AeECAJaqti.iFj1olxJ1ERTCezBHBRPIK', 'ppmh1990', 'pengurus', 'profile.jpg', NULL, NULL, '2026-01-08 14:09:27', '2026-01-16 06:36:39', NULL, NULL),
(18, 'Bidin', 'bidin@ppmh.com', '$2y$12$RbZiI/fbxNwE6QUVwhrzA.dJ4UC6kCQwSM/3V43mugZmfxflDLvVe', NULL, 'pengurus', 'profile.jpg', NULL, NULL, '2026-01-08 14:09:41', '2026-01-16 06:36:13', NULL, NULL),
(19, 'Affan', 'affan@ppmh.com', '$2y$12$O5ewIoOqtFZ8TeZMiU7TkelgHffCm62a8.RwS9TO/MTwLh243PZLK', NULL, 'pengurus', 'profile.jpg', NULL, NULL, '2026-01-08 14:11:01', '2026-01-16 06:36:02', NULL, NULL),
(20, 'Nova', 'nova@ppmh.com', '$2y$12$f6SvpCDORbporCwxhbjDRe9u3WufFioarZ/uJrCFywhPbgC/1Aw4G', NULL, 'pengurus', 'profile.jpg', NULL, NULL, '2026-01-21 16:30:31', '2026-01-21 16:30:44', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_devices`
--

CREATE TABLE `user_devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `device_fingerprint` varchar(255) NOT NULL,
  `device_name` varchar(255) DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `user_devices`
--

INSERT INTO `user_devices` (`id`, `user_id`, `device_fingerprint`, `device_name`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 1, '4f16e310-4f16-4f16-af16-4f16e3100000', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-07 09:09:22', '2026-01-05 07:18:29', '2026-02-07 09:09:22'),
(3, 7, '0b6012ee-0b60-4b60-ab60-0b6012ee0000', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-06 15:20:13', '2026-01-07 09:16:44', '2026-02-06 15:20:13'),
(4, 19, '6f846f5d-6f84-4f84-af84-6f846f5d0000', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', '2026-01-29 11:22:42', '2026-01-09 05:59:33', '2026-01-29 11:22:42'),
(5, 8, '373ad48a-373a-473a-a73a-373ad48a0000', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.6312.118 Mobile Safari/537.36 XiaoMi/MiuiBrowser/14.22.1-gn', '2026-01-09 06:00:51', '2026-01-09 06:00:51', '2026-01-09 06:00:51'),
(6, 9, '4f413135-4f41-4f41-af41-4f4131350000', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Mobile Safari/537.36', '2026-02-03 21:24:30', '2026-01-10 05:58:37', '2026-02-03 21:24:30'),
(7, 14, '105a893a-105a-405a-a05a-105a893a0000', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2026-01-21 15:48:48', '2026-01-12 20:28:21', '2026-01-21 15:48:48'),
(8, 15, '345ee170-345e-445e-a45e-345ee1700000', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2026-01-21 16:18:18', '2026-01-21 15:48:47', '2026-01-21 16:18:18'),
(9, 11, '58cfef6c-58cf-48cf-a8cf-58cfef6c0000', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-01-30 03:17:07', '2026-01-21 15:50:40', '2026-01-30 03:17:07'),
(10, 20, '2a1c2f36-2a1c-4a1c-aa1c-2a1c2f360000', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2026-01-21 22:15:22', '2026-01-21 22:15:22', '2026-01-21 22:15:22'),
(11, 10, '21293332-2129-4129-a129-212933320000', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', '2026-02-06 03:11:01', '2026-02-03 16:11:19', '2026-02-06 03:11:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `idx_user_id` (`user_id`) USING BTREE,
  ADD KEY `idx_action` (`action`) USING BTREE,
  ADD KEY `idx_table_name` (`table_name`) USING BTREE,
  ADD KEY `idx_created_at` (`created_at`) USING BTREE;

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `jadwal_id` (`jadwal_id`) USING BTREE,
  ADD KEY `attendances_fk_santri` (`user_id`) USING BTREE;

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `catatan_aktivitas`
--
ALTER TABLE `catatan_aktivitas`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `dibuat_oleh` (`dibuat_oleh`) USING BTREE,
  ADD KEY `catatan_aktivitas_fk_santri` (`siswa_id`) USING BTREE;

--
-- Indexes for table `data_induk`
--
ALTER TABLE `data_induk`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `idx_nama` (`nama_lengkap`) USING BTREE,
  ADD KEY `idx_nisn` (`nisn`) USING BTREE,
  ADD KEY `idx_nik` (`nik`) USING BTREE,
  ADD KEY `idx_kelas` (`kelas`) USING BTREE,
  ADD KEY `idx_status` (`status`) USING BTREE,
  ADD KEY `idx_rfid` (`nomor_rfid`) USING BTREE;

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jadwal_absens`
--
ALTER TABLE `jadwal_absens`
  ADD PRIMARY KEY (`id`) USING BTREE;

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
-- Indexes for table `print_izin_history`
--
ALTER TABLE `print_izin_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `printed_by` (`printed_by`),
  ADD KEY `idx_nomor_surat` (`nomor_surat`),
  ADD KEY `idx_printed_at` (`printed_at`);

--
-- Indexes for table `print_queue`
--
ALTER TABLE `print_queue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_token_hash` (`token_hash`),
  ADD KEY `idx_expires_at` (`expires_at`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `key` (`key`) USING BTREE;

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `setting_key` (`setting_key`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `email` (`email`) USING BTREE;

--
-- Indexes for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=338;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `catatan_aktivitas`
--
ALTER TABLE `catatan_aktivitas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `data_induk`
--
ALTER TABLE `data_induk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_absens`
--
ALTER TABLE `jadwal_absens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `print_izin_history`
--
ALTER TABLE `print_izin_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `print_queue`
--
ALTER TABLE `print_queue`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_devices`
--
ALTER TABLE `user_devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_fk_santri` FOREIGN KEY (`user_id`) REFERENCES `data_induk` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_ibfk_2` FOREIGN KEY (`jadwal_id`) REFERENCES `jadwal_absens` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `catatan_aktivitas`
--
ALTER TABLE `catatan_aktivitas`
  ADD CONSTRAINT `catatan_aktivitas_fk_santri` FOREIGN KEY (`siswa_id`) REFERENCES `data_induk` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `catatan_aktivitas_ibfk_2` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `print_izin_history`
--
ALTER TABLE `print_izin_history`
  ADD CONSTRAINT `print_izin_history_ibfk_1` FOREIGN KEY (`printed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `print_queue`
--
ALTER TABLE `print_queue`
  ADD CONSTRAINT `print_queue_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `remember_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD CONSTRAINT `user_devices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
