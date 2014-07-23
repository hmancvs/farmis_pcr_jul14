-- select * from location l where l.locationtype = 2 and l.country = 'ke';

DELETE from location where id = 93 OR id = 43358 OR id = 115 OR id = 113 OR id = 119;

ALTER TABLE `location` ADD COLUMN `wikiregionid` int(11) unsigned DEFAULT NULL;

INSERT INTO `location` (`id`,`name`,`description`,`locationtype`,`country`,`regionid`,`districtid`,`countyid`,`subcountyid`,`parishid`,`datecreated`,`createdby`,`lastupdatedate`,`lastupdatedby`,`gpslat`,`gpslng`,`parishname`,`villagename`,wikiregionid) VALUES 
 (125,'Sheema','',2,'UG',4,NULL,NULL,NULL,NULL,'2011-03-28 14:52:41',1,'2011-05-31 15:21:07',1,NULL,NULL,NULL,NULL, 4);
-- add the missing data for wikiend and gps before continuing

-- update regions data 
update farmis.location f inner join agmis.location a set f.regionid = a.regionid where (f.id = a.id AND f.country = 'UG');
-- UPDATE location set regionid = wikiregionid where locationtype = 2 AND country = 'UG'; 
-- update location l inner join location d on l.districtid = d.id set l.wikiregionid = l.regionid where (l.regionid >= 5 AND l.locationtype > 2  AND l.country = 'UG');
-- update location l inner join location d on l.districtid = d.id set l.regionid = d.regionid where (l.regionid >= 5 AND l.locationtype > 2 AND l.country = 'UG');
update location l set l.locationtype = 0 where l.id IN(5,6,7);

-- ALTER TABLE `location` drop column `linkhref`, drop column `wikihref`;
ALTER TABLE `location` ADD COLUMN `linkhref` varchar(255) DEFAULT NULL;
update location l set l.linkhref = concat('http://en.wikipedia.org/wiki/',l.name,'_District') where l.locationtype = 2 and l.country='UG';

update farmis.location f inner join agmis.location a set f.gpslat = a.gpslat, f.gpslng = a.gpslng where (f.id = a.id AND a.gpslat <> '' AND a.gpslng <> '' AND f.country = 'UG');

ALTER TABLE `useraccount` ADD COLUMN `paymentstatus` int(11) unsigned DEFAULT 0;
ALTER TABLE `useraccount` ADD COLUMN `paymentid` int(11) unsigned DEFAULT NULL;

ALTER TABLE `farmgroup` ADD COLUMN `paymentstatus` int(11) unsigned DEFAULT 0;
ALTER TABLE `farmgroup` ADD COLUMN `paymentid` int(11) unsigned DEFAULT NULL;

-- add country field to payment
ALTER TABLE `payment` ADD COLUMN `country` varchar(2) DEFAULT 'UG';
ALTER TABLE `payment` ADD COLUMN `startdate` date DEFAULT NULL;
ALTER TABLE `payment` ADD COLUMN `enddate` date DEFAULT NULL;
UPDATE payment p inner join useraccount u set p.country = u.country where p.userid = u.id;

-- update status for those who have paid
UPDATE useraccount u inner join payment p set u.paymentstatus = 1, u.paymentid = p.id where p.userid = u.id and p.userid <> '';
UPDATE farmgroup f inner join payment p set f.paymentstatus = 1, f.paymentid = p.id where p.farmgroupid = f.id and p.farmgroupid <> '';

-- permission and role changes (script imported manually)

ALTER TABLE `membershipplan` ADD COLUMN `amountke` decimal(10,0) DEFAULT NULL;
UPDATE `membershipplan` set amount = '0', amountke = '0' where id = 1;
UPDATE `membershipplan` set amount = '20000', amountke = '850' where id = 2;
UPDATE `membershipplan` set amount = '0', amountke = '0' where id = 3;
UPDATE `membershipplan` set amount = '360000', amountke = '12000' where id = 4;