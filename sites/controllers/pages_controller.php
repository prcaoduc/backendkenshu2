<?php
require_once('controllers/base_controller.php');
require_once('models/article.php');
require_once('helpers/session.php');
require_once('models/authentication.php');
// スターティックページクラス
class PagesController extends BaseController
{
  function __construct()
  {
    $this->folder = 'pages';
  }

  public function home()
  {
    $articles = Article::all();
    $data = array('articles' => $articles);
    $this->render('home', $data);
  }

  public function error()
  {
    $this->render('error');
  }
}