/* Database export results for db DBObj */

/* Preserve session variables */
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS;
SET FOREIGN_KEY_CHECKS=0;

/* Export data */

/* Table structure for Addresses */
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Table structure for Blogs */
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Table structure for Contacts */
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Table structure for Emails */
CREATE TABLE `Emails` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PID` int(11) NOT NULL,
  `Name` varchar(520) NOT NULL DEFAULT 'New Email',
  `Address` varchar(520) NOT NULL,
  `Primary` int(11) NOT NULL DEFAULT '0',
  `Created` int(20) DEFAULT NULL,
  `Updated` int(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Table structure for Keys */
CREATE TABLE `Keys` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Key` varchar(255) NOT NULL,
  `Code` varchar(10) NOT NULL,
  `Definition` varchar(1024) DEFAULT NULL,
  `Created` int(20) DEFAULT NULL,
  `Updated` int(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Table structure for Phones */
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Table structure for Posts */
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Table structure for Relations */
CREATE TABLE `Relations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `RID` int(11) DEFAULT NULL,
  `KID` int(11) DEFAULT NULL,
  `Created` int(20) DEFAULT NULL,
  `Updated` int(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Table structure for Settings */
CREATE TABLE `Settings` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) NOT NULL,
  `Value` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Title` (`Title`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Table structure for Users */
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* create command for Relationships */

DELIMITER $$
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `Relationships` AS (select `r`.`ID` AS `ID`,`r`.`RID` AS `RID`,`r`.`KID` AS `KID`,`r`.`Created` AS `Created`,`r`.`Updated` AS `Updated`,`k`.`Key` AS `Key`,`k`.`Code` AS `Code`,`k`.`Definition` AS `Definition` from (`Relations` `r` left join `Keys` `k` on((`k`.`ID` = `r`.`KID`))))$$

DELIMITER ;

/* Restore session variables to original values */
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
