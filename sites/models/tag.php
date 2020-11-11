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

    static function isContained($name){
        $db = DB::getInstance();
        $sql = "SELECT * FROM tags WHERE name = ?";
        $req = $db->prepare($sql);
        $req->execute(array($name));
        $item = $req->fetch(\PDO::FETCH_OBJ);
        return $item;
    }

    static function multiCreate($names = []){
        $db = DB::getInstance();
        $list = [];
        foreach($names as $tag_name){
            $tag = Tag::isContained($tag_name);
            if (isset($tag->name)) $list[] = $tag->id;
            else {
              // 存在しなければ、保存する
              $sql2 = "INSERT INTO tags (name) VALUES (?)";
              $req = $db->prepare($sql2)->execute(array($tag_name));
              if ($req > 0) $list[] = $db->lastInsertId();
            }
          }
          return $list;
    }
}