CREATE TABLE IF NOT EXISTS `cafecham_sparksms`.`contacts` (
  `contact_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'contact id primary key',
  `contact_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'contact name',
  `contact_country_code` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT 'contact country code',
  `contact_number` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'contact number',

  PRIMARY KEY (`contact_id`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table containing contacts information';