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


INSERT INTO `{prefix}authitem` (`name`, `type`, `description`, `bizrule`, `data`) VALUES
('Comments.Default.*', 1, 'Комментарии (*)', NULL, 'N;'),
('Comments.Default.Index', 0, 'Список комментарий', NULL, 'N;'),
('Comments.Default.Delete', 0, 'Удаление комментарий', NULL, 'N;'),
('Comments.Default.Update', 0, 'Редактирование комментарий', NULL, 'N;'),
('Comments.Default.Switch', 0, 'Скрыть/показать комментарий', NULL, 'N;'),
('Comments.Default.UpdateStatus', 0, 'Изменение статусов комментарий', NULL, 'N;'),
('Comments.Settings.*', 1, 'Настройки комментарий (*)', NULL, 'N;'),
('Comments.Settings.Index', 0, 'Настройки комментарий', NULL, 'N;');