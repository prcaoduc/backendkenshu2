<?php
require_once('connection.php');
// ルートのパラメータを設定する
if (isset($_GET['controller'])) {
  $controller = $_GET['controller'];
  if (isset($_GET['action'])) {
    $action = $_GET['action'];
  } else {
    $action = 'index';
  }
}
// これらのパラメータを割り当てない場合は、
// デフォルトとしてcontroller = 'pages', action = 'home'. 
else {
  $controller = 'pages';
  $action = 'home';
}
require_once('routes.php');