/*
SQLyog Community v13.2.0 (64 bit)
MySQL - 8.0.30 : Database - jobrangers
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`jobrangers` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `jobrangers`;

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `job_applications` */

DROP TABLE IF EXISTS `job_applications`;

CREATE TABLE `job_applications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `job_id` bigint unsigned NOT NULL,
  `status` enum('applied','approved','accepted','declined','rejected','withdrawn') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'applied',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_applications_user_id_foreign` (`user_id`),
  KEY `job_applications_job_id_foreign` (`job_id`),
  CONSTRAINT `job_applications_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `job_applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_applications` */

insert  into `job_applications`(`id`,`user_id`,`job_id`,`status`,`created_at`,`updated_at`) values 
(15,1,16,'applied','2025-02-05 05:45:33','2025-02-05 05:45:33'),
(18,2,23,'rejected','2025-02-05 06:45:32','2025-02-06 12:50:49'),
(19,2,17,'rejected','2025-02-05 06:49:35','2025-02-06 12:52:51'),
(20,10,19,'rejected','2025-02-05 06:50:43','2025-02-06 12:47:32'),
(21,10,26,'accepted','2025-02-05 08:47:41','2025-02-06 12:49:46'),
(22,1,17,'rejected','2025-02-06 03:57:21','2025-02-06 13:56:41'),
(23,1,18,'rejected','2025-02-06 03:59:04','2025-02-06 13:53:55'),
(28,11,17,'rejected','2025-02-06 07:45:39','2025-02-06 12:52:51'),
(29,11,23,'accepted','2025-02-06 07:52:37','2025-02-06 12:50:49'),
(30,11,25,'rejected','2025-02-06 07:54:37','2025-02-07 06:48:45'),
(33,1,19,'applied','2025-02-06 13:17:12','2025-02-06 13:17:12'),
(34,1,26,'rejected','2025-02-06 13:17:22','2025-02-06 14:30:56'),
(35,1,27,'rejected','2025-02-06 14:02:02','2025-02-07 02:59:13'),
(36,13,31,'rejected','2025-02-06 15:31:51','2025-02-07 03:19:02'),
(37,1,32,'rejected','2025-02-07 02:54:09','2025-02-07 03:59:26'),
(38,1,31,'rejected','2025-02-07 03:00:07','2025-02-07 07:32:12'),
(39,13,33,'rejected','2025-02-07 03:22:57','2025-02-07 07:31:01'),
(40,13,30,'rejected','2025-02-07 03:23:02','2025-02-07 06:48:21'),
(41,13,32,'rejected','2025-02-07 03:58:40','2025-02-07 06:48:12'),
(42,14,35,'rejected','2025-02-07 04:40:02','2025-02-07 06:47:18'),
(43,14,36,'rejected','2025-02-07 04:40:09','2025-02-07 06:49:15'),
(44,14,37,'approved','2025-02-07 04:40:25','2025-02-07 08:26:31'),
(45,14,34,'rejected','2025-02-07 04:40:29','2025-02-07 06:47:33'),
(46,1,35,'rejected','2025-02-07 04:49:28','2025-02-07 06:47:22'),
(49,13,37,'accepted','2025-02-07 07:34:04','2025-02-07 08:28:27');

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary` decimal(8,2) NOT NULL,
  `duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `application_deadline` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `recruiter_id` int unsigned DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `latitude` float(10,6) DEFAULT NULL,
  `longitude` float(10,6) DEFAULT NULL,
  `start_time` time NOT NULL DEFAULT '09:00:00',
  `end_time` time NOT NULL DEFAULT '17:00:00',
  PRIMARY KEY (`id`),
  KEY `jobs_recruiter_id_foreign` (`recruiter_id`),
  CONSTRAINT `jobs_recruiter_id_foreign` FOREIGN KEY (`recruiter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

insert  into `jobs`(`id`,`title`,`description`,`location`,`salary`,`duration`,`application_deadline`,`created_at`,`updated_at`,`date_from`,`date_to`,`recruiter_id`,`status`,`latitude`,`longitude`,`start_time`,`end_time`) values 
(16,'Data Entry Clerk','data entry','VBest Tuition Centre, Kepong',11.00,'8 hours','2025-02-07','2025-02-05 02:52:18','2025-02-05 02:52:18','2025-02-08','2025-02-15',NULL,'open',NULL,NULL,'09:00:00','17:00:00'),
(17,'Lipat Baju','Lipatkan baju saya, saya malas','Kolej Melati',30.00,'2 hours','2025-02-09','2025-02-05 02:55:01','2025-02-06 12:52:51','2025-02-10','2025-02-10',NULL,'active',NULL,NULL,'21:00:00','23:00:00'),
(18,'Runner Miku','Amikkan barang @ Miku','Kolej Melati',5.00,'2 hours','2025-02-11','2025-02-05 02:56:31','2025-02-06 13:19:31','2025-02-12','2025-02-12',NULL,'active',NULL,NULL,'21:00:00','23:00:00'),
(19,'Kedai Air Ain','Jaga kedai air dekat DC','Dataran Cendekia',50.00,'13 hours','2025-02-12','2025-02-05 05:42:42','2025-02-05 05:42:42','2025-02-13','2025-02-16',NULL,'open',NULL,NULL,'09:00:00','22:00:00'),
(23,'Tutor ITT400','ajar subject itt400','Kolej Mawar',25.00,'2 hours','2025-02-13','2025-02-05 06:45:05','2025-02-06 12:50:49','2025-02-14','2025-02-16',5,'active',NULL,NULL,'21:00:00','23:00:00'),
(24,'Testing 1','test','shah alam',100.00,'12 hours','2025-02-12','2025-02-05 08:36:51','2025-02-06 08:23:05','2025-02-13','2025-02-14',6,'closed',0.000000,0.000000,'10:00:00','22:00:00'),
(25,'Part-Timers - Steve Madden (Pop-Up Store)','Part timers wanted for our pop-up store @ SOGO KL','SOGO, Kuala lumpur',12.00,'11 hours','2025-02-15','2025-02-05 08:39:54','2025-02-06 13:02:36','2025-02-17','2025-02-23',6,'scheduled',0.000000,0.000000,'10:00:00','21:00:00'),
(26,'Web Programmer','Web Programmer is needed for Abbey Road Programs (remote-working)\r\n\r\nWe are looking for an experienced and hardworking web programmer to join our team.\r\nContract for 1 month only','Abbey Road Programs, Kuala Lumpur',1000.00,'7 hours','2025-02-14','2025-02-05 08:46:28','2025-02-06 12:49:46','2025-02-17','2025-03-16',6,'active',0.000000,0.000000,'09:00:00','16:00:00'),
(27,'Testing 4','test','shah alam',100.00,'8 hours','2025-01-22','2025-02-06 08:06:30','2025-02-06 08:06:30','2025-01-23','2025-01-30',5,'completed',NULL,NULL,'09:00:00','17:00:00'),
(28,'testing 5','testing active job','batang kali',100.00,'8 hours','2025-02-01','2025-02-06 08:10:26','2025-02-06 08:10:26','2025-02-02','2025-02-08',6,'active',0.000000,0.000000,'09:00:00','17:00:00'),
(29,'testing 2','test 2 active','batang kali',100.00,'8 hours','2025-02-02','2025-02-06 08:52:25','2025-02-06 14:38:22','2025-02-03','2025-02-06',6,'completed',0.000000,0.000000,'09:00:00','17:00:00'),
(30,'Photographer for Convo','Hi! looking for a photographer for my convo later','UiTM Shah Alam',350.00,'3 hours','2025-02-21','2025-02-06 14:14:33','2025-02-07 03:42:49','2025-02-22','2025-02-22',6,'active',0.000000,0.000000,'15:00:00','18:00:00'),
(31,'Make-up Artist','Finding a MUA for my convo, preferred woman only around kolej mawar or melati','UiTM Shah Alam',200.00,'3 hours','2025-02-21','2025-02-06 15:31:29','2025-02-07 07:32:12','2025-02-22','2025-02-22',6,'open',0.000000,0.000000,'09:00:00','12:00:00'),
(32,'Rider Pickup Barang','Hi, saya nak kirim beli barang dekat Giant Seksyen 7. Salary tu saya bagi upah dengan kos pergi balik, x tambah harga barang tau.','Kolej Melati',100.00,'1 hour','2025-02-08','2025-02-06 16:21:58','2025-02-07 07:31:51','2025-02-08','2025-02-08',12,'open',0.000000,0.000000,'11:00:00','12:00:00'),
(33,'Runner Dobi Baju','Tolong jadi runner bawak baju saya pergi dobi','Kolej Melati',25.00,'0 hours 45 minutes','2025-02-08','2025-02-07 03:22:10','2025-02-07 07:31:01','2025-02-09','2025-02-09',6,'open',0.000000,0.000000,'20:00:00','20:45:00'),
(34,'Part time Receptionist','corporate receptionist\r\nat least 2 years of experience in related field\r\nwork monday to friday office hour\r\n8am-5pm\r\n( 24 feb - 3 March )- The Ascent, Paradigm \r\n\r\nJob Type: Temporary\r\nContract length: 3 days\r\n\r\nPay: RM100.00 per day\r\n\r\nSchedule:\r\n\r\nDay shift','DAMANSARA UPTOWN RETAIL CENTRE SDN BHD, Bandar Utama',100.00,'10 hours','2025-02-22','2025-02-07 04:33:09','2025-02-07 07:20:40','2025-02-24','2025-03-03',9,'open',0.000000,0.000000,'08:00:00','18:00:00'),
(35,'Bookfair Part Timer','Part Time - BookFest (Looking for Promoter/ Retail Assistant/ Cashier)\r\n26/03/2025 to 07/04/2025 (9:30am – 10:00pm) (MUST BE ABLE TO WORK FOR FULL PERIOD)\r\nSalary: - RM120/RM130 per day (depends on position)\r\nFixed Shift!\r\n\r\nRequirement: -\r\n- Age: 16 - 35\r\n- Malaysian ONLY\r\n\r\nPlease send and enquiries to me about the job. only selected applicants will be chosen.','KLCC Convention Center',130.00,'12 hours 30 minutes','2025-03-22','2025-02-07 04:36:29','2025-02-07 04:36:29','2025-03-26','2025-04-07',9,'open',0.000000,0.000000,'09:30:00','22:00:00'),
(36,'Bookfair Part Timer 2','Part Time - BookFest (Looking for Promoter/ Retail Assistant/ Cashier)\r\n26/03/2025 to 07/04/2025 (9:30am – 10:00pm) (MUST BE ABLE TO WORK FOR FULL PERIOD)\r\nSalary: - RM120/RM130 per day (depends on position)\r\nFixed Shift!\r\n\r\nRequirement: -\r\n- Age: 16 - 35\r\n- Malaysian ONLY\r\n\r\nPlease send and enquiries to me about the job. only selected applicants will be chosen.','KLCC Convention Center',130.00,'12 hours 30 minutes','2025-03-22','2025-02-07 04:37:50','2025-02-07 07:11:34','2025-03-26','2025-04-07',9,'open',0.000000,0.000000,'09:30:00','22:00:00'),
(37,'Part Time Production Crew Central Kitchen','He or She have to perform manufacturing operations and processes to foods perform packaging, operate machines manually or automatically, follow predetermined procedures, and take food safety regulations on board.\r\n\r\nPayment : RM8 per hour\r\nExpected hours: 24 – 45 per week','Simple Bites Sdn Bhd, Selayang',10.00,'3 hours','2025-02-14','2025-02-07 04:39:30','2025-02-09 15:08:54','2025-02-15','2025-02-17',9,'open',0.000000,0.000000,'15:00:00','18:00:00'),
(39,'testing mapbox','test mapbox','40450, Shah Alam, Selangor, Malaysia',10.00,'8 hours','2025-02-07','2025-02-07 17:08:29','2025-02-08 11:38:12','2025-02-08','2025-02-08',6,'completed',3.071085,101.497978,'09:00:00','17:00:00'),
(40,'tetsing mapbox admin','testing','40450, Shah Alam, Selangor, Malaysia',10.00,'8 hours','2025-02-08','2025-02-07 17:12:40','2025-02-07 17:12:40','2025-02-08','2025-02-08',5,'open',3.071040,101.497978,'09:00:00','17:00:00'),
(41,'tetsing mapbox admin','FSKM','40450, Shah Alam, Selangor, Malaysia',10.00,'8 hours','2025-02-08','2025-02-07 17:13:41','2025-02-07 17:13:41','2025-02-08','2025-02-08',5,'open',3.072127,101.499504,'09:00:00','17:00:00'),
(42,'tetsing mapbox admin','test','40000, Shah Alam, Selangor, Malaysia',10.00,'8 hours','2025-02-08','2025-02-07 17:15:30','2025-02-07 17:50:40','2025-02-08','2025-02-08',5,'open',NULL,NULL,'09:00:00','17:00:00'),
(43,'Florist - Frontdesk','Jaga kedai and kaunter @ Lee Wah Florist','50000, Kuala Lumpur, Malaysia',180.00,'6 hours','2025-02-13','2025-02-08 11:29:13','2025-02-08 11:42:21','2025-02-14','2025-02-16',6,'open',3.144755,101.696503,'15:00:00','21:00:00');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(9,'2014_10_12_000000_create_users_table',1),
(10,'2014_10_12_100000_create_password_reset_tokens_table',1),
(11,'2014_10_12_100000_create_password_resets_table',1),
(12,'2019_08_19_000000_create_failed_jobs_table',1),
(13,'2019_12_14_000001_create_personal_access_tokens_table',1),
(14,'2024_10_22_093625_add_role_to_users_table',1),
(15,'2024_11_04_025231_create_jobs_table',1),
(16,'2024_11_06_155354_drop_role_column_from_users_table',1),
(17,'2024_11_25_054312_rename_type_to_role_in_users_table',2),
(18,'2024_11_25_132921_create_permission_tables',3),
(21,'2024_12_04_084729_add_date_columns_to_jobs_table',4),
(22,'2024_12_05_180333_create_user_logs_table',5),
(23,'2024_12_11_154658_create_job_applications_table',6),
(24,'2024_12_14_161510_create_schedules_table',7),
(25,'2025_01_01_091750_add_description_to_schedules_table',8),
(26,'2025_01_16_095234_create_notifications_table',9),
(27,'2025_01_17_163343_add_status_to_jobs_table',10),
(28,'2025_01_18_022056_create_notifications_table',11),
(29,'2025_01_18_022853_add_user_id_to_notifications_table',12),
(30,'2025_01_18_113635_add_schedule_date_to_schedules_table',13),
(31,'2025_02_05_023052_add_time_fields_to_jobs_table',14),
(32,'2025_02_06_061704_create_recruiter_applications_table',15),
(33,'2025_02_06_064328_add_recruiter_request_status_to_users_table',15),
(34,'2025_02_07_041102_update_job_applications_status_options',16),
(35,'2025_02_07_065820_update_jobs_status_enum',17);

/*Table structure for table `model_has_permissions` */

DROP TABLE IF EXISTS `model_has_permissions`;

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_permissions` */

/*Table structure for table `model_has_roles` */

DROP TABLE IF EXISTS `model_has_roles`;

CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `model_has_roles` */

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `notifications` */

insert  into `notifications`(`id`,`user_id`,`type`,`notifiable_type`,`notifiable_id`,`data`,`read_at`,`created_at`,`updated_at`) values 
('a25365a1-9670-4a8d-9278-bf702797d92f',13,'App\\Notifications\\NewRecruiterRequest','App\\Models\\User',8,'{\"user_id\":13,\"message\":\"New recruiter application request from Nur Rabia\'tuladawiyah\",\"type\":\"recruiter_request\"}',NULL,'2025-02-08 06:06:19','2025-02-08 06:06:19'),
('e30c0c77-e37b-4c15-975e-5a4479f18b2c',13,'App\\Notifications\\NewRecruiterRequest','App\\Models\\User',5,'{\"user_id\":13,\"message\":\"New recruiter application request from Nur Rabia\'tuladawiyah\",\"type\":\"recruiter_request\"}',NULL,'2025-02-08 06:06:19','2025-02-08 06:06:19');

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `permissions` */

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `permissions` */

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

/*Table structure for table `recruiter_applications` */

DROP TABLE IF EXISTS `recruiter_applications`;

CREATE TABLE `recruiter_applications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `recruiter_applications` */

/*Table structure for table `role_has_permissions` */

DROP TABLE IF EXISTS `role_has_permissions`;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `role_has_permissions` */

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

/*Table structure for table `schedules` */

DROP TABLE IF EXISTS `schedules`;

CREATE TABLE `schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL,
  `repeat` enum('none','daily','weekly','yearly') COLLATE utf8mb4_unicode_ci DEFAULT 'none',
  `repeat_days` json DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `schedules` */

insert  into `schedules`(`id`,`user_id`,`title`,`start`,`end`,`repeat`,`repeat_days`,`description`,`created_at`,`updated_at`,`schedule_date`) values 
(4,5,'ITT420','08:00:00','10:00:00','none',NULL,NULL,'2025-01-01 17:06:28','2025-01-01 17:06:28',NULL),
(5,5,'Nur Huda Thai Tomyam - Cuci Pinggan','13:14:00','21:14:00','none',NULL,NULL,'2025-01-01 17:14:23','2025-01-01 17:14:23',NULL),
(9,1,'ICT652','08:00:00','10:00:00','weekly','[\"tuesday\"]','Lab Class','2025-02-06 02:46:42','2025-02-07 15:40:17','2025-02-06'),
(10,10,'Appointment Klinik Gigi','09:00:00','12:00:00','none',NULL,NULL,'2025-02-06 03:13:46','2025-02-06 03:13:46','2025-02-07'),
(11,10,'sekolah','07:00:00','13:00:00','daily',NULL,NULL,'2025-02-06 03:14:37','2025-02-06 03:14:37',NULL),
(12,13,'Kerja Hakiki','09:00:00','17:00:00','weekly','[\"monday\", \"tuesday\", \"wednesday\", \"thursday\", \"friday\"]','Kerja la weh','2025-02-07 07:48:07','2025-02-07 16:57:11',NULL),
(13,13,'Monthly meeting','11:00:00','13:00:00','none',NULL,'Meeting with the team and stakeholders','2025-02-07 07:56:41','2025-02-07 08:29:50','2025-02-08'),
(14,5,'Lepak','20:15:00','23:00:00','none',NULL,NULL,'2025-02-07 08:18:29','2025-02-07 08:18:29','2025-02-08'),
(17,1,'ITT420','10:00:00','12:00:00','weekly','[\"wednesday\"]','Kelas Lab','2025-02-07 15:37:56','2025-02-07 15:38:16',NULL),
(18,1,'ITT420','08:00:00','10:00:00','weekly','[\"monday\"]','Mass Lecture','2025-02-07 15:39:18','2025-02-07 15:39:18',NULL),
(19,1,'ICT652','08:00:00','10:00:00','weekly','[\"friday\"]','Mass Lecture','2025-02-07 15:40:52','2025-02-07 15:40:52',NULL),
(20,1,'CSP650','10:00:00','12:00:00','weekly','[\"monday\"]','Mass Lecture','2025-02-07 15:41:32','2025-02-07 15:41:32',NULL),
(21,1,'CSP650','10:00:00','12:00:00','weekly','[\"thursday\"]','Lab class','2025-02-07 15:42:06','2025-02-07 15:42:06',NULL),
(22,1,'ENT600','14:00:00','17:00:00','weekly','[\"thursday\"]','Mass Class','2025-02-07 15:42:41','2025-02-07 15:42:41',NULL),
(23,1,'ITT632','16:00:00','18:00:00','weekly','[\"wednesday\"]','Lab Class','2025-02-07 15:43:14','2025-02-07 15:43:14',NULL),
(24,1,'ITT632','14:00:00','16:00:00','weekly','[\"tuesday\"]','Mass Lecture','2025-02-07 15:44:34','2025-02-07 15:44:34',NULL),
(25,1,'Lepak','20:00:00','23:00:00','none',NULL,'Lepak with Haura','2025-02-07 15:47:56','2025-02-07 15:47:56','2025-02-07');

/*Table structure for table `user_logs` */

DROP TABLE IF EXISTS `user_logs`;

CREATE TABLE `user_logs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `user_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `user_logs` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci,
  `role` tinyint NOT NULL DEFAULT '0',
  `profile_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duitnow_qr` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recruiter_request_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`,`phone_number`,`address`,`role`,`profile_picture`,`emergency_contact`,`bank_account_name`,`bank_account_number`,`bank_name`,`duitnow_qr`,`recruiter_request_status`) values 
(1,'NURIN EWANI BINTI SHAHUDIN','nurinewani@gmail.com',NULL,'$2y$12$76.97n94TrHDDmqQqyoO8OfyRcfIv6LAYf026Y3EekTIBc2thXENC','ebq55LC2GBepfwbc0EzR9McnqLi86moyC8J1ZUhEkRtIIUp1mbptaJZ56rhH','2024-11-25 01:57:39','2025-02-06 07:31:15','01110380118','No 17, Jln Meranti 3B, Fasa 2, Bdr Utama Batang Kali, 44300 Batang Kali, Hulu Selangor, Selangor D.E',0,NULL,NULL,'Maybank','1234567890',NULL,NULL,'approved'),
(2,'User','user@user.com',NULL,'$2y$12$Me7VK6R/pxZsaGiOhNk6Puv98uutgBnijLRNSm0S4ziKH0LpvmgRG','8m5PUUhGdaIZpKvkrhMI06u0NyuJV6vTEEHo2gtEFBI1CCZpciDAxR1TCc92','2024-11-25 01:58:37','2025-02-08 06:22:49','1234567890','shah alam',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(3,'Recruiter','recruiter@recruiter.com',NULL,'$2y$12$vtkHbjAQK1GlU5dgcHE.FOS4rq9z6rcV24xLnJPBSEjaa/On/GNrq',NULL,'2024-11-25 01:59:05','2024-12-05 21:05:37','1234567890','shah alam',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(5,'Admin User','admin@example.com',NULL,'$2y$12$G1/0do2crrFzNQ2ubE8ZkOZwg0mase/k/lBWK3UgzKt019DYMwoo.','lDJqWd4ANWTPgBp56uPvNZT5PfL7aWQvOWu4kReE192i6pyhXxWB8CqgBhIp','2024-11-25 05:43:48','2025-02-05 15:53:00','012-3456789','123 Admin Street, Kuala Lumpur, Malaysia',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(6,'Recruiter User','recruiter@example.com',NULL,'$2y$12$wdvjC99NfruLIjJ98jdPSus2dHqeY46xD8Uy.cNLZVewyi5PvS9p.',NULL,'2024-11-25 05:43:48','2024-11-25 05:43:48','011-9876543','456 Recruiter Lane, Shah Alam, Malaysia',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(7,'User','user@example.com',NULL,'$2y$12$Igy8tEoRn706DP8Q8tB2EeNs/GEjHHFpn8Q/z/khhJjUOvHM8ifnS','FFNtl5R8ZxkUmhmrNl9AenMqUjuKhOYOqeeB5iJ69pmtwCkwYKARxaIhz8kM','2024-11-25 05:43:48','2024-12-05 16:32:45','019-8765432','789 User Drive, Penang, Malaysia',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(8,'Nur Najiha','najiha@example.com',NULL,'$2y$12$EqLXSsA5SI.jJYdIb/pp8eDjAqy57vyM1ud8merfAICqT3dyPbNIW',NULL,'2024-12-05 15:53:52','2024-12-05 21:22:12','0182928044','No 17, Jln Meranti 3B, Fasa 2, Bdr Utama Batang Kali, 44300 Batang Kali, Hulu Selangor, Selangor D.E',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(9,'Nur Adylla','adylla@example.com',NULL,'$2y$12$kajDLDbCIVnAInRuvc9l9.ZQUYNGqXGo2KVwtvDJp6cCKLrFemC.m',NULL,'2024-12-05 15:56:14','2024-12-05 21:22:39','011-10370117','No 17, Jln Meranti 3B, Fasa 2, Bdr Utama Batang Kali, 44300 Batang Kali, Hulu Selangor, Selangor D.E',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(10,'Muhammad Ammar','ammar@user.com',NULL,'$2y$12$KMGBN3LVrrG3Qdhnn356RuAdIRdK15.YWJpC6gKq3WhPVqJEw2unO','eM5Po853lODORwUKxG3U6hzqKJ9MPWVuKPzWKEv1LIktoGRtTuthSkiVO4BI','2024-12-05 21:21:03','2024-12-05 21:22:29','019-2242724','No 17, Jln Meranti 3B, Fasa 2, Bdr Utama Batang Kali, 44300 Batang Kali, Hulu Selangor, Selangor D.E',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(11,'Nursakinah Abd Aziz','nursakinah@user.com',NULL,'$2y$12$Ebd3yp7/f80g3aZaevzTZ.g5xSeP3ovOLqafrpArHkKX2VZfuyE3y','EKkl1FQdgFTIdIKVyIfb1wzr8aUIO8WQYMj4qfBnWJU2L0VTRngqdyU5Lpkp','2025-02-06 06:52:40','2025-02-06 06:52:40','01234567890','Batang Kali, Selangor',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(12,'Izzatul Widad','widad@user.com',NULL,'$2y$12$Jmdyt0VX2pC8.Clye.jYqu3t7qDhM7a9GmeLQtntPWOXnoMFF8V16',NULL,'2025-02-06 07:03:09','2025-02-07 02:38:41','012-34567890','shah alam',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(13,'Nur Rabia\'tuladawiyah','rabiatul@user.com',NULL,'$2y$12$ixrOzvJiIOiMcjRjFh39xuJlahHXNAMA6fZY/poHKUnhCsdO/24y.',NULL,'2025-02-06 07:04:47','2025-02-08 06:12:06','1234567890','Puncak Alam, Sg Buloh',0,NULL,NULL,'Maybank','1234567890','Nur Rabia\'tuladawiyah','Qr-fake.png','rejected'),
(14,'Nur Ain Farhana','nafmt@user.com',NULL,'$2y$12$k6hoQ.RC/jrMlgZwML1LCOTIgh1I71y.R.5MHj7cniQpGek38Byj2',NULL,'2025-02-06 07:05:55','2025-02-06 07:05:55','12345678','Batang Kali, Selangor',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
(15,'tester2','tester@test.com',NULL,'$2y$12$qWAmdijX4n2nJHASE8XNyuukqjsfINV8U7vIUZq4XgbgsViSpwGHK',NULL,'2025-02-06 07:28:25','2025-02-06 07:28:25','01110370117','test',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
