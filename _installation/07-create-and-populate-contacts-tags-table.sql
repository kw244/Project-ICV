CREATE TABLE IF NOT EXISTS `cafecham_sparksms`.`contacts_tags` (
  `contact_id` int(11) NOT NULL COMMENT 'contact id',
  `tag_id` int(11) NOT NULL COMMENT 'tag id',

  FOREIGN KEY (`contact_id`) REFERENCES contacts(`contact_id`),
  FOREIGN KEY (`tag_id`) REFERENCES tags(`tag_id`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table matching tags to the associated contacts';