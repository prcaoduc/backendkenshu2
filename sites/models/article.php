<?php
// 記事クラス
class Article
{
  public $title;
  public $content;
  public $id;
  public $created_at;
  public $author_id;

  function __construct($id, $title, $content, $created_at, $author_id)
  {
    $this->id = $id;
    $this->title = $title;
    $this->content = $content;
    $this->created_at = $created_at;
    $this->author_id = $author_id;
  }

  // 全ての記事を取得する
  static function all()
  {
    $list = [];
    $db = DB::getInstance();
    $req = $db->query('SELECT * FROM articles');

    foreach ($req->fetchAll() as $item) {
      $list[] = new Article($item['id'], $item['title'], $item['content'], $item['created_at'], $item['author_id']);
    }

    return $list;
  }

  // IDによる取得する
  static function find($id)
  {
    $db = DB::getInstance();
    $req = $db->prepare('SELECT * FROM articles WHERE id = :id');
    $req->execute(array('id' => $id));

    $item = $req->fetch();
    if (isset($item['id'])) {
      return new Article($item['id'], $item['title'], $item['content'], $item['created_at'], $item['author_id']);
    }
    return null;
  }
}