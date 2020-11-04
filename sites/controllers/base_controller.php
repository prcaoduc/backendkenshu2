<?php
require_once('helpers/session.php');
class BaseController
{
  // この変数はアップのviewsの中にあるフォルダを示すため、このフォルダは操作したいviewファイルがある
  protected $folder; 
  
  //　レンダー機能
  function render($file, $data = array())
  {
    // 操作したいファイルが存在するかをチェックする
    $view_file = 'views/' . $this->folder . '/' . $file . '.php';
    if (is_file($view_file)) {
      extract($data);
      ob_start();
      require_once($view_file);
      // この$contentはapplication.phpファイルに呼び出される
      $content = ob_get_clean();
      
      require_once('views/layouts/application.php');
    } else {
      // 操作したいファイルが存在しない場合、pagesのerrorアクションに移動する
      header('Location: index.php?controller=pages&action=error');
      // echo($view_file);
      // exit();
    }
  }
}