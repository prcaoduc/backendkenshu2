<?php
class User
{
    public $id;
    public $nickname;
    public $email;
    public $password;

    function __construct($id = null, $nickname = null, $email = null)
    {
        $this->id = $id;
        $this->nickname = $nickname;
        $this->email = $email;
    }

    // create user
    static function create($nickname, $email, $password)
    {
        $data = [
            'nickname'  => $nickname,
            'email'     => $email,
            'pass'       => sha1($password)
        ];
        $db = DB::getInstance();
        $sql = "INSERT INTO users (nickname, email, pass) VALUES (:nickname, :email, :pass)";
        $req = $db->prepare($sql)->execute($data);
        return $req > 0 ? $db->lastInsertId() : null;
    }

    // get all users
    static function all()
    {
        $db = DB::getInstance();
        $req = $db->query('SELECT * FROM users');
        $list = [];

        foreach ($req->fetchAll() as $item) $list[] = new User($item['id'], $item['nickname'], $item['email']);

        return $list;
    }

    // find user by ID 
    static function find($id)
    {
        $db = DB::getInstance();
        $req = $db->prepare('SELECT * FROM users WHERE id = :id');
        $req->execute(array('id' => $id));

        $item = $req->fetch();
        if (isset($item['id'])) {
            return new User($item['id'], $item['nickname'], $item['email']);
        }
        return null;
    }

    static function login($email, $pwd){
        $db = DB::getInstance();
        $req = $db->prepare('SELECT * FROM users WHERE email = ? AND pass = ?');
        $req->execute(array($email, sha1($pwd)));
        $record = $req->fetch();
        return isset($record['id']) ? new User($record['id'], $record['nickname'], $record['email']) : null;
    }

    // where function (not working yet)
    static function where($row_name, $operator = '=', $values)
    {
        $db = DB::getInstance();
        $req = $db->prepare('SELECT ALL FROM users WHERE :row_name :operator :values');
        $req->execute(array(
            'row_name'  => $row_name,
            'operator'  => $operator,
            'values'    => $values
        ));
        $record = $req->fetchAll();
        return $record;
    }

    // checking users exits or not depend on what row and what keyword is used ( row has 2 option 'email' and 'nickname')
    static function exist($row_name, $keyword)
    {
        $db = DB::getInstance();
        switch ($row_name) {
            case 'email':
                break;
            case 'nickname':
                break;
            return false;
        }
        $req = $db->prepare("SELECT COUNT(*) AS cnt FROM users WHERE $row_name = ?");
        $req->execute(array($keyword));
        $record = $req->fetch();

        return ($record['cnt'] > 0) ? true : false;
    }
}
