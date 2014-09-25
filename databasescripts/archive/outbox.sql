/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50524
Source Host           : localhost:3306
Source Database       : farmis

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2014-08-22 16:01:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `outbox`
-- ----------------------------
DROP TABLE IF EXISTS `outbox`;
CREATE TABLE `outbox` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `datecreated` datetime DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `msg` varchar(1000) DEFAULT NULL,
  `source` varchar(15) DEFAULT NULL,
  `result` varchar(255) DEFAULT NULL,
  `createdby` int(11) unsigned DEFAULT NULL,
  `deliverstatus` varchar(15) DEFAULT NULL,
  `deliverdetails` varchar(255) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of outbox
-- ----------------------------
