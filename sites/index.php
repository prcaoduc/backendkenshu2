<?php
require_once('connection.php');
// ルートのパラメータを設定する
if (isset($_GET['controller']) || isset($_POST['controller'])) {
  $controller = $_GET['controller'].$_POST['controller'];
  if (isset($_GET['action']) || isset($_POST['action'])) {
    $action = $_GET['action'].$_POST['action'];
    // exit;
  } else {
    $action = 'index';
  }
}
else {
  $controller = 'pages';
  $action = 'home';
}
require_once('routes.php');