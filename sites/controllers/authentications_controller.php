<?php
require_once('controllers/base_controller.php');
require_once('helpers/session.php');
require_once('helpers/validator.php');
require_once('models/user.php');
require_once('helpers/authentication.php');
class AuthenticationsController extends BaseController
{
    function __construct()
    {
        $this->folder = 'authentications';
    }

    // ログインviewを表示する
    public function login()
    {
        if (!Authentication::check()) {
            $session = new Session();
            $token = $session->genToken();
            $data = ['token' => $token];
            $this->render('login', $data);
        } else header('Location: ?controller=pages&action=home');
    }

    // POSTから取得された情報を確認し、処理し、ログインする
    public function signin()
    {
        $session = new Session();
        // クッキー確認
        if (isset($_COOKIE['token'])) {
            $user_id = Authentication::checkAuthToken($_COOKIE['token']);
            if(!empty($user_id)){
                $user = User::find($user_id);
                $_POST['email'] = $user->email;
                $_POST['pwd'] = $user->pass;
                $_POST['save'] = 'on';
            }
        }
        // POST空確認
        if (!empty($_POST)) {
            // CSRF確認
            if (!empty($_POST['csrftoken']) && (hash_equals($session->get('token'), $_POST['csrftoken']))) {
                // 情報確認
                if (!empty($_POST['email']) && !empty($_POST['pwd'])) {
                    $email = $_POST['email'];
                    $pwd = $_POST['pwd'];
                    // ログインする
                    $member = User::login($email, $pwd);
                    if ($member) {
                        // ユーザーセッションを設置する
                        $session->set('id', $member->id);
                        $session->set('email', $member->email);
                        $session->set('loggedin_time', time());
                        if ($_POST['save'] == 'on') {
                            Authentication::setAuthToken($member->id);
                        }
                        header('Location: ?controller=users&action=show&id=' . $member->id);
                        exit;
                    } else $errors_array['login'] = 'false';
                } else $errors_array['login'] = 'blank';
            } else {
                // CSRF確認失敗すると、ログアウトする
                $errors_array['csrftoken'] = 'wrong';
                Authentication::logout();
                header('Location: ?controller=authentications&action=login');
            }
        }
        if (!empty($errors_array)) {
            $error = array('errors_array' => $errors_array);
            $this->render('login', $error);
        }
    }

    // メンバー登録viewをコールする
    public function register()
    {
        // ログインした確認
        if (!Authentication::check()) {
            $session = new Session();
            $_POST = $session->get('signup');
            $token = $session->genToken();
            $data = ['token' => $token];
            $this->render('register', $data);
        } else header('Location: ?controller=pages&action=home');
    }

    // メンバー登録情報の確認ステップviewをコールする
    public function check()
    {
        $session = new Session();
        // POST空確認
        if (!empty($_POST)) {
            // CSRF確認
            if (!empty($_POST['csrftoken']) && (hash_equals($session->get('token'), $_POST['csrftoken']))) {
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
                // blankValidation is using for blank verify
                $errors_array = array_merge($errors_array, Validator::blankValidator($input));
                // duplicate unique rows validation
                User::exist('email', $input['email']) ? $errors_array['email'] = 'duplicate' : null;
                User::exist('nickname', $input['nickname']) ? $errors_array['nickname'] = 'duplicate' : null;
                filter_var($input['email'], FILTER_VALIDATE_EMAIL) ? null : $errors_array['email'] = 'email';
                
                // エラーが発生しないと、情報確認viewを表示する
                if (empty($errors_array)) {
                    $session->set('signup', $_POST);
                    $check_data = array(
                        'email'     => $session->get('signup', 'email'),
                        'nickname'  => $session->get('signup', 'nickname')
                    );
                    $data = array('check_data' => $check_data);
                    $token = $session->genToken();
                    $data = ['token' => $token];
                    $this->render('check', $data);
                }
                // エラーが発生する場合、register viewに戻ります。
                $error = array('errors_array' => $errors_array);
                $this->render('register', $error);
            } else {
                // CSRF確認失敗すると、ログアウトする
                $errors_array['csrftoken'] = 'wrong';
                Authentication::logout();
                header('Location: ?controller=authentications&action=login');
            }
        } else {
            $errors_array['input'] = 'blank';
            $error = array('errors_array' => $errors_array);
            $this->render('check', $error);
        }
    }

    public function signup()
    {
        $session = new Session();
        // POST空確認
        if (!empty($_POST)) {
            // CSRF確認
            if (!empty($_POST['csrftoken']) && (hash_equals($session->get('token'), $_POST['csrftoken']))) {
                if (!empty($session->get('signup'))) {
                    // 新しいメンバーのじょうほうをDBに保存する
                    $user = $session->get('signup');
                    User::create($user['nickname'], $user['email'], $user['pwd']);
                    $session->delete('signup');
                    header('Location: ?controller=authentications&action=thanks');
                    exit;
                } else {
                    $session->delete('signup');
                    header('Location: ?controller=pages&action=error');
                    exit;
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
            $this->render('check', $error);
        }
    }

    public function thanks()
    {
        $this->render('thanks');
    }

    public function logout()
    {
        Authentication::logout();
        header('Location: ?controller=authentications&action=login');
    }
}
