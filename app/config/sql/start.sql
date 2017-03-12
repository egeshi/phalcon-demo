-- MySQL dump 10.13  Distrib 5.5.50, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: phalcon_custom
-- ------------------------------------------------------
-- Server version	5.5.50-0ubuntu0.14.04.1-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Software'),(2,'Medical'),(3,'Mining');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'guest','Guests'),(2,'staff','Registered staff'),(3,'customer','Registered customers');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(164) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(128) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UK_users_email` (`email`),
  UNIQUE KEY `UK_users_id` (`id`),
  UNIQUE KEY `UK_users_token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (101,'admin@server.com','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','fb71c4129a80fab3956e297b7261f831','2017-03-04 21:20:48','2017-03-04 21:20:48'),(102,'user1@server.com','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','MjAxNy0wMy0wNFQyMTozOTo0MyswMjowMA==','2017-03-04 21:39:43','2017-03-04 21:39:43'),(103,'user_xxx@server.com','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','MjAxNy0wMy0xMlQwMTowMzoxNiswMjowMA==','2017-03-12 01:03:16','2017-03-12 01:03:16'),(108,'sqsclub@gmail.com','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','576662cbbb555c111655876430351981','2017-03-12 04:25:21','2017-03-12 04:25:21'),(109,'customer1@gmail.com','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','MjAxNy0wMy0xMlQwNDo0NzowMiswMjowMA==','2017-03-12 04:47:02','2017-03-12 04:47:02');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_companies`
--

DROP TABLE IF EXISTS `users_companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_companies` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `category_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_companies_user_id` (`user_id`),
  KEY `FK_uesrs_companies_category_id` (`category_id`),
  CONSTRAINT `FK_uesrs_companies_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `FK_companies_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_companies`
--

LOCK TABLES `users_companies` WRITE;
/*!40000 ALTER TABLE `users_companies` DISABLE KEYS */;
INSERT INTO `users_companies` VALUES (8,'Microsoft',1,101),(9,'Oracle',1,102),(10,'SAP',1,103),(11,'VMware',1,108),(12,'Adobe Systems',1,109),(13,'HCL Technologies',1,101),(14,'Fiserv',1,102),(15,'Salesforce.com',1,103),(16,'Symantec',1,108),(17,'Amadeus IT Holdings',1,109),(18,'Johnson & Johnson',2,101),(19,'General Electric Co.',2,102),(20,'Medtronic Inc.',2,103),(21,'Siemens AG ',2,108),(22,'Baxter International Inc.',2,109),(23,'Fresenius Medical Care AG & Co. KGAA ',2,101),(24,'Koninklijke Philips NV ',2,102),(25,'Cardinal Health Inc.',2,103),(26,'Novartis AG',2,108),(27,'Covidien plc ',2,109),(28,'Glencore Xtrata',3,101),(29,'BHP Billiton',3,102),(30,'Rio Tinto',3,103),(31,'Vale',3,108),(32,'Anglo American',3,109),(33,'China Shenhua Energy',3,101),(34,'Freeport McMoRan Copper & Gold',3,102),(35,'Barrick Gold',3,103),(36,'Coal India Limited',3,108),(37,'Fortescue Metals Group',3,109);
/*!40000 ALTER TABLE `users_companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_roles`
--

DROP TABLE IF EXISTS `users_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_roles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `role_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_users_groups_role_id` (`role_id`),
  KEY `FK_users_groups_user_id` (`user_id`),
  CONSTRAINT `FK_users_groups_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `FK_users_groups_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_roles`
--

LOCK TABLES `users_roles` WRITE;
/*!40000 ALTER TABLE `users_roles` DISABLE KEYS */;
INSERT INTO `users_roles` VALUES (29,101,2),(30,102,2),(31,103,2),(32,108,2),(33,109,3);
/*!40000 ALTER TABLE `users_roles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-03-12  8:43:28
