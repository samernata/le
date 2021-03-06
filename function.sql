DELIMITER $$

CREATE FUNCTION insertContent(
id_p varchar(50),	voucherType_p VARCHAR(30),	voucherStatus_p VARCHAR(30),	voucherNumber_p varchar(50),	voucherDate_p dateTime,	createdDate_p dateTime,	updatedDate_p dateTime,	dueDate_p dateTime,	contactId_p varchar(50),	contactName_p varchar(50),	totalAmount_p decimal,	openAmount_p decimal,	currency_p varchar(10),	archived_p BOOLEAN
) 
RETURNS VARCHAR(20)
DETERMINISTIC
BEGIN
    DECLARE found_id varchar(50) default null;
     declare retvalue int default 0;
   SET found_id=(SELECT `id` FROM `content` WHERE `id`=id_p);

  

    IF  found_id is null   THEN
    INSERT INTO `content`(`id`, `voucherType`, `voucherStatus`, `voucherNumber`, `voucherDate`, `createdDate`, `updatedDate`, `dueDate`, `contactId`, `contactName`, `totalAmount`, `openAmount`, `currency`, `archived`) VALUES (
        id_p,voucherType_p,voucherStatus_p,voucherNumber_p,voucherDate_p,createdDate_p,updatedDate_p,dueDate_p,contactId_p,contactName_p,totalAmount_p,openAmount_p,currency_p,archived_p);
		   SET retvalue=1;   
      return retvalue;
    ELSE 
        UPDATE `content` SET `voucherType`=voucherType_p,`voucherStatus`=voucherStatus_p,`voucherNumber`=voucherNumber_p,`voucherDate`=voucherDate_p,`createdDate`=createdDate_p,`updatedDate`=updatedDate_p,`dueDate`=dueDate_p,`contactId`=contactId_p,`contactName`=contactName_p,`totalAmount`=totalAmount_p,`openAmount`=openAmount_p,`currency`=currency_p,`archived`=archived_p WHERE `id`=id_p;
        	   SET retvalue=1;   
      return retvalue;
    END IF;

      return retvalue;
END$$
DELIMITER ;