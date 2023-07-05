<?php

namespace Controller;


use Model\User;
use Model\UserModel;
use Enumeration\UserType;

class UserController
{

  public function __construct(private readonly User $user)
  {
  }

  public function signUpHandler($username, $file, $email, $password): array
  {

    $userRepository = $this->user;
    $user_regex =  "/^[A-Z][A-Za-z\d]{2,10}$/";
    $email_regex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";
    $password_regex = "/^(?=.*[A-Z])(?=.*\d).{8,}$/";
    $values = [];
    $errors = [
      "username_error_empty" => "",
      "username_error_wrong_format" => "",
      "username_unavailable" => "",

      "password_error_empty" => "",
      "password_error_wrong_format" => "",

      "email_error_empty" => "",
      "email_error_wrong_format" => "",
      "email_unavailable" => "",

      "file_error_empty" => '',
      "file_error_type_file" => "",
    ];

    if (!empty($username)) {
      switch (true) {
        case preg_match($user_regex, $username) == 1:
          $values["username"] = $username;
          break;
        case !preg_match($user_regex, $username):
          header("HTTP/1.1 400");
          $errors["username_error_wrong_format"] = "Oops ! Merci de suivre le format ci-dessous pour votre nom d'utilisateur !";
          break;
      }
    } else {
      header("HTTP/1.1 400");
      $errors["username_error_empty"] = "Ce champ ne peut être vide !";
    }

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
            $values["file_settings"] = "$filename_generated;$filename_tmp;$dir_images";
          } else {
            header("HTTP/1.1 400");
            $errors["file_error_type_file"] = "Seuls les fichiers de type : jpg, jpeg , png et webp sont acceptés !";
          }
          break;
      }
    } else {
      header("HTTP/1.1 400");
      $errors["file_error_empty"] = "Ce champ ne peut être vide !";
    }


    if (!empty($email)) {
      switch (true) {
        case preg_match($email_regex, $email) == 1;
          $values["email"] = $email;
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
      switch (true) {
        case preg_match($password_regex, $password) == 1:
          $hash_password = password_hash($password, PASSWORD_DEFAULT);
          $values["password"] = $hash_password;
          break;
        case !preg_match($password_regex, $password):
          header("HTTP/1.1 400");
          $errors["password_error_wrong_format"] = "Oops ! Le format de votre mot de passe est incorrect. Merci de suivre le format ci-dessous";
          break;
      }
    } else {
      header("HTTP/1.1 400");
      $errors["password_error_empty"] = "Ce champ ne peut être vide !";
    }
    if (count($values) == 4) {

      $user_data = new UserModel($values["username"], $values["file_settings"], $values["email"], $values["password"], UserType::USER);
      $userRepository->createUser($user_data);

      switch (true) {
        case count($userRepository->createUser($user_data)) == 2:
          $errors["username_unavailable"] = $userRepository->createUser($user_data)["username_failed"];
          $errors["email_unavailable"] = $userRepository->createUser($user_data)["email_failed"];
          break;
        case count($userRepository->createUser($user_data)) == 1 && array_key_exists("username_failed", $userRepository->createUser($user_data)):

          $errors["username_unavailable"] = $userRepository->createUser($user_data)["username_failed"];
          break;
        case count($userRepository->createUser($user_data)) == 1 && array_key_exists("email_failed", $userRepository->createUser($user_data)):
          $errors["email_unavailable"] = $userRepository->createUser($user_data)["email_failed"];
          break;
      }
    }

    return  $errors;
  }

  public function loginHandler($email, $password): array
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
        case array_key_exists("success_login", $userRepository->loginUser($logs)):
          return $userRepository->loginUser($logs);

        case array_key_exists("password_failed", $userRepository->loginUser($logs)):
          $errors["password_error_incorrect"] = $userRepository->loginUser($logs)["password_failed"];
          break;

        case array_key_exists("email_failed", $userRepository->loginUser($logs)):
          $errors["email_unexist"] = $userRepository->loginUser($logs)["email_failed"];
          break;
      }
    }
    return $errors;
  }
}
