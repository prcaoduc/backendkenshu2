<?php
//  autologinテーブルレコードをマッピングするためのモデルクラス
require_once(__DIR__.'/../models/user.php');
class Autologin{
    static function create($user_id, $token, $expired_time){
        $data=[
            'user_id'       => $user_id,
            'token'         => $token,
            'expired_time'  => $expired_time,
        ];
        $db = DB::getInstance();
        $query = "INSERT INTO autologin VALUES (:user_id, :token, :expired_time)";
        $req = $db->prepare($query)->execute($data);
        var_dump($req);
        return $req ? $token : null; 
    }

    static function update($user_id, $token, $expired_time){
        $data=[
            'user_id'       => $user_id,
            'token'         => $token,
            'expired_time'  => $expired_time,
        ];
        $db = DB::getInstance();
        $query = "UPDATE autologin SET token=:token, expired_time=:expired_time WHERE user_id=:user_id";
        $req = $db->prepare($query)->execute($data);
        return $req ? $token : null; 
    }

    static function exists($user_id){
        $db = Db::getInstance();
        $query = "SELECT * FROM autologin WHERE user_id = ?";
        $req = $db->prepare($query);
        $req->execute(array($user_id));
        return ($req->fetch() > 0) ? true : false;
    }

    static function findByToken($token){
        $db = DB::getInstance();
        $query = "SELECT * FROM autologin WHERE token = ?";
        $req = $db->prepare($query);
        $req->execute([$token]);
        $req->setFetchMode(PDO::FETCH_CLASS, 'Autologin');
        $autologin = $req->fetch();
        return isset($autologin) ? $autologin : null;
    }
}