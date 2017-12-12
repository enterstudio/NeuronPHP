<?php
set_include_path(__DIR__.'/classes/');
spl_autoload_extensions('.php');
spl_autoload_register();

Settings::init(<<<CONFIG
{
	"site": {
		"title": "Мой сайт"
	},
	"session": {
		"name": "neuronphp",
		"time": 3600,
		"path": "/"
	},
	"pdo": {
		"dsn": "mysql:host=localhost;dbname=DB_NAME;charset=utf8",
		"username": "DB_USER",
		"password": "DB_PASSWORD",
		"timezone_set": "SET TIME_ZONE=\"Asia/Yekaterinburg\""
	},
	"admins": [1]
}
CONFIG
);

date_default_timezone_set("Asia/Yekaterinburg");
set_exception_handler('HTML::Exception');