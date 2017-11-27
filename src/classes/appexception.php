<?php
class AppException extends Exception {
  const WARNING_MESSAGE=0;
  const AUTH_REQUIRED=1;
  const ACCESS_DENIED=2;
  
  public function __construct($message="",$code=0) {
    if(DB::isConnected()) {
      if(DB::inTransaction()) {
        DB::rollback();
      }
    }
    parent::__construct($message, $code, null);
  }
}
