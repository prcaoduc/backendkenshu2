<?php
require_once('controllers/base_controller.php');
require_once('helpers/authentication.php');
require_once('models/user.php');
require_once('models/article.php');
require_once('helpers/validator.php');
require_once('helpers/session.php');
class ImagesController extends BaseController
{
    function __construct()
    {
        $this->folder = 'images';
    }

    // ユーザーリストを閲覧するためのアクション
    public function create()
    {
        // var_dump($_FILES);
        // imageValidator is using for validate image
        // if (!empty(Validator::imageValidator($_FILES['images']))) $errors_array['images'] = Validator::imageValidator($_FILES['images']);
        $upload_dir = './assets/images/';
        $names = $_FILES['images']['name'];
        $tmp_names = $_FILES['images']['tmp_name'];
        $db = DB::getInstance();
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $db->beginTransaction();
            for ($i = 0; $i < count($tmp_names); $i++) {
                // イメージの拡張を取得する
                $img_ext = strtolower(pathinfo($names[$i], PATHINFO_EXTENSION));
                // イメージの名前を設置しておく
                $target_image = rand(1000, 1000000) . "." . $img_ext;
                // イメージの位置を作成する
                $image_path = $upload_dir . $target_image;
                // サムネイルであるかではないかイメージをDBに保存する。
                Image::create(Authentication::user()->id, $image_path);
                // ファイルを設置した位置に移動する
                move_uploaded_file($tmp_names[$i], $image_path);
            }
            $db->commit();
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
}
