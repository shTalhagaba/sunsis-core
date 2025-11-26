-- MySQL dump 10.13  Distrib 8.0.23, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: folio_iwoodtrain
-- ------------------------------------------------------
-- Server version	5.7.24

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `addressable_id` int(11) DEFAULT NULL,
  `addressable_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_2` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_3` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_4` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--

LOCK TABLES `addresses` WRITE;
/*!40000 ALTER TABLE `addresses` DISABLE KEYS */;
INSERT INTO `addresses` VALUES (15,'Home',9,'App\\Models\\User',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-04-11 21:05:45','2025-04-11 21:05:45'),(16,'Work',9,'App\\Models\\User',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-04-11 21:05:45','2025-04-11 21:05:45'),(17,'Home',10,'App\\Models\\User',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-04-12 07:42:34','2025-04-12 07:42:34'),(18,'Work',10,'App\\Models\\User',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-04-12 07:42:34','2025-04-12 07:42:34');
/*!40000 ALTER TABLE `addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `als_review_form_sessions`
--

DROP TABLE IF EXISTS `als_review_form_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `als_review_form_sessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `als_review_id` bigint(20) NOT NULL,
  `session_date` date DEFAULT NULL,
  `session_topics` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `learner_support_detail` text COLLATE utf8mb4_unicode_ci,
  `session_type` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `als_review_form_sessions`
--

LOCK TABLES `als_review_form_sessions` WRITE;
/*!40000 ALTER TABLE `als_review_form_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `als_review_form_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `als_reviews`
--

DROP TABLE IF EXISTS `als_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `als_reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_id` bigint(20) NOT NULL,
  `planned_date` date DEFAULT NULL,
  `date_of_review` date DEFAULT NULL,
  `assessor` bigint(20) DEFAULT NULL,
  `tutor` bigint(20) DEFAULT NULL,
  `current_progress` float(5,2) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `reasonable_adjustments_assessor` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reasonable_adjustments_other_assessor` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `support_detail_assessor` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `learner_comments_to_assessor` text COLLATE utf8mb4_unicode_ci,
  `reasonable_adjustments_tutor` varchar(2000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reasonable_adjustments_other_tutor` varbinary(2000) DEFAULT NULL,
  `learner_comments_to_tutor` text COLLATE utf8mb4_unicode_ci,
  `learner_sign` tinyint(4) NOT NULL DEFAULT '0',
  `learner_sign_date` date DEFAULT NULL,
  `assessor_sign` tinyint(4) NOT NULL DEFAULT '0',
  `assessor_sign_date` date DEFAULT NULL,
  `tutor_sign` tinyint(4) NOT NULL DEFAULT '0',
  `tutor_sign_date` date DEFAULT NULL,
  `als_tutor_sign` tinyint(4) DEFAULT '0',
  `als_tutor_sign_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `als_reviews`
--

LOCK TABLES `als_reviews` WRITE;
/*!40000 ALTER TABLE `als_reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `als_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audits`
--

DROP TABLE IF EXISTS `audits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint(20) unsigned NOT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci,
  `new_values` text COLLATE utf8mb4_unicode_ci,
  `url` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audits_auditable_type_auditable_id_index` (`auditable_type`(191),`auditable_id`),
  KEY `audits_user_id_user_type_index` (`user_id`,`user_type`(191))
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audits`
--

LOCK TABLES `audits` WRITE;
/*!40000 ALTER TABLE `audits` DISABLE KEYS */;
INSERT INTO `audits` VALUES (60,'App\\Models\\User',9,'updated','App\\Models\\User',9,'{\"password\":\"$2y$10$dSAzDxJqDVHFOp\\/DZ4tASevbdkXbyixshqXZLiuaQCCSEs0zA5DO.\",\"password_changed_at\":\"2025-04-11 22:48:49\"}','{\"password\":\"$2y$10$6eLYVvTfJvjKd62nxP911e6fPviP1W9Av5BXxq\\/6wIMK\\/DduuWuKa\",\"password_changed_at\":\"2025-04-11 21:57:14\"}','https://iwoodtrain.folio.uk.net/my/cp','185.39.248.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36',NULL,'2025-04-11 20:57:14','2025-04-11 20:57:14'),(61,'App\\Models\\User',9,'updated','App\\Models\\User',9,'{\"password\":\"$2y$10$6eLYVvTfJvjKd62nxP911e6fPviP1W9Av5BXxq\\/6wIMK\\/DduuWuKa\"}','{\"password\":\"$2y$10$1R7APDqhKg9aYRgFjQiEqujmD5cUz\\/NmfBXf02j\\/3zZ6jayQaWPyS\"}','https://iwoodtrain.folio.uk.net/my/cp','185.39.248.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36',NULL,'2025-04-11 20:57:14','2025-04-11 20:57:14'),(62,'App\\Models\\User',9,'updated','App\\Models\\User',9,'{\"password\":\"$2y$10$1R7APDqhKg9aYRgFjQiEqujmD5cUz\\/NmfBXf02j\\/3zZ6jayQaWPyS\",\"password_changed_at\":\"2025-04-11 21:57:14\"}','{\"password\":\"$2y$10$7tRs8a.7YC3Qeonvgh1UBeRIYdDcKHWcbUowhXiIEP540kH39PhU6\",\"password_changed_at\":\"2025-04-11 21:57:57\"}','https://iwoodtrain.folio.uk.net/my/cp','185.39.248.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36',NULL,'2025-04-11 20:57:57','2025-04-11 20:57:57'),(63,'App\\Models\\User',9,'updated','App\\Models\\User',9,'{\"password\":\"$2y$10$7tRs8a.7YC3Qeonvgh1UBeRIYdDcKHWcbUowhXiIEP540kH39PhU6\"}','{\"password\":\"$2y$10$DQ45kyPhCtzHa7iB2R.yNOQD9atX4l3eWpPMWnw4I1hTCdAOfIoWO\"}','https://iwoodtrain.folio.uk.net/my/cp','185.39.248.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36',NULL,'2025-04-11 20:57:57','2025-04-11 20:57:57'),(64,'App\\Models\\User',9,'created','App\\Models\\User',10,'[]','{\"firstnames\":\"Irena\",\"surname\":\"Wood\",\"gender\":\"F\",\"primary_email\":\"irena@iwoodtrain.co.uk\",\"secondary_email\":null,\"fb_id\":null,\"twitter_handle\":null,\"web_access\":1,\"user_type\":\"1\",\"employer_location\":null,\"username\":\"irenawood\",\"password\":\"$2y$10$8.PjTELHPV0dpFRSWRl1lOJ72kgGapa7FuvhCmmY0iEWSIrTCw1dG\",\"email\":\"irena@iwoodtrain.co.uk\",\"id\":10}','https://iwoodtrain.folio.uk.net/system/users','185.39.248.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',NULL,'2025-04-12 07:42:34','2025-04-12 07:42:34');
/*!40000 ALTER TABLE `audits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authentication_log`
--

DROP TABLE IF EXISTS `authentication_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `authentication_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `authenticatable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `authenticatable_id` bigint(20) unsigned NOT NULL,
  `ip_address` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_at` timestamp NULL DEFAULT NULL,
  `logout_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authentication_log`
--

LOCK TABLES `authentication_log` WRITE;
/*!40000 ALTER TABLE `authentication_log` DISABLE KEYS */;
INSERT INTO `authentication_log` VALUES (62,'App\\Models\\User',9,'185.39.248.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36','2025-04-11 20:52:55','2025-04-11 20:57:17'),(63,'App\\Models\\User',9,'185.39.248.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36','2025-04-11 20:57:30','2025-04-11 21:06:08'),(64,'App\\Models\\User',9,'92.238.144.108','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/27.0 Chrome/125.0.0.0 Mobile Safari/537.36','2025-04-11 21:23:42','2025-04-11 21:24:59'),(65,'App\\Models\\User',9,'185.39.248.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36','2025-04-12 07:39:13',NULL),(66,'App\\Models\\User',9,'92.238.144.108','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36','2025-04-12 18:33:39','2025-04-12 18:34:40');
/*!40000 ALTER TABLE `authentication_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendar_events`
--

DROP TABLE IF EXISTS `calendar_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calendar_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendar_events`
--

LOCK TABLES `calendar_events` WRITE;
/*!40000 ALTER TABLE `calendar_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendar_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuration`
--

DROP TABLE IF EXISTS `configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuration` (
  `entity` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`entity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuration`
--

LOCK TABLES `configuration` WRITE;
/*!40000 ALTER TABLE `configuration` DISABLE KEYS */;
INSERT INTO `configuration` VALUES ('DELETE-TRAINING-ALLOWED','0'),('FOLIO_CLIENT_NAME','Perspective Ltd'),('FOLIO_CLIENT_URL','http://folio-local.test'),('FOLIO_LOGO_NAME','FolioLogo2.jpg'),('FOLIO_SEND_EMAIL_TO_LEARNERS_NOT_LOGGED_IN_FOR_FOUR_WEEKS','0'),('FOLIO_SEND_EMAIL_TO_PERSPECTIVE_ON_USER_CREATION','0');
/*!40000 ALTER TABLE `configuration` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crm_notes`
--

DROP TABLE IF EXISTS `crm_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `crm_notes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `noteable_type` varchar(255) DEFAULT NULL,
  `noteable_id` bigint(20) NOT NULL,
  `type_of_contact` tinyint(4) DEFAULT NULL,
  `subject` tinyint(4) DEFAULT NULL,
  `date_of_contact` date DEFAULT NULL,
  `time_of_contact` time DEFAULT NULL,
  `by_whom` varchar(255) DEFAULT NULL,
  `details` text,
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crm_notes`
--

LOCK TABLES `crm_notes` WRITE;
/*!40000 ALTER TABLE `crm_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `crm_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_signatures`
--

DROP TABLE IF EXISTS `document_signatures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_signatures` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `signatory_system_id` bigint(20) DEFAULT NULL,
  `signatory_name` varchar(70) DEFAULT NULL,
  `signatory_system_user_type` int(11) DEFAULT NULL,
  `signatory_ip_address` varchar(15) DEFAULT NULL,
  `signatory_user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_signatures`
--

LOCK TABLES `document_signatures` WRITE;
/*!40000 ALTER TABLE `document_signatures` DISABLE KEYS */;
/*!40000 ALTER TABLE `document_signatures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employer_user_assessor`
--

DROP TABLE IF EXISTS `employer_user_assessor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employer_user_assessor` (
  `employer_user_id` bigint(20) NOT NULL,
  `assessor_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employer_user_assessor`
--

LOCK TABLES `employer_user_assessor` WRITE;
/*!40000 ALTER TABLE `employer_user_assessor` DISABLE KEYS */;
/*!40000 ALTER TABLE `employer_user_assessor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eqa_samples`
--

DROP TABLE IF EXISTS `eqa_samples`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eqa_samples` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) DEFAULT NULL,
  `active_from` date DEFAULT NULL,
  `active_to` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eqa_samples`
--

LOCK TABLES `eqa_samples` WRITE;
/*!40000 ALTER TABLE `eqa_samples` DISABLE KEYS */;
/*!40000 ALTER TABLE `eqa_samples` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eqa_samples_personnels`
--

DROP TABLE IF EXISTS `eqa_samples_personnels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eqa_samples_personnels` (
  `sample_id` bigint(20) NOT NULL,
  `eqa_user_id` bigint(20) NOT NULL,
  PRIMARY KEY (`sample_id`,`eqa_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eqa_samples_personnels`
--

LOCK TABLES `eqa_samples_personnels` WRITE;
/*!40000 ALTER TABLE `eqa_samples_personnels` DISABLE KEYS */;
/*!40000 ALTER TABLE `eqa_samples_personnels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eqa_samples_trs`
--

DROP TABLE IF EXISTS `eqa_samples_trs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eqa_samples_trs` (
  `sample_id` bigint(20) NOT NULL,
  `tr_id` bigint(20) NOT NULL,
  PRIMARY KEY (`sample_id`,`tr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eqa_samples_trs`
--

LOCK TABLES `eqa_samples_trs` WRITE;
/*!40000 ALTER TABLE `eqa_samples_trs` DISABLE KEYS */;
/*!40000 ALTER TABLE `eqa_samples_trs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_logins`
--

DROP TABLE IF EXISTS `failed_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_logins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `email_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_logins`
--

LOCK TABLES `failed_logins` WRITE;
/*!40000 ALTER TABLE `failed_logins` DISABLE KEYS */;
INSERT INTO `failed_logins` VALUES (20,9,'perspective','185.39.248.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36','2025-04-11 20:57:25','2025-04-11 20:57:25'),(21,NULL,'Inaam.Azmat@perspective-uk.com','92.238.144.108','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/27.0 Chrome/125.0.0.0 Mobile Safari/537.36','2025-04-11 21:22:43','2025-04-11 21:22:43'),(22,NULL,'Inaam.Azmat@perspective-uk.com','92.238.144.108','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/27.0 Chrome/125.0.0.0 Mobile Safari/537.36','2025-04-11 21:23:08','2025-04-11 21:23:08'),(23,NULL,'Inaam.Azmat@perspective-uk.com','92.238.144.108','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/27.0 Chrome/125.0.0.0 Mobile Safari/537.36','2025-04-11 21:23:20','2025-04-11 21:23:20'),(24,NULL,'inaam.azmat@perspective-uk.com','185.39.248.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36','2025-04-12 07:37:03','2025-04-12 07:37:03'),(25,NULL,'inaam.azmat@perspective-uk.com','185.39.248.50','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36','2025-04-12 07:37:17','2025-04-12 07:37:17');
/*!40000 ALTER TABLE `failed_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `folio_sunesis_learners`
--

DROP TABLE IF EXISTS `folio_sunesis_learners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `folio_sunesis_learners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `folio_student_id` bigint(20) DEFAULT NULL,
  `folio_tr_id` bigint(20) DEFAULT NULL,
  `sunesis_learner_id` bigint(20) DEFAULT NULL,
  `sunesis_tr_id` bigint(20) DEFAULT NULL,
  `sunesis_course_id` bigint(20) DEFAULT NULL,
  `sunesis_provider_id` bigint(20) DEFAULT NULL,
  `sunesis_provider_location_id` bigint(20) DEFAULT NULL,
  `sunesis_employer_id` bigint(20) DEFAULT NULL,
  `sunesis_employer_location_id` bigint(20) DEFAULT NULL,
  `sunesis_assessor_id` bigint(20) DEFAULT NULL,
  `sunesis_tutor_id` bigint(20) DEFAULT NULL,
  `sunesis_verifier_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `folio_sunesis_learners`
--

LOCK TABLES `folio_sunesis_learners` WRITE;
/*!40000 ALTER TABLE `folio_sunesis_learners` DISABLE KEYS */;
/*!40000 ALTER TABLE `folio_sunesis_learners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fs_course_question_options`
--

DROP TABLE IF EXISTS `fs_course_question_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fs_course_question_options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `question_id` bigint(20) NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `is_correct` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fs_course_question_options`
--

LOCK TABLES `fs_course_question_options` WRITE;
/*!40000 ALTER TABLE `fs_course_question_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `fs_course_question_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fs_course_questions`
--

DROP TABLE IF EXISTS `fs_course_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fs_course_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned NOT NULL,
  `question_order` int(11) NOT NULL,
  `type` varchar(15) NOT NULL,
  `question_text` varchar(5000) NOT NULL,
  `correct_answer` varchar(5000) DEFAULT NULL,
  `acceptable_answers` varchar(5000) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `created_by` bigint(20) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fs_course_questions`
--

LOCK TABLES `fs_course_questions` WRITE;
/*!40000 ALTER TABLE `fs_course_questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `fs_course_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fs_courses`
--

DROP TABLE IF EXISTS `fs_courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fs_courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `fs_type` varchar(15) DEFAULT NULL,
  `details` varchar(800) DEFAULT NULL,
  `video_link` varchar(2000) DEFAULT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fs_courses`
--

LOCK TABLES `fs_courses` WRITE;
/*!40000 ALTER TABLE `fs_courses` DISABLE KEYS */;
/*!40000 ALTER TABLE `fs_courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iqa_sample_plan_qualifications`
--

DROP TABLE IF EXISTS `iqa_sample_plan_qualifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `iqa_sample_plan_qualifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `iqa_sample_id` bigint(20) DEFAULT NULL,
  `qan` varchar(12) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `min_glh` int(11) DEFAULT NULL,
  `max_glh` int(11) DEFAULT NULL,
  `glh` int(11) DEFAULT NULL,
  `total_credits` int(11) DEFAULT NULL,
  `assessment_methods` mediumtext,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iqa_sample_plan_qualifications`
--

LOCK TABLES `iqa_sample_plan_qualifications` WRITE;
/*!40000 ALTER TABLE `iqa_sample_plan_qualifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `iqa_sample_plan_qualifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iqa_sample_plan_tr_unit_comments`
--

DROP TABLE IF EXISTS `iqa_sample_plan_tr_unit_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `iqa_sample_plan_tr_unit_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `iqa_sample_plan_tr_unit_id` bigint(20) unsigned NOT NULL,
  `iqa_status` varchar(15) DEFAULT NULL,
  `iqa_comments` text,
  `assessor_comments` text,
  `verifier_id` bigint(20) DEFAULT NULL,
  `assessor_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iqa_sample_plan_tr_unit_comments`
--

LOCK TABLES `iqa_sample_plan_tr_unit_comments` WRITE;
/*!40000 ALTER TABLE `iqa_sample_plan_tr_unit_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `iqa_sample_plan_tr_unit_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iqa_sample_plan_tr_units`
--

DROP TABLE IF EXISTS `iqa_sample_plan_tr_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `iqa_sample_plan_tr_units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `iqa_sample_id` bigint(20) unsigned NOT NULL,
  `tr_id` bigint(20) unsigned NOT NULL,
  `portfolio_id` bigint(20) unsigned NOT NULL,
  `portfolio_unit_id` bigint(20) unsigned NOT NULL,
  `portfolio_unit_system_code` varchar(100) NOT NULL,
  `iqa_status` varchar(15) NOT NULL DEFAULT 'added',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iqa_sample_plan_tr_units`
--

LOCK TABLES `iqa_sample_plan_tr_units` WRITE;
/*!40000 ALTER TABLE `iqa_sample_plan_tr_units` DISABLE KEYS */;
/*!40000 ALTER TABLE `iqa_sample_plan_tr_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iqa_sample_plan_trainings`
--

DROP TABLE IF EXISTS `iqa_sample_plan_trainings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `iqa_sample_plan_trainings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `iqa_sample_id` bigint(20) DEFAULT NULL,
  `tr_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iqa_sample_plan_trainings`
--

LOCK TABLES `iqa_sample_plan_trainings` WRITE;
/*!40000 ALTER TABLE `iqa_sample_plan_trainings` DISABLE KEYS */;
/*!40000 ALTER TABLE `iqa_sample_plan_trainings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iqa_sample_plan_units`
--

DROP TABLE IF EXISTS `iqa_sample_plan_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `iqa_sample_plan_units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `iqa_sample_id` bigint(20) DEFAULT NULL,
  `unit_group` varchar(10) DEFAULT NULL,
  `unit_owner_ref` varchar(15) DEFAULT NULL,
  `unique_ref_number` varchar(15) DEFAULT NULL,
  `title` varchar(850) DEFAULT NULL,
  `glh` int(11) DEFAULT NULL,
  `unit_credit_value` int(11) DEFAULT NULL,
  `qual_qan` varchar(12) DEFAULT NULL,
  `qual_title` varchar(250) DEFAULT NULL,
  `qual_min_glh` int(11) DEFAULT NULL,
  `qual_max_glh` int(11) DEFAULT NULL,
  `qual_glh` int(11) DEFAULT NULL,
  `qual_total_credits` int(11) DEFAULT NULL,
  `system_code` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iqa_sample_plan_units`
--

LOCK TABLES `iqa_sample_plan_units` WRITE;
/*!40000 ALTER TABLE `iqa_sample_plan_units` DISABLE KEYS */;
/*!40000 ALTER TABLE `iqa_sample_plan_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `iqa_sample_plans`
--

DROP TABLE IF EXISTS `iqa_sample_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `iqa_sample_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(70) DEFAULT NULL,
  `verifier_id` bigint(20) DEFAULT NULL,
  `programme_id` bigint(20) DEFAULT NULL,
  `completed_by_date` date DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `iqa_sample_plans`
--

LOCK TABLES `iqa_sample_plans` WRITE;
/*!40000 ALTER TABLE `iqa_sample_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `iqa_sample_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `laravel_logger_activity`
--

DROP TABLE IF EXISTS `laravel_logger_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `laravel_logger_activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `userType` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `route` longtext COLLATE utf8mb4_unicode_ci,
  `ipAddress` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userAgent` text COLLATE utf8mb4_unicode_ci,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referer` longtext COLLATE utf8mb4_unicode_ci,
  `methodType` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `laravel_logger_activity`
--

LOCK TABLES `laravel_logger_activity` WRITE;
/*!40000 ALTER TABLE `laravel_logger_activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `laravel_logger_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `learner_responses`
--

DROP TABLE IF EXISTS `learner_responses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `learner_responses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `test_session_id` bigint(20) unsigned NOT NULL,
  `question_id` bigint(20) unsigned NOT NULL,
  `answer_text` varchar(255) DEFAULT NULL,
  `answer_mcq_option_id` bigint(20) DEFAULT NULL,
  `is_correct` tinyint(4) NOT NULL DEFAULT '0',
  `tr_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `learner_responses`
--

LOCK TABLES `learner_responses` WRITE;
/*!40000 ALTER TABLE `learner_responses` DISABLE KEYS */;
/*!40000 ALTER TABLE `learner_responses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `learning_resource_user`
--

DROP TABLE IF EXISTS `learning_resource_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `learning_resource_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `learning_resource_id` bigint(20) unsigned NOT NULL,
  `liked` tinyint(4) NOT NULL DEFAULT '0',
  `bookmarked` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `learning_resource_user`
--

LOCK TABLES `learning_resource_user` WRITE;
/*!40000 ALTER TABLE `learning_resource_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `learning_resource_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `learning_resources`
--

DROP TABLE IF EXISTS `learning_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `learning_resources` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `resource_type` tinyint(3) unsigned NOT NULL,
  `resource_name` varchar(100) NOT NULL,
  `resource_short_description` varchar(1000) NOT NULL,
  `resource_content` text,
  `resource_url` varchar(2048) DEFAULT NULL,
  `is_featured` tinyint(4) NOT NULL DEFAULT '0',
  `likes` int(11) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `learning_resources`
--

LOCK TABLES `learning_resources` WRITE;
/*!40000 ALTER TABLE `learning_resources` DISABLE KEYS */;
INSERT INTO `learning_resources` VALUES (1,1,'Test Upload S3 ...','Testing Upload ...',NULL,NULL,0,NULL,9,'2025-04-11 20:54:39','2025-04-11 20:54:39');
/*!40000 ALTER TABLE `learning_resources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `licenses`
--

DROP TABLE IF EXISTS `licenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `licenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_number` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number_of_licenses` int(11) NOT NULL,
  `levy` tinyint(4) NOT NULL DEFAULT '0',
  `expiry_date` date DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `licenses`
--

LOCK TABLES `licenses` WRITE;
/*!40000 ALTER TABLE `licenses` DISABLE KEYS */;
INSERT INTO `licenses` VALUES (1,NULL,100,0,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `licenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_crm_subjects`
--

DROP TABLE IF EXISTS `lookup_crm_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_crm_subjects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_crm_subjects`
--

LOCK TABLES `lookup_crm_subjects` WRITE;
/*!40000 ALTER TABLE `lookup_crm_subjects` DISABLE KEYS */;
INSERT INTO `lookup_crm_subjects` VALUES (1,'Attitude',NULL,NULL),(2,'General Inquiry',NULL,NULL),(3,'Issue Resolution',NULL,NULL),(4,'Learner Information',NULL,NULL),(5,'Additional Support',NULL,NULL),(6,'Assessment',NULL,NULL),(7,'Concern',NULL,NULL),(8,'General',NULL,NULL),(9,'Mental Health',NULL,NULL),(10,'Onboarding',NULL,NULL),(11,'Potential at Risk',NULL,NULL),(12,'Training',NULL,NULL),(13,'EPA Feedback',NULL,NULL);
/*!40000 ALTER TABLE `lookup_crm_subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_crm_type_of_contacts`
--

DROP TABLE IF EXISTS `lookup_crm_type_of_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_crm_type_of_contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_crm_type_of_contacts`
--

LOCK TABLES `lookup_crm_type_of_contacts` WRITE;
/*!40000 ALTER TABLE `lookup_crm_type_of_contacts` DISABLE KEYS */;
INSERT INTO `lookup_crm_type_of_contacts` VALUES (1,'Email',NULL),(2,'Letter',NULL),(3,'Meeting',NULL),(4,'Phone Call',NULL),(5,'Chat',NULL),(6,'Other',99),(7,'User Note',NULL);
/*!40000 ALTER TABLE `lookup_crm_type_of_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_epa_status`
--

DROP TABLE IF EXISTS `lookup_epa_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_epa_status` (
  `id` varchar(15) DEFAULT NULL,
  `description` varchar(210) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_epa_status`
--

LOCK TABLES `lookup_epa_status` WRITE;
/*!40000 ALTER TABLE `lookup_epa_status` DISABLE KEYS */;
INSERT INTO `lookup_epa_status` VALUES ('F','Fail'),('NR','Not Ready'),('P','Pass'),('R','Ready'),('REF','Referred');
/*!40000 ALTER TABLE `lookup_epa_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_ethnicities`
--

DROP TABLE IF EXISTS `lookup_ethnicities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_ethnicities` (
  `id` tinyint(4) NOT NULL,
  `description` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_ethnicities`
--

LOCK TABLES `lookup_ethnicities` WRITE;
/*!40000 ALTER TABLE `lookup_ethnicities` DISABLE KEYS */;
INSERT INTO `lookup_ethnicities` VALUES (31,'English / Welsh / Scottish / Northern Irish / British'),(32,'Irish'),(33,'Gypsy or Irish Traveller'),(34,'Any other White background'),(35,'White and Black Caribbean'),(36,'White and Black African'),(37,'White and Asian'),(38,'Any other Mixed / multiple ethnic background'),(39,'Indian'),(40,'Pakistani'),(41,'Bangladeshi'),(42,'Chinese'),(43,'Any other Asian background'),(44,'African'),(45,'Caribbean'),(46,'Any other Black / African / Caribbean background'),(47,'Arab'),(98,'Any other ethnic group'),(99,'Not known/not provided');
/*!40000 ALTER TABLE `lookup_ethnicities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_evidence_assessment_methods`
--

DROP TABLE IF EXISTS `lookup_evidence_assessment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_evidence_assessment_methods` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_evidence_assessment_methods`
--

LOCK TABLES `lookup_evidence_assessment_methods` WRITE;
/*!40000 ALTER TABLE `lookup_evidence_assessment_methods` DISABLE KEYS */;
INSERT INTO `lookup_evidence_assessment_methods` VALUES (1,'Observation'),(2,'Test'),(3,'Interview'),(4,'Video'),(5,'On line assessment'),(6,'Phase test'),(7,'Assessment'),(8,'Practical Assessment Tcert Level 2'),(9,'Practical Assessment Tcert Level 3'),(10,'Guided Discussion'),(11,'Project'),(12,'Case Study'),(13,'Reflective Account'),(14,'Witness Testimony'),(15,'Webinar and Assignment'),(16,'Webinar and Examination'),(17,'Assignments / Tests'),(18,'Portfolio of Evidence'),(19,'Certificate');
/*!40000 ALTER TABLE `lookup_evidence_assessment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_evidence_categories`
--

DROP TABLE IF EXISTS `lookup_evidence_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_evidence_categories` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_evidence_categories`
--

LOCK TABLES `lookup_evidence_categories` WRITE;
/*!40000 ALTER TABLE `lookup_evidence_categories` DISABLE KEYS */;
INSERT INTO `lookup_evidence_categories` VALUES (1,'Specific Objectives'),(2,'Scope'),(3,'Performance Objectives'),(4,'Underpinning Knowledge'),(5,'Tech Knowledge and Understanding'),(6,'Competence'),(7,'Workplace Evidence'),(8,'Expected Outcome'),(9,'KSB - Knowledge'),(10,'KSB - Skills'),(11,'KSB - Behaviours'),(12,'Observation'),(13,'Range');
/*!40000 ALTER TABLE `lookup_evidence_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_evidence_types`
--

DROP TABLE IF EXISTS `lookup_evidence_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_evidence_types` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_evidence_types`
--

LOCK TABLES `lookup_evidence_types` WRITE;
/*!40000 ALTER TABLE `lookup_evidence_types` DISABLE KEYS */;
INSERT INTO `lookup_evidence_types` VALUES (1,'Picture'),(2,'Spreadsheet'),(3,'Answersheet'),(4,'Marksheet'),(5,'Video'),(6,'Work Sheet'),(7,'Job Card'),(8,'Electronic answer sheet'),(9,'Portfolio'),(10,'Assignment'),(11,'URL');
/*!40000 ALTER TABLE `lookup_evidence_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_notifications`
--

DROP TABLE IF EXISTS `lookup_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_notifications` (
  `id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `description` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_notifications`
--

LOCK TABLES `lookup_notifications` WRITE;
/*!40000 ALTER TABLE `lookup_notifications` DISABLE KEYS */;
INSERT INTO `lookup_notifications` VALUES (1,'Evidence Submitted by Learner');
/*!40000 ALTER TABLE `lookup_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_org_sectors`
--

DROP TABLE IF EXISTS `lookup_org_sectors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_org_sectors` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_org_sectors`
--

LOCK TABLES `lookup_org_sectors` WRITE;
/*!40000 ALTER TABLE `lookup_org_sectors` DISABLE KEYS */;
INSERT INTO `lookup_org_sectors` VALUES (1,'Health, Public Services and Care'),(2,'Science and Mathematics'),(3,'Agriculture, Horticulture and Animal Care'),(4,'Engineering and Manufacturing Technologies'),(5,'Construction, Planning and the Built Environment'),(6,'Information and Communication Technology'),(7,'Retail and Commercial Enterprise'),(8,'Leisure, Travel and Tourism'),(9,'Arts, Media and Publishing'),(10,'History, Philosophy and Theology'),(11,'Social Sciences'),(12,'Languages, Literature and Culture'),(13,'Education and Training'),(14,'Preparation for Life and Work'),(15,'Business, Administration and Law');
/*!40000 ALTER TABLE `lookup_org_sectors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_otj_types`
--

DROP TABLE IF EXISTS `lookup_otj_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_otj_types` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_otj_types`
--

LOCK TABLES `lookup_otj_types` WRITE;
/*!40000 ALTER TABLE `lookup_otj_types` DISABLE KEYS */;
INSERT INTO `lookup_otj_types` VALUES (1,'Employer induction'),(2,'Group assignment'),(3,'Health & Safety'),(4,'Individual assignment'),(5,'Industry visits'),(6,'Learning support'),(7,'Lecture'),(8,'Manufacture/ Employer Training'),(9,'Mentoring'),(10,'On-line learning'),(11,'Practical training'),(12,'Research'),(13,'Role playing'),(14,'Shadowing'),(15,'Simulation exercise'),(16,'Writing assessments'),(17,'Writing assignments'),(18,'Delivery Plan Session'),(19,'Classroom Delivery'),(20,'Onefile Assessment'),(21,'1-1 Tutor/teaching delivery'),(22,'Learning Activity/Assessment (Assignment)'),(23,'Learning Activity/Assessment (Functional Skills Prep)'),(24,'Teaching and Learning Activity'),(25,'Reflective Account'),(26,'Conference'),(27,'Special Project'),(28,'Workbook');
/*!40000 ALTER TABLE `lookup_otj_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_programme_types`
--

DROP TABLE IF EXISTS `lookup_programme_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_programme_types` (
  `id` int(11) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_programme_types`
--

LOCK TABLES `lookup_programme_types` WRITE;
/*!40000 ALTER TABLE `lookup_programme_types` DISABLE KEYS */;
INSERT INTO `lookup_programme_types` VALUES (-2,'Not Applicable'),(2,'Advanced Apprenticeship (Level 3)'),(3,'Intermediate Apprenticeship (Level 2)'),(10,'Higher Level Apprenticeship'),(15,'Diploma Level 1 (Foundation)'),(16,'Diploma Level 2 (Higher)'),(17,'Diploma Level 3 (Progression)'),(18,'Diploma Level 3 (Advanced)'),(20,'Higher Apprenticeship (Level 4)'),(21,'Higher Apprenticeship (Level 5)'),(22,'Higher Apprenticeship (Level 6)'),(23,'Higher Apprenticeship (Level 7+)'),(24,'Traineeship'),(25,'Apprenticeship Standard'),(30,'T Level Transition'),(31,'T Level'),(99,'None Of The Above');
/*!40000 ALTER TABLE `lookup_programme_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_qual_levels`
--

DROP TABLE IF EXISTS `lookup_qual_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_qual_levels` (
  `id` varchar(6) NOT NULL,
  `description` varchar(12) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_qual_levels`
--

LOCK TABLES `lookup_qual_levels` WRITE;
/*!40000 ALTER TABLE `lookup_qual_levels` DISABLE KEYS */;
INSERT INTO `lookup_qual_levels` VALUES ('EL','Entry Level'),('L1','Level 1'),('L1L2','Level 1/2'),('L2','Level 2'),('L3','Level 3'),('L4','Level 4'),('L5','Level 5'),('L6','Level 6'),('L7','Level 7'),('L8','Level 8');
/*!40000 ALTER TABLE `lookup_qual_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_qual_owners`
--

DROP TABLE IF EXISTS `lookup_qual_owners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_qual_owners` (
  `owner_org_rn` varchar(8) NOT NULL,
  `owner_org_acronym` varchar(50) DEFAULT NULL,
  `owner_org_name` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`owner_org_rn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_qual_owners`
--

LOCK TABLES `lookup_qual_owners` WRITE;
/*!40000 ALTER TABLE `lookup_qual_owners` DISABLE KEYS */;
INSERT INTO `lookup_qual_owners` VALUES ('RN5112','SEG Awards','Skills and Education Group Awards'),('RN5113','ACCA','Association of Chartered Certified Accountants'),('RN5114','SEQ','Swim England Qualifications'),('RN5115','BBO','British Ballet Organisation Limited'),('RN5116','BDS','The British Driving Society Limited'),('RN5117','IWFM','Institute of Workplace and Facilities Management'),('RN5118','BIIAB','BIIAB'),('RN5119','BSC','British Safety Council'),('RN5120','BWYQ','The British Wheel of Yoga Qualifications'),('RN5121','CACHE','Council for Awards in Care, Health and Education'),('RN5122','Cambridge International','Cambridge Assessment International Education'),('RN5123','CCEA','Council for the Curriculum, Examinations and Assessment'),('RN5124','CIBTAC','CIBTAC Limited'),('RN5126','CII','Chartered Insurance Institute'),('RN5127','CIM','Chartered Institute of Marketing'),('RN5128','CMI','Chartered Management Institute'),('RN5130','CQI','The Chartered Quality Institute'),('RN5131','CTH','The Confederation of Tourism and Hospitality'),('RN5132','ECITB','Engineering Construction Industry Training Board'),('RN5133','Pearson','Pearson Education Ltd'),('RN5134','Pearson EDI','Pearson Education Ltd (EDI)'),('RN5135','ETCAL','ETC Awards Limited'),('RN5136','FAQ','Future (Awards and Qualifications) Ltd'),('RN5137','GQA','GQA Qualifications Limited'),('RN5138','GQAL','Graded Qualifications Alliance'),('RN5139','IAM','Institute of Administrative Management (The)'),('RN5140','IBO','International Baccalaureate Organisation'),('RN5141','IBSL','Institute of British Sign Language Community Interest Company'),('RN5142','CILEx','The Chartered Institute of Legal Executives'),('RN5143','ILM','The Institute of Leadership & Management'),('RN5145','CIOLQ','IoL Educational Trust'),('RN5146','ISM','ISM Education Limited'),('RN5147','ISTD','The Imperial Society of Teachers of Dancing'),('RN5148','ITC','ITC First Aid Ltd'),('RN5149','ITEC','Education & Media Services Ltd'),('RN5150','ITG','The Institute of Tourist Guiding'),('RN5151','LAMDA','LAMDA Limited'),('RN5152','Lantra Awards','Lantra Awards'),('RN5153','MRS','The Market Research Society'),('RN5154','NALP','NALP'),('RN5155','NCC Education','NCC Education Limited'),('RN5156','NCFE','NCFE'),('RN5157','NCTJ','NCTJ Training Limited'),('RN5158','PADI','Professional Association of Diving Instructors'),('RN5159','QNUK','Qualifications Network'),('RN5160','QUALIFI','Qualifi Ltd'),('RN5161','RCVS','Royal College of Veterinary Surgeons'),('RN5162','REC','Recruitment & Employment Confederation'),('RN5163','RHS','The Royal Horticultural Society'),('RN5164','RPII','Register of Play Inspectors International Limited'),('RN5165','Skillsfirst','Skillsfirst Awards Ltd'),('RN5166','Leadership Skills Foundation','The British Sports Trust '),('RN5167','SQA','Scottish Qualifications Authority'),('RN5168','STA','The Swimming Teachers Association Limited'),('RN5169','TCL','Trinity College London'),('RN5170','UWLQ','University of West London'),('RN5171','WAMITAB','Waste Management Industry Training & Advisory Board'),('RN5172','WJEC','WJEC-CBAC'),('RN5173','WJEC-EDEXCEL','WJEC- EDEXCEL partnership'),('RN5174','WSET','WSET Awards'),('RN5181','CIH','The Chartered Institute of Housing'),('RN5182','CIOB - CIH','Chartered Institute of Building and Chartered Institute of Housing Joint Awarding Body'),('RN5183','WCSM','Worshipful Company of Spectacle Makers'),('RN5184','FDQ','FDQ Limited'),('RN5185','Cambridge English','University of Cambridge (The Chancellor, Masters and Scholars of the University of Cambridge)'),('RN5186','Agored Cymru','Agored Cymru'),('RN5187','PAAVQSET','The Process Awards Authority (T/A PAA/VQ-SET)'),('RN5188','BCS','British Computer Society'),('RN5189','RAD','Royal Academy of Dance'),('RN5190','NEBOSH','National Examination Board in Occupational Safety and Health'),('RN5191','MTE','Mountain Training England'),('RN5192','RSL','RSL Awards Ltd'),('RN5193','OCR','OCR'),('RN5194','NOCN','NOCN'),('RN5195','MPQC','Mineral Products Qualifications Council'),('RN5196','AQA','AQA Education'),('RN5197','PIABC','PIABC Ltd'),('RN5198','VTCT','VTCT'),('RN5199','AABPS','Accrediting & Assessment Bureau for Post-Secondary Schools Ltd'),('RN5200','Ascentis','Ascentis'),('RN5201','ASFI','Accredited Skills for Industry'),('RN5202','PMQ','Propertymark Qualifications Ltd'),('RN5203','McDonalds','McDonald\'s Restaurants Ltd'),('RN5204','IAO','Innovate Awarding Ltd'),('RN5205','OU','Open University'),('RN5206','IAB','Institute of Accountants and Bookkeepers'),('RN5210','LCM','London Centre of Marketing Ltd'),('RN5211','WCF','Farriery Examinations Limited'),('RN5212','UAL','University of the Arts London'),('RN5213','PMI','The Pensions Management Institute'),('RN5216','1st4sport','UK Coaching Solutions Limited'),('RN5217','City & Guilds','City and Guilds of London Institute'),('RN5218','SFEDI Ltd','Small Firms Enterprise Development Initiative Ltd'),('RN5219','Highfield Qualifications','Highfield Qualifications'),('RN5220','AAT','Association of Accounting Technicians'),('RN5221','ABE','Association of Business Executives'),('RN5222','AIA','The Association of International Accountants'),('RN5223','CABWI','CABWI Awarding Body'),('RN5224','YMCA','YMCA Awards'),('RN5226','CIPD','Chartered Institute of Personnel and Development'),('RN5227','CIPS','The Chartered Institute of Procurement and Supply'),('RN5228','CCNQ','City College Norwich Qualifications'),('RN5229','CIOB','The Chartered Institute of Building'),('RN5230','Cskills Awards','NOCN'),('RN5231','CPCAB','CPCAB Ltd'),('RN5232','DAO','Defence Awarding Organisation'),('RN5233','BAA','Awarding Body for Vocational Achievement (AVA) Ltd'),('RN5234','HAB','The City and Guilds of London Institute (HAB)'),('RN5235','ESB','English Speaking Board (International) Ltd'),('RN5236','FAA','First Aid Awards Ltd'),('RN5237','LIBF','The London Institute of Banking & Finance'),('RN5238','iRSQ','Moody\'s Analytics UK Limited'),('RN5239','Active IQ','Active IQ Limited'),('RN5240','NPTC','The City and Guilds of London Institute (NPTC)'),('RN5241','AQA - City & Guilds','The City and Guilds of London Institute'),('RN5242','Gem-A','The Gemmological Association of Great Britain'),('RN5245','Institute of Hospita','Institute of Hospitality'),('RN5246','Accounting Technicians Ireland','The Institute of Accounting Technicians in Ireland Limited'),('RN5247','CISI','Chartered Institute for Securities & Investment'),('RN5248','CIEH','Chartered Institute of Environmental Health'),('RN5249','Prince\'s Trust','The Prince\'s Trust'),('RN5250','CILT(UK)','The Chartered Institute of Logistics and Transport in the UK'),('RN5251','ABBE','Awarding Body for the Built Environment Limited'),('RN5252','IAT','The Institute of Animal Technology'),('RN5253','CELL','Constructing Excellence in Learning Limited'),('RN5254','PLASA','Professional Lighting and Sound Association'),('RN5255','RSPH','Royal Society for Public Health'),('RN5256','BPEC','BPEC Certification Ltd'),('RN5257','CfA','Skills CFA'),('RN5258','IOCM','The Institute of Commercial Management'),('RN5259','Signature','CACDP Trading as Signature'),('RN5260','EAL','Excellence, Achievement & Learning Limited'),('RN5261','BCAB','British Canoeing Awarding Body'),('RN5263','AMSPAR','AMSPAR'),('RN5264','CICM','Chartered Institute of Credit Management'),('RN5265','BG','British Gymnastics'),('RN5266','BHSQ','The British Horse Society Qualifications Limited'),('RN5267','UCLanEB','UCLan Business Services Ltd'),('RN5268','ABRSM','The Associated Board of the Royal Schools of Music'),('RN5269','BHEST','British Horseracing Education and Standards Trust'),('RN5270','IMI (SSC)','The Institute of the Motor Industry (SSC)'),('RN5271','Flybe Awards','Flybe Awards'),('RN5272','ABDO','The Association of British Dispensing Opticians'),('RN5273','TLM','The Learning Machine'),('RN5274','CIOE&IT','Chartered Institute of Export & International Trade'),('RN5275','BICSc','The British Institute of Cleaning Science Limited'),('RN5276','IOM','The Institute of Operations Management, a Trading Arm of The Chartered Institute of Logistics and Transport in the UK'),('RN5277','ABMA','ABMA Education Ltd'),('RN5278','IQL','IQL'),('RN5279','ASQ','Associated Sports Qualifications LLP'),('RN5280','IFE','The Institution of Fire Engineers'),('RN5281','ICAAE','ICAA (Examinations) Ltd'),('RN5282','ASDAN','Award Scheme Development and Accreditation Network'),('RN5283','KPA','Kaplan Professional Awards'),('RN5284','OTHM','OTHM Qualifications'),('RN5285','IRRV','Institute of Revenues Rating and Valuation'),('RN5287','Network Rail','Network Rail'),('RN5288','NEA','New Era Academy of Drama and Music (London) Ltd'),('RN5289','Open Awards','Open Awards'),('RN5291','QA','Qualsafe Awards'),('RN5292','CL:AIRE','Contaminated Land: Applications in Real Environments'),('RN5293','DSAQ','DSA Qualifications Awarding Body Limited'),('RN5294','CITB-Construction Skills','CITB-Construction Skills'),('RN5295','SEMTA (SSC)','Science, Engineering and Manufacturing Technologies Alliance'),('RN5296','QCA','Qualifications and Curriculum Authority'),('RN5297','e-skills','e-skills UK (Note: not an awarding body)'),('RN5298','UKCG','United Kingdom Co-ordinating Group'),('RN5299','TDA','Training and Development Agency for Schools (not an awarding body)'),('RN5300','CWDC','Childrens Workforce Development Council (note: not an awarding body)'),('RN5301','NPAL','National Plant Awards Limited'),('RN5302','Asset Skills','Asset Skills'),('RN5303','SFEDI Awards','SFEDI Enterprises Ltd. T/A SFEDI Awards'),('RN5304','CIEA','The Chartered Institute of Educational Assessors'),('RN5305','Cogent','Cogent SSC Ltd'),('RN5306','CITB','CITB'),('RN5307','EU Skills','Energy and Utility Skills Limited'),('RN5308','Go Skills','Go Skills'),('RN5309','Lantra SSC','Lantra SSC'),('RN5310','LLUK','Lifelong Learning UK'),('RN5311','MSC','Management Standards Centre Limited'),('RN5312','Skills Active','Skills Active UK'),('RN5313','SfJ','JSSC'),('RN5314','Skills for Security','Skills for Security'),('RN5315','Skillset','Skillset Limited'),('RN5316','skillsmart retail','Skillsmart Retail Ltd'),('RN5317','SummitSkills','SummitSkills Limited'),('RN5318','IMIAL','IMI Awards Ltd'),('RN5319','CFA UK','CFA Society of the UK'),('RN5320','e-skills UK','e-skills UK Sector Skills Council Ltd'),('RN5321','SEMTA','Science, Engineering and Manufacturing  Technologies Alliance'),('RN5322','AIM Awards','AIM Awards'),('RN5323','Improve','Improve Ltd'),('RN5324','AptEd','AptEd, the trading name of Open College Network South West Region (OCNSWR) Ltd'),('RN5325','iCQ','iCan Qualifications Limited'),('RN5326','LASER','Open College Network South East Region Ltd'),('RN5327','OCN NI','Open College Network Northern Ireland'),('RN5328','Gateway Qualifications','Gateway Qualifications Limited'),('RN5329','OCNNER','OCN North East Region'),('RN5330','IQ','Industry Qualifications'),('RN5331','IFA','The Institute of Financial Accountants'),('RN5332','LSIS','Learning and Skills Improvement Service'),('RN5333','ASAO','Alzheimer\'s Society'),('RN5334','ProQual','ProQual Awarding Body'),('RN5335','UELGB','University of East London Global Examinations Board'),('RN5336','CQ','Central Veterinary Services Limited'),('RN5337','OCNLR','Open College Network London Region'),('RN5338','GA','Gatehouse Awards Ltd'),('RN5339','AOFAQ','Awarding Organisation for Accredited Qualifications'),('RN5340','LCL Awards','Logic Certification Limited'),('RN5341','LRN','Learning Resource Network Limited'),('RN5342','SfL','Skills For Logistics'),('RN5343','Open College Network West Midlands','Open College Network West Midlands'),('RN5344','ATHE','ATHE Ltd'),('RN5345','Certa','Open College Network Yorkshire and Humber Region'),('RN5346','IDTA','International Dance Teachers\' Association Limited'),('RN5347','NIAT Awarding','National Inter Action Trust Awarding'),('RN5348','APMG-International','APMG Group Limited'),('RN5349','FPSB UK','FPSB UK Ltd'),('RN5350','Proskills','Proskills UK'),('RN5351','SFJ Awards Ltd','SFJ Awards Ltd'),('RN5352','AI','Accountants Inst. Ltd'),('RN5353','Focus Awards','Focus Awards Limited'),('RN5354','People1st','People1st'),('RN5355','TQUK','Training Qualifications UK Ltd'),('RN5356','FIA','Fire Industry Association'),('RN5357','NATD','The National Association of Teachers of Dancing'),('RN5358','CI','Crossfields Institute'),('RN5359','VetSkill','VetSkill'),('RN5360','OAL','Occupational Awards Limited'),('RN5361','IMI','The Institute of the Motor Industry'),('RN5362','RoSPA','Royal Society for the Prevention of Accidents'),('RN6000','SafeCert','SafeCert Awards'),('RN6001','LanguageCert','LanguageCert'),('RN6002','telc','telc gGmbH'),('RN6003','MTB Exams','MTB Exams Limited'),('RN6004','NICEIC','Certsure LLP'),('RN6005','MPSUK','Manpower Services Limited'),('RN6006','DSW','Doran Scott Williams and Co Ltd'),('RN6007','Transcend','Transcend Awards Limited'),('RN6008','SSid','Certass Limited'),('RN6009','Prospect Awards','Prospect Awards CIC'),('RN6010','EMPI Awards','Philip Brain Associates Limited'),('RN6011','FRM','Fire Risk Management Ltd'),('RN6012','ISP','The Professional Sales Leadership Alliance Ltd'),('RN6013','IETTL','Insulation Environmental Training Trust Ltd'),('RN6014','BePro','Bespoke Professional Development and Training Ltd'),('RN6015','NISQ','N.I. Security Qualifications Ltd'),('RN6016','ICM','The Institute of Commercial Management'),('RN6017','PADI','PADI EMEA Limited'),('RN6022','iPET Network','iPET Network Limited'),('RN6023','Rambert Grades','RAMBERT CREATIVE CONTEMPORARY DANCE GRADES LTD '),('RN6024','Autoexel Ltd','Autoexel Ltd'),('RN6025','Awarding UK','Bishop Grosseteste University'),('RN6026','Achieve+Partners','Achieve and Partners Limited'),('RN6027','DTQ','Dental Team Qualifications Limited'),('RN6028','EUIAS','ENERGY AND UTILITY SKILLS LIMITED'),('RN6029','QFI','Qualifications for Industry Limited'),('RN6030','SWC','South West Councils'),('RN6031','Smart Awards','Smart Awards Ltd'),('RN6032','SSES','SS Educational Services Ltd'),('RN6033','RAeS','Royal Aeronautical Society'),('RN6034','FireQual','BAFE FireQual Ltd'),('RN6035','The British Council','The British Council'),('RN6036','CIWM','Chartered Institution of Wastes Management'),('RN6037','LifeSkills Solutions Ltd','Lifeskills Solutions Ltd'),('RN6038','CIPFA','CIPFA Business Limited'),('RN6039','The IET','The Institution of Engineering and Technology'),('RN6040','IOSH','Institution of Occupational Safety and Health'),('RN6041','NQual','NQual LTD'),('RN6042','IAMI','International Association of Maritime Institutions '),('RN6043','1st for EPA','1st for EPA Ltd'),('RN6044','NAS','Notebook Assessment Services Ltd'),('RN6045','SCC','Suffolk County Council'),('RN6046','ICAS','The Institute of Chartered Accountants of Scotland'),('RN6047','ICE','The Institution of Civil Engineers '),('RN6048','PAL','Professional Assessment Ltd'),('RN6049','MIAA (RN)','ROYAL NAVY APPRENTICESHIPS'),('RN6050','CIHT','CIHT'),('RN6051','CIBSE','The Chartered Institution of Building Services Engineers'),('RN6052','MIAA (RAF)','Royal Air Force'),('RN6053','BPSAA EPA','BPS Assessments and Awards Limited'),('RN6054','CILIP Pathways','CILIP Pathways Limited'),('RN6055','1st Awards','1st Awards Limited'),('RN6056','ICME','Institute of Cast Metals Engineers'),('RN6057','L.E.I.A. ','LIFT AND ESCALATOR INDUSTRY ASSOCIATION (EPA0269)'),('RN6058','TPS','The Transport Planning Society Ltd'),('RN6059','CIMA','Chartered Institute of Management Accountants '),('RN6060','ICAEW','ICAEW'),('RN6061','Advance HE','Advance HE'),('RN6062','IQP','Intqual-pro Limited'),('RN6063','Summit Qualifications UK','The British Institute of Recruiters'),('RN6064','Verge EPA','Verge EPA Limited'),('RN6065','SIAS','Science Industry Assessment Service Limited'),('RN6066','Elite Awarding','Elite Awarding Limited '),('RN6067','Vistar','Vistar Qualifications Limited'),('RN6068','ICA','International Compliance Association'),('RN6069','CIfA','Chartered Institute for Archaeologists'),('RN6070','IMechE','Institution of Mechanical Engineers'),('RN6071','BPN','Best Practice Network Limited'),('RN6072','Steadfast EPA','Steadfast Training Ltd'),('RN6073','PMA','JGA Limited'),('RN6074','Chartered IIA','Chartered Institute of Internal Auditors'),('RN6075','ELS','Explosive Learning Solutions'),('RN6076','EngEPA','ENGEPA Ltd'),('RN6077','Academy4PM','Academy for Project Management LTD'),('RN6078','Xact Assessment','Xact Training Ltd'),('RN6079','CIEEM','Chartered Institute of Ecology and Environmental Management'),('RN6080','AAS','Advanced Analytics Solutions LLP'),('RN6081','TRCC','The Real Consultancy Company Ltd'),('RN6082','CFRS','Cornwall Council (Cornwall Fire and Rescue Service)'),('RN6083','JMA Contract Services Ltd','JMA Contract Services Ltd'),('RN6084','NLTC','National Logistics Training Consortium Ltd'),('RN6085','NSAN','National Skills Academy Nuclear'),('RN6086','IPPE','IPP Education Ltd'),('RN6087','GPSAS','GP Strategies Training Ltd'),('RN6088','Sheldrake','Sheldrake Training Limited'),('RN6089','Construction EPA Company','North West Skills Academy Ltd'),('RN6090','G2G','Qualitrain Ltd'),('RN6091','TLI','Westcountry Schools Trust'),('RN6092','AP Ltd','Accelerate People Limited'),('RN6093','QAL','Quantum Awards Limited'),('RN6094','BATF','British Allied Trades Federation'),('RN6095','RTITB','RTITB Limited'),('RN6096','A2A Training and EPA','A2A Training Limited '),('RN6097','NHS England NSHCS','NHS England'),('RN6098','MA','Marshall Assessment Limited'),('RN6099','AA','Advance Assessments Limited'),('RN6100','Besafe Training Limited','Besafe Training Limited'),('RN6101','ACTT','The Army Catering Training Trust'),('RN6102','Icon','Institute of Conservation'),('RN6103','CIRO','Chartered Institution of Railway Operators'),('RN6104','AEL','Assessed Education Ltd'),('RN6105','LIBF','LIBF Limited'),('RN6106','TQH','The Qualification Hub Ltd'),('RN6107','EFIA','EDUCATION FOR INDUSTRY AWARDS LIMITED'),('RN6108','NEBOSH','The National Examination Board in Occupational Safety and Health'),('RN6109','NEBDN','National Examining Board for Dental Nurses'),('RN6110','safeagents','Approved Letting Scheme Limited'),('RN6111','BM','British Marine Federation'),('RN6112','SPA','Systems Practitioner Assessment Ltd.'),('RN6113','NXEPA','Newcross Healthcare Solutions Limited'),('RN6114','DNA Awarding','SCL Education & Training Limited'),('RN6115','IRM','Institute of Risk Management'),('RN6116','SMS','The Society of Master Saddlers (UK) Ltd'),('RN6117','In2A','In2action'),('RN6118','RCG','Railway Competence Group Ltd'),('RN6119','RPD','Rail Professional Development Limited'),('RN6120','TEC','Maritime and Engineering College North West'),('RN6121','FuturU','FuturU Limited'),('RN6122','UCE','United Centre of Excellence Limited'),('RN6123','The Rail Academy','The Rail Academy Limited'),('RN6124','BINDT','The British Institute of Non-Destructive Testing'),('RN6125','ICB','The Institute of Certified Bookkeepers'),('RN6126','E4H','Cords Education'),('RN6127','CAL','Chief Assessments Limited'),('RN6128','MNA','MN Awards Ltd'),('RN6129','RLSS UK Qualifications','RLSS UK Enterprises Ltd'),('RN6130','Lead Edge Ltd','Lead Edge Ltd'),('RN6131','ISoM','International School of Musicians Ltd'),('RN6132','Advance EPA','Advance EPA Ltd'),('RN6133','OR Society','The Operational Research Society'),('RN6134','CTQ','CERTIFY TRAINING QUALIFICATIONS LTD'),('RN6135','Elevate EPA Ltd','Elevate EPA Ltd'),('RN6136','ISSP','Lighthouse Business Consultants Limited');
/*!40000 ALTER TABLE `lookup_qual_owners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_qual_ssa`
--

DROP TABLE IF EXISTS `lookup_qual_ssa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_qual_ssa` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `description` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_qual_ssa`
--

LOCK TABLES `lookup_qual_ssa` WRITE;
/*!40000 ALTER TABLE `lookup_qual_ssa` DISABLE KEYS */;
INSERT INTO `lookup_qual_ssa` VALUES (1,'01.1 Medicine and Dentistry'),(2,'01.2 Nursing and subjects and vocations allied to medicine'),(3,'01.3 Health and social care'),(4,'01.4 Public services'),(5,'01.5 Child development and well-being'),(6,'02.1 Science'),(7,'02.2 Mathematics and statistics'),(8,'03.1 Agriculture'),(9,'03.2 Horticulture and forestry'),(10,'03.3 Animal care and veterinary science'),(11,'03.4 Environmental conservation'),(12,'04.1 Engineering'),(13,'04.2 Manufacturing technologies'),(14,'04.3 Transportation operations and maintenance'),(15,'05.1 Architecture'),(16,'05.2 Building and construction'),(17,'05.3 Urban, rural and regional planning'),(18,'06.1 ICT practitioners'),(19,'06.2 ICT for users'),(20,'07.1 Retailing and wholesaling'),(21,'07.2 Warehousing and distribution'),(22,'07.3 Service enterprises'),(23,'07.4 Hospitality and catering'),(24,'08.1 Sport, leisure and recreation'),(25,'08.2 Travel and tourism'),(26,'09.1 Performing arts'),(27,'09.2 Crafts, creative arts and design'),(28,'09.3 Media and communication'),(29,'09.4 Publishing and information services'),(30,'10.1 History'),(31,'10.2 Archaeology and archaeological sciences'),(32,'10.3 Philosophy'),(33,'10.4 Theology and religious studies'),(34,'11.1 Geography'),(35,'11.2 Sociology and social policy'),(36,'11.3 Politics'),(37,'11.4 Economics'),(38,'11.5 Anthropology'),(39,'12.1 Languages, literature and culture of the British Isles'),(40,'12.2 Other languages, literature and culture'),(41,'12.3 Linguistics'),(42,'13.1 Teaching and lecturing'),(43,'13.2 Direct learning support'),(44,'14.1 Foundations for learning and life'),(45,'14.2 Preparation for work'),(46,'15.1 Accounting and finance'),(47,'15.2 Administration'),(48,'15.3 Business management'),(49,'15.4 Marketing and sales'),(50,'15.5 Law and legal services'),(51,'Qualification SSA');
/*!40000 ALTER TABLE `lookup_qual_ssa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_qual_status`
--

DROP TABLE IF EXISTS `lookup_qual_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_qual_status` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `description` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_qual_status`
--

LOCK TABLES `lookup_qual_status` WRITE;
/*!40000 ALTER TABLE `lookup_qual_status` DISABLE KEYS */;
INSERT INTO `lookup_qual_status` VALUES (1,'Available to learners'),(2,'No longer available to new learners'),(3,'No longer awarded'),(4,'Not yet available to learners');
/*!40000 ALTER TABLE `lookup_qual_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_qual_types`
--

DROP TABLE IF EXISTS `lookup_qual_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_qual_types` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_qual_types`
--

LOCK TABLES `lookup_qual_types` WRITE;
/*!40000 ALTER TABLE `lookup_qual_types` DISABLE KEYS */;
INSERT INTO `lookup_qual_types` VALUES (1,'AEA - Advanced Extension Award'),(2,'BS - Basic Skills'),(3,'EP - End-Point Assessment'),(5,'EL - Entry Level'),(7,'FSMQ - Free Standing Mathematics Qualification'),(8,'FS - Functional Skills'),(10,'GCE - GCE A Level'),(11,'GCE AS - GCE AS Level'),(12,'GCSE (9 to 1)'),(13,'GCSE (A* to G)'),(14,'GNVQ - General National Vocational Qualification'),(15,'HL - Higher Level'),(16,'KS - Key Skills'),(17,'NVQ - National Vocational Qualification'),(18,'OQ - Occupational Qualification'),(19,'OG - Other General Qualification'),(25,'QCF - QCF Qualifcation'),(27,'VCE AS - VCE Advanced Subsidiary Level'),(28,'VCE - Vocational Certificate of Education'),(29,'VRQ - Vocationally-Related Qualification');
/*!40000 ALTER TABLE `lookup_qual_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_review_types`
--

DROP TABLE IF EXISTS `lookup_review_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_review_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_review_types`
--

LOCK TABLES `lookup_review_types` WRITE;
/*!40000 ALTER TABLE `lookup_review_types` DISABLE KEYS */;
INSERT INTO `lookup_review_types` VALUES (1,'Face-to-face'),(2,'Telephone'),(3,'Workplace'),(4,'Formal Review'),(5,'Progress Review');
/*!40000 ALTER TABLE `lookup_review_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_supp_ticket_category`
--

DROP TABLE IF EXISTS `lookup_supp_ticket_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_supp_ticket_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_supp_ticket_category`
--

LOCK TABLES `lookup_supp_ticket_category` WRITE;
/*!40000 ALTER TABLE `lookup_supp_ticket_category` DISABLE KEYS */;
INSERT INTO `lookup_supp_ticket_category` VALUES (1,'Documentation','light'),(2,'Enhancement Request','pink'),(3,'General Enquiry','light'),(4,'How to?','light'),(5,'Incident','warning'),(6,'Inputting/Data Collection','light'),(7,'Non techincal','grey'),(8,'Programming bug','danger'),(9,'Reports','grey'),(10,'Training','light'),(11,'User log-in issues','transparent');
/*!40000 ALTER TABLE `lookup_supp_ticket_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_supp_ticket_priority`
--

DROP TABLE IF EXISTS `lookup_supp_ticket_priority`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_supp_ticket_priority` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_supp_ticket_priority`
--

LOCK TABLES `lookup_supp_ticket_priority` WRITE;
/*!40000 ALTER TABLE `lookup_supp_ticket_priority` DISABLE KEYS */;
INSERT INTO `lookup_supp_ticket_priority` VALUES (1,'Low','grey'),(2,'Medium','warning'),(3,'High','yellow'),(4,'Show Stopper','danger');
/*!40000 ALTER TABLE `lookup_supp_ticket_priority` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_supp_ticket_status`
--

DROP TABLE IF EXISTS `lookup_supp_ticket_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_supp_ticket_status` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(50) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_supp_ticket_status`
--

LOCK TABLES `lookup_supp_ticket_status` WRITE;
/*!40000 ALTER TABLE `lookup_supp_ticket_status` DISABLE KEYS */;
INSERT INTO `lookup_supp_ticket_status` VALUES (1,'New','info'),(2,'Assigned','primary'),(3,'Awaiting Client','warning'),(4,'Awaiting Confirmation','success'),(5,'Closed','success'),(6,'Development','transparent'),(7,'Duplicate','danger'),(8,'On Hold','grey'),(9,'Refused Development','danger'),(10,'Reopened','yellow');
/*!40000 ALTER TABLE `lookup_supp_ticket_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_tr_bil_reasons`
--

DROP TABLE IF EXISTS `lookup_tr_bil_reasons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_tr_bil_reasons` (
  `id` tinyint(4) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_tr_bil_reasons`
--

LOCK TABLES `lookup_tr_bil_reasons` WRITE;
/*!40000 ALTER TABLE `lookup_tr_bil_reasons` DISABLE KEYS */;
INSERT INTO `lookup_tr_bil_reasons` VALUES (1,'Other personal reasons'),(2,'Dismissed from work place');
/*!40000 ALTER TABLE `lookup_tr_bil_reasons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_tr_evidence_categories`
--

DROP TABLE IF EXISTS `lookup_tr_evidence_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_tr_evidence_categories` (
  `id` int(10) DEFAULT NULL,
  `description` varchar(210) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_tr_evidence_categories`
--

LOCK TABLES `lookup_tr_evidence_categories` WRITE;
/*!40000 ALTER TABLE `lookup_tr_evidence_categories` DISABLE KEYS */;
INSERT INTO `lookup_tr_evidence_categories` VALUES (1,'Activity Plan'),(2,'APL/RPL'),(3,'Case Study/Scenario'),(4,'Classroom Delivery'),(5,'EPA Preparation'),(6,'Exam'),(7,'Functional Skills Prep'),(8,'Informal discussion'),(9,'Knowledge'),(10,'Observation'),(11,'Observation by learner'),(12,'Oral Questions'),(13,'Portfolio check'),(14,'Professional Discussion'),(15,'Project'),(16,'Reflective Account'),(17,'Witness Testimony'),(18,'Work Product'),(19,'Written Questions\r\n');
/*!40000 ALTER TABLE `lookup_tr_evidence_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_tr_learning_outcome`
--

DROP TABLE IF EXISTS `lookup_tr_learning_outcome`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_tr_learning_outcome` (
  `id` int(10) DEFAULT NULL,
  `description` varchar(210) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_tr_learning_outcome`
--

LOCK TABLES `lookup_tr_learning_outcome` WRITE;
/*!40000 ALTER TABLE `lookup_tr_learning_outcome` DISABLE KEYS */;
INSERT INTO `lookup_tr_learning_outcome` VALUES (1,'Achieved'),(2,'Partial Achievement'),(3,'No Achievement'),(4,'Learning activities are complete but outcome unknown');
/*!40000 ALTER TABLE `lookup_tr_learning_outcome` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_tr_status`
--

DROP TABLE IF EXISTS `lookup_tr_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_tr_status` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `description` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_tr_status`
--

LOCK TABLES `lookup_tr_status` WRITE;
/*!40000 ALTER TABLE `lookup_tr_status` DISABLE KEYS */;
INSERT INTO `lookup_tr_status` VALUES (1,'Continuing'),(2,'Completed'),(3,'Withdrawn'),(5,'Deactivated'),(7,'Break in Learning');
/*!40000 ALTER TABLE `lookup_tr_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_tr_withdrawl_reasons`
--

DROP TABLE IF EXISTS `lookup_tr_withdrawl_reasons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_tr_withdrawl_reasons` (
  `id` int(10) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_tr_withdrawl_reasons`
--

LOCK TABLES `lookup_tr_withdrawl_reasons` WRITE;
/*!40000 ALTER TABLE `lookup_tr_withdrawl_reasons` DISABLE KEYS */;
INSERT INTO `lookup_tr_withdrawl_reasons` VALUES (1,'Learner transferred to another provider'),(2,'Learner injury/illness'),(3,'Learner has been made redundant'),(4,'Financial reasons'),(5,'Other personal reasons'),(6,'Exclusion'),(7,'Other'),(8,'Reason not known');
/*!40000 ALTER TABLE `lookup_tr_withdrawl_reasons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_unit_groups`
--

DROP TABLE IF EXISTS `lookup_unit_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_unit_groups` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_unit_groups`
--

LOCK TABLES `lookup_unit_groups` WRITE;
/*!40000 ALTER TABLE `lookup_unit_groups` DISABLE KEYS */;
INSERT INTO `lookup_unit_groups` VALUES (1,'Mandatory'),(2,'Optional');
/*!40000 ALTER TABLE `lookup_unit_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_user_event_participant_status`
--

DROP TABLE IF EXISTS `lookup_user_event_participant_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_user_event_participant_status` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_user_event_participant_status`
--

LOCK TABLES `lookup_user_event_participant_status` WRITE;
/*!40000 ALTER TABLE `lookup_user_event_participant_status` DISABLE KEYS */;
INSERT INTO `lookup_user_event_participant_status` VALUES (1,'Invited'),(2,'Accepted'),(3,'Declined');
/*!40000 ALTER TABLE `lookup_user_event_participant_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_user_events_status`
--

DROP TABLE IF EXISTS `lookup_user_events_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_user_events_status` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_user_events_status`
--

LOCK TABLES `lookup_user_events_status` WRITE;
/*!40000 ALTER TABLE `lookup_user_events_status` DISABLE KEYS */;
INSERT INTO `lookup_user_events_status` VALUES (1,'Booked'),(2,'Cancelled'),(3,'Closed');
/*!40000 ALTER TABLE `lookup_user_events_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_user_events_types`
--

DROP TABLE IF EXISTS `lookup_user_events_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_user_events_types` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_user_events_types`
--

LOCK TABLES `lookup_user_events_types` WRITE;
/*!40000 ALTER TABLE `lookup_user_events_types` DISABLE KEYS */;
INSERT INTO `lookup_user_events_types` VALUES (1,'Review'),(2,'Appointment'),(3,'Meeting'),(4,'Face-to-face Session'),(5,'Observation'),(6,'Training Presentation'),(7,'Online Session'),(8,'Other');
/*!40000 ALTER TABLE `lookup_user_events_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lookup_user_types`
--

DROP TABLE IF EXISTS `lookup_user_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lookup_user_types` (
  `id` tinyint(2) DEFAULT NULL,
  `description` varchar(210) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lookup_user_types`
--

LOCK TABLES `lookup_user_types` WRITE;
/*!40000 ALTER TABLE `lookup_user_types` DISABLE KEYS */;
INSERT INTO `lookup_user_types` VALUES (1,'Administrator'),(2,'Tutor'),(3,'Assessor'),(4,'Verifier'),(5,'Student'),(8,'Manager'),(12,'System Viewer'),(17,'EQA'),(18,'Employer User');
/*!40000 ALTER TABLE `lookup_user_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  `collection_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `manipulations` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_properties` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `responsive_images` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_column` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (1,'App\\Models\\LearningResources\\LearningResource',1,'learning_resources','Blank File','cb1c628ca4e4c3d1733093262d798414.pdf','application/pdf','s3',79754,'[]','{\"uploaded_by\":9}','[]',1,'2025-04-11 20:54:39','2025-04-11 20:54:39'),(2,'App\\Models\\User',9,'avatars','30442668','f7c0be26fcb90bca13a6a5904774084b.jpg','image/jpeg','users_avatars',30386,'[]','[]','[]',2,'2025-04-11 21:05:45','2025-04-11 21:05:45');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_section_has_models`
--

DROP TABLE IF EXISTS `media_section_has_models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_section_has_models` (
  `section_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_section_has_models`
--

LOCK TABLES `media_section_has_models` WRITE;
/*!40000 ALTER TABLE `media_section_has_models` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_section_has_models` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_sections`
--

DROP TABLE IF EXISTS `media_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_column` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_sections`
--

LOCK TABLES `media_sections` WRITE;
/*!40000 ALTER TABLE `media_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_id` bigint(20) unsigned NOT NULL,
  `to_id` bigint(20) unsigned DEFAULT NULL,
  `root_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '0',
  `delete_for_sender` tinyint(1) NOT NULL DEFAULT '0',
  `delete_for_receiver` tinyint(1) NOT NULL DEFAULT '0',
  `archive_for_sender` tinyint(1) NOT NULL DEFAULT '0',
  `archive_for_receiver` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_root_id` (`root_id`),
  KEY `FK_from_id` (`from_id`),
  KEY `FK_to_id` (`to_id`),
  CONSTRAINT `FK_from_id` FOREIGN KEY (`from_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_root_id` FOREIGN KEY (`root_id`) REFERENCES `messages` (`id`),
  CONSTRAINT `FK_to_id` FOREIGN KEY (`to_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
INSERT INTO `model_has_permissions` VALUES (1,'App\\Models\\User',9),(2,'App\\Models\\User',9),(3,'App\\Models\\User',9),(4,'App\\Models\\User',9),(5,'App\\Models\\User',9),(6,'App\\Models\\User',9),(7,'App\\Models\\User',9),(8,'App\\Models\\User',9),(9,'App\\Models\\User',9),(10,'App\\Models\\User',9),(11,'App\\Models\\User',9),(12,'App\\Models\\User',9),(13,'App\\Models\\User',9),(14,'App\\Models\\User',9),(15,'App\\Models\\User',9),(16,'App\\Models\\User',9),(17,'App\\Models\\User',9),(18,'App\\Models\\User',9),(19,'App\\Models\\User',9),(20,'App\\Models\\User',9),(21,'App\\Models\\User',9),(22,'App\\Models\\User',9),(23,'App\\Models\\User',9),(24,'App\\Models\\User',9),(25,'App\\Models\\User',9),(26,'App\\Models\\User',9),(27,'App\\Models\\User',9),(28,'App\\Models\\User',9),(29,'App\\Models\\User',9),(30,'App\\Models\\User',9),(31,'App\\Models\\User',9),(32,'App\\Models\\User',9),(33,'App\\Models\\User',9),(34,'App\\Models\\User',9),(35,'App\\Models\\User',9),(36,'App\\Models\\User',9),(37,'App\\Models\\User',9),(38,'App\\Models\\User',9),(39,'App\\Models\\User',9),(40,'App\\Models\\User',9),(41,'App\\Models\\User',9),(42,'App\\Models\\User',9),(43,'App\\Models\\User',9),(44,'App\\Models\\User',9),(45,'App\\Models\\User',9),(46,'App\\Models\\User',9),(47,'App\\Models\\User',9),(48,'App\\Models\\User',9),(49,'App\\Models\\User',9),(50,'App\\Models\\User',9),(51,'App\\Models\\User',9),(52,'App\\Models\\User',9),(53,'App\\Models\\User',9),(54,'App\\Models\\User',9),(55,'App\\Models\\User',9),(56,'App\\Models\\User',9),(57,'App\\Models\\User',9),(58,'App\\Models\\User',9),(59,'App\\Models\\User',9),(1,'App\\Models\\User',10),(2,'App\\Models\\User',10),(3,'App\\Models\\User',10),(4,'App\\Models\\User',10),(5,'App\\Models\\User',10),(6,'App\\Models\\User',10),(7,'App\\Models\\User',10),(8,'App\\Models\\User',10),(9,'App\\Models\\User',10),(10,'App\\Models\\User',10),(11,'App\\Models\\User',10),(12,'App\\Models\\User',10),(13,'App\\Models\\User',10),(14,'App\\Models\\User',10),(15,'App\\Models\\User',10),(16,'App\\Models\\User',10),(17,'App\\Models\\User',10),(18,'App\\Models\\User',10),(19,'App\\Models\\User',10),(20,'App\\Models\\User',10),(21,'App\\Models\\User',10),(22,'App\\Models\\User',10),(23,'App\\Models\\User',10),(24,'App\\Models\\User',10),(25,'App\\Models\\User',10),(26,'App\\Models\\User',10),(27,'App\\Models\\User',10),(28,'App\\Models\\User',10),(29,'App\\Models\\User',10),(30,'App\\Models\\User',10),(31,'App\\Models\\User',10),(32,'App\\Models\\User',10),(33,'App\\Models\\User',10),(34,'App\\Models\\User',10),(35,'App\\Models\\User',10),(36,'App\\Models\\User',10),(37,'App\\Models\\User',10),(38,'App\\Models\\User',10),(39,'App\\Models\\User',10),(40,'App\\Models\\User',10),(41,'App\\Models\\User',10),(42,'App\\Models\\User',10),(43,'App\\Models\\User',10),(44,'App\\Models\\User',10),(45,'App\\Models\\User',10),(46,'App\\Models\\User',10),(47,'App\\Models\\User',10),(48,'App\\Models\\User',10),(49,'App\\Models\\User',10),(50,'App\\Models\\User',10),(51,'App\\Models\\User',10),(52,'App\\Models\\User',10),(53,'App\\Models\\User',10),(54,'App\\Models\\User',10),(55,'App\\Models\\User',10),(56,'App\\Models\\User',10),(57,'App\\Models\\User',10),(58,'App\\Models\\User',10),(59,'App\\Models\\User',10);
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` int(10) unsigned NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notifiable_id` bigint(20) unsigned DEFAULT NULL,
  `notifier_id` bigint(20) DEFAULT NULL,
  `data` text COLLATE utf8mb4_unicode_ci,
  `read_at` timestamp NULL DEFAULT NULL,
  `actor_id` bigint(20) DEFAULT NULL,
  `checked` tinyint(1) NOT NULL DEFAULT '0',
  `detail` varchar(750) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `org_locations`
--

DROP TABLE IF EXISTS `org_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `org_locations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint(20) unsigned NOT NULL,
  `is_legal_address` tinyint(4) DEFAULT '0',
  `title` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_1` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_2` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_3` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line_4` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sunesis_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `org_locations`
--

LOCK TABLES `org_locations` WRITE;
/*!40000 ALTER TABLE `org_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `org_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organisation_contacts`
--

DROP TABLE IF EXISTS `organisation_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `organisation_contacts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `organisation_id` bigint(20) NOT NULL,
  `location_id` bigint(20) DEFAULT NULL,
  `title` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surname` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstnames` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `job_title` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department` varchar(70) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organisation_contacts`
--

LOCK TABLES `organisation_contacts` WRITE;
/*!40000 ALTER TABLE `organisation_contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `organisation_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organisations`
--

DROP TABLE IF EXISTS `organisations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `organisations` (
  `legal_name` varchar(600) DEFAULT NULL,
  `trading_name` varchar(600) DEFAULT NULL,
  `short_name` varchar(60) DEFAULT NULL,
  `company_number` varchar(60) DEFAULT NULL,
  `vat_number` varchar(60) DEFAULT NULL,
  `sector` int(5) DEFAULT NULL,
  `edrs` varchar(90) DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organisations`
--

LOCK TABLES `organisations` WRITE;
/*!40000 ALTER TABLE `organisations` DISABLE KEYS */;
/*!40000 ALTER TABLE `organisations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orgs`
--

DROP TABLE IF EXISTS `orgs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orgs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `org_type` tinyint(4) NOT NULL,
  `legal_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `trading_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_number` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sector` int(11) DEFAULT NULL,
  `edrs` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `onefile_id` bigint(20) DEFAULT NULL,
  `sunesis_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orgs`
--

LOCK TABLES `orgs` WRITE;
/*!40000 ALTER TABLE `orgs` DISABLE KEYS */;
/*!40000 ALTER TABLE `orgs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `otj`
--

DROP TABLE IF EXISTS `otj`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `otj` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_id` bigint(20) unsigned NOT NULL,
  `title` varchar(500) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `duration` time DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `details` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Submitted',
  `assessor_comments` text,
  `is_otj` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i_tr_id` (`tr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otj`
--

LOCK TABLES `otj` WRITE;
/*!40000 ALTER TABLE `otj` DISABLE KEYS */;
/*!40000 ALTER TABLE `otj` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `otj1`
--

DROP TABLE IF EXISTS `otj1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `otj1` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_id` bigint(20) unsigned NOT NULL,
  `title` varchar(500) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `duration` time DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `details` varchar(1200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Submitted',
  `assessor_comments` varchar(1200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otj1`
--

LOCK TABLES `otj1` WRITE;
/*!40000 ALTER TABLE `otj1` DISABLE KEYS */;
/*!40000 ALTER TABLE `otj1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `otj_ksbs`
--

DROP TABLE IF EXISTS `otj_ksbs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `otj_ksbs` (
  `otj_id` bigint(20) NOT NULL,
  `pc_id` bigint(20) NOT NULL,
  PRIMARY KEY (`otj_id`,`pc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `otj_ksbs`
--

LOCK TABLES `otj_ksbs` WRITE;
/*!40000 ALTER TABLE `otj_ksbs` DISABLE KEYS */;
/*!40000 ALTER TABLE `otj_ksbs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pc_evidence_mappings`
--

DROP TABLE IF EXISTS `pc_evidence_mappings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pc_evidence_mappings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `portfolio_pc_id` bigint(20) unsigned DEFAULT NULL,
  `tr_evidence_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `tr_evidence_id` (`tr_evidence_id`),
  KEY `portfolio_pc_id` (`portfolio_pc_id`),
  CONSTRAINT `pc_evidence_mappings_ibfk_1` FOREIGN KEY (`tr_evidence_id`) REFERENCES `tr_evidences` (`id`),
  CONSTRAINT `pc_evidence_mappings_ibfk_2` FOREIGN KEY (`portfolio_pc_id`) REFERENCES `portfolio_pcs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pc_evidence_mappings`
--

LOCK TABLES `pc_evidence_mappings` WRITE;
/*!40000 ALTER TABLE `pc_evidence_mappings` DISABLE KEYS */;
/*!40000 ALTER TABLE `pc_evidence_mappings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'menu-system-admin','web',NULL,NULL,NULL),(2,'submenu-roles-permissions','web',NULL,NULL,NULL),(3,'submenu-system-users','web',NULL,NULL,NULL),(4,'submenu-logins','web',NULL,NULL,NULL),(5,'submenu-failed-logins','web',NULL,NULL,NULL),(6,'menu-organisations','web',NULL,NULL,NULL),(7,'submenu-employers','web',NULL,NULL,NULL),(8,'menu-programmes','web',NULL,NULL,NULL),(9,'submenu-programmes','web',NULL,NULL,NULL),(10,'menu-students','web',NULL,NULL,NULL),(11,'submenu-view-students','web',NULL,NULL,NULL),(12,'menu-training-records','web',NULL,NULL,NULL),(13,'submenu-view-training-records','web',NULL,NULL,NULL),(14,'menu-qualifications','web',NULL,NULL,NULL),(15,'submenu-view-qualifications','web',NULL,NULL,NULL),(16,'create-system-user','web',NULL,NULL,NULL),(17,'read-system-user','web',NULL,NULL,NULL),(18,'update-system-user','web',NULL,NULL,NULL),(19,'delete-system-user','web',NULL,NULL,NULL),(20,'export-system-user','web',NULL,NULL,NULL),(21,'create-employer-organisation','web',NULL,NULL,NULL),(22,'read-employer-organisation','web',NULL,NULL,NULL),(23,'update-employer-organisation','web',NULL,NULL,NULL),(24,'delete-employer-organisation','web',NULL,NULL,NULL),(25,'export-employer-organisation','web',NULL,NULL,NULL),(26,'create-programme','web',NULL,NULL,NULL),(27,'read-programme','web',NULL,NULL,NULL),(28,'update-programme','web',NULL,NULL,NULL),(29,'delete-programme','web',NULL,NULL,NULL),(30,'export-programme','web',NULL,NULL,NULL),(31,'create-student','web',NULL,NULL,NULL),(32,'read-student','web',NULL,NULL,NULL),(33,'update-student','web',NULL,NULL,NULL),(34,'delete-student','web',NULL,NULL,NULL),(35,'export-student','web',NULL,NULL,NULL),(36,'enrol-student','web',NULL,NULL,NULL),(37,'read-training-record','web',NULL,NULL,NULL),(38,'update-training-record','web',NULL,NULL,NULL),(39,'delete-training-record','web',NULL,NULL,NULL),(40,'create-qualification','web',NULL,NULL,NULL),(41,'read-qualification','web',NULL,NULL,NULL),(42,'update-qualification','web',NULL,NULL,NULL),(43,'delete-qualification','web',NULL,NULL,NULL),(44,'signoff-progress','web',NULL,NULL,NULL),(45,'view-license-info','web',NULL,NULL,NULL),(46,'create-evidence','web',NULL,NULL,NULL),(47,'assess-evidence','web',NULL,NULL,NULL),(48,'download-evidence','web',NULL,NULL,NULL),(49,'iqa-assessment','web',NULL,NULL,NULL),(50,'view-iqa-feedback','web',NULL,NULL,NULL),(51,'delete-evidence','web',NULL,NULL,NULL),(52,'menu-iqa','web',NULL,NULL,NULL),(53,'submenu-iqa-sample-plans','web',NULL,NULL,NULL),(54,'create-iqa-sample-plan','web',NULL,NULL,NULL),(55,'update-iqa-sample-plan','web',NULL,NULL,NULL),(56,'delete-iqa-sample-plan','web',NULL,NULL,NULL),(57,'read-iqa-sample-plan','web',NULL,NULL,NULL),(58,'menu-staff-development','web',NULL,NULL,NULL),(59,'cancel-signoff-progress','web',NULL,NULL,NULL);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolio_pcs`
--

DROP TABLE IF EXISTS `portfolio_pcs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portfolio_pcs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `portfolio_unit_id` bigint(20) unsigned DEFAULT NULL,
  `pc_sequence` int(11) DEFAULT NULL,
  `reference` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` tinyint(4) DEFAULT NULL,
  `title` varchar(850) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_req_evidences` tinyint(2) DEFAULT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assessor_signoff` tinyint(1) NOT NULL DEFAULT '0',
  `iqa_status` tinyint(2) DEFAULT NULL,
  `portfolio_id` bigint(20) DEFAULT NULL,
  `accepted_evidences` int(11) DEFAULT NULL,
  `awaiting_evidences` int(11) DEFAULT NULL,
  `delivery_hours` tinyint(4) DEFAULT NULL,
  `system_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `portfolio_unit_id` (`portfolio_unit_id`),
  CONSTRAINT `portfolio_pcs_ibfk_1` FOREIGN KEY (`portfolio_unit_id`) REFERENCES `portfolio_units` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolio_pcs`
--

LOCK TABLES `portfolio_pcs` WRITE;
/*!40000 ALTER TABLE `portfolio_pcs` DISABLE KEYS */;
/*!40000 ALTER TABLE `portfolio_pcs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolio_pcs_cancel_signoff`
--

DROP TABLE IF EXISTS `portfolio_pcs_cancel_signoff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portfolio_pcs_cancel_signoff` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pc_id` bigint(20) unsigned NOT NULL,
  `reason` varchar(500) DEFAULT NULL,
  `actor_id` bigint(20) unsigned NOT NULL,
  `training_progress` float(5,2) DEFAULT NULL,
  `portfolio_progress` float(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolio_pcs_cancel_signoff`
--

LOCK TABLES `portfolio_pcs_cancel_signoff` WRITE;
/*!40000 ALTER TABLE `portfolio_pcs_cancel_signoff` DISABLE KEYS */;
/*!40000 ALTER TABLE `portfolio_pcs_cancel_signoff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolio_pcs_signoff`
--

DROP TABLE IF EXISTS `portfolio_pcs_signoff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portfolio_pcs_signoff` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_id` bigint(20) NOT NULL,
  `portfolio_id` bigint(20) NOT NULL,
  `pc_ids` text NOT NULL,
  `training_progress` float(5,2) DEFAULT NULL,
  `portfolio_progress` float(5,2) DEFAULT NULL,
  `signedoff_by` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i_tr_id` (`tr_id`),
  KEY `i_portfolio_id` (`portfolio_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolio_pcs_signoff`
--

LOCK TABLES `portfolio_pcs_signoff` WRITE;
/*!40000 ALTER TABLE `portfolio_pcs_signoff` DISABLE KEYS */;
/*!40000 ALTER TABLE `portfolio_pcs_signoff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolio_units`
--

DROP TABLE IF EXISTS `portfolio_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portfolio_units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `portfolio_id` bigint(20) unsigned DEFAULT NULL,
  `unit_sequence` int(11) DEFAULT NULL,
  `unit_group` tinyint(4) DEFAULT NULL,
  `unit_owner_ref` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unique_ref_number` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(850) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `glh` int(11) DEFAULT NULL,
  `unit_credit_value` int(11) DEFAULT NULL,
  `learning_outcomes` mediumtext COLLATE utf8mb4_unicode_ci,
  `system_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_status` tinyint(4) DEFAULT NULL,
  `assessor_signoff` tinyint(4) NOT NULL DEFAULT '0',
  `iqa_status` tinyint(2) DEFAULT NULL,
  `assessment_complete` tinyint(2) DEFAULT NULL,
  `iqa_completed` tinyint(2) DEFAULT NULL,
  `iqa_sample_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `portfolio_id` (`portfolio_id`),
  CONSTRAINT `portfolio_units_ibfk_1` FOREIGN KEY (`portfolio_id`) REFERENCES `portfolios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolio_units`
--

LOCK TABLES `portfolio_units` WRITE;
/*!40000 ALTER TABLE `portfolio_units` DISABLE KEYS */;
/*!40000 ALTER TABLE `portfolio_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolio_units_eqa`
--

DROP TABLE IF EXISTS `portfolio_units_eqa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portfolio_units_eqa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `portfolio_unit_id` bigint(20) unsigned NOT NULL,
  `comments` varchar(2000) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolio_units_eqa`
--

LOCK TABLES `portfolio_units_eqa` WRITE;
/*!40000 ALTER TABLE `portfolio_units_eqa` DISABLE KEYS */;
/*!40000 ALTER TABLE `portfolio_units_eqa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolio_units_iqa`
--

DROP TABLE IF EXISTS `portfolio_units_iqa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portfolio_units_iqa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `portfolio_unit_id` bigint(20) unsigned NOT NULL,
  `accepted_pcs` varchar(2000) NOT NULL,
  `rejected_pcs` varchar(2000) NOT NULL,
  `comments` varchar(2000) NOT NULL,
  `iqa_type` varchar(15) DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `iqa_sample_id` bigint(20) DEFAULT NULL,
  `system_code` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolio_units_iqa`
--

LOCK TABLES `portfolio_units_iqa` WRITE;
/*!40000 ALTER TABLE `portfolio_units_iqa` DISABLE KEYS */;
/*!40000 ALTER TABLE `portfolio_units_iqa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `portfolios`
--

DROP TABLE IF EXISTS `portfolios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `portfolios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_id` bigint(20) unsigned NOT NULL,
  `start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `status_code` tinyint(1) DEFAULT NULL,
  `qan` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_glh` int(11) DEFAULT NULL,
  `max_glh` int(11) DEFAULT NULL,
  `glh` int(11) DEFAULT NULL,
  `total_credits` int(11) DEFAULT NULL,
  `assessment_methods` mediumtext COLLATE utf8mb4_unicode_ci,
  `ab_registration_number` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Awarding Body Number',
  `ab_registration_date` date DEFAULT NULL COMMENT 'Awarding Body Registration Date',
  `tbl_qualification_id` bigint(20) DEFAULT NULL,
  `cert_applied` date DEFAULT NULL,
  `cert_received` date DEFAULT NULL,
  `cert_sent_to_learner` date DEFAULT NULL,
  `main` tinyint(2) NOT NULL DEFAULT '0',
  `sequence` tinyint(4) DEFAULT NULL,
  `proportion` tinyint(4) DEFAULT NULL,
  `duration` tinyint(4) DEFAULT NULL,
  `offset` tinyint(4) DEFAULT NULL,
  `learning_outcome` int(11) DEFAULT NULL,
  `fs_tutor_id` bigint(20) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `certificate_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cert_expiry_date` date DEFAULT NULL,
  `batch_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `candidate_no` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tr_id` (`tr_id`),
  CONSTRAINT `portfolios_ibfk_1` FOREIGN KEY (`tr_id`) REFERENCES `tr` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `portfolios`
--

LOCK TABLES `portfolios` WRITE;
/*!40000 ALTER TABLE `portfolios` DISABLE KEYS */;
/*!40000 ALTER TABLE `portfolios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programme_dp_sessions`
--

DROP TABLE IF EXISTS `programme_dp_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programme_dp_sessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `programme_id` bigint(20) DEFAULT NULL,
  `session_number` varchar(5) DEFAULT NULL,
  `session_sequence` int(11) DEFAULT NULL,
  `session_details_1` varchar(5000) DEFAULT NULL,
  `session_details_2` varchar(5000) DEFAULT NULL,
  `session_pcs` varchar(5000) DEFAULT NULL,
  `session_planned_hours` int(11) DEFAULT NULL,
  `is_template` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programme_dp_sessions`
--

LOCK TABLES `programme_dp_sessions` WRITE;
/*!40000 ALTER TABLE `programme_dp_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `programme_dp_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programme_qualification_unit_pcs`
--

DROP TABLE IF EXISTS `programme_qualification_unit_pcs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programme_qualification_unit_pcs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `programme_qualification_unit_id` bigint(20) unsigned NOT NULL,
  `pc_sequence` int(11) DEFAULT NULL,
  `reference` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` tinyint(4) DEFAULT NULL,
  `title` varchar(850) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_req_evidences` tinyint(2) DEFAULT NULL,
  `delivery_hours` tinyint(4) DEFAULT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `programme_qualification_unit` (`programme_qualification_unit_id`),
  CONSTRAINT `programme_qualification_unit_pcs_ibfk_1` FOREIGN KEY (`programme_qualification_unit_id`) REFERENCES `programme_qualification_units` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=529 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programme_qualification_unit_pcs`
--

LOCK TABLES `programme_qualification_unit_pcs` WRITE;
/*!40000 ALTER TABLE `programme_qualification_unit_pcs` DISABLE KEYS */;
/*!40000 ALTER TABLE `programme_qualification_unit_pcs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programme_qualification_units`
--

DROP TABLE IF EXISTS `programme_qualification_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programme_qualification_units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `programme_qualification_id` bigint(20) unsigned DEFAULT NULL,
  `unit_sequence` int(11) DEFAULT NULL,
  `unit_group` tinyint(4) DEFAULT NULL,
  `unit_owner_ref` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unique_ref_number` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(850) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `glh` int(11) DEFAULT NULL,
  `unit_credit_value` int(11) DEFAULT NULL,
  `learning_outcomes` mediumtext COLLATE utf8mb4_unicode_ci,
  `system_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_status` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `programme_qualification_id` (`programme_qualification_id`),
  CONSTRAINT `programme_qualification_units_ibfk_1` FOREIGN KEY (`programme_qualification_id`) REFERENCES `programme_qualifications` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programme_qualification_units`
--

LOCK TABLES `programme_qualification_units` WRITE;
/*!40000 ALTER TABLE `programme_qualification_units` DISABLE KEYS */;
/*!40000 ALTER TABLE `programme_qualification_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programme_qualifications`
--

DROP TABLE IF EXISTS `programme_qualifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programme_qualifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `programme_id` bigint(20) unsigned NOT NULL,
  `main` tinyint(2) NOT NULL DEFAULT '0',
  `sequence` tinyint(4) DEFAULT NULL,
  `proportion` tinyint(4) DEFAULT NULL,
  `duration` tinyint(4) DEFAULT NULL,
  `offset` tinyint(4) DEFAULT NULL,
  `qan` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_glh` int(11) DEFAULT NULL,
  `max_glh` int(11) DEFAULT NULL,
  `glh` int(11) DEFAULT NULL,
  `total_credits` int(11) DEFAULT NULL,
  `assessment_methods` mediumtext COLLATE utf8mb4_unicode_ci,
  `tbl_qualification_id` bigint(20) DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `programme_id` (`programme_id`),
  CONSTRAINT `programme_qualifications_ibfk_1` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programme_qualifications`
--

LOCK TABLES `programme_qualifications` WRITE;
/*!40000 ALTER TABLE `programme_qualifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `programme_qualifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programme_training_plans`
--

DROP TABLE IF EXISTS `programme_training_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programme_training_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `programme_id` bigint(20) unsigned DEFAULT NULL,
  `plan_number` tinyint(2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `plan_units` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programme_training_plans`
--

LOCK TABLES `programme_training_plans` WRITE;
/*!40000 ALTER TABLE `programme_training_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `programme_training_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `programmes`
--

DROP TABLE IF EXISTS `programmes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `programmes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `epa_duration` int(11) DEFAULT NULL,
  `programme_type` tinyint(2) DEFAULT NULL,
  `reference_number` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lars_standard_code` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `epa_organisation` varchar(8) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otj_hours` int(11) DEFAULT NULL,
  `first_review` int(11) DEFAULT NULL,
  `review_frequency` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL,
  `comments` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `leeway` int(11) NOT NULL DEFAULT '0',
  `sunesis_framework_id` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `programmes`
--

LOCK TABLES `programmes` WRITE;
/*!40000 ALTER TABLE `programmes` DISABLE KEYS */;
/*!40000 ALTER TABLE `programmes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qualification_unit_pcs`
--

DROP TABLE IF EXISTS `qualification_unit_pcs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qualification_unit_pcs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `unit_id` bigint(20) NOT NULL,
  `pc_sequence` int(11) DEFAULT NULL,
  `reference` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` tinyint(4) DEFAULT NULL,
  `title` varchar(850) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_req_evidences` tinyint(2) DEFAULT NULL,
  `delivery_hours` tinyint(4) DEFAULT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_unit_id_reference` (`unit_id`,`reference`),
  CONSTRAINT `evidences_ibfk1` FOREIGN KEY (`unit_id`) REFERENCES `qualification_units` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=775 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qualification_unit_pcs`
--

LOCK TABLES `qualification_unit_pcs` WRITE;
/*!40000 ALTER TABLE `qualification_unit_pcs` DISABLE KEYS */;
/*!40000 ALTER TABLE `qualification_unit_pcs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qualification_units`
--

DROP TABLE IF EXISTS `qualification_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qualification_units` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `qualification_id` bigint(20) unsigned NOT NULL,
  `unit_sequence` int(11) DEFAULT NULL,
  `unit_group` tinyint(2) DEFAULT NULL,
  `unit_owner_ref` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unique_ref_number` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(850) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `glh` int(11) DEFAULT NULL,
  `unit_credit_value` int(11) DEFAULT NULL,
  `learning_outcomes` mediumtext COLLATE utf8mb4_unicode_ci,
  `system_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `i_qualification_id_unique_ref_number` (`qualification_id`,`unique_ref_number`),
  CONSTRAINT `qualification_units_ibfk_1` FOREIGN KEY (`qualification_id`) REFERENCES `qualifications` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qualification_units`
--

LOCK TABLES `qualification_units` WRITE;
/*!40000 ALTER TABLE `qualification_units` DISABLE KEYS */;
/*!40000 ALTER TABLE `qualification_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qualifications`
--

DROP TABLE IF EXISTS `qualifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qualifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `qan` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(400) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_org_rn` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_level` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eqf_level` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint(4) DEFAULT NULL,
  `total_credits` int(11) DEFAULT '0',
  `ssa` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `regulation_start_date` date DEFAULT NULL,
  `operational_start_date` date DEFAULT NULL,
  `operational_end_date` date DEFAULT NULL,
  `certification_end_date` date DEFAULT NULL,
  `min_glh` int(11) DEFAULT '0',
  `max_glh` int(11) DEFAULT '0',
  `total_qual_time` int(11) DEFAULT '0',
  `glh` int(11) DEFAULT '0',
  `offered_in_england` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `offerend_in_ni` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `overall_grading_type` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assessment_methods` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ni_discount_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gce_size_equivalence` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gcse_size_equivalence` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entitlement_framework_designation` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grading_scale` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialism` mediumtext COLLATE utf8mb4_unicode_ci,
  `pathways` mediumtext COLLATE utf8mb4_unicode_ci,
  `approved_for_DEL_funded_programme` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_to_specs` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `system_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qualifications`
--

LOCK TABLES `qualifications` WRITE;
/*!40000 ALTER TABLE `qualifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `qualifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,3),(2,3),(3,3),(4,3),(5,3),(6,3),(7,3),(8,3),(9,3),(10,3),(11,3),(12,3),(13,3),(14,3),(15,3),(16,3),(17,3),(18,3),(19,3),(20,3),(21,3),(22,3),(23,3),(24,3),(25,3),(26,3),(27,3),(28,3),(29,3),(30,3),(31,3),(32,3),(33,3),(34,3),(35,3),(36,3),(37,3),(38,3),(39,3),(40,3),(41,3),(42,3),(43,3),(44,3),(45,3),(46,3),(47,3),(48,3),(49,3),(50,3),(51,3),(52,3),(53,3),(54,3),(55,3),(56,3),(57,3),(58,3),(59,3),(10,5),(11,5),(12,5),(13,5),(17,5),(18,5),(20,5),(21,5),(26,5),(27,5),(30,5),(31,5),(32,5),(33,5),(35,5),(37,5),(38,5),(44,5),(46,5),(47,5),(48,5),(50,5),(51,5),(59,5),(7,6),(8,6),(11,6),(12,6),(13,6),(14,6),(15,6),(16,6),(17,6),(18,6),(19,6),(20,6),(21,6),(22,6),(23,6),(24,6),(25,6),(26,6),(27,6),(28,6),(29,6),(30,6),(31,6),(32,6),(33,6),(37,6),(38,6),(46,6),(47,6),(48,6),(51,6),(59,6),(32,7),(37,7),(46,7),(48,7),(12,8),(13,8),(14,8),(15,8),(37,8),(48,8),(49,8),(52,8),(53,8),(54,8),(55,8),(56,8),(57,8),(6,9),(32,10),(33,10),(6,11),(7,11),(8,11),(9,11),(10,11),(11,11),(12,11),(13,11),(14,11),(15,11),(22,11),(27,11),(32,11),(37,11),(41,11),(48,11),(50,11),(10,12),(12,12),(13,12),(17,12),(27,12),(32,12),(37,12),(48,12);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `system_type_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (3,'Administrator','web','Administrator role.','2019-06-19 11:36:40','2020-02-10 12:35:37',1),(5,'Assessor','web','performs learners assessment','2019-10-17 11:25:45','2019-10-17 11:25:45',3),(6,'Tutor','web','Tutor','2020-01-27 13:44:01','2020-01-27 13:44:01',2),(7,'Student','web','Learner record, should have access to his/her own information.','2020-01-27 13:45:08','2020-01-27 13:45:08',5),(8,'Verifier','web','IQA role','2020-01-27 13:47:24','2020-01-27 13:47:24',4),(9,'HR Manager','web','New Role.','2020-02-10 12:38:36','2020-02-10 12:38:36',8),(10,'External Quality Assessor','web','External Quality Assessor','2021-09-30 21:42:00','2021-09-30 21:42:00',NULL),(11,'Manager','web','Manager role which can view information. Manager role has to be linked with Assessors, Tutors, or Verifiers for caseloading.','2024-12-16 17:38:15','2024-12-16 17:38:15',NULL),(12,'Centre Monitor','web',NULL,'2025-02-03 15:53:06','2025-02-03 15:53:06',NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  UNIQUE KEY `sessions_id_unique` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_development_support`
--

DROP TABLE IF EXISTS `staff_development_support`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `staff_development_support` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `support_to_id` bigint(20) unsigned NOT NULL,
  `support_from_id` bigint(20) unsigned NOT NULL,
  `support_type` varchar(50) DEFAULT NULL,
  `provision_date` date NOT NULL,
  `duration` time NOT NULL,
  `details` text,
  `support_to_sign` tinyint(4) DEFAULT NULL,
  `support_from_sign` tinyint(4) DEFAULT NULL,
  `support_to_sign_date` timestamp NULL DEFAULT NULL,
  `support_from_sign_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_development_support`
--

LOCK TABLES `staff_development_support` WRITE;
/*!40000 ALTER TABLE `staff_development_support` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff_development_support` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_ticket_comments`
--

DROP TABLE IF EXISTS `support_ticket_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_ticket_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `comment_text` longtext,
  `ticket_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `support_ticket_comments_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`),
  CONSTRAINT `support_ticket_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_ticket_comments`
--

LOCK TABLES `support_ticket_comments` WRITE;
/*!40000 ALTER TABLE `support_ticket_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `support_ticket_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_tickets`
--

DROP TABLE IF EXISTS `support_tickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_tickets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `content` text,
  `author_id` bigint(20) unsigned NOT NULL,
  `author_email` varchar(200) NOT NULL,
  `status_id` int(11) unsigned DEFAULT NULL,
  `priority_id` int(11) unsigned DEFAULT NULL,
  `category_id` int(11) unsigned DEFAULT NULL,
  `assigned_to_user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `status_id` (`status_id`),
  KEY `priority_id` (`priority_id`),
  KEY `assigned_to_user_id` (`assigned_to_user_id`),
  KEY `author_id` (`author_id`),
  CONSTRAINT `support_tickets_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `lookup_supp_ticket_category` (`id`),
  CONSTRAINT `support_tickets_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `lookup_supp_ticket_status` (`id`),
  CONSTRAINT `support_tickets_ibfk_3` FOREIGN KEY (`priority_id`) REFERENCES `lookup_supp_ticket_priority` (`id`),
  CONSTRAINT `support_tickets_ibfk_4` FOREIGN KEY (`assigned_to_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `support_tickets_ibfk_5` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  CONSTRAINT `support_tickets_ibfk_6` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_tickets`
--

LOCK TABLES `support_tickets` WRITE;
/*!40000 ALTER TABLE `support_tickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `support_tickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taggables`
--

DROP TABLE IF EXISTS `taggables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `taggables` (
  `tag_id` bigint(20) unsigned NOT NULL,
  `taggable_type` varchar(255) NOT NULL,
  `taggable_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taggables`
--

LOCK TABLES `taggables` WRITE;
/*!40000 ALTER TABLE `taggables` DISABLE KEYS */;
/*!40000 ALTER TABLE `taggables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_column` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telescope_entries`
--

DROP TABLE IF EXISTS `telescope_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries` (
  `sequence` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`),
  KEY `telescope_entries_family_hash_index` (`family_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_entries`
--

LOCK TABLES `telescope_entries` WRITE;
/*!40000 ALTER TABLE `telescope_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telescope_entries_tags`
--

DROP TABLE IF EXISTS `telescope_entries_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  KEY `telescope_entries_tags_entry_uuid_tag_index` (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`),
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_entries_tags`
--

LOCK TABLES `telescope_entries_tags` WRITE;
/*!40000 ALTER TABLE `telescope_entries_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_entries_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `telescope_monitoring`
--

DROP TABLE IF EXISTS `telescope_monitoring`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `telescope_monitoring` (
  `tag` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `telescope_monitoring`
--

LOCK TABLES `telescope_monitoring` WRITE;
/*!40000 ALTER TABLE `telescope_monitoring` DISABLE KEYS */;
/*!40000 ALTER TABLE `telescope_monitoring` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp`
--

DROP TABLE IF EXISTS `temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `temp` (
  `home_email` varchar(255) DEFAULT NULL,
  `sunesis_user_id` int(11) DEFAULT NULL,
  `sunesis_tr_id` int(11) DEFAULT NULL,
  `sunesis_otj_hours` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp`
--

LOCK TABLES `temp` WRITE;
/*!40000 ALTER TABLE `temp` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_session_assessments`
--

DROP TABLE IF EXISTS `test_session_assessments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `test_session_assessments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `test_session_id` bigint(20) NOT NULL,
  `assessor_id` bigint(20) NOT NULL,
  `status` varchar(15) NOT NULL,
  `comments` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_session_assessments`
--

LOCK TABLES `test_session_assessments` WRITE;
/*!40000 ALTER TABLE `test_session_assessments` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_session_assessments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_sessions`
--

DROP TABLE IF EXISTS `test_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `test_sessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_id` bigint(20) unsigned NOT NULL,
  `course_id` bigint(20) unsigned NOT NULL,
  `attempt_no` int(11) NOT NULL,
  `complete_by` date DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `status` varchar(15) NOT NULL,
  `allocated_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_sessions`
--

LOCK TABLES `test_sessions` WRITE;
/*!40000 ALTER TABLE `test_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `todo_task_communications`
--

DROP TABLE IF EXISTS `todo_task_communications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `todo_task_communications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `task_id` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `read_by_user` tinyint(2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `todo_task_communications`
--

LOCK TABLES `todo_task_communications` WRITE;
/*!40000 ALTER TABLE `todo_task_communications` DISABLE KEYS */;
/*!40000 ALTER TABLE `todo_task_communications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `todo_tasks`
--

DROP TABLE IF EXISTS `todo_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `todo_tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(70) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `belongs_to` bigint(20) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `completed` tinyint(2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `todo_tasks`
--

LOCK TABLES `todo_tasks` WRITE;
/*!40000 ALTER TABLE `todo_tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `todo_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr`
--

DROP TABLE IF EXISTS `tr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `programme_id` bigint(20) DEFAULT NULL,
  `learner_ref` varchar(15) DEFAULT NULL,
  `system_ref` varchar(15) DEFAULT NULL,
  `status_code` tinyint(1) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `epa_date` date DEFAULT NULL,
  `employer_location` bigint(20) DEFAULT NULL,
  `primary_assessor` bigint(20) DEFAULT NULL,
  `secondary_assessor` bigint(20) DEFAULT NULL,
  `verifier` bigint(20) DEFAULT NULL,
  `tutor` bigint(20) DEFAULT NULL,
  `otj_hours` int(11) DEFAULT NULL,
  `contracted_hours_per_week` float(3,1) DEFAULT NULL,
  `weeks_to_worked_per_year` float(3,1) DEFAULT NULL,
  `onefile_episode` varchar(255) DEFAULT NULL,
  `sunesis_id` int(11) DEFAULT NULL,
  `employer_user_id` bigint(20) DEFAULT NULL,
  `show_als_tab_to_employer` tinyint(2) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `tr_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr`
--

LOCK TABLES `tr` WRITE;
/*!40000 ALTER TABLE `tr` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_dp_session_ksb`
--

DROP TABLE IF EXISTS `tr_dp_session_ksb`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_dp_session_ksb` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dp_session_id` bigint(20) NOT NULL,
  `tr_pc_id` bigint(20) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `pc_title` varchar(1800) DEFAULT NULL,
  `delivery_hours` int(11) DEFAULT NULL,
  `system_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i_dp_session_id` (`dp_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_dp_session_ksb`
--

LOCK TABLES `tr_dp_session_ksb` WRITE;
/*!40000 ALTER TABLE `tr_dp_session_ksb` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_dp_session_ksb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_dp_sessions`
--

DROP TABLE IF EXISTS `tr_dp_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_dp_sessions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_id` bigint(20) DEFAULT NULL,
  `session_number` varchar(5) DEFAULT NULL,
  `session_sequence` int(11) DEFAULT NULL,
  `session_details_1` varchar(5000) DEFAULT NULL,
  `session_details_2` varchar(5000) DEFAULT NULL,
  `session_planned_hours` int(11) DEFAULT NULL,
  `student_comments` text,
  `assessor_comments` text,
  `student_sign` tinyint(2) NOT NULL DEFAULT '0',
  `student_sign_date` date DEFAULT NULL,
  `assessor_sign` tinyint(2) NOT NULL DEFAULT '0',
  `assessor_sign_date` date DEFAULT NULL,
  `actual_date` date DEFAULT NULL,
  `session_start_time` time DEFAULT NULL,
  `session_end_time` time DEFAULT NULL,
  `revised_date` date DEFAULT NULL,
  `session_start_date` date DEFAULT NULL,
  `session_end_date` date DEFAULT NULL,
  `extra_session` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i_tr_id` (`tr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_dp_sessions`
--

LOCK TABLES `tr_dp_sessions` WRITE;
/*!40000 ALTER TABLE `tr_dp_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_dp_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_evidence_assesments`
--

DROP TABLE IF EXISTS `tr_evidence_assesments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_evidence_assesments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `evidence_id` bigint(20) unsigned NOT NULL,
  `tr_id` bigint(20) unsigned NOT NULL,
  `assessment_by` char(1) NOT NULL,
  `assessment_status` tinyint(1) DEFAULT NULL,
  `assessment_comments` text NOT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i_evidence_id` (`evidence_id`),
  KEY `i_tr_id` (`tr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_evidence_assesments`
--

LOCK TABLES `tr_evidence_assesments` WRITE;
/*!40000 ALTER TABLE `tr_evidence_assesments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_evidence_assesments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_evidence_assessors_comments`
--

DROP TABLE IF EXISTS `tr_evidence_assessors_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_evidence_assessors_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `evidence_id` bigint(20) unsigned NOT NULL,
  `comments` varchar(800) DEFAULT NULL,
  `created_by` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i_evidence_id` (`evidence_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_evidence_assessors_comments`
--

LOCK TABLES `tr_evidence_assessors_comments` WRITE;
/*!40000 ALTER TABLE `tr_evidence_assessors_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_evidence_assessors_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_evidence_categories`
--

DROP TABLE IF EXISTS `tr_evidence_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_evidence_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `evidence_id` bigint(20) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i_evidence_id` (`evidence_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_evidence_categories`
--

LOCK TABLES `tr_evidence_categories` WRITE;
/*!40000 ALTER TABLE `tr_evidence_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_evidence_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_evidence_typed_submissions`
--

DROP TABLE IF EXISTS `tr_evidence_typed_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_evidence_typed_submissions` (
  `tr_evidence_id` bigint(20) unsigned NOT NULL,
  `evidence_text_content` longtext,
  PRIMARY KEY (`tr_evidence_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_evidence_typed_submissions`
--

LOCK TABLES `tr_evidence_typed_submissions` WRITE;
/*!40000 ALTER TABLE `tr_evidence_typed_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_evidence_typed_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_evidences`
--

DROP TABLE IF EXISTS `tr_evidences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_evidences` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_id` bigint(20) unsigned DEFAULT NULL,
  `evidence_name` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evidence_desc` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evidence_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint(20) DEFAULT NULL,
  `learner_comments` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `learner_sign` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `learner_declaration` tinyint(1) NOT NULL DEFAULT '0',
  `assessor_comments` mediumtext COLLATE utf8mb4_unicode_ci,
  `assessor_sign` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verifier_comments` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `assessment_method` tinyint(4) DEFAULT NULL,
  `evidence_type` tinyint(2) DEFAULT NULL,
  `evidence_url` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evidence_ref` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint(20) DEFAULT NULL,
  `evidence_files` varchar(800) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iqa_status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tr_id` (`tr_id`),
  CONSTRAINT `tr_evidences_ibfk_1` FOREIGN KEY (`tr_id`) REFERENCES `tr` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_evidences`
--

LOCK TABLES `tr_evidences` WRITE;
/*!40000 ALTER TABLE `tr_evidences` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_evidences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_folio`
--

DROP TABLE IF EXISTS `tr_folio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_folio` (
  `id` int(10) unsigned NOT NULL DEFAULT '0',
  `firstnames` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `surname` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
  `uln` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Unique Learner Number',
  `start_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_folio`
--

LOCK TABLES `tr_folio` WRITE;
/*!40000 ALTER TABLE `tr_folio` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_folio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_old`
--

DROP TABLE IF EXISTS `tr_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_old` (
  `id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `student_id` bigint(20) unsigned NOT NULL,
  `programme_id` bigint(20) DEFAULT NULL,
  `learner_ref` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `system_ref` varchar(15) CHARACTER SET latin1 DEFAULT NULL,
  `status_code` tinyint(1) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `epa_date` date DEFAULT NULL,
  `employer_location` bigint(20) DEFAULT NULL,
  `primary_assessor` bigint(20) DEFAULT NULL,
  `secondary_assessor` bigint(20) DEFAULT NULL,
  `verifier` bigint(20) DEFAULT NULL,
  `tutor` bigint(20) DEFAULT NULL,
  `otj_hours` int(11) DEFAULT NULL,
  `contracted_hours_per_week` float(3,1) DEFAULT NULL,
  `weeks_to_worked_per_year` float(3,1) DEFAULT NULL,
  `onefile_episode` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `sunesis_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_old`
--

LOCK TABLES `tr_old` WRITE;
/*!40000 ALTER TABLE `tr_old` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_status_change_logs`
--

DROP TABLE IF EXISTS `tr_status_change_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_status_change_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_id` bigint(20) DEFAULT NULL,
  `status_code_from` int(11) DEFAULT NULL,
  `status_code_to` int(11) DEFAULT NULL,
  `bil_last_day` date DEFAULT NULL,
  `bil_reason` int(11) DEFAULT NULL,
  `bil_expected_return` date DEFAULT NULL,
  `restart_date` date DEFAULT NULL,
  `revised_planned_end_date` date DEFAULT NULL,
  `revised_epa_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `achievement_date` date DEFAULT NULL,
  `learning_outcome` int(11) DEFAULT NULL,
  `withdraw_date` date DEFAULT NULL,
  `withdrawal_reason` int(11) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_status_change_logs`
--

LOCK TABLES `tr_status_change_logs` WRITE;
/*!40000 ALTER TABLE `tr_status_change_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_status_change_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_task_evidence_links`
--

DROP TABLE IF EXISTS `tr_task_evidence_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_task_evidence_links` (
  `tr_evidence_id` bigint(20) unsigned NOT NULL,
  `tr_task_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_task_evidence_links`
--

LOCK TABLES `tr_task_evidence_links` WRITE;
/*!40000 ALTER TABLE `tr_task_evidence_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_task_evidence_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_task_pcs`
--

DROP TABLE IF EXISTS `tr_task_pcs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_task_pcs` (
  `task_id` bigint(20) NOT NULL,
  `pc_id` bigint(20) NOT NULL,
  PRIMARY KEY (`task_id`,`pc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_task_pcs`
--

LOCK TABLES `tr_task_pcs` WRITE;
/*!40000 ALTER TABLE `tr_task_pcs` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_task_pcs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_tasks`
--

DROP TABLE IF EXISTS `tr_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_tasks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_id` bigint(20) unsigned NOT NULL,
  `dp_session_id` bigint(20) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `complete_by` date DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `details` text,
  `created_by` bigint(20) DEFAULT NULL,
  `learner_signed_datetime` datetime DEFAULT NULL,
  `assessor_signed_datetime` datetime DEFAULT NULL,
  `verifier_signed_datetime` datetime DEFAULT NULL,
  `learner_comments` text,
  `assessor_comments` text,
  `verifier_comments` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i_dp_session_id` (`dp_session_id`),
  KEY `i_tr_id` (`tr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_tasks`
--

LOCK TABLES `tr_tasks` WRITE;
/*!40000 ALTER TABLE `tr_tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_tasks_history`
--

DROP TABLE IF EXISTS `tr_tasks_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_tasks_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_task_id` bigint(20) DEFAULT NULL,
  `tr_id` bigint(20) DEFAULT NULL,
  `comments` text,
  `status` tinyint(4) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i_tr_task_id` (`tr_task_id`),
  KEY `i_tr_id` (`tr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_tasks_history`
--

LOCK TABLES `tr_tasks_history` WRITE;
/*!40000 ALTER TABLE `tr_tasks_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_tasks_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tr_training_plans`
--

DROP TABLE IF EXISTS `tr_training_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tr_training_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_id` bigint(20) unsigned DEFAULT NULL,
  `plan_number` tinyint(2) DEFAULT NULL,
  `plan_units` mediumtext COLLATE utf8mb4_unicode_ci,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tr_training_plans`
--

LOCK TABLES `tr_training_plans` WRITE;
/*!40000 ALTER TABLE `tr_training_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `tr_training_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_plan_mapping`
--

DROP TABLE IF EXISTS `training_plan_mapping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_plan_mapping` (
  `training_plan_id` bigint(20) unsigned NOT NULL,
  `portfolio_pc_id` bigint(20) unsigned NOT NULL,
  KEY `training_plan_id` (`training_plan_id`),
  KEY `portfolio_pc_id` (`portfolio_pc_id`),
  CONSTRAINT `training_plan_mapping_ibfk_2` FOREIGN KEY (`training_plan_id`) REFERENCES `training_plans` (`id`),
  CONSTRAINT `training_plan_mapping_ibfk_3` FOREIGN KEY (`portfolio_pc_id`) REFERENCES `portfolio_pcs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_plan_mapping`
--

LOCK TABLES `training_plan_mapping` WRITE;
/*!40000 ALTER TABLE `training_plan_mapping` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_plan_mapping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_plans`
--

DROP TABLE IF EXISTS `training_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_id` bigint(20) unsigned NOT NULL,
  `title` varchar(150) NOT NULL,
  `start_date` date DEFAULT NULL,
  `planned_end_date` date DEFAULT NULL,
  `actual_end_date` date DEFAULT NULL,
  `status` tinyint(2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `tr_id` (`tr_id`),
  CONSTRAINT `training_plans_ibfk_1` FOREIGN KEY (`tr_id`) REFERENCES `tr` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_plans`
--

LOCK TABLES `training_plans` WRITE;
/*!40000 ALTER TABLE `training_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_review_forms`
--

DROP TABLE IF EXISTS `training_review_forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_review_forms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `review_id` bigint(20) NOT NULL,
  `form_data` mediumtext,
  `assessor_signed` tinyint(2) NOT NULL DEFAULT '0',
  `learner_signed` tinyint(2) NOT NULL DEFAULT '0',
  `employer_signed` tinyint(2) NOT NULL DEFAULT '0',
  `assessor_signed_at` datetime DEFAULT NULL,
  `learner_signed_at` datetime DEFAULT NULL,
  `employer_signed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i_review_id` (`review_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_review_forms`
--

LOCK TABLES `training_review_forms` WRITE;
/*!40000 ALTER TABLE `training_review_forms` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_review_forms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `training_reviews`
--

DROP TABLE IF EXISTS `training_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `training_reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tr_id` bigint(20) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `meeting_date` date DEFAULT NULL,
  `assessor` int(11) DEFAULT NULL,
  `comments` varchar(500) DEFAULT NULL,
  `assessor_comments` longtext,
  `created_by` bigint(10) DEFAULT NULL,
  `type_of_review` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `portfolio_id` bigint(20) DEFAULT NULL,
  `learner_signed_at` timestamp NULL DEFAULT NULL,
  `assessor_signed_at` timestamp NULL DEFAULT NULL,
  `employer_signed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `i_tr_id` (`tr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `training_reviews`
--

LOCK TABLES `training_reviews` WRITE;
/*!40000 ALTER TABLE `training_reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `training_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_caseload_accounts`
--

DROP TABLE IF EXISTS `user_caseload_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_caseload_accounts` (
  `user_id` bigint(20) unsigned NOT NULL,
  `caseload_account_id` bigint(20) unsigned NOT NULL,
  `caseload_account_type` tinyint(4) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_caseload_accounts`
--

LOCK TABLES `user_caseload_accounts` WRITE;
/*!40000 ALTER TABLE `user_caseload_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_caseload_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_event_participants`
--

DROP TABLE IF EXISTS `user_event_participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_event_participants` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `status` tinyint(2) NOT NULL DEFAULT '1',
  `comments` varchar(500) DEFAULT NULL,
  `tr_id` bigint(20) DEFAULT NULL,
  `attended` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`,`event_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_event_participants`
--

LOCK TABLES `user_event_participants` WRITE;
/*!40000 ALTER TABLE `user_event_participants` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_event_participants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_events`
--

DROP TABLE IF EXISTS `user_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `event_type` tinyint(2) DEFAULT NULL,
  `event_status` tinyint(2) DEFAULT NULL,
  `description` varchar(800) DEFAULT NULL,
  `color` varchar(8) DEFAULT NULL,
  `location` varchar(250) DEFAULT NULL,
  `personal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_events`
--

LOCK TABLES `user_events` WRITE;
/*!40000 ALTER TABLE `user_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_links`
--

DROP TABLE IF EXISTS `user_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_links` (
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `linked_user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `Unique_User_Link` (`user_id`,`linked_user_id`),
  KEY `FK_linked_user_id` (`linked_user_id`),
  CONSTRAINT `FK_linked_user_id` FOREIGN KEY (`linked_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_links`
--

LOCK TABLES `user_links` WRITE;
/*!40000 ALTER TABLE `user_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `firstnames` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` tinyint(2) NOT NULL,
  `is_super` tinyint(1) NOT NULL DEFAULT '0',
  `gender` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'U',
  `web_access` tinyint(1) NOT NULL DEFAULT '0',
  `settings` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fb_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_handle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `ni` varchar(17) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uln` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_changed_at` timestamp NULL DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `ethnicity` tinyint(4) DEFAULT NULL,
  `employer_location` bigint(20) DEFAULT NULL,
  `is_support` tinyint(1) NOT NULL DEFAULT '0',
  `onefile_id` bigint(20) DEFAULT NULL,
  `sunesis_id` int(11) DEFAULT NULL,
  `support_contact_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (9,'inaam.azmat@perspective-uk.com','perspective',NULL,'$2y$10$D5U4l2NsBYvrJy4fi0uF/uAuhfNW45fRX6DxfXAATtPNrvcULqUP2',NULL,'Perspective','Admin',1,0,'U',1,NULL,NULL,NULL,'inaam.azmat@perspective-uk.com','2025-04-11 21:48:41','2025-04-11 20:57:57',NULL,NULL,NULL,'2025-04-11 20:57:57',NULL,NULL,NULL,0,NULL,NULL,NULL),(10,'irena@email.test','irenadoow',NULL,'$2y$10$8.PjTELHPV0dpFRSWRl1lOJ72kgGapa7FuvhCmmY0iEWSIrTCw1dG',NULL,'Helen','Doow',1,0,'F',1,NULL,NULL,NULL,'irena@email.test','2025-04-12 07:42:34','2025-04-12 07:42:34',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'folio_iwoodtrain'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-13 20:25:56
