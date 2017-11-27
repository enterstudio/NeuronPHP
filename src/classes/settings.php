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
    $settings=self::$_instance->settings;
    if(!isset($settings->$name)) {
      throw new AppException('Не найден параметр в файле конфигурации!');
    }
    return $settings->$name;
  }
}
