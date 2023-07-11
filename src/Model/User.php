<?php

namespace Model;

use Config\DatabaseConnection;
use Enumeration\UserType;


readonly class User
{

  public function __construct(private DatabaseConnection $connector)
  {
  }


  public function createUser(object $user): ?array
  {

    $dbConnect = $this->connector->connect();

    $userData = $user->getData();
    $username = $userData["username"];
    $file = $userData["profileImage"];
    $email = $userData["email"];
    $password = $userData["password"];

    $statement = $dbConnect->prepare('SELECT username,email FROM users WHERE username = :username OR  email = :email');
    $statement->bindParam("username", $username);
    $statement->bindParam("email", $email);
    $statement->execute();
    $result = $statement->fetch();


    if (!$result) {
      $fileRequirements = explode(';', $file);
      $fileSettings["file_name"] = $fileRequirements[0];
      $fileSettings["tmp_name"] = $fileRequirements[1];
      $fileSettings["directory"] = $fileRequirements[2];
      $filePath = "http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/" . $fileSettings["file_name"];
      $statement2 = $dbConnect->prepare("INSERT INTO users (username,profile_image,email,password,type) VALUES(?,?,?,?,?)");
      $values = [
        $username,
        $filePath,
        $email,
        $password,
        UserType::USER->value
      ];
      $statement2->execute($values);
      move_uploaded_file($fileSettings["tmp_name"], $fileSettings["directory"] . "/" . $fileSettings["file_name"]);
      header("HTTP/1.1 302");
      header("Location: ?selection=sign_in");
    }

    return !empty($result) ? $result: null ;
  }

  public function loginUser(string $email, string $password): ?array
  {

    $dbConnect = $this->connector->connect();
    $statement = $dbConnect->prepare("SELECT username,email,password FROM users WHERE email = :email  ");
    $statement->bindParam(":email", $email);
    $statement->execute();
    $user = $statement->fetch();
    if ($user) {
      $check_password = password_verify($password, $user['password']);
        if (!$check_password) {
          header('HTTP/1.1 401');
          return ["password_error" => 1];
        }
        header("Location: index.php?selection=blog",true,302);
    }
    else{
      header('HTTP/1.1 400');
      return ["email_error" => 1];
    }
    
  }
}
