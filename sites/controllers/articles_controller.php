<?php
require_once('controllers/base_controller.php');
require_once('models/article.php');

class ArticlesController extends BaseController
{
  function __construct()
  {
    $this->folder = 'articles';
  }

  public function index()
  {
    $articles = Article::all();
    $data = array('articles' => $articles);
    $this->render('index', $data);
  }

  public function show()
  {
    $article = Article::find($_GET['id']);
    $data = array('article' => $article);
    $this->render('show', $data);
  }
}