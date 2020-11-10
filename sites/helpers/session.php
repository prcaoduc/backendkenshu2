<?php
class Session
{
    public $session_name;
  
    public function __construct($session_name = null, $session_id = null, $regenerate_id = false)
    {
        // 既存セッションを開始します。または新しいセッションを開始します。
        if (!is_null($session_id))     session_id($session_id);
        ob_start();
        session_start();

        if (!is_null($session_name))   $this->session_name = $session_name;
        if ($regenerate_id) session_regenerate_id(true);
    }

    //CSRFのため、トークン発生
    public function genToken(){
        $_SESSION['token'] = bin2hex(random_bytes(32));
        return $_SESSION['token'];
    }

    // セッションに情報を設置する
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    // セッションから情報を取得する
    public function get($firstkey, $secondkey = false)
    {
        if ($secondkey) {
            return isset($_SESSION[$firstkey][$secondkey]) ? $_SESSION[$firstkey][$secondkey] : null;
        } else return isset($_SESSION[$firstkey]) ? $_SESSION[$firstkey] : null;
    }

    // セッションの情報を削除する
    public function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }

    // 新しいセッションIDを作成する
    // まだ使いません
    public function regenerateID($destroy_oldsession = false)
    {
        session_regenerate_id(false);

        if ($destroy_oldsession) {
            //  hang on to the new session id
            $sid = session_id();
            //  close the old and new sessions
            session_write_close();
            //  re-open the new session
            session_id($sid);
            session_start();
        }
    }

    // セッションを破壊する
    public function destroy()
    {
        return session_destroy();
    }

    // まだ使いません
    public function getName()
    {
        return $this->session_name;
    }
}
