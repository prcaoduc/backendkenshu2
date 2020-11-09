<?php
require_once('controllers/base_controller.php');
require_once('models/article.php');
require_once('helpers/authentication.php');
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
    } else header('Location: ?controller=pages&action=home');
  }

  public function create()
  {
    if (!empty($_POST)) {
      $errors_array = [];
      $thumbnail_position = 0;
      $input = [
        'title'     => $_POST['title'],
        'content'   => $_POST['content'],
        'tag'       => $_POST['tag'],
        'author_id' => Authentication::user()->id,
      ];
      if(!empty($_POST['thumbnail'])) $thumbnail_position = $_POST['thumbnail'];
      
      ///////////// validation codes /////////////
      //length validation
      strlen($input['title']) > 80 ? $errors_array['title'] = 'length' : null;
      // concate all errors to 1 array
      // blankValidator is using for blank verify
      $errors_array = array_merge($errors_array, Validator::blankValidator($input));
      // imageValidator is using for validate image
      if (!empty(Validator::imageValidator($_FILES['images']))) $errors_array['images'] = Validator::imageValidator($_FILES['images']);
      $session = new Session();
      $id = $session->get('id');
      $email = $session->get('email');
      if (empty($errors_array)) {
        $db = DB::getInstance();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
          $db->beginTransaction();

          $article_id = Article::create($input['title'], $input['content'], $input['tag'], $input['author_id']);
          // var_dump($_FILES['images']);

          $upload_dir = './assets/images/';
          $names = $_FILES['images']['name'];
          $tmp_names = $_FILES['images']['tmp_name'];

          for ($i = 0; $i < count($tmp_names); $i++) {
            $img_ext = strtolower(pathinfo($names[$i], PATHINFO_EXTENSION));
            $target_image = rand(1000, 1000000) . "." . $img_ext;
            $image_path = $upload_dir . $target_image;
            if ($i == $thumbnail_position) Image::create($article_id, $image_path, 1);
            else Image::create($article_id, $image_path, 0);
            move_uploaded_file($tmp_names[$i], $image_path);
          }
          $db->commit();
        } catch (PDOException $e) {
          $db->rollback();
          throw $e;
        }
        header('Location: ?controller=articles&action=show&id=' . $article_id);
        exit;
      }
      $error = array('errors_array' => $errors_array);
      var_dump($errors_array);
      $this->render('add', $error);
    }
    else {
      $errors_array['input'] = 'blank';
      $error = array('errors_array' => $errors_array);
      $this->render('add', $error);
    }
  }
}
