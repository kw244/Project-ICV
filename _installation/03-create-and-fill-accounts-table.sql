CREATE TABLE IF NOT EXISTS `cafecham_sparksms`.`accounts` (
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, foreign key',
  `account_type` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s account type',
  `sms_credits` int NOT NULL COMMENT 'sms credits available to the user',
  `keyword_credits` smallint NOT NULL COMMENT 'keyword credits available to the user',
  FOREIGN KEY (`user_name`) REFERENCES users(`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user account data';
