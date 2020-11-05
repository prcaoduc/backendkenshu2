<?php
require_once('controllers/base_controller.php');
require_once('models/article.php');
require_once('models/authentication.php');
require_once('helpers/validator.php');

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

  public function add()
  {
    if (Authentication::check()) {
      $user = Authentication::user();
      $this->render('add');
    }
    else header('Location: ?controller=pages&action=home');
  }

  public function create(){
    $errors_array = [];
    $input = [
      'title'     => $_POST['title'],
      'content'   => $_POST['content'],
      'tag'       => $_POST['tag'],
      'author_id' => Authentication::user()->id
    ];

    
    ///////////// validation codes /////////////
    //length validation
    strlen($input['title']) > 50 ? $errors_array['title'] = 'length' : null;
    // concate all errors to 1 array
    // blankValidation is using for blank verify
    $errors_array = array_merge($errors_array, Validator::blankValidator($input));

    if (empty($errors_array)) {
      $article_id = Article::create($input['title'], $input['content'], $input['tag'], $input['author_id']);
      header('Location: ?controller=articles&action=show&id=' . $article_id);
    }
    $error = array('errors_array' => $errors_array);
    $this->render('add', $error);
  }
}
