<?php

namespace Config;

use PDOException;

require_once "../vendor/autoload.php";



class DatabaseConnection
{

  private $connect;
  private static $_instance = null;
  private string $db_name;
  private string $user;
  private string $password;

  private function __construct()
  {
  }

  public static function getInstance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }

  public function connect($db_name, $user, $password)
  {

    $this->db_name = $db_name;
    $this->user = $user;
    $this->password = $password;

    try {
      $this->connect = new \PDO("mysql:host=localhost;dbname={$this->db_name}", "{$this->user}", "{$this->password}");
    } catch (PDOException $e) {
      "Database Error :" . $e->getMessage();
    }

    return $this->connect;
  }
}
