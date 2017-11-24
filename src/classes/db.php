<?php
class DB {
  private static $_instance=null;

  private function __construct() {
  }

  private function __clone() {
  }

  public static function getInstance() {
    if (self::$_instance) {
      return self::$_instance;
    }
    $config=Settings::get('pdo');
    self::$_instance=new PDO($config->dsn,$config->username,$config->password);
    self::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    self::$_instance->query('SET TIME_ZONE="'.Settings::get('timezone').'"');
    return self::$_instance;
  }
  
  public static function isConnected() {
    return !is_null(self::$_instance);
  }
  
  public static function __callStatic ($name,$args) {
    $callback=array(self::getInstance(),$name);
    return call_user_func_array($callback,$args);
  }
}