CREATE TABLE IF NOT EXISTS `cafecham_sparksms`.`contacts_users` (
  `contact_id` int(11) NOT NULL COMMENT 'contact id',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user name',

  FOREIGN KEY (`contact_id`) REFERENCES contacts(`contact_id`),
  FOREIGN KEY (`user_name`) REFERENCES users(`user_name`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table matching contacts and associated users';