<?php
//  imagesテーブルレコードをマッピングするためのモデルクラス
class Image{
    public $url;
    public $isthumbnail;

    // 新しいイメージ情報をDBに保存する
    public static function create($user_id = null, $url){
        $data = [
            'user_id'        => $user_id,
            'url'               => $url
        ];
        $db = DB::getInstance();
        $sql = "INSERT INTO images (user_id, url) VALUES (:user_id, :url)";
        $req = $db->prepare($sql);
        $req->execute($data);
        $image_id = $db->lastInsertId();
        return !empty($image_id) ? $image_id : null;
    }
}