<?php
require_once('controllers/base_controller.php');
require_once('helpers/session.php');
require_once('helpers/validator.php');
require_once('models/user.php');
require_once('helpers/authentication.php');
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
}
