<?php

namespace Model;

use Config\DatabaseConnection;
use Model\UserModel;
use Enumeration\UserType;
use Exception;

class UserException extends Exception
{
  const USERNAME_MESSAGE_ERROR_EMPTY = "Ce champ ne peut-être vide !";
  const USERNAME_MESSAGE_ERROR_WRONG_FORMAT = "Oops ! Merci de suivre le format ci-dessous pour votre nom d'utilisateur !";
  const USERNAME_MESSAGE_ERROR_UNAVAILABLE = "Ce nom d'utilisateur est déja pris !";

  const FILE_MESSAGE_ERROR_TYPE_FILE = "Seuls les fichiers de type : jpg, jpeg , png et webp sont acceptés !";
  const FILE_MESSAGE_ERROR_NO_FILE_SELECTED = "Veuillez sélectionner un fichier !";

  const EMAIL_MESSAGE_ERROR_ALREADY_EXIST = "L'adresse email suivante ";
  const EMAIL_MESSAGE_ERROR_EMPTY = "Ce champ ne peut-être vide !";
  const EMAIL_MESSAGE_ERROR_WRONG_FORMAT = "Oops ! Le format de votre saisie est incorrect. Merci de suivre le format requis : nomadressemail@domaine.extension";
  const EMAIL_MESSAGE_ERROR_DOESNT_EXIST = "Oups ! Nous n'avons trouvé aucun compte associé à cette adresse e-mail. Assurez-vous que vous avez saisi correctement votre adresse e-mail et réessayez";

  const PASSWORD_MESSAGE_ERROR_EMPTY = "Ce champ ne peut être vide !";
  const PASSWORD_MESSAGE_ERROR_WRONG_FORMAT = "Oops ! Le format de votre mot de passe est incorrect. Merci de suivre le format ci-dessous";
  const PASSWORD_MESSAGE_ERROR_INCORRECT = "Oups ! Le mot de passe saisi est incorrect !";
}

class User extends UserModel
{

  public function __construct(private DatabaseConnection $connector)
  {
  }

  public function checkUsernameInput(): ?string
  {
    $dbConnect = $this->connector->connect();
    $user_regex =  "/^[A-Z][A-Za-z\d]{2,10}$/";

    if (isset($_POST['submit'])) {
      $username = $_POST['username'];
      if (!empty($username)) {
        if (preg_match($user_regex, $username)) {
          $statement = $dbConnect->prepare("SELECT username FROM users WHERE username = :username");
          $statement->bindValue(":username", $username);
          $statement->execute();
          $result = $statement->fetch();
          try {
            if (!$result) return $username;
            else {
              throw new UserException(UserException::USERNAME_MESSAGE_ERROR_UNAVAILABLE);
            }
          } catch (UserException $e) {
            header('HTTP/1.1 400');
            throw $e;
          }
        } else {
          try {
            throw new UserException(UserException::USERNAME_MESSAGE_ERROR_WRONG_FORMAT);
          } catch (UserException $e) {
            header('HTTP/1.1 400');
            throw $e;
          }
        }
      } else {

        try {
          throw new UserException(UserException::USERNAME_MESSAGE_ERROR_EMPTY);
        } catch (UserException $e) {
          header('HTTP/1.1 400');
          throw $e;
        }
      }
      return null;
    }
  }
  public function checkFileInput(): ?array
  {

    if (isset($_POST['submit'])) {
      if (!empty($_FILES['profile_image']["name"])) {
        $dir_images = "../src/admin/assets/images";
        $filename = $_FILES['profile_image']['name'];
        $filename_tmp = $_FILES['profile_image']['tmp_name'];
        $filename_code_error = $_FILES['profile_image']['error'];
        $authorized_extensions = array("jpg", "jpeg", "png", "webp");

        try {
          if ($filename_code_error == UPLOAD_ERR_OK) {
            $extension_of_the_uploaded_file = explode('.', $filename);
            if (in_array($extension_of_the_uploaded_file[1], $authorized_extensions)) {
              $generate_bytes = random_bytes(9);
              $bytes_to_str  = base64_encode($generate_bytes);
              $bytes_to_str = str_replace("/", "", $bytes_to_str);
              $filename_and_extension = explode('.', $filename);
              $filename_generated = $bytes_to_str . "." . $filename_and_extension[1];
              $file_settings = [
                "file" => [
                  "name" => $filename_generated,
                  "tmp" => $filename_tmp,
                  "directory" => $dir_images
                ]
              ];
              return $file_settings;
            } else {

              header('HTTP/1.1 400');
              throw new UserException(UserException::FILE_MESSAGE_ERROR_TYPE_FILE);
            }
          }
        } catch (UserException $e) {
          throw $e;
        }
      } else {

        try {
          header('HTTP/1.1 400');
          throw new UserException(UserException::FILE_MESSAGE_ERROR_NO_FILE_SELECTED);
        } catch (UserException $e) {
          throw $e;
        }
      }
    }
    return null;
  }



  public function checkEmailInput(): ?string
  {
    $dbConnect = $this->connector->connect();
    $email_regex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";
    if (isset($_POST['submit'])) {

      if (!empty($_POST['mail'])) {
        $email = $_POST['mail'];
        if (preg_match($email_regex, $email)) {
          $statement = $dbConnect->prepare("SELECT email FROM users WHERE email = :email");
          $statement->bindValue(":email", $email);
          $statement->execute();
          $result = $statement->fetch();
          try {
            if (!$result) {
              return $email;
            } else {
              header('HTTP/1.1 400');
              throw new UserException(UserException::EMAIL_MESSAGE_ERROR_ALREADY_EXIST . $email . " est déjà prise !");
            }
          } catch (UserException $e) {
            throw $e;
          }
        } else {
          try {
            header('HTTP/1.1 400');
            throw new UserException(UserException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT);
          } catch (UserException $e) {
            throw $e;
          }
        }
      } else {
        try {
          header('HTTP/1.1 400');
          throw new UserException(UserException::EMAIL_MESSAGE_ERROR_EMPTY);
        } catch (UserException $e) {
          throw $e;
        }
      }
    }
    return null;
  }
  public function checkPasswordInput(): ?string
  {

    $password_regex =  "/^(?=.*[A-Z])(?=.*\d).{8,}$/";
    if (isset($_POST['submit'])) {

      if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        try {
          if (preg_match($password_regex, $password)) {
            $hash_password = password_hash($password, PASSWORD_DEFAULT);
            return $hash_password;
          } else {
            header('HTTP/1.1 400');
            throw new UserException(UserException::PASSWORD_MESSAGE_ERROR_WRONG_FORMAT);
          }
        } catch (UserException $e) {
          throw $e;
        }
      } else {
        try {
          header('HTTP/1.1 400');
          throw new UserException(UserException::PASSWORD_MESSAGE_ERROR_EMPTY);
        } catch (UserException $e) {
          throw $e;
        }
      }
    }
    return null;
  }

  // TODO Rewrite this part because it's not enough clear
  public function inputsValidation(): void
  {
    $dbConnect = $this->connector->connect();

    $username_value = $this->checkUsernameInput();
    $file_value = $this->checkFileInput();
    $email_value = $this->checkEmailInput();
    $password_value = $this->checkPasswordInput();

    try {

      if (is_string($username_value) && is_array($file_value) &&  is_string($email_value) && is_string($password_value)) {
        $this->username = $username_value;
        $this->profileImage = "http:localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/src/admin/assets/images/" . $file_value["file"]["name"];
        $this->email = $email_value;
        $this->password = $password_value;
        $this->type = UserType::USER ?? UserType::ADMIN;
        try {

          if (str_contains($this->type->value, "user") || str_contains($this->type->value, "admin")) {

            $statement = $dbConnect->prepare("INSERT INTO users (username,profile_image,email,password,type) VALUES(?,?,?,?,?)");
            $data_values = [
              $this->username,
              $this->profileImage,
              $this->email,
              $this->password,
              $this->type->value
            ];
            $statement->execute($data_values);
            move_uploaded_file($file_value["file"]["tmp"], $file_value["file"]["directory"] . "/" . $file_value["file"]["name"]);
          }
        } finally {
          header('HTTP/1.1 500');
        }
      }
    } finally {

      header('HTTP/1.1 302');
      header("Location: ?selection=sign_in");
    }
  }


  // public function login( string $email, string $password): array
  // {

  //   $dbConnect = $this->connector->connect();
  //   if (isset($_POST['submit'])) {

  //     if (empty($email)) {
  //       header('HTTP/1.1 400');
  //       $this->error_messages["email_message_error_empty"] = "Ce champ ne peut-être vide !";
  //     }
  //     if (empty($password)) {
  //       header('HTTP/1.1 400');
  //       $this->error_messages["password_message_error_empty"] = "Ce champ ne peut-être vide !";
  //     } else {
  //       $statement = $dbConnect->prepare("SELECT email,password FROM users WHERE email = :email");
  //       $this->email = $email;
  //       $this->password = $password;
  //       $statement->bindParam(":email", $this->email);
  //       $statement->execute();
  //       $user = $statement->fetch();
  //       if ($user) {
  //         if (password_verify($this->password, $user['password'])) {
  //         } else {
  //           header('HTTP/1.1 400');
  //           $this->error_messages["password_message_error_incorrect"] = "Oups ! Le mot de passe saisi est incorrect !";
  //         }
  //       } else {
  //         header('HTTP/1.1 400');
  //         $this->error_messages["email_message_error_doesnt_exist"] = "Oups ! Nous n'avons trouvé aucun compte associé à cette adresse e-mail. Assurez-vous que vous avez saisi correctement votre adresse e-mail et réessayez";
  //       }
  //     }
  //     return $this->error_messages;
  //   }
  // }
}
