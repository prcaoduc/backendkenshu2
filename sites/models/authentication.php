<?php
require_once('controllers/base_controller.php');
require_once('helpers/session.php');
require_once('models/user.php');
class Authentication {

    protected static $db;

    public function __construct() {
        $this->db = DB::getInstance();
    }

    public static function getDB(){
        self::$db = DB::getInstance();
        return self::$db;
    }

    public static function check(){
        self::$db = DB::getInstance();
        $session = new Session();
        $loggedin = $session->get('loggedin');
        return ($loggedin && $loggedin == true) ? true : false; 
    }

    public static function user(){
        $session = new Session();
        $id = $session->get('id');
        $email = $session->get('email');

        $query = self::getDB()->prepare('SELECT * FROM users WHERE id = :id AND email = :email');
        $query->execute(array('id' => $id, 'email' => $email));
        $query->setFetchMode(PDO::FETCH_CLASS, 'User');
        $req = $query->fetch();
        return !empty($req) ? $req : null;
    }

    // public static function getAuthentication($email, $pwd) {
    //     $query = $this->db->prepare('SELECT email, pass FROM users WHERE email = :email');
    //     $query->bindParam(':email', $email, \PDO::PARAM_STR);
    //     $query->execute();
    //     $queryRequest = $query->fetch(\PDO::FETCH_OBJ);
    //     if($queryRequest) {
    //         return password_verify($pwd, $queryRequest->pwd);
    //     }
    // }

    /**
     * Changing password authentication
     * @param $password
     * @return bool
     */
    // public static function setAuthentication($password) {
    //     $query = $this->db_connection->prepare('UPDATE users SET password=:password LIMIT 1');
    //     $newPassword = password_hash($password , PASSWORD_DEFAULT);
    //     $query->bindParam(':password', $newPassword);
    //     return $query->execute();
    // }

}