<?php

namespace Model;

use Config\DatabaseConnection;
use Enumeration\UserType;


class User
{

  public function __construct(private DatabaseConnection $connector)
  {
  }


  public function createUser(object $user): ?array
  {


    $dbConnect = $this->connector->connect();
    $username = $user->getData()["username"];
    $file = $user->getData()["profileImage"];
    $email = $user->getData()["email"];
    $password = $user->getData()["password"];

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
    } else {
      switch (true) {
        case $result["username"] == $username && $result["email"] == $email:
          header('HTTP/1.1 400');
          return ["username_taken" => 1, "email_taken" => 1];
        case $result["username"] == $username:
          header("HTTP/1.1 400");
          return ["username_taken" => 1];

        case $result["email"] == $email:
          header("HTTP/1.1 400");
          return ["email_taken" => 1];
      }
    }

    return null;
  }

  public function loginUser(array $logs): ?array
  {

    $dbConnect = $this->connector->connect();
    $statement = $dbConnect->prepare("SELECT username,email,password FROM users WHERE email = :email  ");

    $statement->bindParam(":email", $logs["email"]);
    $statement->execute();
    $user = $statement->fetch();
    if ($user) {
      switch (true) {
        case password_verify($logs["password"], $user['password']):
          header('HTTP/1.1 200');
          header("Location: ?selection=blog");
          return ["success_login" => 1];

        default:
          header('HTTP/1.1 401');
          return ["password_failed" => 1];
      }
    } else {
      header('HTTP/1.1 400');
      return ["email_unexist" => 1];
    }
  }
}
