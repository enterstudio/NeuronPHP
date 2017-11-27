CREATE TABLE `sessions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `session_data` text NOT NULL,
  `ip` blob NOT NULL,
  `browser` text NOT NULL,
  `start_session_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `session_expires` timestamp NOT NULL,
  `session_duration` int(10) UNSIGNED NOT NULL DEFAULT '14400',
  `state` enum('ACTIVE','TIMEOUT','LOGOUT','') NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
