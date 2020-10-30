<?php
require_once('controllers/base_controller.php');
// スターティックページクラス
class PagesController extends BaseController
{
  function __construct()
  {
    $this->folder = 'pages';
  }

  public function home()
  {
    $data = array(
      'name' => 'Cao Son Duc',
      'yomikata' => 'カオ・ソン・ドゥック',
      'kanjiname' => '高・山・徳',
      'age' => 23
    );
    $this->render('home', $data);
  }

  public function error()
  {
    $this->render('error');
  }
}