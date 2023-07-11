<?php

namespace Controller;


use Model\User;
use Model\UserModel;
use Enumeration\UserType;
use Exceptions\InvalidFieldException;
use Exceptions\EmptyFieldException;


 readonly class UserController
{


  public function __construct(private User $user)
  {
  }


  public function handleUsernameField(string $username): array |string
  {
    try {
      $userRegex =  "/^[A-Z][A-Za-z\d]{2,10}$/";
      if (!empty($username)) {
          if (preg_match($userRegex, $username)) {
              return ["username" => $username];
          }
        header("HTTP/1.1 400");
        throw new InvalidFieldException(InvalidFieldException::USERNAME_MESSAGE_ERROR_WRONG_FORMAT);
      }
      header("HTTP/1.1 400");
      throw new EmptyFieldException(EmptyFieldException::USERNAME_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException|EmptyFieldException $e) {
      return $e->getMessage();
    }
  }
  public function handleFileField(array $file): array|string
  {
    try {
      if (!empty($file["name"]) && $file["error"] == UPLOAD_ERR_OK) {

        $filename = $file["name"];
        $dirImages = "../public/assets/images/";
        $filenameTmp = $file['tmp_name'];
        $extensionOfTheUploaded_file = explode('.', $filename);
        $authorizedExtensions = array("jpg", "jpeg", "png", "webp");

        if (in_array($extensionOfTheUploaded_file[1], $authorizedExtensions)) {
          $bytesToStr = str_replace("/", "", base64_encode(random_bytes(9)));
          $filenameAndExtension = explode('.', $filename);
          $filenameGenerated = $bytesToStr . "." . $filenameAndExtension[1];

          return ["file" => "$filenameGenerated;$filenameTmp;$dirImages"];
        }
        header("HTTP/1.1 400");
        throw new InvalidFieldException(InvalidFieldException::FILE_MESSAGE_ERROR_TYPE_FILE);
      }
      header("HTTP/1.1 400");
      throw new EmptyFieldException(EmptyFieldException::FILE_MESSAGE_ERROR_NO_FILE_SELECTED);
    } catch (InvalidFieldException|EmptyFieldException $e) {
      return $e->getMessage();
    }
  }


  public function handleEmailField(string $email): array|string
  {
    try {
      $emailRegex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";
      if (!empty($email)) {
          if (preg_match($emailRegex, $email)) {
              return ["email" => $email];
          }
        header("HTTP/1.1 400");
        throw new InvalidFieldException(InvalidFieldException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT);
      }
      header("HTTP/1.1 400");
      throw new EmptyFieldException(EmptyFieldException::EMAIL_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException|EmptyFieldException $e) {
      return $e->getMessage();
    }
  }
  public function handlePasswordField(string $password): array|string
  {
    try {
      $passwordRegex = "/^(?=.*[A-Z])(?=.*\d).{8,}$/";
      if (!empty($password)) {
          if (preg_match($passwordRegex, $password)) {
              $hashPassword = password_hash($password, PASSWORD_DEFAULT);

              return ["password" => $hashPassword];
          }
        header("HTTP/1.1 400");
        throw new InvalidFieldException(InvalidFieldException::PASSWORD_MESSAGE_ERROR_WRONG_FORMAT);
      }
      header("HTTP/1.1 400");
      throw new EmptyFieldException(EmptyFieldException::PASSWORD_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException|EmptyFieldException $e) {
      return $e->getMessage();
    }
  }


  public function signUpValidator(string $username, array $file, string $email, string $password): ?array
  {

    $usernameResult = $this->handleUsernameField($username);
    $emailResult = $this->handleEmailField($email);
    $passwordResult = $this->handlePasswordField($password);
    $fileResult = $this->handleFileField($file);
    $counter = 0;

    $fields = [
      "username" => $usernameResult,
      "email" => $emailResult,
      "password" => $passwordResult,
      "file" => $fileResult
    ];
    $errors = [];

    foreach ($fields as $key => $v) {
      if (gettype($v) === "string") $errors[$key . "_error"] = $v;
    }


    foreach ($fields as $v) {
      if (is_array($v)) $counter++;
    }
    if ($counter == 4) {
      $userRepository = $this->user;
      $username = $fields["username"];
      $fileSettings = $fields["file"];
      $email = $fields["email"];
      $password = $fields["password"];
      $userType = UserType::USER;
      $userData = new UserModel($username["username"], $fileSettings["file"], $email["email"], $password["password"], $userType);

      $userDb = $userRepository->createUser($userData);

      switch(true){
        case  $userDb["username"] === $username["username"] && $userDb["email"] === $email["email"]:
          return ["username_error" => "Le nom d'utilisateur ".$username["username"]." n'est pas disponible !", "email_error" => "L'adresse email ".$email["email"]." n'est pas disponible !"];

        case $userDb["username"] === $username["username"] :
          return ["username_error" => "Le nom d'utilisateur ".$username["username"]." n'est pas disponible !"];
        
        case $userDb["email"] === $email["email"]:
          return ["email_error" => "L'adresse email ".$email["email"]." n'est pas disponible !"];
      }

    }
    return !empty($errors) ? $errors : null;
  }


  public function verifyEmailOnLogin(string $email): array|string
  {
    try {

      $emailRegex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";

      if (!empty($email)) {
          if (preg_match($emailRegex, $email)) {
              return ["email" => $email];
          }
        header("HTTP/1.1 400");
        throw new InvalidFieldException(InvalidFieldException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT);
      }
      header("HTTP/1.1 400");
      throw new EmptyFieldException(EmptyFieldException::EMAIL_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException|EmptyFieldException $e) {
      return $e->getMessage();
    }
  }

  public function verifyPasswordOnLogin(string $password): array|string
  {

    try {

      if (!empty($password)) {
        return ["password" => $password];
      }
      header("HTTP/1.1 400");
      throw new EmptyFieldException(EmptyFieldException::PASSWORD_MESSAGE_ERROR_EMPTY);
    } catch (EmptyFieldException $e) {
      return $e->getMessage();
    }
  }


  public function loginValidator(string $email,string $password): ?array
  {
    $userRepository = $this->user;
    $emailResult = $this->verifyEmailOnLogin($email);
    $passwordResult = $this->verifyPasswordOnLogin($password);
    $counter = 0;

    $fields = [
      "email" => $emailResult,
      "password" => $passwordResult
    ];

    $errors = [];

    foreach($fields as $key => $v){
      if(gettype($v) === "string") $errors[$key."_error"] = $v;
    }

    foreach($fields as $v){
      if(is_array($v)) $counter++;
    }
    if ($counter == 2) {
      $emailField = $fields["email"]["email"];
      $passwordField = $fields["password"]["password"];
      $login = $userRepository->loginUser($emailField,$passwordField);
      return match (true) {
        array_key_exists("password_error", $login) => ["password_error" => "Le mot de passe est incorrect !"],
        array_key_exists("email_error", $login) => ["email_error" => "Oups ! Nous n'avons trouvé aucun compte associé à cette adresse e-mail. Assurez-vous que vous avez saisi correctement votre adresse e-mail et réessayez"],
      };

    }
    return !empty($errors) ? $errors:null;
  }
}
