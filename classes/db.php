<?php
class DB {
  private static $_instance=null;
  private static $init_params=[];

  private function __construct() {
  }

  private function __clone() {
  }
  
  public static function init($dsn, $username, $password,$timezone_set) {
    self::$init_params['dsn']=$dsn;
    self::$init_params['username']=$username;
    self::$init_params['password']=$password;
    self::$init_params['timezone_set']=$timezone_set;
  }

  public static function getInstance() {
    if (self::$_instance) {
      return self::$_instance;
    }
    self::$_instance=new PDO(self::$init_params['dsn'],self::$init_params['username'],self::$init_params['password']);
    self::$_instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    self::$_instance->query(self::$init_params['timezone_set']);
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