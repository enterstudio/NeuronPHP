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
      }
    } else {
      session_unset();
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
  
  /**
   * Вход в систему с помощью логина или пароля
   * @param type $login
   * @param type $password
   * @throws AppException
   */
  public static function authentication($login,$password) {
    $user=self::fetchUser($login, $password);
    if(!$user) {
      throw new AppException('Неверное имя пользователя или пароль.');
    }
    self::$_instance=new self($user);
  }
  
  /**
   * Вход в систему посредством внешней системы аутентификации
   * @param type $ext_user
   * @return boolean
   * @throws AppException
   */
  public static function authenticationExternal($ext_user) {
    if(!$ext_user) {
      throw new AppException('Не удалось войти в систему.');
    }
    $q=DB::prepare('SELECT user_id FROM user_external WHERE ext_login=?');
    $q->execute([$ext_user->login]);
    $user_id=$q->fetch(PDO::FETCH_COLUMN);
    $q->closeCursor();
    if(!$user_id) {
      return false;
    }
    $q=DB::prepare('SELECT * FROM users WHERE id=?');
    $q->execute([$user_id]);
    $user=$q->fetch(PDO::FETCH_OBJ);
    $q->closeCursor();
    if(!$user) {
      return false;
    }
    self::$_instance=new self($user);
    return true;
  }

  /**
   * Вход в систему указанного пользователя
   * @param type $user
   * @throws AppException
   */
  public static function login($user) {
    if(!$user) {
      throw new AppException('Не удалось войти в систему.');
    }
    self::$_instance=new self($user);    
  }
  
  public static function logout() {
    Session::start();
    session_unset();
    session_destroy();
    Session::write_close();
  }

  public static function grantAccess(string ... $groups) {
    $auth=self::getInstance();
    if($auth->checkAccess($groups)) {
      return true;
    }
    if($auth->user->login=='guest') {
      throw new AppException('Для доступа к разделу необходимо войти в систему!', AppException::AUTH_REQUIRED);
    } else {
      throw new AppException('У вас неn прав доступа к разделу.', AppException::ACCESS_DENIED);
    }
  }
  
  public static function memberOf(string ... $groups) {
    $auth=self::getInstance();
    return $auth->checkAccess($groups);    
  }
  
  private function checkAccess($groups) {
    if(sizeof($groups)==0) {
      return !is_null($this->user->id);
    }
    if(isset($this->user->admin) and $this->user->admin) {
      return true;
    }
    foreach($groups AS $group) {
      if(isset($this->user->$group) and $this->user->$group==1) {
        return true;
      }
    }
    return false;
  }
  
  private static function fetchUser($login,$password) {
    $q=DB::prepare('SELECT * FROM users WHERE login=?');
    $q->execute([$login]);
    $user=$q->fetch(PDO::FETCH_OBJ);
    $q->closeCursor();
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