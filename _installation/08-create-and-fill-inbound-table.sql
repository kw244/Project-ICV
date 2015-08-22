CREATE TABLE IF NOT EXISTS `cafecham_sparksms`.`inbound` (
  `inbound_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing id for each inbound msg, primary key',
  `inbound_from` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'alias of the sender for inbound msg',
  `inbound_text` TEXT COLLATE utf8_unicode_ci NOT NULL COMMENT 'text of the inbound msg',
  `inbound_created` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'time the inbound msg was created',
  PRIMARY KEY (`inbound_id`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Inbound msgs record';
