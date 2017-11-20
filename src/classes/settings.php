<?php
class Settings {
  private static $_instance = null;
  private $settings;
  
  private function __clone() {}

  private function __construct($settings) {
    $this->settings=json_decode($settings);
    if(is_null($this->settings)) {
      throw new AppException('Ошибка в файле конфигурации!');
    }
  }
  
  public static function init($settings) {
    self::$_instance = new self($settings);
    return self::$_instance;
  }

  public static function get($name) {
    if(!isset(self::$settings->$name)) {
      throw new AppException('Не найден параметр в файле конфигурации!');
    }
    return self::$settings->$name;
  }
}
