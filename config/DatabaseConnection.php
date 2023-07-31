<?php

namespace Config;

use PDO;
use PDOException;


 class DatabaseConnection
{


    public function __construct(
    private readonly string $dbName,
    private readonly string $user,
    private readonly string $password
  ) {
  }




  public function connect() : string|object
  {


    try {
      $connect = new PDO("mysql:host=localhost;dbname=$this->dbName", "$this->user", "$this->password");
    } catch (PDOException $e) {
      return "Database Error :" . $e->getMessage();
    }

    return $connect;
  }
}
