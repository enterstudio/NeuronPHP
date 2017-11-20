<?php
class HTML {
  private static $_instance = null;
  private static $header_shown = false;

  private function __clone() {
  }

  private function __construct() {
  }

  public static function getInstance() {
    if (self::$_instance === null) {
      self::$_instance = new Templates\HTML();
      self::$_instance->header='';
    }
    return self::$_instance;
  }

  public static function setTemplate($template) {
    self::$_instance = $template;
  }

  public static function getTitle() {
    $html = self::getInstance();
    return $html->title;
  }

  public static function showPageHeader($title = null, $timestamp = false) {
    $html = self::getInstance();
    self::$header_shown = true;
    if (!$timestamp) {
      $timestamp = filemtime(filter_input(INPUT_SERVER, 'SCRIPT_FILENAME'));
    }
    header("HTTP/1.0 200 OK");
    header("Content-Type: text/html; charset=utf-8");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s \G\M\T", $timestamp));
    self::disableBrowserCache();
    $html->title = $title;
    $html->Header();
  }

  public static function showPageFooter() {
    $html = self::getInstance();
    $html->Footer();
  }

  public static function addHeader($data) {
    $html = self::getInstance();
    $html->header .= $data . PHP_EOL;
  }

  public static function addDescription($description) {
    self::addHeader("<meta name=\"description\" content=\"$description\">");
  }

  public static function addKeyword($keywords) {
    self::addHeader("<meta name=\"keywords\" content=\"$keywords\">");
  }

  public static function showException($message) {
    $title = 'Ошибка';
    $message = "<p>$message</p>" . PHP_EOL;
    if (self::$header_shown) {
      self::showPopup($title, $message);
      self::showPageFooter();
      return;
    }
    $html = new \Templates\Message();
    $html->style = 'danger';
    $html->title = $title;
    $html->site_title = \Settings::get('site')->title;
    $html->message = $message;
    $html->show();
  }

  public static function showPopup($title, $message) {
    $html = new \Templates\Popup();
    $html->title = $title;
    $html->message = "<h1>$title</h1>" . PHP_EOL . "$message";
    $html->show();
  }

  public static function showNotification($title, $message, $url = null) {
    $html = new \Templates\Message();
    $message = "<p>$message</p>" . PHP_EOL;
    if (!is_null($url)) {
      $html->header = '<meta http-equiv="Refresh" content="5;URL=' . $url . '">';
      $message .= "<p><a href=\"$url\">Продолжить.</a></p>";
    }
    Header("Content-Type: text/html; charset=utf-8");
    self::disableBrowserCache();
    $html->title = $title;
    $html->message = $message;
    $html->site_title = \Settings::get('site')->title;
    $html->show();
    die;
  }

  public static function disableBrowserCache() {
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Expires: " . date("r"));
    header("Pragma: no-cache"); // HTTP/1.0
  }

  public static function isSecure() {
    return getenv('HTTPS') !== false;
  }

  public static function redirect($location) {
    header("Location: $location");
    echo "<a href=\"$location\">$location</a>";
    exit;
  }

  public static function redirect301($location) {
    header(filter_input(INPUT_SERVER, 'SERVER_PROTOCOL') . " 301 Moved Permanently", true);
    header("Location: $location");
    exit;
  }

  public static function error404() {
    header(filter_input(INPUT_SERVER, 'SERVER_PROTOCOL') . " 404 Not Found", true);
    echo 'File not found';
    exit;
  }

  public static function Exception($ex) {
    if ($ex instanceof AppException) {
      $message = $ex->getMessage();
    } else {
      $message = $ex->getFile() . '(' . $ex->getLine() . ')' . ': ' . $ex->getMessage() . PHP_EOL;
      if (ini_get('display_errors') != 1) {
        error_log($message, 0);
        $message = 'Извините. Произошла программная ошибка. Если вы часто видите это сообщение, сообщите о нём администратору сайта.';
      }
    }
    self::showException($message);
  }

  public static function __callStatic($name, $args) {
    if (substr($name, 0, 4) != 'show') {
      throw new Exception('Вызван несуществующий метод ' . $name);
    }
    $name = substr($name, 4);
    $callback = array(self::getInstance(), $name);
    return call_user_func_array($callback, $args);
  }

}