/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.16-MariaDB, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: db_dms
-- ------------------------------------------------------
-- Server version	10.11.16-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
INSERT INTO `activity_log` VALUES
(1,'document','Dokumen \"test nama sk\" ditambahkan','App\\Models\\Document','created',1,'App\\Models\\User',1,'{\"attributes\":{\"title\":\"test nama sk\",\"doc_number\":\"SK-0001\\/07\\/2026\",\"status\":\"active\",\"document_type_id\":1,\"document_category_id\":7}}',NULL,'2026-07-02 01:05:40','2026-07-02 01:05:40'),
(2,'default','Logout: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\"}',NULL,'2026-07-02 01:06:06','2026-07-02 01:06:06'),
(3,'default','Logout: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\"}',NULL,'2026-07-02 01:06:06','2026-07-02 01:06:06'),
(4,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-02 20:39:30','2026-07-02 20:39:30'),
(5,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-02 20:39:30','2026-07-02 20:39:30'),
(6,'document','Dokumen \"Installasi Server\" ditambahkan','App\\Models\\Document','created',2,'App\\Models\\User',1,'{\"attributes\":{\"title\":\"Installasi Server\",\"doc_number\":\"SOP-0001\\/07\\/2026\",\"status\":\"active\",\"document_type_id\":2,\"document_category_id\":15}}',NULL,'2026-07-02 20:45:49','2026-07-02 20:45:49'),
(7,'default','Logout: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\"}',NULL,'2026-07-02 23:52:36','2026-07-02 23:52:36'),
(8,'default','Logout: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\"}',NULL,'2026-07-02 23:52:36','2026-07-02 23:52:36'),
(9,'default','Login berhasil: tommy',NULL,NULL,NULL,'App\\Models\\User',2,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-02 23:52:42','2026-07-02 23:52:42'),
(10,'default','Login berhasil: tommy',NULL,NULL,NULL,'App\\Models\\User',2,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-02 23:52:42','2026-07-02 23:52:42'),
(11,'document','Dokumen \"test contoh ruk\" ditambahkan','App\\Models\\Document','created',3,'App\\Models\\User',2,'{\"attributes\":{\"title\":\"test contoh ruk\",\"doc_number\":\"RUK-0001\\/07\\/2026\",\"status\":\"active\",\"document_type_id\":3,\"document_category_id\":null}}',NULL,'2026-07-02 23:53:42','2026-07-02 23:53:42'),
(12,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64; rv:151.0) Gecko\\/20100101 Firefox\\/151.0\"}',NULL,'2026-07-04 23:55:00','2026-07-04 23:55:00'),
(13,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64; rv:151.0) Gecko\\/20100101 Firefox\\/151.0\"}',NULL,'2026-07-04 23:55:00','2026-07-04 23:55:00'),
(14,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64; rv:151.0) Gecko\\/20100101 Firefox\\/151.0\"}',NULL,'2026-07-05 06:02:46','2026-07-05 06:02:46'),
(15,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64; rv:151.0) Gecko\\/20100101 Firefox\\/151.0\"}',NULL,'2026-07-05 06:02:46','2026-07-05 06:02:46'),
(16,'document','Dokumen \"test upload\" ditambahkan','App\\Models\\Document','created',4,'App\\Models\\User',1,'{\"attributes\":{\"title\":\"test upload\",\"doc_number\":\"SOP-0002\\/07\\/2026\",\"status\":\"active\",\"document_type_id\":2,\"document_category_id\":1}}',NULL,'2026-07-05 06:34:14','2026-07-05 06:34:14'),
(17,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-05 07:00:45','2026-07-05 07:00:45'),
(18,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-05 07:00:45','2026-07-05 07:00:45'),
(19,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-10 06:25:11','2026-07-10 06:25:11'),
(20,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-10 06:25:11','2026-07-10 06:25:11'),
(21,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-11 21:48:07','2026-07-11 21:48:07'),
(22,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-11 21:48:07','2026-07-11 21:48:07'),
(23,'default','Logout: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\"}',NULL,'2026-07-11 22:07:50','2026-07-11 22:07:50'),
(24,'default','Logout: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\"}',NULL,'2026-07-11 22:07:50','2026-07-11 22:07:50'),
(25,'default','Login berhasil: tommy',NULL,NULL,NULL,'App\\Models\\User',2,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-11 22:07:56','2026-07-11 22:07:56'),
(26,'default','Login berhasil: tommy',NULL,NULL,NULL,'App\\Models\\User',2,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-11 22:07:56','2026-07-11 22:07:56'),
(27,'default','Logout: tommy',NULL,NULL,NULL,'App\\Models\\User',2,'{\"ip\":\"127.0.0.1\"}',NULL,'2026-07-11 22:18:16','2026-07-11 22:18:16'),
(28,'default','Logout: tommy',NULL,NULL,NULL,'App\\Models\\User',2,'{\"ip\":\"127.0.0.1\"}',NULL,'2026-07-11 22:18:16','2026-07-11 22:18:16'),
(29,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-11 22:18:23','2026-07-11 22:18:23'),
(30,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-11 22:18:23','2026-07-11 22:18:23'),
(31,'default','Logout: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\"}',NULL,'2026-07-11 22:19:07','2026-07-11 22:19:07'),
(32,'default','Logout: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\"}',NULL,'2026-07-11 22:19:07','2026-07-11 22:19:07'),
(33,'default','Percobaan login gagal',NULL,NULL,NULL,NULL,NULL,'{\"ip\":\"127.0.0.1\",\"email\":\"tommy@mail.com\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-11 22:19:11','2026-07-11 22:19:11'),
(34,'default','Percobaan login gagal',NULL,NULL,NULL,NULL,NULL,'{\"ip\":\"127.0.0.1\",\"email\":\"tommy@mail.com\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-11 22:19:12','2026-07-11 22:19:12'),
(35,'default','Login berhasil: tommy',NULL,NULL,NULL,'App\\Models\\User',2,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-11 22:19:16','2026-07-11 22:19:16'),
(36,'default','Login berhasil: tommy',NULL,NULL,NULL,'App\\Models\\User',2,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/149.0.0.0 Safari\\/537.36\"}',NULL,'2026-07-11 22:19:16','2026-07-11 22:19:16'),
(37,'document','Dokumen \"test upload\" dihapus','App\\Models\\Document','deleted',4,'App\\Models\\User',2,'{\"old\":{\"title\":\"test upload\",\"doc_number\":\"SOP-0002\\/07\\/2026\",\"status\":\"active\",\"document_type_id\":2,\"document_category_id\":1}}',NULL,'2026-07-11 22:19:35','2026-07-11 22:19:35'),
(38,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64; rv:151.0) Gecko\\/20100101 Firefox\\/151.0\"}',NULL,'2026-07-11 22:20:02','2026-07-11 22:20:02'),
(39,'default','Login berhasil: admin',NULL,NULL,NULL,'App\\Models\\User',1,'{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (X11; Linux x86_64; rv:151.0) Gecko\\/20100101 Firefox\\/151.0\"}',NULL,'2026-07-11 22:20:02','2026-07-11 22:20:02');
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agenda`
--

DROP TABLE IF EXISTS `agenda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `agenda` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `waktu` timestamp NOT NULL,
  `tempat` varchar(255) DEFAULT NULL,
  `dokumen_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agenda`
--

LOCK TABLES `agenda` WRITE;
/*!40000 ALTER TABLE `agenda` DISABLE KEYS */;
/*!40000 ALTER TABLE `agenda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `agenda_worker`
--

DROP TABLE IF EXISTS `agenda_worker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `agenda_worker` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `agenda_id` bigint(20) unsigned NOT NULL,
  `worker_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `agenda_worker_agenda_id_foreign` (`agenda_id`),
  KEY `agenda_worker_worker_id_foreign` (`worker_id`),
  CONSTRAINT `agenda_worker_agenda_id_foreign` FOREIGN KEY (`agenda_id`) REFERENCES `agenda` (`id`) ON DELETE CASCADE,
  CONSTRAINT `agenda_worker_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agenda_worker`
--

LOCK TABLES `agenda_worker` WRITE;
/*!40000 ALTER TABLE `agenda_worker` DISABLE KEYS */;
/*!40000 ALTER TABLE `agenda_worker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES
('livewire-rate-limiter:056fc329aaaa757d31db450f525da23fde4d1b36','i:1;',1783833662),
('livewire-rate-limiter:056fc329aaaa757d31db450f525da23fde4d1b36:timer','i:1783833662;',1783833662);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disposisi`
--

DROP TABLE IF EXISTS `disposisi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `disposisi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `surat_masuk_id` bigint(20) unsigned NOT NULL,
  `dari_worker_id` bigint(20) unsigned DEFAULT NULL,
  `ke_worker_id` bigint(20) unsigned NOT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('pending','selesai') NOT NULL DEFAULT 'pending',
  `dibaca` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disposisi_surat_masuk_id_foreign` (`surat_masuk_id`),
  KEY `disposisi_dari_worker_id_foreign` (`dari_worker_id`),
  KEY `disposisi_ke_worker_id_foreign` (`ke_worker_id`),
  CONSTRAINT `disposisi_dari_worker_id_foreign` FOREIGN KEY (`dari_worker_id`) REFERENCES `workers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `disposisi_ke_worker_id_foreign` FOREIGN KEY (`ke_worker_id`) REFERENCES `workers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `disposisi_surat_masuk_id_foreign` FOREIGN KEY (`surat_masuk_id`) REFERENCES `surat_masuk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disposisi`
--

LOCK TABLES `disposisi` WRITE;
/*!40000 ALTER TABLE `disposisi` DISABLE KEYS */;
/*!40000 ALTER TABLE `disposisi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_categories`
--

DROP TABLE IF EXISTS `document_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL COMMENT 'Kode unik kategori, mis: KEUANGAN, SDM, UMUM',
  `name` varchar(100) NOT NULL COMMENT 'Nama kategori',
  `description` text DEFAULT NULL,
  `document_type_id` bigint(20) unsigned DEFAULT NULL,
  `color` varchar(7) DEFAULT NULL COMMENT 'Warna HEX untuk badge, mis: #3B82F6',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_categories_code_unique` (`code`),
  KEY `document_categories_document_type_id_index` (`document_type_id`),
  KEY `document_categories_is_active_index` (`is_active`),
  CONSTRAINT `document_categories_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_categories`
--

LOCK TABLES `document_categories` WRITE;
/*!40000 ALTER TABLE `document_categories` DISABLE KEYS */;
INSERT INTO `document_categories` VALUES
(1,'UMUM','Umum',NULL,NULL,'#6B7280',1,10,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(2,'KEUANGAN','Keuangan',NULL,NULL,'#F59E0B',1,20,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(3,'SDM','Sumber Daya Manusia',NULL,NULL,'#3B82F6',1,30,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(4,'ASET','Aset & Logistik',NULL,NULL,'#8B5CF6',1,40,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(5,'PROG','Program & Kegiatan',NULL,NULL,'#10B981',1,50,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(6,'MUTU','Mutu & Akreditasi',NULL,NULL,'#EC4899',1,60,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(7,'SK-JABATAN','SK Jabatan',NULL,1,'#EF4444',1,100,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(8,'SK-TUGAS','SK Tugas',NULL,1,'#F97316',1,110,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(9,'SK-SANKSI','SK Sanksi',NULL,1,'#DC2626',1,120,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(10,'SK-UMK','SK UMK / Insentif',NULL,1,'#16A34A',1,130,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(11,'SOP-KLINIS','SOP Klinis',NULL,2,'#0EA5E9',1,200,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(12,'SOP-ADMIN','SOP Administrasi',NULL,2,'#7C3AED',1,210,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(13,'SOP-FARMASI','SOP Farmasi',NULL,2,'#059669',1,220,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(14,'SOP-LABORAT','SOP Laboratorium',NULL,2,'#D97706',1,230,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(15,'KTR01','SOP Installasi',NULL,2,'#028dc4',1,0,NULL,'2026-07-02 20:44:44','2026-07-05 07:13:24');
/*!40000 ALTER TABLE `document_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_files`
--

DROP TABLE IF EXISTS `document_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned NOT NULL,
  `file_name` varchar(255) NOT NULL COMMENT 'Nama file asli saat upload',
  `file_path` varchar(500) NOT NULL COMMENT 'Path file yang tersimpan di storage disk',
  `file_size` bigint(20) unsigned DEFAULT NULL COMMENT 'Ukuran file dalam bytes',
  `mime_type` varchar(100) DEFAULT NULL COMMENT 'MIME type, mis: application/pdf, image/jpeg',
  `disk` varchar(50) NOT NULL DEFAULT 'public' COMMENT 'Storage disk: public, s3, dll',
  `file_hash` varchar(64) DEFAULT NULL COMMENT 'SHA-256 hash untuk verifikasi integritas file',
  `is_primary` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'File utama/induk dokumen (hanya satu per dokumen)',
  `file_type` enum('original','signed','attachment','draft') NOT NULL DEFAULT 'original' COMMENT 'Jenis file: original, signed, attachment, draft',
  `uploaded_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_files_uploaded_by_foreign` (`uploaded_by`),
  KEY `document_files_document_id_index` (`document_id`),
  KEY `document_files_is_primary_index` (`is_primary`),
  KEY `document_files_file_type_index` (`file_type`),
  KEY `document_files_file_hash_index` (`file_hash`),
  KEY `idx_document_primary` (`document_id`,`is_primary`),
  KEY `idx_document_file_type` (`document_id`,`file_type`),
  CONSTRAINT `document_files_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `document_files_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_files`
--

LOCK TABLES `document_files` WRITE;
/*!40000 ALTER TABLE `document_files` DISABLE KEYS */;
INSERT INTO `document_files` VALUES
(1,1,'2704107.pdf','documents/sk/2026/01KWGXSDNB4AMGX8DVPCVENYC7.pdf',NULL,NULL,'public',NULL,1,'original',1,'2026-07-02 01:05:40','2026-07-02 01:05:40'),
(2,2,'Instalasi Server 22.04.docx','documents/sop/2026/01KWK1AAX1HXMVJ0J60QPPP868.docx',NULL,NULL,'public',NULL,1,'original',1,'2026-07-02 20:45:49','2026-07-02 20:45:49'),
(3,3,'signed_soap_rm_765947_edc8da1c681d47c4ae2048fcf1eb7cbc-38.pdf','documents/ruk/2026/01KWS7ANJ8K0A1NJAE39JZ59F3.pdf',NULL,NULL,'public',NULL,1,'original',2,'2026-07-02 23:53:42','2026-07-05 06:26:18'),
(4,1,'DomaiNesia - Invoice #2199885.pdf','documents/sk/2026/01KWS629P69WDG3RZQSR7GGVWX.pdf',NULL,NULL,'public',NULL,0,'original',1,'2026-07-05 06:04:15','2026-07-05 06:04:15'),
(5,4,'PETUNJUK TEKNIS DFO 1.pdf','documents/sop/2026/01KWS7S6SVV1CHH2F40638D3BE.pdf',NULL,NULL,'public',NULL,1,'original',1,'2026-07-05 06:34:14','2026-07-05 06:34:14');
/*!40000 ALTER TABLE `document_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_types`
--

DROP TABLE IF EXISTS `document_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL COMMENT 'Kode singkat: SM, SK-OUT, SK, SOP, DL',
  `name` varchar(100) NOT NULL COMMENT 'Nama lengkap jenis dokumen',
  `description` text DEFAULT NULL,
  `prefix` varchar(10) NOT NULL COMMENT 'Prefix kode dokumen: SM-, SK-, SOP-, DL-',
  `numbering_format` varchar(100) NOT NULL DEFAULT '{prefix}{seq}/{mm}/{yyyy}' COMMENT 'Format penomoran: {prefix}, {seq}, {dd}, {mm}, {yyyy}',
  `seq_length` tinyint(3) unsigned NOT NULL DEFAULT 4 COMMENT 'Panjang padding angka urut, mis: 4 → 0001',
  `has_detail` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Apakah jenis ini punya tabel detail (sk_details, sop_details)',
  `detail_table` varchar(100) DEFAULT NULL COMMENT 'Nama tabel detail yang digunakan, mis: sk_details',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_types_code_unique` (`code`),
  KEY `document_types_is_active_index` (`is_active`),
  KEY `document_types_code_index` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_types`
--

LOCK TABLES `document_types` WRITE;
/*!40000 ALTER TABLE `document_types` DISABLE KEYS */;
INSERT INTO `document_types` VALUES
(1,'SK','Surat Keputusan (SK)',NULL,'SK-','{prefix}{seq}/{mm}/{yyyy}',4,1,'sk_details',1,0,NULL,'2026-07-01 21:19:55','2026-07-01 21:19:55'),
(2,'SOP','Standar Operasional Prosedur (SOP)',NULL,'SOP-','{prefix}{seq}/{mm}/{yyyy}',4,1,'sop_details',1,0,NULL,'2026-07-01 21:19:55','2026-07-01 21:19:55'),
(3,'RUK','Rencana Usulan Kegiatan (RUK)',NULL,'RUK-','{prefix}{seq}/{mm}/{yyyy}',4,0,NULL,1,0,NULL,'2026-07-01 21:19:55','2026-07-01 21:19:55'),
(4,'RPK','Rencana Pelaksanaan Kegiatan (RPK)',NULL,'RPK-','{prefix}{seq}/{mm}/{yyyy}',4,0,NULL,1,0,NULL,'2026-07-01 21:19:55','2026-07-01 21:19:55'),
(5,'MG','Magang','Dokumen Magang','MG-','{prefix}{seq}/{mm}/{yyyy}',4,0,NULL,1,0,'2026-07-05 00:11:48','2026-07-05 00:08:41','2026-07-05 00:11:48');
/*!40000 ALTER TABLE `document_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `doc_number` varchar(100) NOT NULL COMMENT 'Nomor dokumen auto-generate, mis: SM-0001/07/2026',
  `reference_number` varchar(100) DEFAULT NULL COMMENT 'Nomor referensi asli dari pengirim/penerima',
  `title` varchar(255) NOT NULL COMMENT 'Judul dokumen',
  `subject` varchar(255) DEFAULT NULL COMMENT 'Perihal',
  `document_type_id` bigint(20) unsigned NOT NULL,
  `document_category_id` bigint(20) unsigned DEFAULT NULL,
  `direction` enum('incoming','outgoing','internal') NOT NULL COMMENT 'Arah: incoming=masuk, outgoing=keluar, internal=internal',
  `source_type` enum('unit','external') NOT NULL DEFAULT 'external' COMMENT 'Tipe asal: unit (internal) atau external',
  `source_unit_id` bigint(20) unsigned DEFAULT NULL,
  `source_name` varchar(255) DEFAULT NULL COMMENT 'Nama asal jika source_type=external (instansi/orang)',
  `destination_type` enum('unit','external','person') NOT NULL DEFAULT 'unit' COMMENT 'Tipe tujuan',
  `destination_unit_id` bigint(20) unsigned DEFAULT NULL,
  `destination_name` varchar(255) DEFAULT NULL COMMENT 'Nama tujuan jika external atau person',
  `document_date` date NOT NULL COMMENT 'Tanggal tertera pada dokumen',
  `received_date` date DEFAULT NULL COMMENT 'Tanggal diterima (untuk incoming)',
  `issued_date` date DEFAULT NULL COMMENT 'Tanggal dikeluarkan/dikirim (untuk outgoing)',
  `status` enum('draft','active','archived','void') NOT NULL DEFAULT 'draft' COMMENT 'Status dokumen',
  `notes` text DEFAULT NULL COMMENT 'Keterangan tambahan',
  `created_by` bigint(20) unsigned NOT NULL,
  `updated_by` bigint(20) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `documents_doc_number_unique` (`doc_number`),
  KEY `documents_updated_by_foreign` (`updated_by`),
  KEY `documents_document_type_id_index` (`document_type_id`),
  KEY `documents_document_category_id_index` (`document_category_id`),
  KEY `documents_direction_index` (`direction`),
  KEY `documents_status_index` (`status`),
  KEY `documents_document_date_index` (`document_date`),
  KEY `documents_received_date_index` (`received_date`),
  KEY `documents_issued_date_index` (`issued_date`),
  KEY `documents_source_unit_id_index` (`source_unit_id`),
  KEY `documents_destination_unit_id_index` (`destination_unit_id`),
  KEY `documents_created_by_index` (`created_by`),
  KEY `documents_deleted_at_index` (`deleted_at`),
  KEY `idx_direction_status` (`direction`,`status`),
  KEY `idx_type_direction` (`document_type_id`,`direction`),
  KEY `idx_type_status` (`document_type_id`,`status`),
  KEY `idx_source` (`source_type`,`source_unit_id`),
  KEY `idx_destination` (`destination_type`,`destination_unit_id`),
  CONSTRAINT `documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `documents_destination_unit_id_foreign` FOREIGN KEY (`destination_unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  CONSTRAINT `documents_document_category_id_foreign` FOREIGN KEY (`document_category_id`) REFERENCES `document_categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `documents_document_type_id_foreign` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`),
  CONSTRAINT `documents_source_unit_id_foreign` FOREIGN KEY (`source_unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  CONSTRAINT `documents_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES
(1,'SK-0001/07/2026',NULL,'test nama sk',NULL,1,7,'internal','external',NULL,NULL,'unit',NULL,NULL,'2026-07-02',NULL,NULL,'active','dadasdadsadsa',1,NULL,NULL,'2026-07-02 01:05:40','2026-07-02 01:05:40'),
(2,'SOP-0001/07/2026',NULL,'Installasi Server',NULL,2,15,'internal','external',NULL,NULL,'unit',NULL,NULL,'2026-07-03',NULL,NULL,'active','panduan installasi server ubuntu 22.04LTS',1,NULL,NULL,'2026-07-02 20:45:49','2026-07-02 20:45:49'),
(3,'RUK-0001/07/2026',NULL,'test contoh ruk',NULL,3,NULL,'internal','external',NULL,NULL,'unit',NULL,NULL,'2026-07-03',NULL,NULL,'active','ini hanya contoh ruk',2,NULL,NULL,'2026-07-02 23:53:42','2026-07-02 23:53:42'),
(4,'SOP-0002/07/2026',NULL,'test upload',NULL,2,1,'internal','external',NULL,NULL,'unit',NULL,NULL,'2026-07-05',NULL,NULL,'active','kolom keterangan ',1,NULL,'2026-07-11 22:19:35','2026-07-05 06:34:14','2026-07-11 22:19:35');
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dokumen_lain`
--

DROP TABLE IF EXISTS `dokumen_lain`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `dokumen_lain` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_dokumen` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori_id` bigint(20) unsigned DEFAULT NULL,
  `sumber_id` bigint(20) unsigned DEFAULT NULL,
  `tanggal_dokumen` date NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_original` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dokumen_lain_kode_dokumen_unique` (`kode_dokumen`),
  KEY `dokumen_lain_kategori_id_foreign` (`kategori_id`),
  KEY `dokumen_lain_sumber_id_foreign` (`sumber_id`),
  CONSTRAINT `dokumen_lain_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_dokumen` (`id`) ON DELETE SET NULL,
  CONSTRAINT `dokumen_lain_sumber_id_foreign` FOREIGN KEY (`sumber_id`) REFERENCES `sumber_dokumen` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dokumen_lain`
--

LOCK TABLES `dokumen_lain` WRITE;
/*!40000 ALTER TABLE `dokumen_lain` DISABLE KEYS */;
/*!40000 ALTER TABLE `dokumen_lain` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori_dokumen`
--

DROP TABLE IF EXISTS `kategori_dokumen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori_dokumen` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_dokumen`
--

LOCK TABLES `kategori_dokumen` WRITE;
/*!40000 ALTER TABLE `kategori_dokumen` DISABLE KEYS */;
/*!40000 ALTER TABLE `kategori_dokumen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_04_30_151232_create_kategori_dokumen_table',1),
(5,'2025_05_02_011323_create_sumber_dokumen_table',1),
(6,'2025_05_02_064546_create_workers_table',1),
(7,'2025_05_02_143903_create_surat_masuk_table',1),
(8,'2025_05_03_031154_create_surat_keluar_table',1),
(9,'2025_05_03_062039_create_dokumen_lain_table',1),
(10,'2025_05_03_065023_create_disposisi_table',1),
(11,'2025_05_03_081941_add_worker_id_to_users_table',1),
(12,'2025_05_03_085723_add_role_to_users_table',1),
(13,'2025_05_04_072519_create_agenda_table',1),
(14,'2025_05_14_122511_add_deleted_at_to_surat_masuk_table',1),
(15,'2025_05_18_053047_add_deleted_at_to_surat_keluar_table',1),
(16,'2025_12_08_013746_create_roles_table',1),
(17,'2026_07_02_000001_create_units_table',1),
(18,'2026_07_02_000002_create_permissions_table',1),
(19,'2026_07_02_000003_refactor_roles_add_columns',1),
(20,'2026_07_02_000004_refactor_users_add_unit_and_role_id',1),
(21,'2026_07_02_000005_create_document_types_table',1),
(22,'2026_07_02_000006_create_document_categories_table',1),
(23,'2026_07_02_000007_create_documents_table',1),
(24,'2026_07_02_000008_create_sk_details_table',1),
(25,'2026_07_02_000009_create_sop_details_table',1),
(26,'2026_07_02_000010_create_document_files_table',1),
(27,'2026_07_02_053302_add_soft_deletes_to_master_tables',2),
(28,'2026_07_02_060030_create_activity_log_table',3),
(29,'2026_07_02_060031_add_event_column_to_activity_log_table',3),
(30,'2026_07_02_060032_add_batch_uuid_column_to_activity_log_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Slug permission, mis: document.create, document.view-any',
  `display_name` varchar(100) NOT NULL COMMENT 'Nama tampilan',
  `group` varchar(50) DEFAULT NULL COMMENT 'Kelompok permission, mis: document, admin, report',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`),
  KEY `permissions_group_index` (`group`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES
(1,'document.view-any','Lihat Semua Dokumen','Dokumen','Melihat daftar dokumen','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(2,'document.view','Lihat Detail Dokumen','Dokumen','Melihat detail dokumen spesifik','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(3,'document.create','Buat Dokumen','Dokumen','Membuat dokumen baru','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(4,'document.update','Ubah Dokumen','Dokumen','Mengubah dokumen','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(5,'document.delete','Hapus Dokumen','Dokumen','Menghapus dokumen','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(6,'unit.view-any','Lihat Semua Unit','Unit Organisasi','Melihat daftar unit organisasi','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(7,'unit.view','Lihat Detail Unit','Unit Organisasi','Melihat detail unit organisasi','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(8,'unit.create','Buat Unit','Unit Organisasi','Membuat unit organisasi baru','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(9,'unit.update','Ubah Unit','Unit Organisasi','Mengubah unit organisasi','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(10,'unit.delete','Hapus Unit','Unit Organisasi','Menghapus unit organisasi','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(11,'document-type.view-any','Lihat Semua Jenis Dokumen','Jenis Dokumen','Melihat daftar jenis dokumen','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(12,'document-type.view','Lihat Detail Jenis Dokumen','Jenis Dokumen','Melihat detail jenis dokumen','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(13,'document-type.create','Buat Jenis Dokumen','Jenis Dokumen','Membuat jenis dokumen baru','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(14,'document-type.update','Ubah Jenis Dokumen','Jenis Dokumen','Mengubah jenis dokumen','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(15,'document-type.delete','Hapus Jenis Dokumen','Jenis Dokumen','Menghapus jenis dokumen','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(16,'document-category.view-any','Lihat Semua Kategori Dokumen','Kategori Dokumen','Melihat daftar kategori dokumen','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(17,'document-category.view','Lihat Detail Kategori Dokumen','Kategori Dokumen','Melihat detail kategori dokumen','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(18,'document-category.create','Buat Kategori Dokumen','Kategori Dokumen','Membuat kategori dokumen baru','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(19,'document-category.update','Ubah Kategori Dokumen','Kategori Dokumen','Mengubah kategori dokumen','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(20,'document-category.delete','Hapus Kategori Dokumen','Kategori Dokumen','Menghapus kategori dokumen','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(21,'user.view-any','Lihat Semua Pengguna','Pengguna','Melihat daftar pengguna','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(22,'user.view','Lihat Detail Pengguna','Pengguna','Melihat detail pengguna','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(23,'user.create','Buat Pengguna','Pengguna','Membuat pengguna baru','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(24,'user.update','Ubah Pengguna','Pengguna','Mengubah data pengguna','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(25,'user.delete','Hapus Pengguna','Pengguna','Menghapus pengguna','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(26,'role.view-any','Lihat Semua Peran','Peran & Izin','Melihat daftar peran pengguna','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(27,'role.view','Lihat Detail Peran','Peran & Izin','Melihat detail peran pengguna','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(28,'role.create','Buat Peran','Peran & Izin','Membuat peran baru','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(29,'role.update','Ubah Peran','Peran & Izin','Mengubah peran dan izin','2026-07-01 21:41:45','2026-07-01 21:41:45'),
(30,'role.delete','Hapus Peran','Peran & Izin','Menghapus peran','2026-07-01 21:41:45','2026-07-01 21:41:45');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permission`
--

DROP TABLE IF EXISTS `role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permission` (
  `role_id` bigint(20) unsigned NOT NULL,
  `permission_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `role_permission_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permission`
--

LOCK TABLES `role_permission` WRITE;
/*!40000 ALTER TABLE `role_permission` DISABLE KEYS */;
INSERT INTO `role_permission` VALUES
(1,1),
(1,2),
(1,3),
(1,4),
(1,5),
(1,6),
(1,7),
(1,8),
(1,9),
(1,10),
(1,11),
(1,12),
(1,13),
(1,14),
(1,15),
(1,16),
(1,17),
(1,18),
(1,19),
(1,20),
(1,21),
(1,22),
(1,23),
(1,24),
(1,25),
(1,26),
(1,27),
(1,28),
(1,29),
(1,30),
(2,1),
(2,2),
(3,1),
(3,2),
(3,3),
(3,4),
(4,1),
(4,2),
(4,3);
/*!40000 ALTER TABLE `role_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_nama_unique` (`nama`),
  KEY `roles_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES
(1,'admin','Administrator','Administrator sistem','blue',1,0,'2026-07-01 21:19:54','2026-07-01 21:19:54'),
(2,'pimpinan','Pimpinan','Pimpinan organisasi','green',1,0,'2026-07-01 21:19:55','2026-07-01 21:19:55'),
(3,'tu','Tata Usaha','Tata Usaha','yellow',1,0,'2026-07-01 21:19:55','2026-07-01 21:19:55'),
(4,'karyawan','Karyawan','Karyawan','gray',1,0,'2026-07-01 21:19:55','2026-07-01 21:19:55');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('GRYM4d7knSKgeuLpEiygCPaAKUPQMQMrk4J5vxHX',NULL,'127.0.0.1','Symfony','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaDlwYXFxdld0V2dWTENTNEt5cUoxbkJvamhLblVaVTBSVlgzWkxuRyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoxNjoiaHR0cDovL2xvY2FsaG9zdCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjE2OiJodHRwOi8vbG9jYWxob3N0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1783831795),
('lSncwN3ynx2hWLrwLBex1SAtbw2TPZ6ssVC3UTeH',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:151.0) Gecko/20100101 Firefox/151.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiWU5zVEV4b2dydlFzMk5Pb2dURzc1dG42NXZTV2dmZ01aa0M0TjBKYyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM0OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWN0aXZpdHktbG9nIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJGsxTG5jTG9yb01RcXRIVGJIVXBFTy5rbktNci51SFg3VUpReEFMdnNxS1lBMFZVcGs0alZlIjt9',1783834858),
('mF089CxNI65QAZq2iTu3SCxQUWkyZE6L0pklHpdx',2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiMzZSRUlNa2pyZWNHRVFJbHVnYUdOWWs3dlRGUFZ3QlMwNnlPd0pKaiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zb3AiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkNW9sSXpBTWVSVkRjOU8wRXFHbks2dTROUk45WU1hLmZ1dEw1dzg1a01FbktFZzhZLlNOWE8iO3M6ODoiZmlsYW1lbnQiO2E6MDp7fX0=',1783833582);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sk_details`
--

DROP TABLE IF EXISTS `sk_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sk_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned NOT NULL,
  `effective_date` date NOT NULL COMMENT 'Tanggal mulai berlaku SK',
  `expiry_date` date DEFAULT NULL COMMENT 'Tanggal berakhir SK (null = tidak terbatas)',
  `decree_type` enum('pengangkatan','pemberhentian','penugasan','penetapan','kebijakan','peraturan','lainnya') NOT NULL DEFAULT 'penetapan' COMMENT 'Jenis keputusan',
  `decree_scope` enum('internal','external') NOT NULL DEFAULT 'internal' COMMENT 'Ruang lingkup SK',
  `regarding` text DEFAULT NULL COMMENT 'Tentang / isi pokok SK',
  `consideration` text DEFAULT NULL COMMENT 'Menimbang / Mengingat',
  `signer_name` varchar(100) DEFAULT NULL COMMENT 'Nama penandatangan',
  `signer_position` varchar(100) DEFAULT NULL COMMENT 'Jabatan penandatangan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sk_details_document_id_unique` (`document_id`),
  KEY `sk_details_effective_date_index` (`effective_date`),
  KEY `sk_details_expiry_date_index` (`expiry_date`),
  KEY `sk_details_decree_type_index` (`decree_type`),
  KEY `sk_details_decree_scope_index` (`decree_scope`),
  CONSTRAINT `sk_details_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sk_details`
--

LOCK TABLES `sk_details` WRITE;
/*!40000 ALTER TABLE `sk_details` DISABLE KEYS */;
INSERT INTO `sk_details` VALUES
(1,1,'2026-07-02','2028-07-05','penetapan','internal','adadsadsad','dsadsadsa','dada','sadasdada','2026-07-02 01:05:40','2026-07-02 01:05:40');
/*!40000 ALTER TABLE `sk_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sop_details`
--

DROP TABLE IF EXISTS `sop_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sop_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` bigint(20) unsigned NOT NULL,
  `version` varchar(20) NOT NULL DEFAULT '1.0' COMMENT 'Versi SOP, mis: 1.0, 2.1',
  `revision_number` smallint(5) unsigned NOT NULL DEFAULT 0 COMMENT 'Nomor revisi ke-N',
  `effective_date` date NOT NULL COMMENT 'Tanggal mulai berlaku SOP',
  `review_date` date DEFAULT NULL COMMENT 'Tanggal review/evaluasi berikutnya',
  `process_owner_unit_id` bigint(20) unsigned DEFAULT NULL,
  `scope` text DEFAULT NULL COMMENT 'Ruang lingkup SOP',
  `purpose` text DEFAULT NULL COMMENT 'Tujuan SOP',
  `reference` text DEFAULT NULL COMMENT 'Referensi/dasar hukum yang digunakan',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sop_details_document_id_unique` (`document_id`),
  KEY `sop_details_effective_date_index` (`effective_date`),
  KEY `sop_details_review_date_index` (`review_date`),
  KEY `sop_details_version_index` (`version`),
  KEY `sop_details_process_owner_unit_id_index` (`process_owner_unit_id`),
  KEY `idx_version_revision` (`version`,`revision_number`),
  CONSTRAINT `sop_details_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sop_details_process_owner_unit_id_foreign` FOREIGN KEY (`process_owner_unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sop_details`
--

LOCK TABLES `sop_details` WRITE;
/*!40000 ALTER TABLE `sop_details` DISABLE KEYS */;
INSERT INTO `sop_details` VALUES
(1,2,'1.0',0,'2026-07-01','2026-07-01',2,NULL,NULL,NULL,'2026-07-02 20:45:49','2026-07-02 20:45:49'),
(2,4,'1.1',0,'2026-07-05',NULL,2,'catatan ruang lingkup','catatan tujuan sop','catatan referensi','2026-07-05 06:34:14','2026-07-05 06:34:14');
/*!40000 ALTER TABLE `sop_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sumber_dokumen`
--

DROP TABLE IF EXISTS `sumber_dokumen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sumber_dokumen` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_sumber` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sumber_dokumen_kode_sumber_unique` (`kode_sumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sumber_dokumen`
--

LOCK TABLES `sumber_dokumen` WRITE;
/*!40000 ALTER TABLE `sumber_dokumen` DISABLE KEYS */;
/*!40000 ALTER TABLE `sumber_dokumen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `surat_keluar`
--

DROP TABLE IF EXISTS `surat_keluar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `surat_keluar` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_surat` varchar(255) DEFAULT NULL,
  `nomor_surat` varchar(255) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `perihal` varchar(255) DEFAULT NULL,
  `kategori_id` bigint(20) unsigned DEFAULT NULL,
  `sumber_id` bigint(20) unsigned DEFAULT NULL,
  `tanggal_surat` date NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `ditujukan` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_original` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `surat_keluar_kategori_id_foreign` (`kategori_id`),
  KEY `surat_keluar_sumber_id_foreign` (`sumber_id`),
  CONSTRAINT `surat_keluar_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_dokumen` (`id`) ON DELETE SET NULL,
  CONSTRAINT `surat_keluar_sumber_id_foreign` FOREIGN KEY (`sumber_id`) REFERENCES `sumber_dokumen` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `surat_keluar`
--

LOCK TABLES `surat_keluar` WRITE;
/*!40000 ALTER TABLE `surat_keluar` DISABLE KEYS */;
/*!40000 ALTER TABLE `surat_keluar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `surat_masuk`
--

DROP TABLE IF EXISTS `surat_masuk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `surat_masuk` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_surat` varchar(255) NOT NULL,
  `nomor_surat` varchar(255) DEFAULT NULL,
  `judul` varchar(255) NOT NULL,
  `perihal` varchar(255) DEFAULT NULL,
  `tanggal_surat` date NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `kategori_id` bigint(20) unsigned DEFAULT NULL,
  `sumber_id` bigint(20) unsigned DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_original` varchar(255) DEFAULT NULL,
  `ditujukan_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `surat_masuk_kode_surat_unique` (`kode_surat`),
  KEY `surat_masuk_kategori_id_foreign` (`kategori_id`),
  KEY `surat_masuk_sumber_id_foreign` (`sumber_id`),
  KEY `surat_masuk_ditujukan_id_foreign` (`ditujukan_id`),
  CONSTRAINT `surat_masuk_ditujukan_id_foreign` FOREIGN KEY (`ditujukan_id`) REFERENCES `workers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `surat_masuk_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori_dokumen` (`id`) ON DELETE SET NULL,
  CONSTRAINT `surat_masuk_sumber_id_foreign` FOREIGN KEY (`sumber_id`) REFERENCES `sumber_dokumen` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `surat_masuk`
--

LOCK TABLES `surat_masuk` WRITE;
/*!40000 ALTER TABLE `surat_masuk` DISABLE KEYS */;
/*!40000 ALTER TABLE `surat_masuk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL COMMENT 'Kode unik unit, mis: DIR-01, DIV-02',
  `name` varchar(150) NOT NULL COMMENT 'Nama lengkap unit',
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `type` enum('lembaga','direktorat','divisi','bagian','seksi','unit') NOT NULL DEFAULT 'unit' COMMENT 'Jenis unit organisasi',
  `head_name` varchar(100) DEFAULT NULL COMMENT 'Nama kepala unit',
  `head_position` varchar(100) DEFAULT NULL COMMENT 'Jabatan kepala unit',
  `phone` varchar(25) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `units_code_unique` (`code`),
  KEY `units_parent_id_index` (`parent_id`),
  KEY `units_type_index` (`type`),
  KEY `units_is_active_index` (`is_active`),
  KEY `units_code_index` (`code`),
  CONSTRAINT `units_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `units` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES
(1,'PUSK','Puskesmas Contoh',NULL,'lembaga','Dr. Kepala','Kepala Puskesmas',NULL,NULL,NULL,1,1,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(2,'TU','Tata Usaha',1,'bagian','Kepala TU','Kepala Tata Usaha',NULL,NULL,NULL,1,10,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(3,'PROM-KES','Promosi Kesehatan',1,'bagian',NULL,NULL,NULL,NULL,NULL,0,20,NULL,'2026-07-01 22:37:34','2026-07-02 23:39:29'),
(4,'GIZI','Gizi',1,'bagian',NULL,NULL,NULL,NULL,NULL,1,30,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(5,'KIA','KIA / KB',1,'bagian',NULL,NULL,NULL,NULL,NULL,1,40,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34'),
(6,'P2P','Pencegahan & Pengendalian Penyakit',1,'bagian',NULL,NULL,NULL,NULL,NULL,1,50,NULL,'2026-07-01 22:37:34','2026-07-01 22:37:34');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `worker_id` bigint(20) unsigned DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'karyawan',
  `unit_id` bigint(20) unsigned DEFAULT NULL,
  `role_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_worker_id_foreign` (`worker_id`),
  KEY `users_unit_id_index` (`unit_id`),
  KEY `users_role_id_index` (`role_id`),
  KEY `users_is_active_index` (`is_active`),
  CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'admin','admin@mail.com',NULL,'$2y$12$k1LncLoroMQqtHTbHUpEO.knKMr.uHX7UJQxALvsqKYA0VUpk4jVe',NULL,'2026-07-01 21:19:55','2026-07-01 22:37:34',NULL,'admin',NULL,1,1),
(2,'tommy','tommy@mail.com',NULL,'$2y$12$5olIzAMeRVDc9O0EqGnK6u4NRN9YMa.futL5w85kMEnKEg8Y.SNXO',NULL,'2026-07-02 23:52:16','2026-07-02 23:52:16',NULL,'karyawan',2,3,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `workers`
--

DROP TABLE IF EXISTS `workers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `workers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_worker` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `workers_kode_worker_unique` (`kode_worker`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `workers`
--

LOCK TABLES `workers` WRITE;
/*!40000 ALTER TABLE `workers` DISABLE KEYS */;
/*!40000 ALTER TABLE `workers` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-15 11:26:19
