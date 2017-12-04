<?php
require_once '../common.php';
$login=filter_input(INPUT_POST,'login');
$password=filter_input(INPUT_POST,'password');
if(is_null($login) or is_null($password)) {
  HTML::showLoginForm();
  exit;
}
Auth::authentication($login,$password);
$url=filter_input(INPUT_POST,'redirect_url');
HTML::redirect(is_null($url)?'/':$url);
