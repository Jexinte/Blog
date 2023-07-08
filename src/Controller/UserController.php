<?php

namespace Controller;


use Model\User;
use Model\UserModel;
use Enumeration\UserType;
use Exceptions\InvalidFieldException;
use Exceptions\EmptyFieldException;

class UserController
{


  public function __construct(private readonly User $user)
  {
  }


  public function handleUsernameField(string $username): array |string
  {
    try {
      $user_regex =  "/^[A-Z][A-Za-z\d]{2,10}$/";
      if (!empty($username)) {
        switch (true) {
          case preg_match($user_regex, $username):

            return ["username" => $username];
        }
        header("HTTP/1.1 400");
        throw new InvalidFieldException(InvalidFieldException::USERNAME_MESSAGE_ERROR_WRONG_FORMAT);
      }
      header("HTTP/1.1 400");
      throw new EmptyFieldException(EmptyFieldException::USERNAME_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException $e) {
      return $e->getMessage();
    } catch (EmptyFieldException $e) {
      return $e->getMessage();
    }
  }
  public function handleFileField(array $file): array|string
  {
    try {
      if (!empty($file["name"]) && $file["error"] == UPLOAD_ERR_OK) {

        $filename = $file["name"];
        $dir_images = "../public/assets/images/";
        $filename_tmp = $file['tmp_name'];
        $extension_of_the_uploaded_file = explode('.', $filename);
        $authorized_extensions = array("jpg", "jpeg", "png", "webp");

        if (in_array($extension_of_the_uploaded_file[1], $authorized_extensions)) {
          $bytes_to_str = str_replace("/", "", base64_encode(random_bytes(9)));
          $filename_and_extension = explode('.', $filename);
          $filename_generated = $bytes_to_str . "." . $filename_and_extension[1];

          return ["file" => "$filename_generated;$filename_tmp;$dir_images"];
        }
        header("HTTP/1.1 400");
        throw new InvalidFieldException(InvalidFieldException::FILE_MESSAGE_ERROR_TYPE_FILE);
      }
      header("HTTP/1.1 400");
      throw new EmptyFieldException(EmptyFieldException::FILE_MESSAGE_ERROR_NO_FILE_SELECTED);
    } catch (InvalidFieldException $e) {
      return $e->getMessage();
    } catch (EmptyFieldException $e) {
      return $e->getMessage();
    }
  }


  public function handleEmailField(string $email): array|string
  {
    try {
      $email_regex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";
      if (!empty($email)) {
        switch (true) {
          case preg_match($email_regex, $email);

            return ["email" => $email];
        }
        header("HTTP/1.1 400");
        throw new InvalidFieldException(InvalidFieldException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT);
      }
      header("HTTP/1.1 400");
      throw new EmptyFieldException(EmptyFieldException::EMAIL_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException $e) {
      return $e->getMessage();
    } catch (EmptyFieldException $e) {
      return $e->getMessage();
    }
  }
  public function handlePasswordField(string $password): array|string
  {
    try {
      $password_regex = "/^(?=.*[A-Z])(?=.*\d).{8,}$/";
      if (!empty($password)) {
        switch (true) {
          case preg_match($password_regex, $password):
            $hash_password = password_hash($password, PASSWORD_DEFAULT);

            return ["password" => $hash_password];
        }
        header("HTTP/1.1 400");
        throw new InvalidFieldException(InvalidFieldException::PASSWORD_MESSAGE_ERROR_WRONG_FORMAT);
      }
      header("HTTP/1.1 400");
      throw new EmptyFieldException(EmptyFieldException::PASSWORD_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException $e) {
      return $e->getMessage();
    } catch (EmptyFieldException $e) {
      return $e->getMessage();
    }
  }


  public function signUpValidator(string $username, array $file, string $email, string $password): ?array
  {

    $username_result = $this->handleUsernameField($username);
    $email_result = $this->handleEmailField($email);
    $password_result = $this->handlePasswordField($password);
    $file_result = $this->handleFileField($file);
    $counter = 0;

    $fields = [
      "username" => $username_result,
      "email" => $email_result,
      "password" => $password_result,
      "file" => $file_result
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
      $file_settings = $fields["file"];
      $email = $fields["email"];
      $password = $fields["password"];
      $user_type = UserType::USER;
      $user_data = new UserModel($username["username"], $file_settings["file"], $email["email"], $password["password"], $user_type);

      $user_db = $userRepository->createUser($user_data);
      
      switch(true){
        case  $user_db["username"] === $username["username"] && $user_db["email"] === $email["email"]:
          return ["username_error" => "Le nom d'utilisateur ".$username["username"]." n'est pas disponible !", "email_error" => "L'adresse email ".$email["email"]." n'est pas disponible !"];

        case $user_db["username"] === $username["username"] :
          return ["username_error" => "Le nom d'utilisateur ".$username["username"]." n'est pas disponible !"];
        
        case $user_db["email"] === $email["username"]:
          return ["email_error" => "L'adresse email ".$email["email"]." n'est pas disponible !"];
      }

    }
    return !empty($errors) ? $errors : null;
  }


  public function verifyEmailOnLogin(string $email): array|string
  {
    try {

      $email_regex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";

      if (!empty($email)) {
        switch (true) {
          case preg_match($email_regex, $email):
            return ["email" => $email];
        }
        header("HTTP/1.1 400");
        throw new InvalidFieldException(InvalidFieldException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT);
      }
      header("HTTP/1.1 400");
      throw new EmptyFieldException(EmptyFieldException::EMAIL_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException $e) {
      return $e->getMessage();
    } catch (EmptyFieldException $e) {
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
    $email_result = $this->verifyEmailOnLogin($email);
    $password_result = $this->verifyPasswordOnLogin($password);
    $counter = 0;

    $fields = [
      "email" => $email_result,
      "password" => $password_result
    ];

    $errors = [];

    foreach($fields as $key => $v){
      if(gettype($v) === "string") $errors[$key."_error"] = $v;
    }

    foreach($fields as $v){
      if(is_array($v)) $counter++;
    }
    if ($counter == 2) {
      $email_field = $fields["email"]["email"];
      $password_field = $fields["password"]["password"];
      $login = $userRepository->loginUser($email_field,$password_field);
      $login_result = match (true) {
        array_key_exists("password_error", $login) => ["password_error" => "Le mot de passe est incorrect !"],
        array_key_exists("email_error", $login) => ["email_error" => "Oups ! Nous n'avons trouvé aucun compte associé à cette adresse e-mail. Assurez-vous que vous avez saisi correctement votre adresse e-mail et réessayez"],
      };
      return $login_result;
    }
    return !empty($errors) ? $errors:null;
  }
}
