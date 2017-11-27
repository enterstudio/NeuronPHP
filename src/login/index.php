<?php
require_once '../common.php';
$login=filter_input(INPUT_POST,'login');
$password=filter_input(INPUT_POST,'password');
if(is_null($login) or is_null($password)) {
  HTML::showLoginForm();
  exit;
}
Auth::login($login,$password);
$url=filter_input(INPUT_POST,'redirect_url');
header('Location: '.(is_null($url)?'/':$url));
