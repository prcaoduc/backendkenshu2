<?php
class Session{
    private $session_name;

    public function __construct($session_name = null, $session_id = null, $regenerate_id = false)
    {
        if( !is_null($session_id) )     session_id($session_id);
        ob_start();
        session_start();

        if( !is_null($session_name) )   $this->session_name = $session_name;
        if($regenerate_id) session_regenerate_id(true);
    }

    public function set($key, $value){
        $_SESSION[$key] = $value;
    }

    public function get($firstkey, $secondkey = false){
        if($secondkey){
            return isset($_SESSION[$firstkey][$secondkey]) ? $_SESSION[$firstkey][$secondkey] : null ;
        }
        else return isset($_SESSION[$firstkey]) ? $_SESSION[$firstkey] : null ;
    }

    public function delete($key){
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }

    public function regenerateID($destroy_oldsession = false){
        session_regenerate_id(false);

        if($destroy_oldsession){
            //  hang on to the new session id
            $sid = session_id();
            //  close the old and new sessions
            session_write_close();
            //  re-open the new session
            session_id($sid);
            session_start();
        }
    }

    public function destroy(){
        return session_destroy();
    }

    public function getName(){
        return $this->session_name;
    }

}