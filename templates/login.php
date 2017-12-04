<?php
namespace Templates;
class Login {  
  public function show() {
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?=$this->title?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<h1><?=$this->site_title?></h1>
<h2><?=$this->title?></h2>
<form method="POST" action="<?=$this->url?>">
  <input type="hidden" name="redirect_url" value="<?=$this->redirect_url?>">
  <label for="inputEmail">Логин:</label>
    <input type="text" name="login" placeholder="Введите имя учётной записи">
  <label for="inputPassword">Пароль:</label>
    <input type="password" name="password" placeholder="Введите пароль">
  <button type="submit">Войти</button>
</form>
</body>
</html>
<?php
  }
}