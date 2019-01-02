-- MySQL dump 10.13  Distrib 5.7.24, for macos10.14 (x86_64)
--
-- Host: localhost    Database: team095fa18_phase3
-- ------------------------------------------------------
-- Server version	5.7.24

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES UTF8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `team095fa18_phase3`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `team095fa18_phase3` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `team095fa18_phase3`;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `category_type` varchar(50) NOT NULL,
  PRIMARY KEY (`categoryID`),
  UNIQUE KEY `category_type` (`category_type`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (5,'Architecture'),(12,'Art'),(1,'Education'),(8,'Food & Drink'),(9,'Home & Garden'),(7,'Other'),(3,'People'),(2,'Pets'),(10,'Photography'),(4,'Sports'),(11,'Technology'),(6,'Travel');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `commentID` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(50) NOT NULL,
  `date_time` datetime NOT NULL,
  `userID` int(11) NOT NULL,
  `pushpinID` int(11) NOT NULL,
  PRIMARY KEY (`commentID`),
  KEY `comment_ibfk_1` (`userID`),
  KEY `comment_ibfk_2` (`pushpinID`),
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`pushpinID`) REFERENCES `pushpin` (`pushpinID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `corkboard`
--

DROP TABLE IF EXISTS `corkboard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `corkboard` (
  `corkboardID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `visibility` tinyint(1) NOT NULL,
  `LastUpdates` datetime NOT NULL,
  PRIMARY KEY (`corkboardID`),
  UNIQUE KEY `title` (`title`),
  KEY `corkboard_ibfk_1` (`userID`),
  KEY `corkboard_ibfk_2` (`categoryID`),
  CONSTRAINT `corkboard_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  CONSTRAINT `corkboard_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `corkboard`
--

LOCK TABLES `corkboard` WRITE;
/*!40000 ALTER TABLE `corkboard` DISABLE KEYS */;
INSERT INTO `corkboard` VALUES (20,1,1,'Education',0,'2018-11-24 23:56:52'),(21,1,3,'People',1,'2018-11-25 00:03:53'),(22,2,5,'Architecture',0,'2018-11-25 00:12:26'),(23,3,4,'Sports',0,'2018-11-25 00:18:10'),(24,4,8,'Food & Drink',0,'2018-11-25 00:29:48'),(25,5,11,'Technology',0,'2018-11-25 00:31:20'),(26,6,6,'Travel',0,'2018-11-25 00:36:40'),(27,6,2,'Pets',1,'2018-11-25 00:38:36');
/*!40000 ALTER TABLE `corkboard` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follow`
--

DROP TABLE IF EXISTS `follow`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `follow` (
  `followID` int(11) NOT NULL AUTO_INCREMENT,
  `FolloweruserID` int(11) NOT NULL,
  `FolloweduserID` int(11) NOT NULL,
  PRIMARY KEY (`followID`),
  UNIQUE KEY `followrelation` (`FolloweruserID`,`FolloweduserID`),
  KEY `followuser_ibfk_2` (`FolloweduserID`),
  CONSTRAINT `followuser_ibfk_1` FOREIGN KEY (`FolloweruserID`) REFERENCES `user` (`userID`),
  CONSTRAINT `followuser_ibfk_2` FOREIGN KEY (`FolloweduserID`) REFERENCES `user` (`userID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follow`
--

LOCK TABLES `follow` WRITE;
/*!40000 ALTER TABLE `follow` DISABLE KEYS */;
/*!40000 ALTER TABLE `follow` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likepushpin`
--

DROP TABLE IF EXISTS `likepushpin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `likepushpin` (
  `likepushpinID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `pushpinID` int(11) NOT NULL,
  PRIMARY KEY (`likepushpinID`),
  UNIQUE KEY `pushcorkboard` (`userID`,`pushpinID`),
  KEY `like_unlike_ibfk_2` (`pushpinID`),
  CONSTRAINT `like_unlike_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  CONSTRAINT `like_unlike_ibfk_2` FOREIGN KEY (`pushpinID`) REFERENCES `pushpin` (`pushpinID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likepushpin`
--

LOCK TABLES `likepushpin` WRITE;
/*!40000 ALTER TABLE `likepushpin` DISABLE KEYS */;
/*!40000 ALTER TABLE `likepushpin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `privatecorkboard`
--

DROP TABLE IF EXISTS `privatecorkboard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `privatecorkboard` (
  `privateID` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(4) NOT NULL,
  `corkboardID` int(11) NOT NULL,
  PRIMARY KEY (`privateID`),
  KEY `privatecorkboard_ibfk_1` (`corkboardID`),
  CONSTRAINT `privatecorkboard_ibfk_1` FOREIGN KEY (`corkboardID`) REFERENCES `corkboard` (`corkboardID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `privatecorkboard`
--

LOCK TABLES `privatecorkboard` WRITE;
/*!40000 ALTER TABLE `privatecorkboard` DISABLE KEYS */;
INSERT INTO `privatecorkboard` VALUES (8,'1111',21),(9,'6666',27);
/*!40000 ALTER TABLE `privatecorkboard` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publiccorkboard`
--

DROP TABLE IF EXISTS `publiccorkboard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publiccorkboard` (
  `publicID` int(11) NOT NULL AUTO_INCREMENT,
  `corkboardID` int(11) NOT NULL,
  PRIMARY KEY (`publicID`),
  KEY `publiccorkboard_ibfk_1` (`corkboardID`),
  CONSTRAINT `publiccorkboard_ibfk_1` FOREIGN KEY (`corkboardID`) REFERENCES `corkboard` (`corkboardID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publiccorkboard`
--

LOCK TABLES `publiccorkboard` WRITE;
/*!40000 ALTER TABLE `publiccorkboard` DISABLE KEYS */;
INSERT INTO `publiccorkboard` VALUES (14,20),(15,22),(16,23),(17,24),(18,25),(19,26);
/*!40000 ALTER TABLE `publiccorkboard` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pushpin`
--

DROP TABLE IF EXISTS `pushpin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pushpin` (
  `pushpinID` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `updatedtime` datetime NOT NULL,
  `description` varchar(200) NOT NULL,
  `corkboardID` int(11) NOT NULL,
  PRIMARY KEY (`pushpinID`),
  UNIQUE KEY `pushcorkboard` (`url`,`corkboardID`),
  KEY `pushpin_ibfk_1` (`corkboardID`),
  CONSTRAINT `pushpin_ibfk_1` FOREIGN KEY (`corkboardID`) REFERENCES `corkboard` (`corkboardID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pushpin`
--

LOCK TABLES `pushpin` WRITE;
/*!40000 ALTER TABLE `pushpin` DISABLE KEYS */;
INSERT INTO `pushpin` VALUES (28,'https://www.cc.gatech.edu/sites/default/files/images/mercury/oms-cs-web-rotator_2_0_3.jpeg','2018-11-24 23:58:24','OMSCS program logo',20),(29,'http://www.buzzcard.gatech.edu/sites/default/files/uploads/images/superblock_images/img_2171.jpg','2018-11-24 23:59:07','student ID for Georgia Tech',20),(30,'https://www.news.gatech.edu/sites/default/files/uploads/mercury_images/piazza-icon.png','2018-11-25 00:01:52',' logo for Piazza',20),(31,'http://www.comm.gatech.edu/sites/default/files/images/brand-graphics/gt-seal.png','2018-11-25 00:03:00','official seal of Georgia Tech',20),(32,'http://www.me.gatech.edu/sites/default/files/styles/180_240/public/gpburdell.jpg','2018-11-25 00:04:41','the struggle is real!',21),(35,'https://www.cc.gatech.edu/projects/XMLApe/people/imgs/leo.jpg','2018-11-25 00:09:17','Leo Mark, CS 6400 professor',21),(36,'https://www.cc.gatech.edu/sites/default/files/images/27126038747_06d417015b_z.jpg','2018-11-25 00:09:56','fearless leader of OMSCS',21),(37,'http://daily.gatech.edu/sites/default/files/styles/1170_x_x/public/hgt-tower-crop.jpg','2018-11-25 00:12:53','ech Tower interior photo',22),(38,'http://www.livinghistory.gatech.edu/s/1481/images/content_images/techtower1_636215523842964533.jpg','2018-11-25 00:13:43','Tech Tower exterior photo',22),(39,'https://www.ece.gatech.edu/sites/default/files/styles/1500_x_scale/public/images/mercury/kessler2.0442077-p16-49.jpg','2018-11-25 00:14:20','Kessler Campanile at Georgia Tech',22),(40,'https://www.scs.gatech.edu/sites/scs.gatech.edu/files/files/klaus-building.jpg','2018-11-25 00:15:01','Klaus building',22),(41,'https://www.news.gatech.edu/sites/default/files/styles/740_x_scale/public/uploads/mercury_images/Tech_Tower_WebFeature_1.jpg','2018-11-25 00:15:43','Tech tower sign',22),(42,'http://traditions.gatech.edu/images/mantle-reck3.jpg','2018-11-25 00:18:44','Ramblin’ wreck today',23),(43,'http://www.swag.gatech.edu/sites/default/files/buzz-android-tablet.jpg','2018-11-25 00:27:47','Driving the mini wreck',23),(44,'http://www.livinghistory.gatech.edu/s/1481/images/content_images/ramblinwreck1_636215542678295357.jpg','2018-11-25 00:28:37',' Ramblin’ Wreck of the past',23),(45,'https://www.news.gatech.edu/sites/default/files/uploads/mercury_images/screen_shot_2016-08-11_at_12.45.48_pm_10.png','2018-11-25 00:29:04','Bobby Dodd stadium',23),(46,'http://www.livinghistory.gatech.edu/s/1481/images/content_images/thevarsity1_636215546286483906.jpg','2018-11-25 00:30:16','The Varsity',24),(47,'http://blogs.iac.gatech.edu/food14/files/2014/09/wafflefries2.jpg','2018-11-25 00:30:41','Chick-fil-a Waffle Fries',24),(48,'http://it.studentlife.gatech.edu/sites/default/files/uploads/images/superblock_images/it_imac.png','2018-11-25 00:32:21','iMac',25),(49,' https://pe.gatech.edu/sites/pe.gatech.edu/files/component_assets/Computer_Lab_Tech_750_x_500.jpg','2018-11-25 00:34:12','Computer lab',25),(50,'https://www.scs.gatech.edu/sites/scs.gatech.edu/files/files/cs-research-databases.jpg','2018-11-25 00:34:41','Database server',25),(51,'https://pbs.twimg.com/media/DZzi7dyU8AAUSJe.jpg','2018-11-25 00:37:16','Georgia Tech Transette',26),(52,'https://www.calendar.gatech.edu/sites/default/files/events/related-images/mini_500_0_0.jpg','2018-11-25 00:37:44','Mini 500',26),(53,'https://www.gatech.edu/sites/default/files/uploads/images/superblock_images/tech-trolly.png','2018-11-25 00:38:17','Tech Trolley',26),(54,'https://hr.gatech.edu/sites/default/files/uploads/images/superblock_images/nee-buzz.jpg','2018-11-25 00:39:14','Buzz',27),(55,'https://georgiadogs.com/images/2018/4/6/18_Uga_VIII.jpg','2018-11-25 00:39:45','Uga the “dog\"',27),(56,'https://www.news.gatech.edu/sites/default/files/pictures/feature_images/running%20sideways.jpg','2018-11-25 00:40:15','Sideways the dog',27);
/*!40000 ALTER TABLE `pushpin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `tagsID` int(11) NOT NULL AUTO_INCREMENT,
  `pushpinTag` varchar(20) DEFAULT NULL,
  `pushpinID` int(11) NOT NULL,
  PRIMARY KEY (`tagsID`),
  UNIQUE KEY `pushcorkboard` (`pushpinTag`,`pushpinID`),
  KEY `tags_ibfk_1` (`pushpinID`),
  CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`pushpinID`) REFERENCES `pushpin` (`pushpinID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (55,'administration',37),(57,'administration',38),(82,'blades',50),(38,'burdell',32),(68,'buzz',43),(32,'buzzcard',29),(77,'computer',48),(51,'computer science',36),(46,'computing',35),(53,'computing',36),(61,'computing',40),(83,'computing',50),(89,'connections',53),(48,'database faculty',35),(95,'dawgs',55),(50,'dean',36),(64,'decked out',42),(75,'delicious',47),(54,'facilities',37),(56,'facilities',38),(72,'football',45),(70,'football game',44),(91,'free',53),(73,'game day',45),(37,'george p burdell',32),(36,'Georgia tech seal',31),(34,'great seal',31),(47,'gtcomputing',35),(52,'gtcomputing',36),(60,'gtcomputing',40),(81,'gtcomputing',49),(85,'historical oddity',51),(78,'Macintosh',48),(76,'macOS',48),(65,'mascot',43),(92,'mascot',54),(97,'mascot',56),(94,'not our mascot',55),(35,'official',31),(31,'OMSCS',28),(63,'parade',42),(67,'parade',43),(69,'parade',44),(79,'PCs',49),(33,'Piazza',30),(86,'race',52),(66,'ramblin wreck',43),(84,'rapid transit',51),(39,'student',32),(59,'student facilities',40),(80,'student facilities',49),(62,'tohellwithgeorgia',42),(71,'tohellwithgeorgia',45),(93,'tohellwithgeorgia',55),(74,'traditions',46),(87,'traditions',52),(96,'traditions',56),(90,'transit',53),(88,'tricycle',52),(49,'zvi',36);
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `pin` varchar(4) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'norasmith@gmail.com','Nora Smith','1111'),(2,'jrobert@gmail.com','Jack Robert','2222'),(3,'janed@gatech.edu','Jane Doe','3333'),(4,'johnd@gatech.edu','John Doe','4444'),(5,'davidj@gatech.edu','David Jane','5555'),(6,'emilyr@gatech.edu','Emily Robinson','6666');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `watchcorkboard`
--

DROP TABLE IF EXISTS `watchcorkboard`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `watchcorkboard` (
  `watchID` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `watchedID` int(11) NOT NULL,
  PRIMARY KEY (`watchID`),
  UNIQUE KEY `watchrelation` (`userID`,`watchedID`),
  KEY `watchcorkboard_ibfk_2` (`watchedID`),
  CONSTRAINT `watchcorkboard_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  CONSTRAINT `watchcorkboard_ibfk_2` FOREIGN KEY (`watchedID`) REFERENCES `corkboard` (`corkboardID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `watchcorkboard`
--

LOCK TABLES `watchcorkboard` WRITE;
/*!40000 ALTER TABLE `watchcorkboard` DISABLE KEYS */;
/*!40000 ALTER TABLE `watchcorkboard` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-11-25  0:43:24
