CREATE TABLE IF NOT EXISTS `{prefix}comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `owner_title` text NOT NULL,
  `ip_create` varchar(12) NOT NULL,
  `user_agent` text NOT NULL,
  `model` varchar(255) NOT NULL,
  `object_id` int(11) NOT NULL,
  `text` text,
  `date_create` datetime NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `switch` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
