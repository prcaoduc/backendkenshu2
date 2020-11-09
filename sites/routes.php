<?php
// システム内のコントロールは自分のアクションを呼び出せる
//
$controllers = array(
  'pages'                 => ['home', 'error'], // staticpages routes
  'articles'              => ['index', 'show', 'add', 'create'], // articles routes
  'users'                 => ['index', 'show'], // users routes
  'authentications'       => ['login', 'signin', 'register', 'check', 'signup', 'thanks', 'logout']
); 

// URLから取得したパラメーターはまだ設定しないばい（まだ作らない）
// controller = 'pages', action = 'error'.
if (!array_key_exists($controller, $controllers) || !in_array($action, $controllers[$controller])) {
  $controller = 'pages';
  $action = 'error';
}

// 定義された controller class を使用するため、ファイルを埋め込む
include_once('controllers/' . $controller . '_controller.php');
// 取得した値からコントローラークラス名を生成して
$klass = str_replace('_', '', ucwords($controller, '_')) . 'Controller';
// それを呼び出してユーザーにリターンを表示する。
$controller = new $klass;
$controller->$action();