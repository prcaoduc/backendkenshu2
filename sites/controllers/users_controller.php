<?php
require_once('controllers/base_controller.php');
require_once('helpers/authentication.php');
require_once('models/user.php');
require_once('models/article.php');
class UsersController extends BaseController
{
  function __construct()
  {
    $this->folder = 'users';
  }

  // ユーザーリストを閲覧するためのアクション
  public function index()
  {
    $users = User::all();
    $data = array('users' => $users);
    $this->render('index', $data);
  }

  // ユーザー情報を閲覧するためのアクション
  public function show()
  {
    $user = User::find($_GET['id']);
    $data = array('user' => $user);
    $this->render('show', $data);
  }

  // ユーザーの記事リストを閲覧するためのアクション
  public function articles()
  {
    if (Authentication::check()) { // ログインした確認
      $session = new Session();
      $token = $session->genToken();
      $curr_user = Authentication::user();
      $data = [
        'user'      => $curr_user,
        'token'     => $token
      ];
      $this->render('articles', $data);
    } else header('Location: ?controller=pages&action=home');
  }
}
