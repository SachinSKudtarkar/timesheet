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
-- Table structure for table `tbl_state_master`
--

DROP TABLE IF EXISTS `tbl_state_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_state_master` (
  `state_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `state_name_short` varchar(2) NOT NULL,
  `state_name` varchar(100) DEFAULT NULL,
  `is_disabled` tinyint(1) DEFAULT '0' COMMENT '1 = Disabled',
  `has_form` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`state_id`),
  KEY `IX_STATE_MASTER_IS_DISABLED` (`is_disabled`),
  KEY `IX_STATE_MASTER_HAS_FORM` (`has_form`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_state_master`
--

LOCK TABLES `tbl_state_master` WRITE;
/*!40000 ALTER TABLE `tbl_state_master` DISABLE KEYS */;
INSERT INTO `tbl_state_master` VALUES (1,'DL','Delhi',0,0),(2,'MH','Maharashtra',0,0),(3,'TN','Tamil Nadu',0,0),(4,'KL','Kerala',0,0),(5,'AP','Andhra Pradesh',0,0),(6,'KA','Karnataka',0,0),(7,'GA','Goa',0,0),(8,'MP','Madhya Pradesh',0,0),(9,'PY','Pondicherry',0,0),(10,'GJ','Gujarat',0,0),(11,'OR','Orrisa',0,0),(12,'CA','Chhatisgarh',0,0),(13,'JH','Jharkhand',0,0),(14,'BR','Bihar',0,0),(15,'WB','West Bengal',0,0),(16,'UP','Uttar Pradesh',0,0),(17,'RJ','Rajasthan',0,0),(18,'PB','Punjab',0,0),(19,'HR','Haryana',0,0),(20,'CH','Chandigarh',0,0),(21,'JK','Jammu & Kashmir',0,0),(22,'HP','Himachal Pradesh',0,0),(23,'UA','Uttaranchal',0,0),(24,'LK','Lakshadweep',0,0),(25,'AN','Andaman & Nicobar',0,0),(26,'MG','Meghalaya',0,0),(27,'AS','Assam',0,0),(28,'DR','Dadra & Nagar Haveli',0,0),(29,'DN','Daman & Diu',0,0),(30,'SK','Sikkim',0,0),(31,'TR','Tripura',0,0),(32,'MZ','Mizoram',0,0),(33,'MN','Manipur',0,0),(34,'NL','Nagaland',0,0),(35,'AR','Arunachal Pradesh',0,0);
/*!40000 ALTER TABLE `tbl_state_master` ENABLE KEYS */;
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
