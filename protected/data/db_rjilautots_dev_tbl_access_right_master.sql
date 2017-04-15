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
-- Table structure for table `tbl_access_right_master`
--

DROP TABLE IF EXISTS `tbl_access_right_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_access_right_master` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL COMMENT 'access type = "admin / employee "',
  `heading` varchar(100) NOT NULL,
  `page_url` varchar(200) DEFAULT NULL,
  `heading_order` tinyint(4) DEFAULT '0',
  `menu_icon` varchar(100) DEFAULT NULL,
  `is_disabled` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_access_right_master`
--

LOCK TABLES `tbl_access_right_master` WRITE;
/*!40000 ALTER TABLE `tbl_access_right_master` DISABLE KEYS */;
INSERT INTO `tbl_access_right_master` VALUES (1,'MANAGER','Manager',NULL,0,NULL,0),(2,'EMPLOYEE','Employee',NULL,0,NULL,0),(3,'PROJECTS','Projects',NULL,0,NULL,0),(4,'RESOURCEALLOCATION','Resource Allocation',NULL,0,NULL,0),(5,'STATUS','Status',NULL,0,NULL,0),(6,'ROLES','Roles',NULL,0,NULL,0),(7,'DASHBOARD','Dashboard',NULL,0,NULL,0);
/*!40000 ALTER TABLE `tbl_access_right_master` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-04-15 16:03:01
