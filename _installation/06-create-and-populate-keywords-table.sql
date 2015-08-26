CREATE TABLE IF NOT EXISTS `cafecham_sparksms`.`keywords` (
  `keyword_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'keyword id primary key',
  `keyword_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'keyword unique',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user name which created the keyword',
  `keyword_response` TEXT COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'automated response for keyword if NOT NULL',
  `keyword_msgs_used` SMALLINT DEFAULT 0 COMMENT 'records the # of msgs used for the autoreply',
  PRIMARY KEY (`keyword_id`),
  UNIQUE KEY `keyword_name` (`keyword_name`),
  FOREIGN KEY (`user_name`) REFERENCES users(`user_name`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table containing the keywords and their associated users';