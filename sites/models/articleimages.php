<?php
require_once('models/article.php');
require_once('models/image.php');
// articleimagesテーブルレコードをマッピングするためのモデルクラス
class ArticleImages
{

    // DBに新たなユーザーを作る
    static function create($article_id, $image_id, $isthumbnail = 0)
    {
        $data = [
            'article_id'        => $article_id,
            'image_id'          => $image_id,
            'isthumbnail'       => $isthumbnail
        ];
        $db = DB::getInstance();
        $sql = "INSERT INTO article_images (article_id, image_id, isthumbnail) VALUES (:article_id, :image_id, :isthumbnail)";
        $req = $db->prepare($sql);
        $req->execute($data);
        return $req > 0 ? true : false;
        // $req->setFetchMode(PDO::FETCH_CLASS, 'User');
        // $item = $req->fetch();
        // return isset($item) ? $item : null;
    }

}
