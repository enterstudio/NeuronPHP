<?php
require_once '../common.php';
Auth::logout();
HTML::showNotification('Выход', 'Вы вышли из системы','/');
