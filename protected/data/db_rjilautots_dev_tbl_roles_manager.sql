-- MySQL dump 10.13  Distrib 5.7.17, for Linux (i686)
--
-- Host: localhost    Database: db_rjilautots_dev
-- ------------------------------------------------------
-- Server version	5.5.40-log

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
-- Table structure for table `tbl_roles_manager`
--

DROP TABLE IF EXISTS `tbl_roles_manager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_roles_manager` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `access_rights` text,
  `is_deleted` tinyint(1) DEFAULT '0',
  `created_by` int(10) NOT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_by` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NewIndex1` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_roles_manager`
--

LOCK TABLES `tbl_roles_manager` WRITE;
/*!40000 ALTER TABLE `tbl_roles_manager` DISABLE KEYS */;
INSERT INTO `tbl_roles_manager` VALUES (1,'Admin','a:6:{s:8:\"EMPLOYEE\";a:4:{i:0;s:6:\"delete\";i:1;s:6:\"update\";i:2;s:6:\"create\";i:3;s:5:\"index\";}s:9:\"DASHBOARD\";a:1:{i:0;s:11:\"full_access\";}s:5:\"ROLES\";a:2:{i:0;s:6:\"delete\";i:1;s:6:\"update\";}s:6:\"STATUS\";a:1:{i:0;s:11:\"full_access\";}s:18:\"RESOURCEALLOCATION\";a:1:{i:0;s:11:\"full_access\";}s:8:\"PROJECTS\";a:1:{i:0;s:11:\"full_access\";}}',0,53,'2016-05-03 13:03:55','2016-05-04 04:00:39',53),(2,'Manager','a:6:{s:8:\"EMPLOYEE\";a:4:{i:0;s:6:\"delete\";i:1;s:6:\"update\";i:2;s:6:\"create\";i:3;s:5:\"index\";}s:9:\"DASHBOARD\";a:1:{i:0;s:11:\"full_access\";}s:5:\"ROLES\";a:2:{i:0;s:6:\"delete\";i:1;s:6:\"update\";}s:6:\"STATUS\";a:1:{i:0;s:11:\"full_access\";}s:18:\"RESOURCEALLOCATION\";a:1:{i:0;s:11:\"full_access\";}s:8:\"PROJECTS\";a:1:{i:0;s:11:\"full_access\";}}',0,53,'2016-05-04 09:18:42','2016-05-04 04:00:50',53);
/*!40000 ALTER TABLE `tbl_roles_manager` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-15 16:03:03
