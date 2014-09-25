-- remove verifier and verifiedby
ALTER TABLE `payment` drop column startdate, drop column enddate;
ALTER TABLE `subscription` drop column isactive, drop column istrial;
ALTER TABLE `subscription` ADD COLUMN `paymentid` int(11) unsigned DEFAULT NULL;
ALTER TABLE `useraccount` ADD COLUMN `startdate` date DEFAULT NULL, ADD COLUMN `enddate` date DEFAULT NULL;
ALTER TABLE `subscription` drop column verifier, drop column verifiedbyid;

update payment p INNER JOIN useraccount u set u.paymentid = p.id, u.paymentstatus = 1, u.startdate = p.trxdate where p.userid is not null and p.userid = u.id;
update useraccount u set u.enddate = DATE_ADD(u.startdate,INTERVAL 365 DAY) where u.paymentstatus = 1 and u.paymentid <> '' AND u.enddate IS NULL;
update payment p INNER JOIN useraccount u set p.phone = u.phone where p.userid = u.id and u.phone is null;

-- insert subscription lines
INSERT INTO subscription(userid, planid, startdate, enddate, datecreated, paymentid)
      SELECT p.userid, p.item, u.startdate, u.enddate, p.datecreated, p.id
      FROM payment p
      INNER JOIN useraccount u
            ON p.userid = u.id      
      WHERE u.paymentid is not null and u.paymentstatus = 1;
	  
update payment p INNER JOIN subscription s set p.subscriptionid = s.id where s.paymentid = p.id and p.subscriptionid is null;	  

