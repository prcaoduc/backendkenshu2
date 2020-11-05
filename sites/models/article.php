<?php
// 記事クラス
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
    $req = $db->query('SELECT * FROM articles');

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

  static function create($title, $content, $tag, $author_id)
  {
    $input = [
      'title'       => $title,
      'content'     => $content,
      'author_id'   => $author_id,
      'tag'         => $tag
    ];
    $db = DB::getInstance();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try {
      $db->beginTransaction();
      // insert into articles table
      $sql1 = "INSERT INTO articles (title, content, author_id) VALUES (?, ?, ?)";
      $req1 = $db->prepare($sql1)->execute(array(
        $input['title'], 
        $input['content'], 
        $input['author_id']));
      if($req1 > 0) $data['article_id'] = $db->lastInsertId();

      // check already had tags if not insert
      $sql2 = "SELECT * FROM tags WHERE name = ?";
      $req2 = $db->prepare($sql2);
      $req2->execute(array($input['tag']));
      $tag = $req2->fetch(\PDO::FETCH_OBJ);
      if (isset($tag->name)) $data['tag_id'] = $tag->id;
      else {
        $sql2 = "INSERT INTO tags (name) VALUES (?)";
        $req = $db->prepare($sql2)->execute(array($input['tag']));
        if ($req > 0) $data['tag'] = $db->lastInsertId();
      }

      // insert into pivot table tag's id and article's id from above
      $article_id = $data['article_id'];
      $tag_id = $data['tag'];
      
      if(!empty($article_id) && !empty($tag_id)){
        $sql3 = "INSERT INTO article_tags VALUES ( $article_id , $tag_id )";
        $req = $db->prepare($sql3)->execute();
        
        $result = $req > 0 ? $data['article_id'] : null;
        $db->commit();
        
      }
    } catch (PDOException $e) {
      $db->rollback();
      throw $e;
    }
    return $result;
  }

  public function tags()
  {
    $db = DB::getInstance();
    $query = 'SELECT * FROM tags INNER JOIN article_tags on tags.id = article_tags.tag_id ' .
      'INNER JOIN articles on articles.id = article_tags.article_id';
    $req = $db->prepare($query);
    $req->execute();
    $req->setFetchMode(PDO::FETCH_CLASS, 'User');
    $record = $req->fetch();
    return (!empty($record)) ? $record : null;
  }

  //   public function author()
  //   {
  // //     select m.name
  // // from movies m
  // // inner join actors_movies am on m.id = am.movie_id
  // // inner join actors a on am.actor_id = a.id
  // // where a.name = 'Christopher Walken'
  //     $query = 'SELECT * FROM users INNER JOIN article_tags '
  //   }
}
