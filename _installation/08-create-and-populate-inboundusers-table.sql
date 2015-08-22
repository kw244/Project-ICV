CREATE TABLE IF NOT EXISTS `cafecham_sparksms`.`inbound_users` (
  `inbound_id` int(11) NOT NULL COMMENT 'inbound msg id',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user name',

  FOREIGN KEY (`inbound_id`) REFERENCES inbound(`inbound_id`),
  FOREIGN KEY (`user_name`) REFERENCES users(`user_name`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table matching inbound msgs and associated users';