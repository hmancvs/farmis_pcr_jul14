-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.5.24


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema farmis
--

CREATE DATABASE IF NOT EXISTS farmis;
USE farmis;

--
-- Definition of table `aclgroup`
--

DROP TABLE IF EXISTS `aclgroup`;
CREATE TABLE `aclgroup` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `datecreated` datetime NOT NULL,
  `createdby` int(11) unsigned NOT NULL,
  `lastupdatedate` datetime DEFAULT NULL,
  `lastupdatedby` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `aclgroup`
--

/*!40000 ALTER TABLE `aclgroup` DISABLE KEYS */;
INSERT INTO `aclgroup` (`id`,`name`,`description`,`datecreated`,`createdby`,`lastupdatedate`,`lastupdatedby`) VALUES 
 (1,'Administrator','System configuration users','2012-09-01 12:00:00',1,NULL,NULL),
 (2,'Farmer','Farmis main entity','2012-09-01 12:00:00',1,NULL,NULL),
 (3,'DNA Admin','Farmis support data clerk','2012-09-01 12:00:00',1,NULL,NULL),
 (4,'PIA','Farm Group users','2012-09-01 12:00:00',1,NULL,NULL),
 (5,'Management','FIT uganda managerial role','2012-09-01 12:00:00',1,NULL,NULL),
 (6,'Partner','Farmis Partners','2014-07-01 12:00:00',1,NULL,NULL),
 (7,'Infotrade Staff','Infotrade Staff','2014-07-01 12:00:00',1,NULL,NULL);
/*!40000 ALTER TABLE `aclgroup` ENABLE KEYS */;


--
-- Definition of table `aclpermission`
--

DROP TABLE IF EXISTS `aclpermission`;
CREATE TABLE `aclpermission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `groupid` int(11) unsigned DEFAULT NULL,
  `resourceid` int(11) unsigned NOT NULL,
  `create` enum('1','0') NOT NULL DEFAULT '0',
  `edit` enum('1','0') NOT NULL DEFAULT '0',
  `view` enum('1','0') NOT NULL DEFAULT '0',
  `list` enum('1','0') NOT NULL DEFAULT '0',
  `delete` enum('1','0') NOT NULL DEFAULT '0',
  `export` enum('1','0') NOT NULL DEFAULT '0',
  `approve` enum('1','0') NOT NULL DEFAULT '0',
  `acclist` enum('1','0') NOT NULL DEFAULT '0',
  `datecreated` datetime NOT NULL,
  `createdby` int(11) unsigned NOT NULL,
  `lastupdatedate` datetime DEFAULT NULL,
  `lastupdatedby` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `aclpermission`
--

/*!40000 ALTER TABLE `aclpermission` DISABLE KEYS */;
INSERT INTO `aclpermission` (`id`,`groupid`,`resourceid`,`create`,`edit`,`view`,`list`,`delete`,`export`,`approve`,`acclist`,`datecreated`,`createdby`,`lastupdatedate`,`lastupdatedby`) VALUES 
 (1,1,1,'0','0','1','1','0','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (2,1,2,'1','1','1','1','1','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (3,1,3,'1','1','1','1','1','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (4,1,4,'0','0','1','1','0','1','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (5,1,5,'1','1','1','1','1','1','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (6,1,7,'1','1','1','1','0','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (7,1,8,'0','0','1','0','0','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (8,1,6,'0','0','1','0','0','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (9,1,9,'0','0','1','0','0','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (10,1,10,'0','0','1','0','0','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (11,1,11,'0','0','1','0','0','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (12,1,12,'1','1','1','1','1','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (13,2,3,'1','1','1','0','0','0','0','0','2012-09-07 22:05:39',1,NULL,NULL),
 (14,2,6,'0','0','1','0','0','0','0','0','2012-09-07 22:05:39',1,NULL,NULL),
 (15,2,12,'1','1','1','1','0','0','0','0','2012-09-07 22:05:39',1,NULL,NULL),
 (16,3,12,'1','1','1','1','0','0','0','0','2012-09-07 22:05:39',1,NULL,NULL),
 (17,4,12,'1','1','1','1','1','0','0','0','2012-09-07 22:05:39',1,NULL,NULL),
 (18,5,12,'1','1','1','1','1','0','0','0','2012-09-07 22:05:39',1,NULL,NULL),
 (19,1,13,'1','1','1','1','1','0','0','0','2012-09-07 22:05:39',1,NULL,NULL),
 (20,2,13,'0','0','1','1','0','0','0','0','2012-09-07 22:05:39',1,NULL,NULL),
 (21,3,13,'1','1','1','1','0','0','0','0','2012-09-07 22:05:39',1,NULL,NULL),
 (22,4,13,'1','1','1','1','0','0','0','0','2012-09-07 22:05:39',1,NULL,NULL),
 (23,5,13,'1','1','1','1','1','0','0','0','2012-09-07 22:05:39',1,NULL,NULL),
 (24,1,14,'0','0','1','0','0','0','0','0','2012-03-20 22:29:09',1,NULL,NULL),
 (25,5,14,'0','0','1','0','0','0','0','0','2012-03-20 22:29:09',1,NULL,NULL),
 (26,2,15,'0','0','1','0','0','0','0','0','2012-03-20 22:29:09',1,NULL,NULL),
 (27,4,16,'0','0','1','0','0','0','0','0','2012-03-20 22:29:09',1,NULL,NULL),
 (28,5,1,'0','0','1','1','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (29,5,2,'1','1','1','1','1','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (30,5,3,'1','1','1','1','1','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (31,3,3,'1','1','1','1','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (32,4,3,'1','1','1','1','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (33,3,6,'0','0','1','0','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (34,4,6,'0','0','1','0','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (35,5,6,'0','0','1','0','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (36,5,7,'1','1','1','1','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (37,3,14,'0','0','1','0','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (38,1,22,'1','1','1','1','1','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (39,1,21,'1','1','1','1','1','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (40,1,20,'1','1','1','1','1','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (41,1,19,'0','0','1','0','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (42,1,18,'0','0','1','0','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (43,1,17,'1','1','1','1','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (44,1,23,'1','1','1','1','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (45,1,25,'0','0','1','0','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (46,1,26,'0','0','1','0','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (47,1,27,'0','0','1','0','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL);
/*!40000 ALTER TABLE `aclpermission` ENABLE KEYS */;


--
-- Definition of table `aclresource`
--

DROP TABLE IF EXISTS `aclresource`;
CREATE TABLE `aclresource` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL,
  `create` enum('1','0') NOT NULL DEFAULT '0',
  `edit` enum('1','0') NOT NULL DEFAULT '0',
  `view` enum('1','0') NOT NULL DEFAULT '0',
  `list` enum('1','0') NOT NULL DEFAULT '0',
  `delete` enum('1','0') NOT NULL DEFAULT '0',
  `approve` enum('1','0') NOT NULL DEFAULT '0',
  `export` enum('1','0') NOT NULL DEFAULT '0',
  `acclist` enum('1','0') NOT NULL DEFAULT '0',
  `datecreated` datetime NOT NULL,
  `createdby` int(11) unsigned NOT NULL,
  `lastupdatedate` datetime DEFAULT NULL,
  `lastupdatedby` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `aclresource`
--

/*!40000 ALTER TABLE `aclresource` DISABLE KEYS */;
INSERT INTO `aclresource` (`id`,`name`,`description`,`create`,`edit`,`view`,`list`,`delete`,`approve`,`export`,`acclist`,`datecreated`,`createdby`,`lastupdatedate`,`lastupdatedby`) VALUES 
 (1,'Lookup Type','Look up types','0','0','1','1','0','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (2,'Lookup Value','Values for the lookup type','1','1','1','1','1','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (3,'User Account','A user within the application','1','1','1','1','1','0','1','0','2012-03-01 12:00:00',1,NULL,NULL),
 (4,'Audit Trail','Log of selected transactions within the application','0','0','1','1','0','0','1','0','2012-03-01 12:00:00',1,NULL,NULL),
 (5,'Role','Actions a member can execute on resources','1','1','1','1','1','0','1','0','2012-03-01 12:00:00',1,NULL,NULL),
 (6,'Dashboard','user dashboard','0','0','1','0','0','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (7,'Application Settings','Application Administration','1','1','1','1','0','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (8,'Report Dashboard','The different reports in the Application','0','0','1','0','0','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (9,'Payments','Billing and payment information','0','0','1','0','0','1','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (10,'Payments Report','Payments Report','0','0','1','0','0','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (11,'Subscription Report','Subscriptions Report','0','0','1','0','0','0','0','0','2012-03-01 12:00:00',1,NULL,NULL),
 (12,'Farmer','Profile management for a farmer','1','1','1','1','1','0','1','0','2012-03-20 22:29:09',1,NULL,NULL),
 (13,'DNA','Profile management for a DNA','1','1','1','1','1','0','1','0','2012-03-20 22:29:09',1,NULL,NULL),
 (17,'Farm Group','Profile management for farm groups under a DNA','1','1','1','1','1','0','1','0','0000-00-00 00:00:00',0,NULL,NULL),
 (18,'Market Prices','Ability to view market prices','0','0','1','1','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (19,'Farmis Coverage Map','Permissions to the coverage map','0','0','1','0','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (20,'Commodities','Persmissions for commodities','1','1','1','1','1','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (21,'Business Directory','Permissions for the Business Directory','1','1','1','1','1','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (22,'Messaging','Messaging permissions','1','1','1','1','1','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (23,'Locations','Ability to view and setup locations','1','1','1','1','1','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (24,'Weather','Weather forecasts','0','0','1','0','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (25,'Bulk SMS','Access rights to sms','0','0','1','0','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL),
 (26,'Mass Mail','Access to email broadcast','0','0','1','0','0','0','0','0','0000-00-00 00:00:00',0,NULL,NULL);
/*!40000 ALTER TABLE `aclresource` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
