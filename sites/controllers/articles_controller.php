<?php
require_once('controllers/base_controller.php');
require_once('models/article.php');
require_once('models/tag.php');
require_once('models/articletags.php');
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
      $data = array('token' => $token);
      $this->render('add', $data);
    } else header('Location: ?controller=pages&action=home');
  }

  // 新しい記事を作成するコントローラー
  public function create()
  {
    $session = new Session();
    // クッキー確認
    if (!empty($_POST)) {
      // CSRF確認
      if (!empty($_POST['csrftoken']) && (hash_equals($session->get('token'), $_POST['csrftoken']))) {
        $errors_array = [];
        $thumbnail_position = 0;
        $input = [
          'title'     => $_POST['title'],
          'content'   => $_POST['content'],
          'tag'       => $_POST['tag'],
          'author_id' => Authentication::user()->id,
        ];
        if (!empty($_POST['thumbnail'])) $thumbnail_position = $_POST['thumbnail'];

        ///////////// validation codes /////////////
        //length validation
        strlen($input['title']) > 80 ? $errors_array['title'] = 'length' : null;
        // concate all errors to 1 array
        // blankValidator is using for blank verify
        $errors_array = array_merge($errors_array, Validator::blankValidator($input));
        // imageValidator is using for validate image
        if (!empty(Validator::imageValidator($_FILES['images']))) $errors_array['images'] = Validator::imageValidator($_FILES['images']);

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

            // イメージファイルの変数
            $upload_dir = './assets/images/';
            $names = $_FILES['images']['name'];
            $tmp_names = $_FILES['images']['tmp_name'];

            for ($i = 0; $i < count($tmp_names); $i++) {
              // イメージの拡張を取得する
              $img_ext = strtolower(pathinfo($names[$i], PATHINFO_EXTENSION));
              // イメージの名前を設置しておく
              $target_image = rand(1000, 1000000) . "." . $img_ext;
              // イメージの位置を作成する
              $image_path = $upload_dir . $target_image;
              // サムネイルであるかではないかイメージをDBに保存する。
              if ($i == $thumbnail_position) Image::create($article_id, $image_path, 1);
              else Image::create($article_id, $image_path, 0);
              // ファイルを設置した位置に移動する
              move_uploaded_file($tmp_names[$i], $image_path);
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
    $db = DB::getInstance();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // POST確認
    if (!empty($_POST)) {
      // CSRF確認
      if (!empty($_POST['csrftoken']) && (hash_equals($session->get('token'), $_POST['csrftoken']))) {
        try {
          $db->beginTransaction();
          // 記事の情報を編集する
          $article = Article::find($_POST['id']);
          $input = [
            'title'     => $_POST['title'],
            'content'   => $_POST['content'],
          ];
          $article->update($input);

          // タグとのリレーションを編集する
          $tags_input = $_POST['tag'];
          $tags_id = Tag::multiCreate($tags_input);
      
          // insert into pivot table tag's id and article's id from above
          // ピボットテーブルに記事とタグのリレーションの情報を保存する
          ArticleTags::multiTagsCreate($article->id, $tags_id);
          $db->commit();
          if (!empty($article)) {
            header('Location: ?controller=users&action=articles');
          } else header('Location: ?controller=articles&action=edit&id=' . $article->id);
        } catch (PDOException $e) {
          $db->rollback();
          throw $e;
        }
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
