-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: bromoijenexpeditionjava
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'Internal Identification',
  `slug` varchar(255) NOT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `subheading` varchar(255) DEFAULT NULL,
  `description` text,
  `cta_text` varchar(255) DEFAULT NULL,
  `cta_url` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'image',
  `html_content` longtext,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `image_path` varchar(255) DEFAULT NULL,
  `bg_color` varchar(255) NOT NULL DEFAULT '#ffffff',
  `overlay_color` varchar(255) NOT NULL DEFAULT 'rgba(0,0,0,0.5)',
  `placements` json DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `banners_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `author_id` bigint unsigned NOT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `excerpt` text,
  `slug` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `body` text,
  `read_time` varchar(255) DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `view_count` int NOT NULL DEFAULT '0',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `thumbnail_media_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blogs_slug_unique` (`slug`),
  KEY `blogs_author_id_foreign` (`author_id`),
  KEY `blogs_category_id_foreign` (`category_id`),
  KEY `blogs_thumbnail_media_id_foreign` (`thumbnail_media_id`),
  CONSTRAINT `blogs_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blogs_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `blogs_thumbnail_media_id_foreign` FOREIGN KEY (`thumbnail_media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,1,NULL,NULL,'Travel Guide','[\"bromo\", \"hiking\", \"sunrise\", \"tips\"]','10 Tips for Hiking Mount Bromo at Sunrise','Planning to catch the legendary Bromo sunrise? Here are essential tips to make your journey unforgettable.','10-tips-for-hiking-mount-bromo-at-sunrise','published',1,'<p>Mount Bromo is one of Indonesia&#039;s most iconic volcanoes, drawing thousands of visitors each year to witness its breathtaking sunrise. Here are 10 essential tips to ensure you have the best experience possible.</p><h2>1. Start Early</h2><p>The journey to the viewpoint starts around 3 AM. Yes, it&#039;s early, but trust us - it&#039;s worth it!</p><h2>2. Dress Warmly</h2><p>Temperatures can drop to near freezing at the summit. Layer up with warm clothing, including a jacket, gloves, and a beanie.</p><h2>3. Book Your Jeep in Advance</h2><p>4WD jeeps are the most popular way to reach the viewpoint. Book ahead during peak season to avoid disappointment.</p>','5 min read','blog-thumbnails/01KFBH9035WZ4R8TPS3SNTD7YM.jpg',0,'2026-01-13 10:26:38','2026-01-18 10:26:38','2026-01-19 09:26:43',NULL),(2,1,NULL,NULL,'Adventure','[\"ijen\", \"blue fire\", \"volcano\", \"photography\"]','Ijen Blue Fire: A Complete Guide','Everything you need to know about witnessing the mystical blue flames at Kawah Ijen.','ijen-blue-fire-complete-guide','published',1,'<p>Kawah Ijen is famous for its ethereal blue flames - a rare natural phenomenon that occurs when sulfuric gases ignite upon contact with air. This comprehensive guide will help you plan your visit.</p><h2>What is Blue Fire?</h2><p>The blue fire is caused by the combustion of sulfuric gases that emerge from cracks in the volcano at extremely high temperatures (up to 600°C).</p><h2>Best Time to Visit</h2><p>The blue flames are only visible in complete darkness, making the pre-dawn hours (2-4 AM) the ideal time to visit.</p>','7 min read',NULL,0,'2026-01-08 10:26:38','2026-01-18 10:26:38','2026-01-18 10:26:38',NULL),(3,1,NULL,NULL,'Photography','[\"photography\", \"east java\", \"landscapes\"]','Best Photography Spots in East Java','Capture stunning shots at these incredible locations across East Java.','best-photography-spots-east-java','published',0,'<p>East Java is a photographer\'s paradise, offering diverse landscapes from volcanic peaks to pristine beaches. Here are the must-visit spots for your camera.</p><h2>1. Penanjakan Viewpoint</h2><p>The classic Bromo sunrise shot - volcanic craters framed by the golden hour light.</p><h2>2. Madakaripura Waterfall</h2><p>A dramatic 200-meter waterfall surrounded by towering cliffs.</p><h2>3. Tumpak Sewu</h2><p>Often called the \"Niagara of Java\", this semicircular waterfall is absolutely breathtaking.</p>','6 min read',NULL,0,'2026-01-03 10:26:38','2026-01-18 10:26:38','2026-01-18 10:26:38',NULL),(4,1,NULL,NULL,'Tips','[\"packing\", \"travel tips\", \"preparation\"]','What to Pack for Your Bromo-Ijen Trip','A complete packing list to ensure you\'re prepared for your volcanic adventure.','what-to-pack-bromo-ijen-trip','published',0,'<p>Packing for a trip to Bromo and Ijen requires careful consideration of the unique conditions you\'ll face. Here\'s our comprehensive packing list.</p><h2>Essential Clothing</h2><ul><li>Warm jacket (temperatures can drop to 5°C)</li><li>Comfortable hiking boots</li><li>Thermal underwear</li><li>Rain jacket</li></ul><h2>Camera Gear</h2><ul><li>DSLR or mirrorless camera</li><li>Wide-angle lens (14-24mm)</li><li>Tripod for long exposures</li><li>Extra batteries (cold drains them fast!)</li></ul>','4 min read',NULL,0,'2025-12-29 10:26:38','2026-01-18 10:26:38','2026-01-18 10:26:38',NULL),(5,1,NULL,NULL,'Solo Travel','[\"solo travel\", \"safety\", \"tips\"]','Solo Travel to Bromo: Safety Tips','Traveling solo? Here\'s how to stay safe while exploring Mount Bromo.','solo-travel-bromo-safety-tips','published',0,'<p>Solo travel can be an incredibly rewarding experience. Mount Bromo is generally safe for solo travelers, but here are some tips to ensure your trip goes smoothly.</p><h2>Join a Tour Group</h2><p>Even as a solo traveler, joining a group tour can enhance safety and provide opportunities to meet other travelers.</p><h2>Stay in Touch</h2><p>Keep friends or family updated on your itinerary and check in regularly.</p>','5 min read',NULL,0,'2025-12-24 10:26:38','2026-01-18 10:26:38','2026-01-18 10:26:38',NULL),(6,1,NULL,NULL,'Culture','[\"culture\", \"tenggerese\", \"traditions\"]','The Culture and Traditions of the Tenggerese People','Learn about the fascinating culture of the indigenous people living around Mount Bromo.','culture-traditions-tenggerese-people','published',0,'<p>The Tenggerese are the indigenous people who have lived in the shadow of Mount Bromo for centuries. Their culture and traditions are deeply intertwined with the volcano they call sacred.</p><h2>Kasada Festival</h2><p>Once a year, the Tenggerese hold the Kasada ceremony, throwing offerings into the Bromo crater to honor their ancestors and seek blessings.</p><h2>Hindu-Buddhist Beliefs</h2><p>Unlike most of Java, the Tenggerese practice a unique blend of Hinduism and Buddhism.</p>','8 min read',NULL,0,'2025-12-19 10:26:38','2026-01-18 10:26:38','2026-01-18 10:26:38',NULL);
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('356a192b7913b04c54574d18c28d46e6395428ab','i:1;',1768874633),('356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1768874633;',1768874633),('livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6','i:1;',1768758466),('livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6:timer','i:1768758466;',1768758466),('spatie.permission.cache','a:3:{s:5:\"alias\";a:0:{}s:11:\"permissions\";a:0:{}s:5:\"roles\";a:0:{}}',1768961725);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL COMMENT 'blog, package',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destinations`
--

DROP TABLE IF EXISTS `destinations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `destinations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `thumbnail_media_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `destinations_slug_unique` (`slug`),
  KEY `destinations_thumbnail_media_id_foreign` (`thumbnail_media_id`),
  CONSTRAINT `destinations_thumbnail_media_id_foreign` FOREIGN KEY (`thumbnail_media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destinations`
--

LOCK TABLES `destinations` WRITE;
/*!40000 ALTER TABLE `destinations` DISABLE KEYS */;
INSERT INTO `destinations` VALUES (1,'Bromo Volcano','bromo-volcano','Experience the sunrise at Mount Bromo.','destinations/01KFB9WWTN8CVQZ9FFTN1TMHAK.jpg',1,'2026-01-18 10:26:38','2026-01-19 07:17:47',NULL),(2,'Ijen Crater','ijen-crater','Witness the blue fire phenomena.','destinations/01KFCJ8437YMZXFGMYMBBB285F.jpg',1,'2026-01-18 10:26:38','2026-01-19 19:02:58',NULL),(3,'Tumpak Sewu','tumpak-sewu','The Niagara of Indonesia.',NULL,1,'2026-01-18 10:26:38','2026-01-18 10:26:38',NULL);
/*!40000 ALTER TABLE `destinations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galleries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL COMMENT 'nature, group, transport',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image_media_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `galleries_image_media_id_foreign` (`image_media_id`),
  CONSTRAINT `galleries_image_media_id_foreign` FOREIGN KEY (`image_media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galleries`
--

LOCK TABLES `galleries` WRITE;
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;
/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hotspots`
--

DROP TABLE IF EXISTS `hotspots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotspots` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint unsigned NOT NULL,
  `destination_id` bigint unsigned DEFAULT NULL,
  `x_coordinate` decimal(5,2) NOT NULL,
  `y_coordinate` decimal(5,2) NOT NULL,
  `label_custom` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hotspots_page_id_foreign` (`page_id`),
  KEY `hotspots_destination_id_foreign` (`destination_id`),
  CONSTRAINT `hotspots_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `hotspots_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotspots`
--

LOCK TABLES `hotspots` WRITE;
/*!40000 ALTER TABLE `hotspots` DISABLE KEYS */;
/*!40000 ALTER TABLE `hotspots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inquiry_logs`
--

DROP TABLE IF EXISTS `inquiry_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inquiry_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text,
  `utm_source` varchar(255) DEFAULT NULL,
  `utm_medium` varchar(255) DEFAULT NULL,
  `utm_campaign` varchar(255) DEFAULT NULL,
  `referer_url` text,
  `clicked_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inquiry_logs_package_id_foreign` (`package_id`),
  CONSTRAINT `inquiry_logs_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inquiry_logs`
--

LOCK TABLES `inquiry_logs` WRITE;
/*!40000 ALTER TABLE `inquiry_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `inquiry_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'image',
  `mime_type` varchar(255) DEFAULT NULL,
  `size` bigint unsigned DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (1,'1760930350667-azzadiva-sawungrana-4v2qkr1u4_g-unsplash--1-.jpg','media/Cpe3JKDEed8xVfmIKuZmuzdliy7lbpxgxGW0hMB4.jpg','image','image/jpeg',955573,'1760930350667-azzadiva-sawungrana-4v2qkr1u4_g-unsplash--1-.jpg','2026-01-19 05:23:23','2026-01-19 05:23:23'),(2,'kawah-ijen-1761408821315.mp4','media/fFDkRKr0jGes8YTJdTpwUTBfMWeomPIJVVgt7Oal.mp4','video','video/mp4',39984689,'kawah-ijen-1761408821315.mp4','2026-01-19 05:25:43','2026-01-19 05:25:43'),(3,'gunung-bromo-1761408659559.mp4','media/R5EVuIPp2vHhoMQQtWgeKXI7BC87LVfBnoP81sbw.mp4','video','video/mp4',30542795,'gunung-bromo-1761408659559.mp4','2026-01-19 05:25:58','2026-01-19 05:25:58'),(4,'1760951864718-levi-ari-pronk-u9ahlw6jhoi-unsplash.jpg','media/1UTplZq2ORoYTaarfNaSXlxbrCbvKKNAhGnvmB2B.jpg','image','image/jpeg',578948,'1760951864718-levi-ari-pronk-u9ahlw6jhoi-unsplash.jpg','2026-01-19 07:15:37','2026-01-19 07:15:37'),(5,'Bromo Sunrise','https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?w=1200','image','image/jpeg',0,'Golden sunrise over Mount Bromo crater','2026-01-19 20:08:21','2026-01-19 20:08:21'),(6,'Ijen Blue Fire','https://images.unsplash.com/photo-1596402184320-417e7178b2cd?w=1200','image','image/jpeg',0,'Blue flames at Kawah Ijen crater','2026-01-19 20:08:21','2026-01-19 20:08:21'),(7,'Bromo Sea of Sand','https://images.unsplash.com/photo-1577717903315-1691ae25ab3f?w=1200','image','image/jpeg',0,'Jeep crossing the sea of sand at Bromo','2026-01-19 20:08:21','2026-01-19 20:08:21'),(8,'Volcano Silhouette','https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=1200','image','image/jpeg',0,'Mountain silhouette at dawn','2026-01-19 20:08:21','2026-01-19 20:08:21'),(9,'Trekking Adventure','https://images.unsplash.com/photo-1551632811-561732d1e306?w=1200','image','image/jpeg',0,'Hikers on volcanic trail','2026-01-19 20:08:21','2026-01-19 20:08:21'),(10,'Milky Way Bromo','https://images.unsplash.com/photo-1419242902214-272b3f66ee7a?w=1200','image','image/jpeg',0,'Milky way galaxy over Mount Bromo','2026-01-19 20:08:21','2026-01-19 20:08:21'),(11,'Savanna Hills','https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200','image','image/jpeg',0,'Green savanna hills near Bromo','2026-01-19 20:08:21','2026-01-19 20:08:21'),(12,'Crater Lake','https://images.unsplash.com/photo-1433086966358-54859d0ed716?w=1200','image','image/jpeg',0,'Turquoise crater lake at Ijen','2026-01-19 20:08:21','2026-01-19 20:08:21'),(13,'Volcanic Smoke','https://images.unsplash.com/photo-1518173946687-a4c036bc4b35?w=1200','image','image/jpeg',0,'Smoke rising from active volcano','2026-01-19 20:08:21','2026-01-19 20:08:21'),(14,'Morning Mist','https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=1200','image','image/jpeg',0,'Morning mist in the valley','2026-01-19 20:08:21','2026-01-19 20:08:21'),(15,'Starry Night','https://images.unsplash.com/photo-1507400492013-162706c8c05e?w=1200','image','image/jpeg',0,'Star trails over mountain peak','2026-01-19 20:08:21','2026-01-19 20:08:21'),(16,'Jeep Safari','https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=1200','image','image/jpeg',0,'Off-road jeep adventure','2026-01-19 20:08:21','2026-01-19 20:08:21');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_assets`
--

DROP TABLE IF EXISTS `media_assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `title_text` varchar(255) DEFAULT NULL,
  `disk` varchar(255) NOT NULL DEFAULT 'public',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_assets`
--

LOCK TABLES `media_assets` WRITE;
/*!40000 ALTER TABLE `media_assets` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_01_000000_create_pages_table',1),(5,'2026_01_01_000002_create_destinations_packages_tables',1),(6,'2026_01_01_000003_create_hotspots_table',1),(7,'2026_01_01_000004_create_seo_metadata_table',1),(8,'2026_01_01_000005_create_blogs_categories_table',1),(9,'2026_01_01_000006_create_galleries_inquiry_logs_table',1),(10,'2026_01_01_000007_create_misc_tables',1),(11,'2026_01_01_000010_update_users_table',1),(12,'2026_01_11_051418_create_media_table',1),(13,'2026_01_14_171137_add_details_to_packages_table',1),(14,'2026_01_14_171757_add_details_to_blogs_table',1),(15,'2026_01_14_172044_create_testimonials_table',1),(16,'2026_01_14_172446_create_banners_table',1),(17,'2026_01_15_031902_create_navigation_menus_table',1),(18,'2026_01_18_071433_add_navigable_to_navigation_menus_table',1),(19,'2026_01_18_143435_create_permission_tables',1),(20,'2026_01_18_144006_seed_page_access_permissions',1),(21,'2026_01_18_150924_add_media_id_to_blogs_destinations_packages_galleries',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `navigation_menus`
--

DROP TABLE IF EXISTS `navigation_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `navigation_menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `target` enum('_self','_blank') NOT NULL DEFAULT '_self',
  `navigable_type` varchar(255) DEFAULT NULL,
  `navigable_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `navigation_menus_parent_id_foreign` (`parent_id`),
  KEY `navigation_menus_navigable_type_navigable_id_index` (`navigable_type`,`navigable_id`),
  CONSTRAINT `navigation_menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `navigation_menus` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `navigation_menus`
--

LOCK TABLES `navigation_menus` WRITE;
/*!40000 ALTER TABLE `navigation_menus` DISABLE KEYS */;
INSERT INTO `navigation_menus` VALUES (1,'Home','http://127.0.0.1:8000/',NULL,1,1,'_self',NULL,NULL),(2,'Tour Packages','http://127.0.0.1:8000/packages',NULL,2,1,'_self',NULL,NULL),(3,'Blogs','http://127.0.0.1:8000/blogs',NULL,3,1,'_self',NULL,NULL),(4,'About Us','http://127.0.0.1:8000/#aboutus',NULL,5,1,'_self',NULL,NULL),(5,'Gallery','http://127.0.0.1:8000/gallery',NULL,4,1,'_self',NULL,NULL);
/*!40000 ALTER TABLE `navigation_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `destination_id` bigint unsigned NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `map_embed_url` text,
  `category` varchar(255) NOT NULL DEFAULT 'Adventure',
  `rating` decimal(3,2) NOT NULL DEFAULT '5.00',
  `review_count` int NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `short_description` text,
  `long_description` longtext,
  `highlights` json DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `gallery` json DEFAULT NULL,
  `price_start_from` decimal(15,2) DEFAULT NULL,
  `duration_days` int NOT NULL DEFAULT '1',
  `duration_nights` int NOT NULL DEFAULT '0',
  `departure_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `max_participants` int DEFAULT NULL,
  `destinations_list` json DEFAULT NULL,
  `itinerary` json DEFAULT NULL,
  `inclusions` text,
  `exclusions` text,
  `faqs` json DEFAULT NULL,
  `is_exclusive` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL DEFAULT 'published',
  `wa_template_message` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `thumbnail_media_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `packages_slug_unique` (`slug`),
  KEY `packages_destination_id_foreign` (`destination_id`),
  KEY `packages_thumbnail_media_id_foreign` (`thumbnail_media_id`),
  CONSTRAINT `packages_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `packages_thumbnail_media_id_foreign` FOREIGN KEY (`thumbnail_media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `packages`
--

LOCK TABLES `packages` WRITE;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;
INSERT INTO `packages` VALUES (1,1,NULL,NULL,'Adventure',5.00,0,'Bromo Midnight Tour',NULL,'<p></p>','[]','bromo-midnight-tour','packages/thumbnails/01KFBH4K2E94E012JG675FWHHS.jpg','[]',750000.00,3,2,NULL,NULL,120,'[]','[{\"day\": 1, \"title\": \"Midnight Adventure\", \"activities\": [\"00:00 - Pick up from Malang/Surabaya\", \"03:00 - Arrive at Tosari/Cemorolawang Hub\", \"03:30 - Jeep ride to Penanjakan Viewpoint\", \"05:00 - Enjoy the Golden Sunrise\", \"06:30 - Hike to Bromo Crater\", \"08:30 - Visit Whispering Sands & Teletubbies Hill\", \"10:00 - Back to Hub & Transfer to City\", \"13:00 - Drop off service\"], \"description\": null}]','<p>- Jeep 4x4 (Hardtop)\n- Professional Driver\n- Fuel &amp; Parking Fees\n- Mineral Water\n- Entrance Tickets</p>','<p>- Personal Expenses\n- Horse Riding\n- Meals (Breakfast/Lunch)\n- Travel Insurance</p>','[]',0,'published','Hello, I am interested in Bromo Midnight Tour. Is it available for next weekend?','2026-01-18 10:26:38','2026-01-19 09:37:33',NULL),(2,2,NULL,NULL,'Adventure',5.00,0,'Ijen Blue Fire Expedition',NULL,NULL,NULL,'ijen-blue-fire',NULL,NULL,850000.00,2,1,NULL,NULL,NULL,NULL,'[{\"day\": 1, \"title\": \"Transfer to Banyuwangi\", \"activities\": [\"09:00 - Pick up from Airport/Station\", \"12:00 - Local lunch en route\", \"15:00 - Check in Hotel in Banyuwangi\", \"19:00 - Free time / Rest\"]}, {\"day\": 2, \"title\": \"Blue Fire Trekking\", \"activities\": [\"00:30 - Wake up & preparation\", \"01:30 - Start trekking from Paltuding\", \"03:30 - Witness Blue Fire at Crater\", \"05:30 - Enjoy Sunrise over the Acid Lake\", \"07:30 - Trek back to Paltuding\", \"09:00 - Back to Hotel, Breakfast & Check out\", \"12:00 - Drop off service\"]}]','- Private AC Transport\n- Hotel Accommodation (1 Night)\n- Gas Mask & Headlamp\n- Local Guide\n- Entrance Fees','- Flight Tickets\n- Tipping\n- Personal Expenses\n- Lunch & Dinner',NULL,0,'published','Hi, I want to book Ijen Blue Fire Expedition. What are the available dates?','2026-01-18 10:26:38','2026-01-18 10:26:38',NULL);
/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_revisions`
--

DROP TABLE IF EXISTS `page_revisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_revisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_id` bigint unsigned NOT NULL,
  `content_snapshot` json NOT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_revisions_page_id_foreign` (`page_id`),
  KEY `page_revisions_created_by_foreign` (`created_by`),
  CONSTRAINT `page_revisions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `page_revisions_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_revisions`
--

LOCK TABLES `page_revisions` WRITE;
/*!40000 ALTER TABLE `page_revisions` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_revisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Home','home','[{\"type\":\"hero_video\",\"data\":{\"heading\":\"Experience the <span class=\\\"text-brand-accent\\\">Mystical Fire<\\/span>\",\"subheading\":\"Journey to the heart of East Java\'s most iconic volcanoes.\",\"video_source\":\"url\",\"video_url\":\"https:\\/\\/assets.mixkit.co\\/videos\\/preview\\/mixkit-aerial-view-of-a-volcano-crater-3965-large.mp4\",\"show_button\":false,\"button_text\":\"Start Adventure\",\"button_url\":\"#packages\",\"template\":\"default\",\"spots\":[],\"backgrounds\":[{\"type\":\"media\",\"id\":3,\"url\":\"http:\\/\\/127.0.0.1:8000\\/storage\\/media\\/R5EVuIPp2vHhoMQQtWgeKXI7BC87LVfBnoP81sbw.mp4\",\"mime_type\":\"video\\/mp4\"},{\"type\":\"media\",\"id\":2,\"url\":\"http:\\/\\/127.0.0.1:8000\\/storage\\/media\\/fFDkRKr0jGes8YTJdTpwUTBfMWeomPIJVVgt7Oal.mp4\",\"mime_type\":\"video\\/mp4\"}]},\"uuid\":\"e1bedf3c-7b51-478c-b27e-471ee04acbdd\"},{\"type\":\"about_us\",\"data\":{\"badge\":\"About BromoIjen\",\"title\":\"Experience The <span class=\\\"text-brand-primary\\\">New Adventure<\\/span> With Us\",\"description\":\"We organize premium trips to Mount Bromo, Ijen Crater, and other exotic destinations in East Java. Our goal is to provide safe, comfortable, and memorable experiences for every traveler.\",\"main_image\":\"https:\\/\\/placehold.co\\/600x800?text=Adventure\",\"secondary_image\":\"https:\\/\\/placehold.co\\/500x500?text=Joy\",\"features\":[{\"title\":\"Trusted Travel Guide\",\"description\":\"Professional English speaking guides.\"},{\"title\":\"Instant Booking\",\"description\":\"Easy and secure online booking.\"}],\"feature_1_title\":\"Trusted Travel Guide\",\"feature_1_text\":\"Professional English speaking guides.\",\"feature_2_title\":\"Instant Booking\",\"feature_2_text\":\"Easy and secure online booking.\",\"show_cta\":false,\"cta_text\":\"Discover More\",\"cta_url\":\"\\/packages\",\"show_founder\":false,\"founder_name\":\"Agus Setiawan\",\"founder_role\":\"Founder, BromoIjen\",\"secondary_media_id\":1,\"secondary_source_type\":\"media_library\",\"media_type\":\"image\",\"source_type\":\"media_library\",\"media_id\":4,\"image_source\":\"media_library\"},\"uuid\":\"1a6bdf04-b91a-4595-853c-783a6589eb8f\"},{\"type\":\"exclusive_destinations\",\"data\":{\"badge\":\"Choose your next adventure\",\"title\":\"Exclusive <span class=\\\"relative inline-block text-brand-primary\\\">Destinations<\\/span>\",\"badge_text\":\"Choose your next adventure\",\"title_prefix\":\"Exclusive\",\"title_suffix\":\"Destinations\",\"destination_ids\":[],\"description\":null},\"uuid\":\"76e8df04-16dd-4495-804b-4103a843144f\"},{\"type\":\"package_slider\",\"data\":{\"badge\":\"Popular Tours\",\"title\":\"Feature <span class=\\\"text-brand-primary font-hand italic\\\">Packages<\\/span>\",\"badge_text\":\"Popular Tours\",\"title_prefix\":\"Feature\",\"title_suffix\":\"Packages\",\"package_ids\":[]},\"uuid\":\"40b5b4df-aa52-43f0-99e4-1ef3ec69052f\"},{\"type\":\"testimonials_marquee\",\"data\":{\"badge\":\"Community Love\",\"title\":\"Trusted by <span class=\\\"text-brand-primary\\\">Adventurers<\\/span>\",\"testimonials\":[{\"name\":\"Sarah Jenner\",\"role\":\"Melbourne, Australia\",\"avatar\":\"https:\\/\\/ui-avatars.com\\/api\\/?name=Sarah+Jenner&background=random\",\"content\":\"The Midnight Bromo Tour was absolutely breathtaking! The jeep ride under the stars and the sunrise view were magical. Perfectly organized!\",\"rating\":5},{\"name\":\"Michael Chen\",\"role\":\"Singapore\",\"avatar\":\"https:\\/\\/ui-avatars.com\\/api\\/?name=Michael+Chen&background=random\",\"content\":\"Ijen Blue Fire was a challenging hike but totally worth it. The guide ensured our safety throughout the trek. A premium experience.\",\"rating\":5}],\"badge_text\":\"Community Love\",\"title_prefix\":\"Trusted by\",\"title_suffix\":\"Adventurers\",\"source\":\"auto\",\"manual_testimonials\":[]},\"uuid\":\"d510a934-97df-4765-a7f2-868f2aeaa5df\"},{\"type\":\"blog_news\",\"data\":{\"badge\":\"Blog & News\",\"title\":\"Explore Blogs <span class=\\\"text-brand-primary font-hand italic\\\">And News<\\/span>\",\"auto_fetch\":true,\"badge_text\":\"Blog & News\",\"title_prefix\":\"Explore Blogs\",\"title_suffix\":\"And News\"},\"uuid\":\"a50e1ef1-57a1-4878-8904-e2e3b87827c8\"},{\"type\":\"gallery\",\"data\":{\"badge\":\"Our Memories\",\"title\":\"Capture The <span class=\\\"text-brand-primary font-hand italic\\\">Moments<\\/span>\",\"description\":\"Explore the beauty of East Java through our lens. From the sunrise of Bromo to the blue fire of Ijen.\",\"images\":[{\"image\":\"https:\\/\\/placehold.co\\/800x800?text=Bromo+Sunrise\",\"size\":\"large\",\"caption\":\"Golden Sunrise\"},{\"image\":\"https:\\/\\/placehold.co\\/400x400?text=Jeep+Ride\",\"size\":\"small\",\"caption\":\"Jeep Adventure\"},{\"image\":\"https:\\/\\/placehold.co\\/400x400?text=Ijen+Crater\",\"size\":\"small\",\"caption\":\"Blue Fire\"},{\"image\":\"https:\\/\\/placehold.co\\/800x800?text=Savana\",\"size\":\"tall\",\"caption\":\"Savana Hills\"},{\"image\":\"https:\\/\\/placehold.co\\/400x400?text=People\",\"size\":\"small\",\"caption\":\"Happy Travelers\"},{\"image\":\"https:\\/\\/placehold.co\\/800x400?text=Milky+Way\",\"size\":\"wide\",\"caption\":\"Milky Way\"}],\"badge_text\":\"Our Memories\",\"title_prefix\":\"Capture The\",\"title_suffix\":\"Moments\"},\"uuid\":\"a24dec52-1c3e-47fd-9d46-52d91164781f\"}]','2026-01-18 10:26:38','2026-01-19 19:53:34');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `price_adjustments`
--

DROP TABLE IF EXISTS `price_adjustments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `price_adjustments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `adjustment_type` varchar(255) NOT NULL COMMENT 'percentage, fixed',
  `amount` decimal(15,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `price_adjustments_package_id_foreign` (`package_id`),
  CONSTRAINT `price_adjustments_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `price_adjustments`
--

LOCK TABLES `price_adjustments` WRITE;
/*!40000 ALTER TABLE `price_adjustments` DISABLE KEYS */;
/*!40000 ALTER TABLE `price_adjustments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'SEO','web','2026-01-19 19:15:25','2026-01-19 19:15:25');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seo_metadata`
--

DROP TABLE IF EXISTS `seo_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seo_metadata` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `seoable_type` varchar(255) NOT NULL,
  `seoable_id` bigint unsigned NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `canonical_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seo_metadata_seoable_type_seoable_id_index` (`seoable_type`,`seoable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seo_metadata`
--

LOCK TABLES `seo_metadata` WRITE;
/*!40000 ALTER TABLE `seo_metadata` DISABLE KEYS */;
INSERT INTO `seo_metadata` VALUES (1,'App\\Models\\Page',1,'Bromo Ijen Expedition - Premium Tours','Book your private Bromo and Ijen Crater tour. Experience the blue fire and sunrise with premium service.',NULL,NULL,NULL,'2026-01-18 10:26:38','2026-01-18 10:26:38'),(2,'App\\Models\\Package',1,'jhkjhkhkjhk',NULL,NULL,NULL,NULL,'2026-01-19 09:24:19','2026-01-19 09:24:19'),(3,'App\\Models\\Blog',1,'10 Tips for Hiking Mount Bromo at Sunrise',NULL,NULL,NULL,NULL,'2026-01-19 09:26:43','2026-01-19 09:26:43');
/*!40000 ALTER TABLE `seo_metadata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('K8xPLYKbdupHeMydqm7SCaXE18DRjoWuVhzz5Bbf',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiazdIWGJjZTdWWHRNTHpMNkFNcFhnd01RZTJLbGd1eTBoMXVhS0o3NSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJEhKaUk1Q1ExOE90bUkxd2VuYVJENU9ocDl0UDM3RzFrUDBQQS9kZ2J0T3dYSFZocldZSlY2IjtzOjY6InRhYmxlcyI7YTo5OntzOjQwOiI4NmFiZGNhNGZiZGVmOTZjMWZkZDc5NjIzZjBkYjc0Ml9jb2x1bW5zIjthOjQ6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNDoidGh1bWJuYWlsX3BhdGgiO3M6NToibGFiZWwiO3M6OToiVGh1bWJuYWlsIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJuYW1lIjtzOjU6ImxhYmVsIjtzOjQ6Ik5hbWUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjExOiJpc19mZWF0dXJlZCI7czo1OiJsYWJlbCI7czoxMToiSXMgZmVhdHVyZWQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjEwOiJjcmVhdGVkX2F0IjtzOjU6ImxhYmVsIjtzOjEwOiJDcmVhdGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MDtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MTtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO2I6MTt9fXM6NDA6IjYzNDUyOTU3ZDFmMWIzNDExNTkwNDI4ZjZiMGEwYWQyX2NvbHVtbnMiO2E6NTp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6NDoiTmFtZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTY6ImRlc3RpbmF0aW9uLm5hbWUiO3M6NToibGFiZWwiO3M6MTE6IkRlc3RpbmF0aW9uIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNjoicHJpY2Vfc3RhcnRfZnJvbSI7czo1OiJsYWJlbCI7czoxNjoiUHJpY2Ugc3RhcnQgZnJvbSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjM7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTM6ImR1cmF0aW9uX2RheXMiO3M6NToibGFiZWwiO3M6NDoiRGF5cyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTI6ImlzX2V4Y2x1c2l2ZSI7czo1OiJsYWJlbCI7czoxMjoiSXMgZXhjbHVzaXZlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiI2OWZlNDQ5MTEyMmNiOGQzYWJiMzE3NmUwYjljOGVmNV9jb2x1bW5zIjthOjc6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxNDoidGh1bWJuYWlsX3BhdGgiO3M6NToibGFiZWwiO3M6NToiSW1hZ2UiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjU6InRpdGxlIjtzOjU6ImxhYmVsIjtzOjU6IlRpdGxlIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo4OiJjYXRlZ29yeSI7czo1OiJsYWJlbCI7czo4OiJDYXRlZ29yeSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjM7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6Njoic3RhdHVzIjtzOjU6ImxhYmVsIjtzOjY6IlN0YXR1cyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTE6ImlzX2ZlYXR1cmVkIjtzOjU6ImxhYmVsIjtzOjExOiJJcyBmZWF0dXJlZCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTE6ImF1dGhvci5uYW1lIjtzOjU6ImxhYmVsIjtzOjY6IkF1dGhvciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTI6InB1Ymxpc2hlZF9hdCI7czo1OiJsYWJlbCI7czoxMjoiUHVibGlzaGVkIGF0IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiI3ZDlmNTBjM2VmNzc2OTcwYjBiYjE1YjI0YjI1NjZhNV9jb2x1bW5zIjthOjc6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czo0OiJ1dWlkIjtzOjU6ImxhYmVsIjtzOjQ6IlVVSUQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6NDoiTmFtZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NToiZW1haWwiO3M6NToibGFiZWwiO3M6MTM6IkVtYWlsIGFkZHJlc3MiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTozO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjE3OiJlbWFpbF92ZXJpZmllZF9hdCI7czo1OiJsYWJlbCI7czoxNzoiRW1haWwgdmVyaWZpZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aTo0O2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6InJvbGUiO3M6NToibGFiZWwiO3M6NDoiUm9sZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjY7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IlVwZGF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319czo0MDoiYjFkMzYwZDJlN2I1YTc3Y2FhMTNmNzg4NjNlYTgwNjRfY29sdW1ucyI7YTo0OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo0OiJOYW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiZ3VhcmRfbmFtZSI7czo1OiJsYWJlbCI7czoxMDoiR3VhcmQgbmFtZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO31pOjM7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InVwZGF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IlVwZGF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjowO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjoxO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7YjoxO319czo0MDoiNmM0NDQzMjQyY2EyNzEzYzQ3YTFmY2IwMDA2MmI1ZDNfY29sdW1ucyI7YTo1OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6InBob3RvX3BhdGgiO3M6NToibGFiZWwiO3M6NToiUGhvdG8iO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToxO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6NDoiTmFtZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NjoicmF0aW5nIjtzOjU6ImxhYmVsIjtzOjY6IlJhdGluZyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjM7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6Njoic3RhdHVzIjtzOjU6ImxhYmVsIjtzOjY6IlN0YXR1cyI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6OToiU3VibWl0dGVkIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fX1zOjQwOiJkNjQyYjEwNzZjN2NkZGI0YTY0OWFiMjNiOTUzY2FjMl9jb2x1bW5zIjthOjU6e2k6MDthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoiaW1hZ2VfcGF0aCI7czo1OiJsYWJlbCI7czo1OiJJbWFnZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NToidGl0bGUiO3M6NToibGFiZWwiO3M6NToiVGl0bGUiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9aToyO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjg6ImNhdGVnb3J5IjtzOjU6ImxhYmVsIjtzOjg6IkNhdGVnb3J5IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoic29ydF9vcmRlciI7czo1OiJsYWJlbCI7czoxMDoiU29ydCBvcmRlciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTA6ImNyZWF0ZWRfYXQiO3M6NToibGFiZWwiO3M6MTA6IkNyZWF0ZWQgYXQiO3M6ODoiaXNIaWRkZW4iO2I6MDtzOjk6ImlzVG9nZ2xlZCI7YjoxO3M6MTI6ImlzVG9nZ2xlYWJsZSI7YjowO3M6MjQ6ImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI7Tjt9fXM6NDA6ImY2OTk0YzdmMTI4ZDMwMjNkNTdiZjAyODBiMDhhZjkxX2NvbHVtbnMiO2E6Njp7aTowO2E6Nzp7czo0OiJ0eXBlIjtzOjY6ImNvbHVtbiI7czo0OiJuYW1lIjtzOjQ6Im5hbWUiO3M6NToibGFiZWwiO3M6NDoiTmFtZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjE7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MzoidXJsIjtzOjU6ImxhYmVsIjtzOjM6IlVybCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjI7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6MTE6InBhcmVudC5uYW1lIjtzOjU6ImxhYmVsIjtzOjEzOiJQYXJlbnQgTGVnYWN5IjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MzthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoic29ydF9vcmRlciI7czo1OiJsYWJlbCI7czoxMDoiU29ydCBvcmRlciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToiaXNfYWN0aXZlIjtzOjU6ImxhYmVsIjtzOjk6IklzIGFjdGl2ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjU7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NjoidGFyZ2V0IjtzOjU6ImxhYmVsIjtzOjY6IlRhcmdldCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO319czo0MDoiYzQ0MmNiYTc5YmIzZmJiNmJhY2QxY2NiMmQ0MjE5NDhfY29sdW1ucyI7YTo1OntpOjA7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NDoibmFtZSI7czo1OiJsYWJlbCI7czo0OiJOYW1lIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MTthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czozOiJ1cmwiO3M6NToibGFiZWwiO3M6MzoiVXJsIjtzOjg6ImlzSGlkZGVuIjtiOjA7czo5OiJpc1RvZ2dsZWQiO2I6MTtzOjEyOiJpc1RvZ2dsZWFibGUiO2I6MDtzOjI0OiJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiO047fWk6MjthOjc6e3M6NDoidHlwZSI7czo2OiJjb2x1bW4iO3M6NDoibmFtZSI7czoxMDoic29ydF9vcmRlciI7czo1OiJsYWJlbCI7czoxMDoiU29ydCBvcmRlciI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjM7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6OToiaXNfYWN0aXZlIjtzOjU6ImxhYmVsIjtzOjk6IklzIGFjdGl2ZSI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO31pOjQ7YTo3OntzOjQ6InR5cGUiO3M6NjoiY29sdW1uIjtzOjQ6Im5hbWUiO3M6NjoidGFyZ2V0IjtzOjU6ImxhYmVsIjtzOjY6IlRhcmdldCI7czo4OiJpc0hpZGRlbiI7YjowO3M6OToiaXNUb2dnbGVkIjtiOjE7czoxMjoiaXNUb2dnbGVhYmxlIjtiOjA7czoyNDoiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjtOO319fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjg6ImZpbGFtZW50IjthOjA6e319',1768879711);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'provider_name','Bromo Ijen Expedition Java','general','2026-01-19 18:54:40','2026-01-19 19:06:45'),(2,'member_since',NULL,'general','2026-01-19 18:54:40','2026-01-19 18:54:40'),(3,'provider_phone',NULL,'general','2026-01-19 18:54:40','2026-01-19 18:54:40'),(4,'provider_email',NULL,'general','2026-01-19 18:54:40','2026-01-19 18:54:40'),(5,'site_name','Bromo Ijen Expedition Java','general','2026-01-19 18:54:40','2026-01-19 19:06:45'),(6,'site_tagline','Bromo Ijen Expedition Java','general','2026-01-19 18:54:40','2026-01-19 19:06:45'),(7,'site_logo',NULL,'general','2026-01-19 18:54:40','2026-01-19 18:54:40'),(8,'favicon',NULL,'general','2026-01-19 18:54:40','2026-01-19 18:54:40'),(9,'default_meta_title',NULL,'general','2026-01-19 18:54:40','2026-01-19 18:54:40'),(10,'default_meta_description',NULL,'general','2026-01-19 18:54:40','2026-01-19 18:54:40'),(11,'site_url',NULL,'general','2026-01-19 18:54:40','2026-01-19 18:54:40'),(12,'default_og_image',NULL,'general','2026-01-19 18:54:40','2026-01-19 18:54:40'),(13,'google_verification_method',NULL,'general','2026-01-19 18:54:40','2026-01-19 18:54:40'),(14,'google_verification_code',NULL,'general','2026-01-19 18:54:40','2026-01-19 18:54:40'),(15,'bing_verification_code',NULL,'general','2026-01-19 18:54:40','2026-01-19 18:54:40'),(16,'active_template','default','general','2026-01-19 18:54:40','2026-01-19 18:54:40'),(17,'maintenance_mode','1','general','2026-01-19 18:54:40','2026-01-19 19:21:05'),(18,'auto_approve_testimonials','0','general','2026-01-19 18:54:40','2026-01-19 19:21:05'),(19,'email_notifications','0','general','2026-01-19 18:54:40','2026-01-19 19:21:05'),(20,'header_button_show','0','general','2026-01-19 19:06:45','2026-01-19 19:21:05'),(21,'header_button_text',NULL,'general','2026-01-19 19:06:45','2026-01-19 19:06:45'),(22,'header_button_url',NULL,'general','2026-01-19 19:06:45','2026-01-19 19:06:45'),(23,'header_button_icon',NULL,'general','2026-01-19 19:06:45','2026-01-19 19:06:45'),(24,'header_button_icon_position',NULL,'general','2026-01-19 19:06:45','2026-01-19 19:06:45'),(25,'floating_social_enabled','1','general','2026-01-19 19:16:49','2026-01-19 19:21:05'),(26,'social_links','[{\"platform\":\"facebook\",\"url\":\"https:\\/\\/www.facebook.com\\/\",\"name\":null}]','general','2026-01-19 19:18:08','2026-01-19 19:18:08');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `testimonials`
--

DROP TABLE IF EXISTS `testimonials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `testimonials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `rating` tinyint unsigned NOT NULL DEFAULT '5',
  `avatar` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `testimonials`
--

LOCK TABLES `testimonials` WRITE;
/*!40000 ALTER TABLE `testimonials` DISABLE KEYS */;
INSERT INTO `testimonials` VALUES (1,'Sarah Jenner','Melbourne, Australia','The Midnight Bromo Tour was absolutely breathtaking! The jeep ride under the stars and the sunrise view were magical. Perfectly organized!',5,'https://ui-avatars.com/api/?name=Sarah+Jenner&background=random','published','2026-01-18 10:26:38','2026-01-18 10:26:38'),(2,'Michael Chen','Singapore','Ijen Blue Fire was a challenging hike but totally worth it. The guide ensured our safety throughout the trek. A premium experience.',5,'https://ui-avatars.com/api/?name=Michael+Chen&background=random','published','2026-01-18 10:26:38','2026-01-18 10:26:38'),(3,'Emma Watson','London, UK','Booking was seamless via WhatsApp. The driver was punctual and the car was very comfortable. Highly recommended for solo travelers.',5,'https://ui-avatars.com/api/?name=Emma+Watson&background=random','published','2026-01-18 10:26:38','2026-01-18 10:26:38'),(4,'David Kim','Seoul, South Korea','The scenery at massive Bromo crater is unlike anything I\'ve ever seen. BromoIjen Expedition handled everything professionally.',5,'https://ui-avatars.com/api/?name=David+Kim&background=random','published','2026-01-18 10:26:38','2026-01-18 10:26:38'),(5,'Jessica Brown','Toronto, Canada','An unforgettable adventure! The Tumpak Sewu waterfall trip was the highlight of our Java journey. Great local guides.',5,'https://ui-avatars.com/api/?name=Jessica+Brown&background=random','published','2026-01-18 10:26:38','2026-01-18 10:26:38');
/*!40000 ALTER TABLE `testimonials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user' COMMENT 'admin, editor, user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'a0de1672-b821-41a4-a99d-5552c03b9c19','Admin Super','admin@bromo.com',NULL,'$2y$12$HJiI5CQ18OtmI1wenaRD5Ohp9tP37G1kP0PA/dgbtOwXHVhrWYJV6','admin','Hrcz6Of9NMfn07BZuiRYNce3CStJEInl9kyOj40arK8l0ctecgNERVZ7CzfD','2026-01-18 10:26:38','2026-01-18 10:26:38');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-20 13:16:37
