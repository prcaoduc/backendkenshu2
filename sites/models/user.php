<?php
require_once('models/article.php');
// usersテーブルレコードをマッピングするためのモデルクラス
class User
{
    public $id;
    public $nickname;
    public $email;

    // DBに新たなユーザーを作る
    static function create($nickname, $email, $pwd)
    {
        $data = [
            'nickname'  => $nickname,
            'email'     => $email,
            'pass'       => password_hash($pwd, PASSWORD_DEFAULT)
        ];
        $db = DB::getInstance();
        $sql = "INSERT INTO users (nickname, email, pass) VALUES (:nickname, :email, :pass)";
        $req = $db->prepare($sql);
        $req->execute($data);
        $user_id = $db->lastInsertId();
        return !empty($user_id) ? $user_id : null;
        // $req->setFetchMode(PDO::FETCH_CLASS, 'User');
        // $item = $req->fetch();
        // return isset($item) ? $item : null;
    }

    // public function save(){}

    // get all users
    // DBからユーザーレコードを全部取得する
    static function all()
    {
        $db = DB::getInstance();
        $req = $db->query('SELECT * FROM users');
        $list = [];

        foreach ($req->fetchAll(\PDO::FETCH_CLASS, 'User') as $item) $list[] = $item;

        return $list;
    }

    // find user by ID 
    // IDに基づいてDBからユーザーを取得する
    static function find($id)
    {
        $db = DB::getInstance();
        $req = $db->prepare('SELECT * FROM users WHERE id = :id');
        $req->execute(array('id' => $id));

        $req->setFetchMode(PDO::FETCH_CLASS, 'User');
        $item = $req->fetch();
        if (isset($item)) {
            return $item;
        }
        return null;
    }

    // ローグイン確認ため、ユーザーを取得する
    static function login($email, $pwd)
    {
        $db = DB::getInstance();
        $req = $db->prepare('SELECT * FROM users WHERE email = ?');
        $req->execute(array($email));
        $req->setFetchMode(PDO::FETCH_CLASS, 'User');
        $record = $req->fetch();
        return password_verify($pwd, $record->pass) ? $record : null;
    }

    // where function (not working yet)
    // まだ完全しません。
    static function where($row_name, $operator = '=', $values)
    {
        $db = DB::getInstance();
        $req = $db->prepare('SELECT ALL FROM users WHERE :row_name :operator :values');
        $req->execute(array(
            'row_name'  => $row_name,
            'operator'  => $operator,
            'values'    => $values
        ));

        $record = $req->fetchAll(\PDO::FETCH_CLASS, 'User');
        return $record;
    }

    // checking users exits or not depend on what row and what keyword is used ( row has 2 option 'email' and 'nickname')
    // DBにユーザー情報を保存する確認
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
        $req->execute([$keyword]);
        $record = $req->fetch();

        return ($record['cnt'] > 0) ? true : false;
    }

    // セッションから情報に基づいて現在ローグインしているユーザーを取得する
    static function getCurrent($email)
    {
        $db  = DB::getInstance();
        $req = $db->prepare('SELECT * FROM users WHERE email = :email');
        $req->execute(array('email' => $email));
        $req->setFetchMode(PDO::FETCH_CLASS, 'User');
        $user = $req->fetch();
        return !empty($user) ? $user : null;
    }

    // このユーザーの記事を全部取得する
    public function articles()
    {
        $db     = DB::getInstance();
        $list   = [];
        $query  = 'SELECT * FROM articles WHERE author_id = ? AND published = 1';
        $req    = $db->prepare($query);
        $req->execute([$this->id]);
        foreach ($req->fetchAll(\PDO::FETCH_CLASS, 'Article') as $record) {
            $list[] = $record;
        }
        return $list;
    }
}
