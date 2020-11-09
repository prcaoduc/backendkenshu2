<?php
require_once('controllers/base_controller.php');
require_once('helpers/session.php');
require_once('models/user.php');
class Authentication
{
    public static function check()
    {
        $session = new Session();
        $login_session_duration = 10*60;
        if (!empty($session->get('loggedin_time')) || !empty($session->get('email'))) {
            
            $current_time = time();
            if ((($current_time - $session->get('loggedin_time')) > $login_session_duration)) {
                return false;
            }
        }
        return true;
    }

    public static function logout()
    {
        $session = new Session();
        $session->delete('id');
        $session->delete('email');
        $session->delete('loggedin_time');
    }

    public static function user()
    {
        $session = new Session();
        
        $email = $session->get('email');

       $user = User::getCurrent($email);
       return isset($user) ? $user : null;
    }
}
