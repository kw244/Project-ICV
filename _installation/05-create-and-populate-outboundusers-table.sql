CREATE TABLE IF NOT EXISTS `cafecham_sparksms`.`outbound_users` (
  `outbound_id` int(11) NOT NULL COMMENT 'outbound msg id',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user name',

  FOREIGN KEY (`outbound_id`) REFERENCES outbound(`outbound_id`),
  FOREIGN KEY (`user_name`) REFERENCES users(`user_name`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table matching outbound msgs and associated users';