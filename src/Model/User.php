<?php

namespace Model;

use Config\DatabaseConnection;
use Enumeration\UserType;


readonly class User
{

  public function __construct(private DatabaseConnection $connector)
  {
  }


  public function createUser(object $user): array
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
      $file_requirements = explode(';', $file);
      $file_settings["file_name"] = $file_requirements[0];
      $file_settings["tmp_name"] = $file_requirements[1];
      $file_settings["directory"] = $file_requirements[2];
      $file_path = "http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/" . $file_settings["file_name"];
      $statement2 = $dbConnect->prepare("INSERT INTO users (username,profile_image,email,password,type) VALUES(?,?,?,?,?)");
      $values = [
        $username,
        $file_path,
        $email,
        $password,
        UserType::USER->value
      ];
      $statement2->execute($values);
      move_uploaded_file($file_settings["tmp_name"], $file_settings["directory"] . "/" . $file_settings["file_name"]);
      header("HTTP/1.1 302");
      header("Location: ?selection=sign_in");
    }




    return $result;
  }

  public function loginUser(string $email, string $password): ?array
  {

    $dbConnect = $this->connector->connect();
    $statement = $dbConnect->prepare("SELECT username,email,password FROM users WHERE email = :email  ");
    $statement->bindParam(":email", $email);
    $statement->execute();
    $user = $statement->fetch();
    if ($user) {
        if (password_verify($password, $user['password'])) {
            header('HTTP/1.1 302');
            header("Location: ?selection=blog");
            return ["success_login" => 1];
        }
      header('HTTP/1.1 401');
      return ["password_error" => 1];
    }
    header('HTTP/1.1 400');
    return ["email_error" => 1];
  }
}
