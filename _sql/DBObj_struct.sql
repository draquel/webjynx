-- MySQL dump 10.13  Distrib 5.7.12, for Win64 (x86_64)
--
-- Host: webjynxrds.cjzpxtjfv2ad.us-east-1.rds.amazonaws.com    Database: DBObj
-- ------------------------------------------------------
-- Server version	5.6.27-log

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
-- Table structure for table `Addresses`
--

DROP TABLE IF EXISTS `Addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Addresses` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PID` int(11) NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Address2` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `State` varchar(255) DEFAULT NULL,
  `Zip` varchar(5) DEFAULT NULL,
  `Created` int(20) DEFAULT NULL,
  `Updated` int(20) DEFAULT NULL,
  `Primary` int(11) DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `CID` (`PID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Blogs`
--

DROP TABLE IF EXISTS `Blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Blogs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Keywords` varchar(255) DEFAULT NULL,
  `PageSize` int(11) NOT NULL DEFAULT '5',
  `Active` tinyint(1) NOT NULL DEFAULT '1',
  `Created` int(11) NOT NULL,
  `Updated` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Contacts`
--

DROP TABLE IF EXISTS `Contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Contacts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `First` varchar(255) NOT NULL,
  `Last` varchar(255) NOT NULL,
  `BDay` int(20) DEFAULT NULL,
  `Company` varchar(255) DEFAULT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Created` int(20) DEFAULT NULL,
  `Updated` int(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Emails`
--

DROP TABLE IF EXISTS `Emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Emails` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PID` int(11) NOT NULL,
  `Name` varchar(520) NOT NULL DEFAULT 'New Email',
  `Address` varchar(520) NOT NULL,
  `Primary` int(11) NOT NULL DEFAULT '0',
  `Created` int(20) DEFAULT NULL,
  `Updated` int(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Emails`
--

LOCK TABLES `Emails` WRITE;
/*!40000 ALTER TABLE `Emails` DISABLE KEYS */;
INSERT INTO `Emails` VALUES (1,1,'Personal','draquel@webjynx.com',1,1471491413,1471491413);
/*!40000 ALTER TABLE `Emails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Keys`
--

DROP TABLE IF EXISTS `Keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Keys` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Key` varchar(255) NOT NULL,
  `Code` varchar(10) NOT NULL,
  `Definition` varchar(1024) DEFAULT NULL,
  `Created` int(20) DEFAULT NULL,
  `Updated` int(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Keys`
--

LOCK TABLES `Keys` WRITE;
/*!40000 ALTER TABLE `Keys` DISABLE KEYS */;
INSERT INTO `Keys` VALUES (1,'Parent','Contact','Objects which are children of Contact Objects',1471408604,1471408604),(2,'Parent','User','Objects which are children of User Objects',1471408604,1471408604),(3,'Parent','Blog','Objects which are children of Blog Objects',1471408604,1471408604);
/*!40000 ALTER TABLE `Keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Phones`
--

DROP TABLE IF EXISTS `Phones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Phones` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PID` int(11) NOT NULL DEFAULT '0',
  `Region` varchar(3) DEFAULT '1',
  `Area` varchar(3) DEFAULT NULL,
  `Number` varchar(8) DEFAULT NULL,
  `Name` varchar(255) DEFAULT 'Phone',
  `Ext` varchar(6) DEFAULT NULL,
  `Primary` int(1) DEFAULT '0',
  `Created` int(20) DEFAULT NULL,
  `Updated` int(20) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `CID` (`PID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Posts`
--

DROP TABLE IF EXISTS `Posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Posts` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Keywords` varchar(255) DEFAULT NULL,
  `Active` tinyint(1) DEFAULT '1',
  `Author` int(11) NOT NULL,
  `HTML` text NOT NULL,
  `CoverImage` varchar(255) DEFAULT NULL,
  `Created` int(11) NOT NULL,
  `Updated` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Relations`
--

DROP TABLE IF EXISTS `Relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Relations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `RID` int(11) DEFAULT NULL,
  `KID` int(11) DEFAULT NULL,
  `Created` int(20) DEFAULT NULL,
  `Updated` int(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Relations`
--

LOCK TABLES `Relations` WRITE;
/*!40000 ALTER TABLE `Relations` DISABLE KEYS */;
INSERT INTO `Relations` VALUES (1,1,2,1471492028,1471492028);
/*!40000 ALTER TABLE `Relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `Relationships`
--

DROP TABLE IF EXISTS `Relationships`;
/*!50001 DROP VIEW IF EXISTS `Relationships`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `Relationships` AS SELECT 
 1 AS `ID`,
 1 AS `RID`,
 1 AS `KID`,
 1 AS `Created`,
 1 AS `Updated`,
 1 AS `Key`,
 1 AS `Code`,
 1 AS `Definition`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `Settings`
--

DROP TABLE IF EXISTS `Settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Settings` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) NOT NULL,
  `Value` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Title` (`Title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `First` varchar(255) NOT NULL,
  `Last` varchar(255) NOT NULL,
  `BDay` int(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL DEFAULT 'password',
  `Created` int(255) NOT NULL,
  `Updated` int(255) NOT NULL,
  `LLogin` int(25) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (1,'Dan','Raquel',463665600,'draquel','6f8278151f7fa554ffa2040ddb9c1ce801223d72',1471491653,1471491653,0);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'DBObj'
--

--
-- Final view structure for view `Relationships`
--

/*!50001 DROP VIEW IF EXISTS `Relationships`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `Relationships` AS (select `r`.`ID` AS `ID`,`r`.`RID` AS `RID`,`r`.`KID` AS `KID`,`r`.`Created` AS `Created`,`r`.`Updated` AS `Updated`,`k`.`Key` AS `Key`,`k`.`Code` AS `Code`,`k`.`Definition` AS `Definition` from (`Relations` `r` left join `Keys` `k` on((`k`.`ID` = `r`.`KID`)))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-20 11:45:31
