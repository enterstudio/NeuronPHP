<?php
class Session implements SessionHandlerInterface {
  private $session_duration;
  
  public static function start($session_duration=false) {
    $session=Settings::get('session');
    session_set_save_handler(new Session($session_duration), true);
    session_set_cookie_params($session->time,$session->path,getenv('HTTP_HOST'),false,true);
    session_name($session->name);
    session_start();
  }
  
  public static function write_close() {
    session_write_close();
  }
  
  public function __construct($session_duration) {
    $this->session_duration=$session_duration?$session_duration:Settings::get('session')->time;
  }
  
  public function open($save_path,$name) {
    return true;
  }
  
  public function close() {
    return true;
  }
  
  public function read($session_id) {
    $q=DB::prepare("SELECT session_data FROM sessions WHERE session_id=? AND session_expires>NOW()");
    $q->execute([$session_id]);
    $result=$q->fetch(PDO::FETCH_COLUMN);
    $q->closeCursor();
    if($result) {
      return $result;
    } else {
      return "";
    }
  }
  
  public function write($session_id, $session_data) {
    $q=DB::prepare('SELECT session_id FROM sessions WHERE session_id=?');
    $q->execute([$session_id]);
    $id=$q->fetch(PDO::FETCH_COLUMN);
    $q->closeCursor();
    if($id) {
      $q=DB::prepare('UPDATE sessions SET session_expires=DATE_ADD(NOW(), INTERVAL session_duration SECOND), session_data=?, ip=?, browser=? WHERE session_id');
      $result=$q->execute([$session_data,getenv('REMOTE_ADDR'),getenv('HTTP_USER_AGENT'),$session_id]);
    } else {
      $q=DB::prepare("INSERT INTO sessions SET session_id=?, session_duration=?, session_expires=DATE_ADD(NOW(), INTERVAL session_duration SECOND), session_data=?, ip=?, browser=?");
      $result=$q->execute([$session_id,$this->session_duration,$session_data,getenv('REMOTE_ADDR'),getenv('HTTP_USER_AGENT')]);
    }
    $q->closeCursor();
    return $result;
  }
  
  public function destroy($session_id) {
    $q=DB::prepare("DELETE FROM sessions WHERE session_id=?");
    $result=$q->execute([$session_id]);
    $q->closeCursor();
    return $result;
  }
  
  public function gc($maxlifetime) {
    $q=DB::query("DELETE FROM sessions WHERE session_expires<=NOW()");
    $result=$q->rowCount();
    $q->closeCursor();
    return $result;
  }
}