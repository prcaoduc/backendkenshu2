<?php
//  articletagsテーブルレコードをマッピングするためのモデルクラス
class ArticleTags
{

    // 一つの記事と複数タグにリレーションを設置する
    static function multiTagsCreate($article_id, $tags_id)
    {
        $db = DB::getInstance();
        $sql = "DELETE FROM article_tags WHERE article_id = ?";
        $db->prepare($sql)->execute([$article_id]);
        foreach ($tags_id as $tag_id) {
            if (!empty($article_id) && !empty($tag_id)) {
                $sql3 = "INSERT INTO article_tags VALUES ( $article_id , $tag_id )";
                $req = $db->prepare($sql3)->execute();
            }
        }
        if($req > 0) true : false;
    }

    // 一つのタグと複数記事にリレーションを設置する
    // まだ完全しません
    static function multiArticleCreate()
    {
    }
}
