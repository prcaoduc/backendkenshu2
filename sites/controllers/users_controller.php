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

  public function index()
  {
    $users = User::all();
    $data = array('users' => $users);
    $this->render('index', $data);
  }

  public function show()
  {
    $user = User::find($_GET['id']);
    $data = array('user' => $user);
    $this->render('show', $data);
  }

  public function articles(){
    $curr_user = Authentication::user();
    $data = ['user'     => $curr_user];
    $this->render('articles', $data);
  }
}
