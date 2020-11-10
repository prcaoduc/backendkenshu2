<?php
// データベース接続クラス
include('config.php');
class DB
{
  private static $instance = NULl;
  public static function getInstance(){
    if (!isset(self::$instance)) {
      try {
        self::$instance = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
        self::$instance->exec("SET NAMES 'utf8'");
      } catch (PDOException $ex) {
        die($ex->getMessage());
      }
    }
    return self::$instance;
  }
}
