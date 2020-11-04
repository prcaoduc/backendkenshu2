<?php
require_once('controllers/base_controller.php');
require_once('models/user.php');
require_once('helpers/session.php');

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

  public function register()
  {
    $session = new Session();
    $_POST = $session->get('signup');
    $this->render('register');
  }

  public function login(){
    $session = new Session();
    $this->render('login');
  }

  public function test(){
    $this->render('test');
  }

  public function signin()
  {
    $session = new Session();
    if (!empty($_COOKIE['email'])) {
      $_POST['email'] = $_COOKIE['email'];
      $_POST['pwd'] = $_COOKIE['pwd'];
      $_POST['save'] = 'on';
    }
    if (!empty($_POST)) {
      if (!empty($_POST['email']) && !empty($_POST['pwd'])) {
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];
        $member = User::login($email, $pwd);
        if ($member) {
          $session->set('id', $member->id);
          $session->set('time', time());
          if ($_POST['save'] == 'on') {
            setcookie('email', $email, time() + 60 * 60 * 24 * 14);
            setcookie('pwd', $pwd, time() + 60 * 60 * 24 * 14);
          }
          echo $_SESSION['id'];
          $data = array('user' => $member);
          $this->render('show', $data);
        } else $errors_array['login'] = 'false';
      } else $errors_array['login'] = 'blank';
    }
  }

  public function check()
  {
    $session = new Session();
    $errors_array = [];
    $input = [
      'nickname' => $_POST['nickname'],
      'email' => $_POST['email'],
      'pwd' => $_POST['pwd'],
      'pwd-repeat' => $_POST['pwd-repeat']
    ];

    ///////////// validation codes /////////////
    // lenght validation
    strlen($input['pwd']) < 4 ? $errors_array['pwd'] = 'length' : null;
    // password and it's repeat match validation
    strcmp($input['pwd'], $input['pwd-repeat']) != 0 ? $errors_array['pwd-repeat'] = 'notsame' : null;
    // concate all errors to 1 array
    $errors_array = array_merge($errors_array, UsersController::blankValidator($input));
    // duplicate unique rows validation
    User::exist('email', $input['email']) ? $errors_array['email'] = 'duplicate' : null;
    User::exist('nickname', $input['nickname']) ? $errors_array['nickname'] = 'duplicate' : null;
    filter_var($input['email'], FILTER_VALIDATE_EMAIL) ? null : $errors_array['email'] = 'email';

    if (empty($errors_array)) {
      $session->set('signup', $_POST);
      $check_data = array(
        'email'     => $session->get('signup', 'email'),
        'nickname'  => $session->get('signup', 'nickname')
      );
      $data = array('check_data' => $check_data);
      $this->render('check', $data);
    }
    $error = array('errors_array' => $errors_array);
    $this->render('register', $error);
  }

  public function signup()
  {
    $session = new Session();
    if (!empty($session->get('signup'))) {
      $user = $session->get('signup');
      $user_id = User::create($user['nickname'], $user['email'], $user['pwd']);
      $session->delete('signup');
      header('Location: ?controller=users&action=show&id=' . $user_id);
      exit;
    } else {
      $session->delete('signup');
      header('Location: ?controller=pages&action=error');
      exit;
    }
  }

  private static function blankValidator($input)
  {
    $error = [];
    $empty_fields = array_filter($input, array('UsersController', 'emptyFilter'));
    foreach ($empty_fields as $key => $value) $error[$key] = 'blank';
    return $error;
  }

  private static function emptyFilter($val)
  {
    return empty($val);
  }
}
