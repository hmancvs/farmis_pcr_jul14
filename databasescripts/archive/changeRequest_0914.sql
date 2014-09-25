ALTER TABLE `useraccount` 
	 ADD COLUMN `services` varchar(50) NOT NULL,
	 ADD COLUMN `languages` varchar(50) NOT NULL,
	 ADD COLUMN `hasmobilemoney` tinyint(4) unsigned DEFAULT 0,
	 ADD COLUMN `hasbankaccount` tinyint(4) unsigned DEFAULT 0;
	 

INSERT INTO `lookuptype` (`id`, `name`, `displayname`, `listable`, `updatable`, `description`, `datecreated`, `createdby`, `lastupdatedate`, `lastupdatedby`) VALUES 
(46,'SERVICE_TYPES','Content Types',1,1,'Content Types','2014-09-01 12:00:00',1,NULL,NULL),
(47,'LANGUAGE_TYPES','Language Types',1,1,'Language Types','2014-09-01 12:00:00',1,NULL,NULL);

INSERT INTO `lookuptypevalue` (`id`, `lookuptypeid`, `lookuptypevalue`, `lookupvaluedescription`, `createdby`, `datecreated`, `lastupdatedate`, `lastupdatedby`) 
VALUES 
(350,46,'1','Market Prices',1,'2014-09-01 12:00:00',NULL,NULL),
(351,46,'2','Weather Alerts',1,'2014-09-01 12:00:00',NULL,NULL),
(352,46,'3','Farming Tips',1,'2014-09-01 12:00:00',NULL,NULL),
(353,46,'4','Financial Tips',1,'2014-09-01 12:00:00',NULL,NULL),
(354,46,'5','Trading Offers',1,'2014-09-01 12:00:00',NULL,NULL),
(355,47,'1','English',1,'2014-09-01 12:00:00',NULL,NULL),
(356,47,'2','Runyankole',1,'2014-09-01 12:00:00',NULL,NULL),
(357,47,'3','Luganda',1,'2014-09-01 12:00:00',NULL,NULL),
(358,47,'4','Luo',1,'2014-09-01 12:00:00',NULL,NULL),
(359,47,'5','Lusoga',1,'2014-09-01 12:00:00',NULL,NULL);

UPDATE `useraccount` set services = '1,2,3,4,5' where type = 2;
UPDATE `useraccount` set languages = '1' where type = 2;

UPDATE `useraccount` set hasmobilemoney = '1' where type = 2 and phone <> '';
UPDATE `useraccount` set hasmobilemoney = '0' where type = 2 and (phone = '' || phone is null);
UPDATE `useraccount` set hasbankaccount = '0' where type = 2;


