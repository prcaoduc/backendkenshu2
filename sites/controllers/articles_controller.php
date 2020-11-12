<?php
require_once('controllers/base_controller.php');
require_once('models/article.php');
require_once('models/articleimages.php');
require_once('helpers/authentication.php');
require_once('helpers/validator.php');
class ArticlesController extends BaseController
{
  function __construct()
  {
    $this->folder = 'articles';
  }

  // 記事を全部閲覧するviewをコール
  public function index()
  {
    $articles = Article::all();
    $data = array('articles' => $articles);
    $this->render('index', $data);
  }

  // 記事を閲覧するviewをコール
  public function show()
  {
    $article = Article::find($_GET['id']);
    $data = array('article' => $article);
    $this->render('show', $data);
  }

  // 新しい記事を作成するviewをコール
  public function add()
  {
    // ログイン確認
    if (Authentication::check()) {
      $session = new Session();
      $token = $session->genToken();
      $user = Authentication::user();
      $data = array('token' => $token, 'user' => $user);
      $this->render('add', $data);
    } else header('Location: ?controller=pages&action=home');
  }

  // 新しい記事を作成するコントローラー
  public function create()
  {
    $session = new Session();
    // POST空確認
    if (!empty($_POST)) {
      // CSRF確認
      if (!empty($_POST['csrftoken']) && (hash_equals($session->get('token'), $_POST['csrftoken']))) {
        $errors_array = [];
        $thumbnail_position = 0;
        $input = [
          'title'           => $_POST['title'],
          'content'         => $_POST['content'],
          'tag'             => $_POST['tag'],
          'author_id'       => Authentication::user()->id,
          'selected_images' => $_POST['selected_images'],
          'thumbnail'       => $_POST['thumbnail']
        ];
        if (!empty($input['thumbnail'])) $thumbnail_position = $input['thumbnail'];

        ///////////// validation codes /////////////
        //length validation
        strlen($input['title']) > 80 ? $errors_array['title'] = 'length' : null;
        // concate all errors to 1 array
        // blankValidator is using for blank verify
        $errors_array = array_merge($errors_array, Validator::blankValidator($input));

        $id = $session->get('id');
        $email = $session->get('email');
        if (empty($errors_array)) {
          // エラーが発生しなければ、記事作成のトランサクションを実施する
          $db = DB::getInstance();
          $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          try {
            $db->beginTransaction();
            // 新しい記事をDBに保存する
            $article_id = Article::create($input['title'], $input['content'], $input['tag'], $input['author_id']);
            
            // 記事とイメージの関係を保存する
            if( !empty($input['selected_images']) && !empty($input['thumbnail']) ){
              foreach($input['selected_images'] as $image_id){
                var_dump($image_id);
                var_dump($article_id);
                if($image_id == $input['thumbnail']) ArticleImages::create($article_id, $image_id, 1);
                else                                ArticleImages::create($article_id, $image_id, 0);
              }
            }
            $db->commit();
          } catch (PDOException $e) {
            $db->rollback();
            throw $e;
          }
          // 作成を成功する場合、その記事の閲覧ページに移動する
          header('Location: ?controller=articles&action=show&id=' . $article_id);
          exit;
        }
        $error = array('errors_array' => $errors_array);
        var_dump($errors_array);
        $this->render('add', $error);
      } else {
        // CSRF確認失敗すると、ログアウトする
        $errors_array['csrftoken'] = 'wrong';
        Authentication::logout();
        header('Location: ?controller=authentications&action=login');
      }
    } else {
      $errors_array['input'] = 'blank';
      $error = array('errors_array' => $errors_array);
      $this->render('add', $error);
    }
  }

  public function edit()
  {
    $session = new Session();
    if (Authentication::check()) {
      $token = $session->genToken();
      $article = Article::find($_GET['id']);
      $data = ['article' => $article, 'token' => $token];
      $this->render('edit', $data);
    } else header('Location: ?controller=pages&action=home');
  }

  public function update()
  {
    $session = new Session();
    // POST確認
    if (!empty($_POST)) {
      // CSRF確認
      if (!empty($_POST['csrftoken']) && (hash_equals($session->get('token'), $_POST['csrftoken']))) {
        $article = Article::find($_POST['id']);
        $input = [
          'title'     => $_POST['title'],
          'content'   => $_POST['content'],
        ];
        $result = $article->update($input);
        if (!empty($article)) {
          header('Location: ?controller=users&action=articles');
        } else header('Location: ?controller=articles&action=edit&id=' . $article->id);
      } else {
        // CSRF確認失敗すると、ログアウトする
        $errors_array['csrftoken'] = 'wrong';
        Authentication::logout();
        header('Location: ?controller=authentications&action=login');
      }
    } else {
      $errors_array['input'] = 'blank';
      $error = array('errors_array' => $errors_array);
      $this->render('edit', $error);
    }
  }

  public function delete()
  {
    $session = new Session();
    // POST空確認
    if (!empty($_POST)) {
      // CSRF確認
      if (!empty($_POST['csrftoken']) && (hash_equals($session->get('token'), $_POST['csrftoken']))) {
        $article = Article::find($_POST['id']);
        $article->revoke();
        header('Location: ?controller=users&action=articles');
      } else {
        // CSRF確認失敗すると、ログアウトする
        $errors_array['csrftoken'] = 'wrong';
        Authentication::logout();
        header('Location: ?controller=authentications&action=login');
      }
    } else {
      $errors_array['input'] = 'blank';
      $error = array('errors_array' => $errors_array);
      $this->render('edit', $error);
    }
  }
}
