CREATE TABLE `user_external` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `ext_login` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `ext_login` (`ext_login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `user_external`
  ADD CONSTRAINT `user_external_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
