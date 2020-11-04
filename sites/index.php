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
// if (isset($_POST['controller'])) {
//   $controller = $_POST['controller'];
//   if (isset($_POST['action'])) {
//     $action = $_GET['action'].$_POST['action'];
//   } else {
//     $action = 'index';
//   }
// }
// これらのパラメータを割り当てない場合は、
// デフォルトとしてcontroller = 'pages', action = 'home'. 
else {
  $controller = 'pages';
  $action = 'home';
}
require_once('routes.php');