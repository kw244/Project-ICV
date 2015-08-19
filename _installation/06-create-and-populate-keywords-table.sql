CREATE TABLE IF NOT EXISTS `login`.`keywords` (
  `keyword_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'keyword id primary key',
  `keyword_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'keyword unique',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user name which created the keyword',

  PRIMARY KEY (`keyword_id`),
  UNIQUE KEY `keyword_name` (`keyword_name`),
  FOREIGN KEY (`user_name`) REFERENCES users(`user_name`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table containing the keywords and their associated users';