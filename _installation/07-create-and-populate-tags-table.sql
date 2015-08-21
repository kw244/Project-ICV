CREATE TABLE IF NOT EXISTS `cafecham_sparksms`.`tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'tag id',
  `tag_name` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT 'tag non-unique',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user name which created the tag',

  PRIMARY KEY (`tag_id`),
  FOREIGN KEY (`user_name`) REFERENCES users(`user_name`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table containing tags and their associated users';