<?php
namespace Templates;
class HTML {
  public function Header() {
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?=$this->title?></title>
<meta name="viewport" content="width=device-width">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link href="/styles.css" rel="stylesheet">
<?=$this->header?>
</head>
<body>
<?php
  }
  public function Footer() {
?>
</body>
</html>
<?php  
  }
}