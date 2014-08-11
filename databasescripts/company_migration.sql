/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50524
Source Host           : localhost:3306
Source Database       : farmis

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2014-07-24 14:44:25
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `company`
-- ----------------------------
DROP TABLE IF EXISTS `company`;
CREATE TABLE `company` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `contactperson` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `type` tinyint(4) unsigned DEFAULT NULL,
  `locationid` int(11) unsigned DEFAULT NULL,
  `country` varchar(3) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `farmistype` tinyint(4) DEFAULT NULL,
  `regionid` int(11) unsigned DEFAULT NULL,
  `regionids` varchar(50) DEFAULT NULL,
  `districtid` int(11) unsigned DEFAULT NULL,
  `districtids` varchar(50) DEFAULT NULL,
  `dnaid` int(11) unsigned DEFAULT NULL,
  `dnaids` varchar(50) DEFAULT NULL,
  `datecreated` datetime DEFAULT NULL,
  `createdby` int(11) unsigned DEFAULT NULL,
  `lastupdatedate` datetime DEFAULT NULL,
  `lastupdatedby` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of company
-- ----------------------------
INSERT INTO `company` VALUES ('1', 'Partner One', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `company` VALUES ('2', 'Partner Two', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `company` VALUES ('3', 'Partner Three', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
