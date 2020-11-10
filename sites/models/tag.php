<?php
//  tagsテーブルレコードをマッピングするためのモデルクラス
class Tag{
    public $name;

    // DBに新しいタグを保存する
    static function create($name){
        $db = DB::getInstance();
        $req = $db->prepare('SELECT ALL FROM tags WHERE name = ?');
        $req->execute(array($name));
        $record = $req->fetch(\PDO::FETCH_OBJ);
        if(isset($record->name)) return $record;
        else {
        $query = "INSERT INTO tags (name) VALUES (?)";
        $req = $db->prepare($query)->execute(array($name));
        if($req > 0) $db->lastInsertId();
        }
    }
}