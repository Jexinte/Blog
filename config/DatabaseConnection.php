<?php

namespace Config;

use PDOException;


class DatabaseConnection
{
  private $connect;


  public function __construct(
    private string $db_name,
    private string $user,
    private string $password
  ) {
  }




  public function connect()
  {


    try {
      $this->connect = new \PDO("mysql:host=localhost;dbname={$this->db_name}", "{$this->user}", "{$this->password}");
    } catch (PDOException $e) {
      "Database Error :" . $e->getMessage();
    }

    return $this->connect;
  }
}
