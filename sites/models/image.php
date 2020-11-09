<?php
class Image{
    public $url;
    public $isthumbnail;

    public static function create($article_id, $url, $isthumbnail){
        $data = [
            'article_id'        => $article_id,
            'url'               => $url,
            'isthumbnail'       => $isthumbnail
        ];
        $db = DB::getInstance();
        $sql = "INSERT INTO images (article_id, url, isthumbnail) VALUES (:article_id, :url, :isthumbnail)";
        $req = $db->prepare($sql);
        $req->execute($data);
        $image_id = $db->lastInsertId();
        return !empty($image_id) ? $image_id : null;
    }
}