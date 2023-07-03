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



  //TODO Retirer les paramètres de la fonction et mettre les valeurs directement dans les propriétés correspondantes
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
