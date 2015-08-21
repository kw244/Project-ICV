CREATE TABLE IF NOT EXISTS `cafecham_sparksms`.`outbound` (
  `outbound_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing id for each outbound msg, primary key',
  `outbound_ref_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'reference id of the outbound msg with the SMS API, unique key',
  `outbound_title` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'aka campaign title',
  `outbound_from` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'alias of the sender for outbound msg',
  `outbound_to` TEXT COLLATE utf8_unicode_ci NOT NULL COMMENT 'receipients of the outbound msg, can be string of numbers or tags',
  `outbound_text` TEXT COLLATE utf8_unicode_ci NOT NULL COMMENT 'text of the outbound msg',
  `outbound_created` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'time the outbound msg was created',
  `outbound_status` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'delivery status of outbound msg, updated by response/delivery notifs',
  `outbound_log` TEXT COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'used to log any error msgs associated with outbound msg',
  `outbound_credits_used` SMALLINT NOT NULL COMMENT 'records the # of credits used for this outbound msg',
  PRIMARY KEY (`outbound_id`),
  UNIQUE KEY `outbound_ref_id` (`outbound_ref_id`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Outbound msgs record';
