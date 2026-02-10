/*
  SAFE PRODUCTION DATABASE UPDATE SCRIPT
  This script uses "IF NOT EXISTS" to safely create tables and add columns
  without dropping any existing data.
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------------------------
-- 1. HELPER PROCEDURES (Created temporarily to allow conditional schema changes)
-- --------------------------------------------------------------------------

DROP PROCEDURE IF EXISTS AddColumnIfNotExist;
DELIMITER //
CREATE PROCEDURE AddColumnIfNotExist(
    IN tableName VARCHAR(64),
    IN columnName VARCHAR(64),
    IN columnDefinition TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT * FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = tableName
        AND COLUMN_NAME = columnName
    ) THEN
        SET @query = CONCAT('ALTER TABLE ', tableName, ' ADD COLUMN ', columnName, ' ', columnDefinition);
        PREPARE stmt FROM @query;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END //
DELIMITER ;

-- --------------------------------------------------------------------------
-- 2. CREATE TABLES (IF NOT EXISTS)
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `activity_logs`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `user_name` varchar(100) NULL,
  `device_name` varchar(100) NULL,
  `ip_address` varchar(45) NULL,
  `action` varchar(50) NOT NULL,
  `table_name` varchar(50) NULL,
  `record_id` int NULL DEFAULT NULL,
  `record_name` varchar(255) NULL,
  `old_data` longtext NULL,
  `new_data` longtext NULL,
  `description` text NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `attendances`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `jadwal_id` bigint UNSIGNED NULL DEFAULT NULL,
  `type` enum('clock_in','clock_out') NULL DEFAULT 'clock_in',
  `attendance_date` date NOT NULL,
  `attendance_time` time NOT NULL,
  `status` enum('hadir','terlambat','absen','izin','sakit','pulang') NULL DEFAULT 'hadir',
  `minutes_late` int NULL DEFAULT 0,
  `notes` text NULL,
  `latitude` varchar(50) NULL,
  `longitude` varchar(50) NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `catatan_aktivitas`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `siswa_id` int NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `judul` varchar(255) NULL,
  `keterangan` text NULL,
  `status_sambangan` varchar(50) NULL,
  `status_kegiatan` varchar(50) NULL,
  `tanggal` datetime NULL,
  `batas_waktu` datetime NULL,
  `tanggal_selesai` datetime NULL,
  `kode_konfirmasi` varchar(10) NULL,
  `foto_dokumen_1` varchar(255) NULL,
  `foto_dokumen_2` varchar(255) NULL,
  `dibuat_oleh` bigint UNSIGNED NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `data_induk`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(255) NOT NULL,
  `nomor_rfid` varchar(50) NULL,
  `kelas` varchar(50) NULL,
  `status` varchar(50) NULL DEFAULT 'AKTIF',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) NULL DEFAULT NULL,
  `user_agent` text NULL,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `sessions_user_id_index`(`user_id`),
  INDEX `sessions_last_activity_index`(`last_activity`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `jobs_queue_index`(`queue`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `print_izin_history`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomor_surat` varchar(50) NOT NULL,
  `kategori` enum('sakit','izin_pulang') NOT NULL,
  `santri_ids` longtext NOT NULL,
  `santri_names` text NULL,
  `tanggal` date NOT NULL,
  `printed_by` bigint UNSIGNED NULL,
  `printed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `print_queue`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `job_type` varchar(50) NOT NULL DEFAULT 'surat_izin',
  `job_data` longtext NOT NULL,
  `status` enum('pending','processing','completed','failed') NULL DEFAULT 'pending',
  `created_by` bigint UNSIGNED NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------------------------
-- 3. ADD MISSING COLUMNS TO EXISTING TABLES
-- --------------------------------------------------------------------------

-- Table: data_induk
CALL AddColumnIfNotExist('data_induk', 'nomor_rfid', "varchar(50) NULL DEFAULT NULL AFTER `no_wa_wali` ");
CALL AddColumnIfNotExist('data_induk', 'foto_santri', "varchar(255) NULL DEFAULT NULL COMMENT 'Path to student photo' ");
CALL AddColumnIfNotExist('data_induk', 'lembaga_sekolah', "varchar(100) NULL DEFAULT NULL ");

-- Table: catatan_aktivitas
CALL AddColumnIfNotExist('catatan_aktivitas', 'kode_konfirmasi', "varchar(10) NULL DEFAULT NULL AFTER `tanggal_selesai` ");
CALL AddColumnIfNotExist('catatan_aktivitas', 'status_sambangan', "varchar(50) NULL DEFAULT NULL ");
CALL AddColumnIfNotExist('catatan_aktivitas', 'status_kegiatan', "varchar(50) NULL DEFAULT NULL ");
CALL AddColumnIfNotExist('catatan_aktivitas', 'batas_waktu', "datetime NULL DEFAULT NULL ");
CALL AddColumnIfNotExist('catatan_aktivitas', 'tanggal_selesai', "datetime NULL DEFAULT NULL ");

-- Table: users
CALL AddColumnIfNotExist('users', 'role', "enum('admin','karyawan','pengurus','guru','keamanan','kesehatan') NOT NULL DEFAULT 'karyawan' ");

-- --------------------------------------------------------------------------
-- 4. CLEANUP & MIGRATIONS LOG
-- --------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP PROCEDURE IF EXISTS AddColumnIfNotExist;
SET FOREIGN_KEY_CHECKS = 1;

-- Update status migrations manual jika perlu
INSERT IGNORE INTO migrations (migration, batch) VALUES ('2026_01_12_000001_create_print_tables', 1);
INSERT IGNORE INTO migrations (migration, batch) VALUES ('2026_02_08_000001_add_kode_konfirmasi_to_catatan_aktivitas', 1);
