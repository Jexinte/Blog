<?php

namespace Controller;


use Model\User;
use Model\UserModel;
use Enumeration\UserType;
use Exceptions\UserException;

class UserController
{

  public array $data_form = [];
  public function __construct(private readonly User $user)
  {
  }

  // public function validator(){  
  // }



  public function handleUsernameField(string $username): ?string
  {
    try {
      $user_regex =  "/^[A-Z][A-Za-z\d]{2,10}$/";
      if (!empty($username)) {
        switch (true) {
          case preg_match($user_regex, $username) == 1:
            $this->data_form["username"] = $username;
            return null;

          case !preg_match($user_regex, $username):
            header("HTTP/1.1 400");
            throw new UserException(UserException::USERNAME_MESSAGE_ERROR_WRONG_FORMAT);
        }
      } else {
        header("HTTP/1.1 400");
        throw new UserException(UserException::USERNAME_MESSAGE_ERROR_EMPTY);
      }
    } catch (UserException $e) {
      return $e->getMessage();
    }
  }
  public function handleFileField(array $file): ?string
  {
    try {
      if (!empty($file["name"])) {
        switch (true) {
          case $file["error"] == UPLOAD_ERR_OK:
            $filename = $file["name"];
            $dir_images = "../public/assets/images/";
            $filename_tmp = $file['tmp_name'];
            $extension_of_the_uploaded_file = explode('.', $filename);
            $authorized_extensions = array("jpg", "jpeg", "png", "webp");

            if (in_array($extension_of_the_uploaded_file[1], $authorized_extensions)) {
              $bytes_to_str = str_replace("/", "", base64_encode(random_bytes(9)));
              $filename_and_extension = explode('.', $filename);
              $filename_generated = $bytes_to_str . "." . $filename_and_extension[1];
              $this->data_form["file_settings"] = "$filename_generated;$filename_tmp;$dir_images";
            } else {
              header("HTTP/1.1 400");
              throw new UserException(UserException::FILE_MESSAGE_ERROR_TYPE_FILE);
            }

            return null;
        }
      } else {
        header("HTTP/1.1 400");
        throw new UserException(UserException::FILE_MESSAGE_ERROR_NO_FILE_SELECTED);
      }
    } catch (UserException $e) {
      return $e->getMessage();
    }
  }


  public function handleEmailField(string $email): ?string
  {
    try {


      $email_regex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";
      if (!empty($email)) {
        switch (true) {
          case preg_match($email_regex, $email) == 1;
            $this->data_form["email"] = $email;
            return null;

          case !preg_match($email_regex, $email):
            header("HTTP/1.1 400");
            throw new UserException(UserException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT);
        }
      } else {
        header("HTTP/1.1 400");
        throw new UserException(UserException::EMAIL_MESSAGE_ERROR_EMPTY);
      }
    } catch (UserException $e) {
      return $e->getMessage();
    }
  }
  public function handlePasswordField(string $password): ?string
  {
    try {
      $password_regex = "/^(?=.*[A-Z])(?=.*\d).{8,}$/";
      if (!empty($password)) {
        switch (true) {
          case preg_match($password_regex, $password) == 1:
            $hash_password = password_hash($password, PASSWORD_DEFAULT);
            $this->data_form["password"] = $hash_password;
            return null;
          case !preg_match($password_regex, $password):
            header("HTTP/1.1 400");
            throw new UserException(UserException::PASSWORD_MESSAGE_ERROR_WRONG_FORMAT);
        }
      } else {
        header("HTTP/1.1 400");
        throw new UserException(UserException::PASSWORD_MESSAGE_ERROR_EMPTY);
      }
    } catch (UserException $e) {
      return $e->getMessage();
    }
  }


  public function signUpHandler(): ?array
  {

    $userRepository = $this->user;
    $errors = [
      "username_taken" => "",
      "email_taken" => ""
    ];
    if (count($this->data_form) == 4) {

      $user_data = new UserModel($this->data_form["username"], $this->data_form["file_settings"], $this->data_form["email"], $this->data_form["password"], UserType::USER);

      switch (true) {
        case count($userRepository->createUser($user_data)) == 2 && array_key_exists("username_taken", $userRepository->createUser($user_data)) && $userRepository->createUser($user_data)["username_taken"] == 1 && array_key_exists("email_taken", $userRepository->createUser($user_data)) && $userRepository->createUser($user_data)["email_taken"] == 1:
          $errors["username_taken"] = "Le nom d'utilisateur " . $this->data_form["username"] . " est déjà pris ! ";
          $errors["email_taken"] = "L'adresse email " . $this->data_form["email"] . " est déjà pris ! ";
          break;

        case count($userRepository->createUser($user_data)) == 1 && array_key_exists("username_taken", $userRepository->createUser($user_data)) && $userRepository->createUser($user_data)["username_taken"] == 1:
          $errors["username_taken"] = "Le nom d'utilisateur " . $this->data_form["username"] . " est déjà pris ! ";
          break;

        case count($userRepository->createUser($user_data)) == 1 && array_key_exists("email_taken", $userRepository->createUser($user_data)) && $userRepository->createUser($user_data)["email_taken"] == 1:
          $errors["email_taken"] = "L'adresse email " . $this->data_form["email"] . " est déjà pris ! ";
          break;
      }
    }

    return $errors;
  }



  public function loginHandler(string $email, string $password): array
  {
    $userRepository = $this->user;
    $email_regex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";
    $logs = [];
    $errors = [
      "password_error_empty" => "",
      "password_error_incorrect" => "",

      "email_error_empty" => "",
      "email_error_wrong_format" => "",
      "email_unavailable" => "",
      "email_unexist" => ""
    ];

    if (!empty($email)) {
      switch (true) {
        case preg_match($email_regex, $email) == 1:
          $logs["email"] = $email;
          break;
        case !preg_match($email_regex, $email):
          header("HTTP/1.1 400");
          $errors["email_error_wrong_format"] = "Oops ! Le format de votre saisie est incorrect. Merci de suivre le format requis : nomadressemail@domaine.extension";
          break;
      }
    } else {
      header("HTTP/1.1 400");
      $errors["email_error_empty"] = "Ce champ ne peut être vide !";
    }

    if (!empty($password)) {
      $logs["password"] = $password;
    } else {
      header("HTTP/1.1 400");
      $errors["password_error_empty"] = "Ce champ ne peut être vide !";
    }


    if (count($logs) == 2) {
      $userRepository->loginUser($logs);
      switch (true) {
        case array_key_exists("success_login", $userRepository->loginUser($logs)) && $userRepository->loginUser($logs)["success_login"] == 1:
          $userRepository->loginUser($logs);
          break;
        case array_key_exists("password_failed", $userRepository->loginUser($logs)) && $userRepository->loginUser($logs)["password_failed"] == 1:
          $errors["password_error_incorrect"] = $userRepository->loginUser($logs)["password_failed"] = "Oups ! Le mot de passe saisi est incorrect !";
          break;

        case array_key_exists("email_unexist", $userRepository->loginUser($logs)) && $userRepository->loginUser($logs)["email_unexist"] == 1:
          $errors["email_unexist"] = $userRepository->loginUser($logs)["email_failed"] = "Oups ! Nous n'avons trouvé aucun compte associé à cette adresse e-mail. Assurez-vous que vous avez saisi correctement votre adresse e-mail et réessayez";
          break;
      }
    }
    return $errors;
  }
}
