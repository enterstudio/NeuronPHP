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
  "admins": [1]
}
CONFIG
);

DB::init("mysql:host=localhost;dbname=neuronphp;charset=utf8","username","password",'SET TIME_ZONE="Asia/Yekaterinburg"');
date_default_timezone_set("Asia/Yekaterinburg");
set_exception_handler('HTML::Exception');