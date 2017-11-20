<?php
class AppException extends Exception {
  public function __construct(string $message = "", int $code = 0) {
    if(DB::isConnected()) {
      DB::rollback();
    }
    parent::__construct($message, $code, null);
  }
}
