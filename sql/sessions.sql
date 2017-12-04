CREATE TABLE `sessions` (
  `session_id` varchar(64) NOT NULL,
  `session_data` text NOT NULL,
  `ip` blob NOT NULL,
  `browser` text NOT NULL,
  `start_session_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `session_expires` timestamp NOT NULL,
  `session_duration` int(10) UNSIGNED NOT NULL DEFAULT '14400',
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
