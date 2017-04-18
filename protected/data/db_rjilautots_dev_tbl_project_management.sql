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
-- Table structure for table `tbl_project_management`
--

DROP TABLE IF EXISTS `tbl_project_management`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_project_management` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(255) DEFAULT NULL,
  `project_name` varchar(250) NOT NULL,
  `project_description` varchar(250) NOT NULL,
  `requester` varchar(250) NOT NULL,
  `estimated_start_date` datetime NOT NULL,
  `estimated_end_date` datetime NOT NULL,
  `total_hr_estimation_hour` varchar(10) NOT NULL,
  `status` varchar(25) NOT NULL,
  `type` int(50) NOT NULL,
  `hr_clocked` int(10) NOT NULL,
  `category` tinyint(4) NOT NULL,
  `customer` varchar(255) NOT NULL,
  `is_billable` tinyint(4) NOT NULL,
  `created_by` int(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_by` int(10) NOT NULL,
  `updated_date` datetime NOT NULL,
  `is_deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pid`),
  UNIQUE KEY `project_name` (`project_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_project_management`
--

LOCK TABLES `tbl_project_management` WRITE;
/*!40000 ALTER TABLE `tbl_project_management` DISABLE KEYS */;
INSERT INTO `tbl_project_management` VALUES (9,NULL,'RJIL Auto','Reliance','CISCO Admin','2016-12-01 00:00:00','2017-06-30 00:00:00','','Newly Created',1,0,0,'',0,51,'2016-12-06 07:07:54',51,'2016-12-06 07:07:54',0);
/*!40000 ALTER TABLE `tbl_project_management` ENABLE KEYS */;
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
