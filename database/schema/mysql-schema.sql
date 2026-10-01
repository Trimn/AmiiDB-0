/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `action` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(255) NOT NULL,
  `table` varchar(255) NOT NULL,
  `from` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`from`)),
  `to` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`to`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `action` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `affiliates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `affiliates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `affiliation` varchar(255) DEFAULT NULL,
  `alternate_email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `affiliates_pid_foreign` (`pid`),
  CONSTRAINT `affiliates_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `people` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `appt_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appt_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appt_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_appt_payments` (`appt_id`,`payment_id`),
  KEY `fk_appt_payments_payments` (`payment_id`),
  CONSTRAINT `fk_appt_payments_payments` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_appt_payments_student_appt` FOREIGN KEY (`appt_id`) REFERENCES `student_appt` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `appt_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appt_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `awards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `awards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `amount` double NOT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `awards_pid_foreign` (`pid`),
  CONSTRAINT `awards_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `people` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cfs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cfs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `fid` int(11) NOT NULL,
  `speedcode` varchar(10) NOT NULL,
  `po` varchar(32) NOT NULL,
  `amount` decimal(19,4) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `remaining` decimal(19,4) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `desc` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cfs_fid_foreign` (`fid`),
  KEY `cfs_speedcode_foreign` (`speedcode`),
  CONSTRAINT `cfs_fid_foreign` FOREIGN KEY (`fid`) REFERENCES `fellows` (`id`),
  CONSTRAINT `cfs_speedcode_foreign` FOREIGN KEY (`speedcode`) REFERENCES `speedcodes` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `country` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
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
DROP TABLE IF EXISTS `fellows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fellows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `supid` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT curdate(),
  `report_id` varchar(100) DEFAULT NULL,
  `assistant_name` varchar(100) DEFAULT NULL,
  `assistant_email` varchar(100) DEFAULT NULL,
  `start` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `committees` varchar(255) DEFAULT NULL,
  `dept` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_fellows_people` (`pid`),
  CONSTRAINT `fk_fellows_people` FOREIGN KEY (`pid`) REFERENCES `people` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `gender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gender` (
  `gender` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`gender`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `immigration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `immigration` (
  `code` varchar(10) NOT NULL,
  `description` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT curdate(),
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `speedcode` varchar(10) NOT NULL,
  `amount` double NOT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_payments_speedcodes` (`speedcode`),
  KEY `fk_payments_people` (`pid`),
  CONSTRAINT `fk_payments_people` FOREIGN KEY (`pid`) REFERENCES `people` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_payments_speedcodes` FOREIGN KEY (`speedcode`) REFERENCES `speedcodes` (`code`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `people`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `people` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `uid` varchar(20) NOT NULL,
  `ccid` varchar(20) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `citizenship` varchar(100) DEFAULT NULL,
  `immigration` varchar(10) DEFAULT NULL,
  `amii_start` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `supervisor` int(11) DEFAULT NULL,
  `supervisor2` varchar(255) DEFAULT NULL,
  `wp_start` date DEFAULT NULL,
  `wp_end` date DEFAULT NULL,
  `amii_end` date DEFAULT NULL,
  `wp_type` enum('study','work') DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_people_uid` (`uid`),
  KEY `fk_people_gender` (`gender`),
  KEY `fk_people_countries` (`citizenship`),
  KEY `fk_people_immigration` (`immigration`),
  KEY `people_supervisor_foreign` (`supervisor`),
  CONSTRAINT `fk_people_countries` FOREIGN KEY (`citizenship`) REFERENCES `countries` (`country`) ON UPDATE CASCADE,
  CONSTRAINT `fk_people_gender` FOREIGN KEY (`gender`) REFERENCES `gender` (`gender`) ON UPDATE CASCADE,
  CONSTRAINT `fk_people_immigration` FOREIGN KEY (`immigration`) REFERENCES `immigration` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `people_supervisor_foreign` FOREIGN KEY (`supervisor`) REFERENCES `fellows` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Base entity for students and staff.';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `program`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `program` (
  `program` varchar(10) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`program`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(5) NOT NULL,
  `program` varchar(10) NOT NULL,
  `program_year` int(11) NOT NULL,
  `salary_step` int(11) NOT NULL,
  `rate_type` enum('CS','Amii','Top','Post') NOT NULL,
  `immigration` varchar(10) NOT NULL,
  `cs_award` decimal(19,4) NOT NULL,
  `cs_salary` decimal(19,4) NOT NULL,
  `amii_topup` decimal(19,4) NOT NULL,
  `int_idf` decimal(19,4) NOT NULL,
  `int_amii` decimal(19,4) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rates_term_foreign` (`term`),
  KEY `rates_immigration_foreign` (`immigration`),
  KEY `rates_program_foreign` (`program`),
  CONSTRAINT `rates_immigration_foreign` FOREIGN KEY (`immigration`) REFERENCES `immigration` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `rates_program_foreign` FOREIGN KEY (`program`) REFERENCES `program` (`program`) ON UPDATE CASCADE,
  CONSTRAINT `rates_term_foreign` FOREIGN KEY (`term`) REFERENCES `terms` (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `rates_view`;
/*!50001 DROP VIEW IF EXISTS `rates_view`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `rates_view` AS SELECT
 1 AS `id`,
  1 AS `term`,
  1 AS `program`,
  1 AS `program_year`,
  1 AS `salary_step`,
  1 AS `rate_type`,
  1 AS `immigration`,
  1 AS `cs_award`,
  1 AS `cs_salary`,
  1 AS `amii_topup`,
  1 AS `int_idf`,
  1 AS `int_amii`,
  1 AS `notes`,
  1 AS `created_at`,
  1 AS `updated_at`,
  1 AS `rate_code`,
  1 AS `rate_amount` */;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `sba`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sba` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `pid` int(11) NOT NULL,
  `reason_code` varchar(3) NOT NULL,
  `reason` text NOT NULL,
  `debit_speedcode` varchar(10) NOT NULL,
  `credit_speedcode` varchar(10) NOT NULL,
  `amount` decimal(19,4) NOT NULL,
  `budget_holder` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sba_pid_foreign` (`pid`),
  KEY `sba_debit_speedcode_foreign` (`debit_speedcode`),
  KEY `sba_credit_speedcode_foreign` (`credit_speedcode`),
  CONSTRAINT `sba_credit_speedcode_foreign` FOREIGN KEY (`credit_speedcode`) REFERENCES `speedcodes` (`code`),
  CONSTRAINT `sba_debit_speedcode_foreign` FOREIGN KEY (`debit_speedcode`) REFERENCES `speedcodes` (`code`),
  CONSTRAINT `sba_pid_foreign` FOREIGN KEY (`pid`) REFERENCES `people` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `speedcodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `speedcodes` (
  `code` varchar(10) NOT NULL,
  `fellow` int(11) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `project` varchar(20) DEFAULT NULL,
  `combo_code` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `award_start` date DEFAULT NULL,
  `award_end` date DEFAULT NULL,
  `status` enum('Active','Inactive','Pending') NOT NULL DEFAULT 'Active',
  PRIMARY KEY (`code`),
  KEY `fk_speedcodes_fellows` (`fellow`),
  CONSTRAINT `fk_speedcodes_fellows` FOREIGN KEY (`fellow`) REFERENCES `fellows` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `notes` varchar(600) DEFAULT NULL,
  `pos_type` int(11) NOT NULL,
  `subtype` int(11) NOT NULL,
  `active` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `job_title` varchar(255) DEFAULT NULL,
  `dept` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_staff_types` (`pid`,`pos_type`,`subtype`),
  KEY `fk_staff_staff_types` (`pos_type`),
  KEY `fk_staff_staff_subtypes` (`subtype`),
  KEY `fk_staff_status` (`active`),
  CONSTRAINT `fk_staff_people` FOREIGN KEY (`pid`) REFERENCES `people` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_staff_staff_subtypes` FOREIGN KEY (`subtype`) REFERENCES `staff_subtypes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_staff_staff_types` FOREIGN KEY (`pos_type`) REFERENCES `staff_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_staff_status` FOREIGN KEY (`active`) REFERENCES `status` (`name`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `staff_appt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_appt` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `rate` decimal(19,4) NOT NULL,
  `grade` int(11) DEFAULT NULL,
  `step` double DEFAULT NULL,
  `hours` double(8,2) NOT NULL,
  `hourly` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `speedcode_1` varchar(10) NOT NULL,
  `speedcode_1_prc` double NOT NULL,
  `speedcode_2` varchar(10) DEFAULT NULL,
  `speedcode_2_prc` double DEFAULT NULL,
  `speedcode_3` varchar(10) DEFAULT NULL,
  `speedcode_3_prc` double DEFAULT NULL,
  `benefits` decimal(19,4) NOT NULL DEFAULT 0.0000,
  PRIMARY KEY (`id`),
  KEY `staff_appt_staff_id_foreign` (`staff_id`),
  KEY `staff_appt_speedcode_1_foreign` (`speedcode_1`),
  KEY `staff_appt_speedcode_2_foreign` (`speedcode_2`),
  KEY `staff_appt_speedcode_3_foreign` (`speedcode_3`),
  CONSTRAINT `staff_appt_speedcode_1_foreign` FOREIGN KEY (`speedcode_1`) REFERENCES `speedcodes` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `staff_appt_speedcode_2_foreign` FOREIGN KEY (`speedcode_2`) REFERENCES `speedcodes` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `staff_appt_speedcode_3_foreign` FOREIGN KEY (`speedcode_3`) REFERENCES `speedcodes` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `staff_appt_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `staff_appt_view`;
/*!50001 DROP VIEW IF EXISTS `staff_appt_view`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `staff_appt_view` AS SELECT
 1 AS `id`,
  1 AS `staff_id`,
  1 AS `start`,
  1 AS `end`,
  1 AS `rate`,
  1 AS `grade`,
  1 AS `step`,
  1 AS `hours`,
  1 AS `hourly`,
  1 AS `created_at`,
  1 AS `updated_at`,
  1 AS `speedcode_1`,
  1 AS `speedcode_1_prc`,
  1 AS `speedcode_2`,
  1 AS `speedcode_2_prc`,
  1 AS `speedcode_3`,
  1 AS `speedcode_3_prc`,
  1 AS `benefits`,
  1 AS `total_pay` */;
SET character_set_client = @saved_cs_client;
DROP TABLE IF EXISTS `staff_subtypes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_subtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `staff_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `status` (
  `name` varchar(100) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `program` varchar(10) NOT NULL,
  `program_start` varchar(5) NOT NULL,
  `dept` varchar(100) NOT NULL,
  `notes` varchar(600) DEFAULT NULL,
  `active` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phd_post` tinyint(1) DEFAULT NULL,
  `curr_step` int(11) NOT NULL DEFAULT 0,
  `term_adj` int(11) DEFAULT NULL,
  `gf_last` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_program` (`program`),
  KEY `fk_student_status` (`active`),
  KEY `fk_student_people` (`pid`),
  KEY `student_gf_last_foreign` (`gf_last`),
  CONSTRAINT `fk_student_people` FOREIGN KEY (`pid`) REFERENCES `people` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_student_program` FOREIGN KEY (`program`) REFERENCES `program` (`program`) ON UPDATE CASCADE,
  CONSTRAINT `fk_student_status` FOREIGN KEY (`active`) REFERENCES `status` (`name`) ON UPDATE CASCADE,
  CONSTRAINT `student_gf_last_foreign` FOREIGN KEY (`gf_last`) REFERENCES `terms` (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `student_appt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `student_appt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `term` varchar(5) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `appt_type` int(11) NOT NULL,
  `eform` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `speedcode_1` varchar(10) NOT NULL,
  `speedcode_1_prc` double NOT NULL,
  `speedcode_2` varchar(10) DEFAULT NULL,
  `speedcode_2_prc` double DEFAULT NULL,
  `speedcode_3` varchar(10) DEFAULT NULL,
  `speedcode_3_prc` double DEFAULT NULL,
  `rate` bigint(20) unsigned DEFAULT NULL,
  `rate_adj` decimal(19,4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_student_appt_student` (`sid`),
  KEY `fk_student_appt_terms` (`term`),
  KEY `fk_student_appt_appt_type` (`appt_type`),
  KEY `student_appt_speedcode_1_foreign` (`speedcode_1`),
  KEY `student_appt_speedcode_2_foreign` (`speedcode_2`),
  KEY `student_appt_speedcode_3_foreign` (`speedcode_3`),
  KEY `student_appt_rate_foreign` (`rate`),
  CONSTRAINT `fk_student_appt_appt_type` FOREIGN KEY (`appt_type`) REFERENCES `appt_type` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_student_appt_student` FOREIGN KEY (`sid`) REFERENCES `student` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_student_appt_terms` FOREIGN KEY (`term`) REFERENCES `terms` (`identifier`) ON UPDATE CASCADE,
  CONSTRAINT `student_appt_rate_foreign` FOREIGN KEY (`rate`) REFERENCES `rates` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `student_appt_speedcode_1_foreign` FOREIGN KEY (`speedcode_1`) REFERENCES `speedcodes` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `student_appt_speedcode_2_foreign` FOREIGN KEY (`speedcode_2`) REFERENCES `speedcodes` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `student_appt_speedcode_3_foreign` FOREIGN KEY (`speedcode_3`) REFERENCES `speedcodes` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `terms` (
  `identifier` varchar(5) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `year` int(11) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `number` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`identifier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `tr_date` date NOT NULL,
  `amount` double DEFAULT NULL,
  `speedcode` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `visitor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visitor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `host_paid` int(11) NOT NULL,
  `visitor_paid` int(11) NOT NULL,
  `fvca_submitted` date DEFAULT NULL,
  `ccid_requested` date DEFAULT NULL,
  `docs_sent` date DEFAULT NULL,
  `office` varchar(10) DEFAULT NULL,
  `notes` varchar(600) DEFAULT NULL,
  `active` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date DEFAULT curdate(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_visitor_pid` (`pid`),
  KEY `fk_visitor_status` (`active`),
  CONSTRAINT `fk_visitor_people` FOREIGN KEY (`pid`) REFERENCES `people` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_visitor_status` FOREIGN KEY (`active`) REFERENCES `status` (`name`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `work_permit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `work_permit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL,
  `start` date NOT NULL,
  `end` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_work_permit_staff` (`sid`),
  CONSTRAINT `fk_work_permit_staff` FOREIGN KEY (`sid`) REFERENCES `staff` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50001 DROP VIEW IF EXISTS `rates_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`amii`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `rates_view` AS select `rates`.`id` AS `id`,`rates`.`term` AS `term`,`rates`.`program` AS `program`,`rates`.`program_year` AS `program_year`,`rates`.`salary_step` AS `salary_step`,`rates`.`rate_type` AS `rate_type`,`rates`.`immigration` AS `immigration`,`rates`.`cs_award` AS `cs_award`,`rates`.`cs_salary` AS `cs_salary`,`rates`.`amii_topup` AS `amii_topup`,`rates`.`int_idf` AS `int_idf`,`rates`.`int_amii` AS `int_amii`,`rates`.`notes` AS `notes`,`rates`.`created_at` AS `created_at`,`rates`.`updated_at` AS `updated_at`,concat(`rates`.`term`,' ',`rates`.`program`,' St',`rates`.`salary_step`,' ',`rates`.`immigration`,' ',`rates`.`rate_type`) AS `rate_code`,`rates`.`cs_award` + `rates`.`cs_salary` + `rates`.`amii_topup` + `rates`.`int_idf` + `rates`.`int_amii` AS `rate_amount` from `rates` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!50001 DROP VIEW IF EXISTS `staff_appt_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`amii`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `staff_appt_view` AS select `staff_appt`.`id` AS `id`,`staff_appt`.`staff_id` AS `staff_id`,`staff_appt`.`start` AS `start`,`staff_appt`.`end` AS `end`,`staff_appt`.`rate` AS `rate`,`staff_appt`.`grade` AS `grade`,`staff_appt`.`step` AS `step`,`staff_appt`.`hours` AS `hours`,`staff_appt`.`hourly` AS `hourly`,`staff_appt`.`created_at` AS `created_at`,`staff_appt`.`updated_at` AS `updated_at`,`staff_appt`.`speedcode_1` AS `speedcode_1`,`staff_appt`.`speedcode_1_prc` AS `speedcode_1_prc`,`staff_appt`.`speedcode_2` AS `speedcode_2`,`staff_appt`.`speedcode_2_prc` AS `speedcode_2_prc`,`staff_appt`.`speedcode_3` AS `speedcode_3`,`staff_appt`.`speedcode_3_prc` AS `speedcode_3_prc`,`staff_appt`.`benefits` AS `benefits`,case when `staff_appt`.`hourly` = 1 then `staff_appt`.`rate` * (`staff_appt`.`hours` / 5) else `staff_appt`.`rate` / 260 end * (5 * ((to_days(`staff_appt`.`end`) - to_days(`staff_appt`.`start`)) DIV 7) + substr('0123455401234434012332340122123401101234000123450',7 * weekday(`staff_appt`.`start`) + weekday(`staff_appt`.`end`) + 1,1)) + `staff_appt`.`benefits` AS `total_pay` from `staff_appt` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'2014_10_12_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2014_10_12_100000_create_password_reset_tokens_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2023_08_02_161153_modify_people',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2023_08_02_164122_fellow_columns',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2023_08_10_163216_speedcodes_project_combo_code',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2023_08_10_181419_speedcodes_notes',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2023_08_19_023458_gender_fk',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2023_08_19_042947_reference_fk',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2023_08_23_163912_create_staff_appt_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2023_09_19_204216_recreate_rates__table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2023_09_25_171002_create_rates_view',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2023_09_27_202718_update_rates_view_query',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2023_10_16_164332_change_supervisor_people',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2023_10_16_210215_people_add_work_permit_dates',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2023_10_16_213011_people_add_amii_end_date',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2023_10_16_213734_remove_unique_pid_staff_students',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2023_10_16_215705_staff_remove_start_end_date',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2023_10_17_223612_change_immigration_fk_cascade',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2023_10_17_224143_change_program_fk_cascade',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2023_10_17_225548_status_fk_cascade',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2023_10_18_225058_staff_unique_type_subtype',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2023_10_18_230219_people_uid_ccid_required',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2023_10_20_194049_people_work_permit_type',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2023_10_20_200715_terms_add_number',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2023_10_20_202720_create_sba_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2023_10_23_204412_update_student_staff_fk',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2023_10_23_211310_update_appt_fk',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2023_10_23_213114_drop_supervisors_table',13);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2023_10_24_203954_add_phd_post_column_student',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2023_10_25_151053_add_curr_step_column_student',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2023_10_25_160652_student_appt_columns',14);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2023_10_30_201807_add_student_fields',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2023_10_30_214031_change_rates_datatype_decimal',15);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2023_11_01_161425_add_speedcodes_staff_appt',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2023_11_01_162444_add_columns_staff',16);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2023_11_06_180031_add_title_fellows',17);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2023_11_06_213449_change_step_datatype_staff_appt',18);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2023_11_07_224157_add_profile_image_fellows',19);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2023_11_09_235046_create_action_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2023_11_24_204646_create_cfs_table',20);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2023_11_28_165734_add_fields_fellows',21);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (45,'2023_12_04_193942_create_staff_appt_view',22);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (46,'2023_12_04_231832_fellows_add_column_dept',23);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (47,'2023_12_05_000547_make_dept_nullable',24);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (48,'2023_12_07_170905_award_columns_speecodes',25);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (49,'2023_12_08_171739_drop_pid_unique_index',26);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (50,'2023_12_08_234834_create_affiliates_table',27);
