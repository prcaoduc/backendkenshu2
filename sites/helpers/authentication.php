<?php
require_once(__DIR__.'/../controllers/base_controller.php');
require_once(__DIR__.'/../helpers/session.php');
require_once(__DIR__.'/../models/user.php');
require_once(__DIR__.'/../models/autologin.php');
// ユーザーセッション処理するためのクラス
class Authentication
{
    // セッションタイムアウト確認
    public static function check()
    {
        $session = new Session();
        $login_session_duration = 20 * 60;
        if (!empty($session->get('loggedin_time')) || !empty($session->get('email'))) {

            $current_time = time();
            if ((($current_time - $session->get('loggedin_time')) > $login_session_duration)) {
                return false;
            }
        } else return false;
        return true;
    }

    // ログアウトするため、ユーザーセッションを削除する
    public static function logout()
    {
        $session = new Session();
        $session->delete('id');
        $session->delete('email');
        $session->delete('loggedin_time');
    }

    // ユーザーセッションに基づいて、ログインしたユーザーを取得する
    public static function user()
    {
        $session = new Session();
        $email = $session->get('email');
        $user = User::getCurrent($email);
        return isset($user) ? $user : null;
    }

    // 自動ログインのため、クッキートークン発生
    public static function setAuthToken($id){
        $token = bin2hex(random_bytes(32));
        $timeout = 7 * 24 * 60 * 60; // 認証の有効期間(1週間) 
        $expired_time = time() + $timeout; // 認証の有効期限
        $token = (Autologin::exists($id)) ? Autologin::update($id, $token, $expired_time) : Autologin::create($id, $token, $expired_time);
        setcookie('token', $token, $expired_time); // トークンをクッキーに
    }

    public static function checkAuthToken($token){
        $savedLogin = Autologin::findByToken($token);
        if(!empty($savedLogin->expired_time) && $savedLogin->expired_time > time()){
            return false;
        }
        else return $savedLogin->id;
    }
}
