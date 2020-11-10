<?php
require_once('models/image.php');
require_once('models/user.php');
//  articlesテーブルレコードをマッピングするためのモデルクラス
class Article
{
  public $id;
  public $title;
  public $content;
  public $created_at;
  public $author_id;

  // 全ての記事を取得する
  static function all()
  {
    $list = [];
    $db = DB::getInstance();
    $req = $db->query('SELECT * FROM articles WHERE published = 1');

    foreach ($req->fetchAll(\PDO::FETCH_CLASS, 'Article') as $item) {
      $list[] = $item;
    }

    return $list;
  }

  // IDによる取得する
  static function find($id)
  {
    $db = DB::getInstance();
    $req = $db->prepare('SELECT * FROM articles WHERE id = :id');
    $req->execute(array('id' => $id));
    $req->setFetchMode(PDO::FETCH_CLASS, 'Article');
    $item = $req->fetch();
    if (isset($item)) {
      return $item;
    }
    return null;
  }

  // 新しい記事をDBに保存する
  static function create($title, $content, $tag, $author_id)
  {
    $input = [
      'title'       => $title,
      'content'     => $content,
      'author_id'   => $author_id,
      'tag'         => $tag
    ];
    $data = [];
    $db = DB::getInstance();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // insert into articles table
    $sql1 = "INSERT INTO articles (title, content, author_id) VALUES (?, ?, ?)";
    $req1 = $db->prepare($sql1)->execute(array(
      $input['title'],
      $input['content'],
      $input['author_id']
    ));
    if ($req1 > 0) $data['article_id'] = $db->lastInsertId();

    // check already had tags if not insert
    // タグが存在の確認
    $sql2 = "SELECT * FROM tags WHERE name = ?";
    $req2 = $db->prepare($sql2);
    $req2->execute(array($input['tag']));
    $tag = $req2->fetch(\PDO::FETCH_OBJ);
    if (isset($tag->name)) $data['tag_id'] = $tag->id;
    else {
      // 存在しなければ、保存する
      $sql2 = "INSERT INTO tags (name) VALUES (?)";
      $req = $db->prepare($sql2)->execute(array($input['tag']));
      if ($req > 0) $data['tag_id'] = $db->lastInsertId();
    }

    // insert into pivot table tag's id and article's id from above
    // ピボットテーブルに記事とタグのリレーションの情報を保存する
    $article_id = $data['article_id'];
    $tag_id = $data['tag_id'];

    if (!empty($article_id) && !empty($tag_id)) {
      $sql3 = "INSERT INTO article_tags VALUES ( $article_id , $tag_id )";
      $req = $db->prepare($sql3)->execute();

      $result = $req > 0 ? $data['article_id'] : null;
    }
    return $result;
  }

  // 作者のIDに基づいて記事を取得する
  static function getbyAuthor($user_id){
    $db     = DB::getInstance();
    $list   = [];
    $query  = 'SELECT * FROM articles WHERE author_id = ?';
    $req    = $db->prepare($query);
    $req->execute([$user_id]);
    foreach ($req->fetchAll(\PDO::FETCH_CLASS, 'Article') as $record) {
      $list[] = $record;
    }
    return (!empty($list)) ? $list : null;
  }

  // この記事インタンスのイメージを取得する
  public function images()
  {
    $db = DB::getInstance();
    $list = [];
    $query = 'SELECT * FROM images WHERE article_id =  ?';
    $req = $db->prepare($query);
    $req->execute(array($this->id));
    foreach ($req->fetchAll(\PDO::FETCH_CLASS, 'Image') as $record) {
      $list[] = $record;
    }
    return (!empty($list)) ? $list : null;
  }

  // この記事インタンスのサムネイルを取得する
  public function getThumbnail()
  {
    $list = $this->images();
    if (!empty($list)) {
      foreach ($list as $image) {
        if ($image->isthumbnail) return $image;
      }
    } else return null;
  }

  // この記事インタンスのタグを取得する
  public function tags()
  {
    $db = DB::getInstance();
    $list = [];
    $query = 'SELECT * FROM tags INNER JOIN article_tags ON tags.id = article_tags.tag_id ' .
      'INNER JOIN articles on articles.id = article_tags.article_id WHERE article_id = ?';
    $req = $db->prepare($query);
    $req->execute(array($this->id));
    foreach ($req->fetchAll(\PDO::FETCH_OBJ) as $record) {
      $list[] = $record;
    }
    return (!empty($list)) ? $list : null;
  }

  //not done yet
  // Laravelのようなsave()をquery string を実施するため
  // まだ完全しません。model.phpの機能です。
  public function save(){
    $data = get_object_vars($this);
    var_dump($data);
  }

  // 記事を編集した情報をDBに保存する
  public function update($input){
    $db = DB::getInstance();
    $input['id'] = $this->id;
    $query = 'UPDATE articles SET title = :title, content = :content WHERE id = :id';
    $req = $db->prepare($query);
    $req->execute($input);
    return ($req > 0) ? $this : false;
  }

  // 記事のソフト削除
  public function revoke(){
    $db = DB::getInstance();
    $query = 'UPDATE articles SET published = 0 WHERE id = ?';
    $req = $db->prepare($query);
    $req->execute(array($this->id));
    return ($req > 0) ? true : false;
  }

  // この記事インタンスの作者を取得する
  public function author(){
    $db = DB::getInstance();
    $query = 'SELECT nickname FROM users INNER JOIN articles ON users.id = articles.author_id WHERE author_id =  ?';
    $req = $db->prepare($query);
    $req->execute(array($this->author_id));
    $req->setFetchMode(PDO::FETCH_CLASS, 'User');
    $item = $req->fetch();
    return (!empty($req)) ? $item : null;
  }
}
