<?php
class Auth {
  private static $_instance=null;
  private $user;
  
  private function __construct($user=null) {
    Session::start();
    if(is_null($user)) {
      if(isset($_SESSION['user'])) {
        $this->user=$_SESSION['user'];
      } else {
        $this->user=new User();
        $this->user->admin=false;
        $_SESSION['user']=$this->user;
      }
    } else {
      session_regenerate_id(true);
      $this->user=$user;
      $_SESSION['user']=$this->user;      
    }
    Session::write_close();
    return $this;
  }
  private function __clone() {}
  private function __wakeup() {}
  
  public static function getInstance() {
    return self::$_instance===null
			? self::$_instance = new self()
			: self::$_instance;
  }
  
  public static function getUser() {
    return self::getInstance()->user;
  }
  
  public static function login($login,$password) {
    $user=self::fetchUser($login, $password);
    if(!$user) {
      throw new AppException('Неверное имя пользователя или пароль.');
    }
    self::$_instance=new self($user);
  }
  
  public static function logout() {
    Session::start();
    unset($_SESSION['user']);
    Session::write_close();
  }

  public static function grantAccess() {
    $auth=self::getInstance();
    if($auth->checkAccess(func_get_args())) {
      return true;
    }
    if($auth->user->login=='guest') {
      throw new AppException('Для доступа к разделу необходимо войти в систему!', AppException::AUTH_REQUIRED);
    } else {
      throw new AppException('У вас неn прав доступа к разделу.', AppException::ACCESS_DENIED);
    }
  }
  
  public static function memberOf() {
    $auth=self::getInstance();
    return $auth->checkAccess(func_get_args());    
  }
  
  private function checkAccess($groups) {
    if($this->user->admin) {
      return true;
    }
    foreach($groups AS $group) {
      if($this->user->$group==1) {
        return true;
      }
    }
    return false;
  }
  
  private static function fetchUser($login,$password) {
    $q=DB::prepare('SELECT * FROM users WHERE login=?');
    $q->execute([$login]);
    $user=$q->fetch(PDO::FETCH_OBJ);
    if(!$user) {
      return false;
    }
    if(!password_verify($password,$user->password)) {
      return false;
    }
    unset($user->password);
    $user->admin=in_array($user->id,Settings::get('admins'));
    return $user;
  }
}