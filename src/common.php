<?php
set_include_path(__DIR__.'/classes/');

spl_autoload_extensions('.php');
spl_autoload_register();

Settings::init(<<<CONFIG
{
  "site": {
    "title": "Мой сайт"
  },
  "pdo": {
      "dsn": "mysql:host=localhost;dbname=neuronphp;charset=utf8",
      "username": "neuronphp",
      "password": "neuronphp"
  },
  "timezone": "Asia/Yekaterinburg",
  "session": {
    "name": "neuronphp",
    "time": 14400,
    "path": "/"
  },
  "admins": [1]
}
CONFIG
);

date_default_timezone_set(Settings::get('timezone'));

#set_exception_handler('HTML::Exception');