<?php
require_once('connection.php');
session_start();
// ルートのパラメータを設定する
// index.phpファイルはルーティングすることを責任する。
if (isset($_GET['controller']) || isset($_POST['controller'])) {
  $controller = $_GET['controller'].$_POST['controller'];
  if (isset($_GET['action']) || isset($_POST['action'])) {
    $action = $_GET['action'].$_POST['action'];
  } else {
    $action = 'index';
  }
}
else {
  $controller = 'pages';
  $action = 'home';
}
require_once('routes.php');