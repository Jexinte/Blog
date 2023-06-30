<?php

namespace Model;

use Config\DatabaseConnection;
use Model\UserModel;
use Enumeration\UserType;




class User extends UserModel
{

  public function __construct(private DatabaseConnection $connector)
  {
  }
  public array $error_messages = [


    // USERNAME
    "username_message_error_empty" => "",
    "username_message_error_wrong_format" => "",
    "username_message_error_unavailable" => " ",

    // FILE
    "file_message_error_type" => "",
    "file_message_error_empty" => "",

    // MAIL
    "email_message_error_already_exist" => "",
    "email_message_error_empty" => "",
    "email_message_error_wrong_format" => "",
    "email_message_error_doesnt_exist" => "",


    // PASSWORD
    "password_message_error_empty" => "",
    "password_message_error_wrong_format" => "",
    "password_message_error_incorrect" => "",

  ];



  public function checkUsernameInput(): string|array
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

          if (!$result) {
            return $username;
          } else {

            header('HTTP/1.1 400');
            $this->error_messages["username_message_error_unavailable"] = "Le nom d'utilisateur : " . $username . " est déjà pris";
            return $this->error_messages;
          }
        } else {
          header('HTTP/1.1 400');
          $this->error_messages["username_message_error_wrong_format"] = "Oops ! Merci de définir un nom d'utilisateur en suivant le format ci-dessous :";
          return $this->error_messages;
        }
      } else {

        header('HTTP/1.1 400');
        $this->error_messages["username_message_error_empty"] = "Ce champ ne peut être vide !";
        return $this->error_messages;
      }
    }
  }

  public function checkFileInput(): array
  {

    if (isset($_POST['submit'])) {
      if (!empty($_FILES['profile_image']["name"])) {
        $dir_images = "../src/admin/assets/images";
        $filename = $_FILES['profile_image']['name'];
        $filename_tmp = $_FILES['profile_image']['tmp_name'];
        $filename_code_error = $_FILES['profile_image']['error'];
        $authorized_extensions = array("jpg", "jpeg", "png", "webp");

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
            $this->error_messages["file_message_error_type"] = "Seuls les fichiers de type : jpg, jpeg , png et webp sont acceptés !";
            return $this->error_messages;
          }
        }
      } else {
        header('HTTP/1.1 400');
        $this->error_messages["file_message_error_empty"] = "Veuillez sélectionner un fichier !";
        return $this->error_messages;
      }
    }
  }


  public function checkEmailInput(): string|array
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

          if (!$result) {
            return $email;
          } else {
            header('HTTP/1.1 400');
            $this->error_messages["email_message_error_already_exist"] = "L'adresse email suivante : " . $email . " est déjà prise";
            return $this->error_messages;
          }
        } else {
          header('HTTP/1.1 400');
          $this->error_messages["email_message_error_wrong_format"] = "Oops ! Le format de votre saisie est incorrect. Merci de suivre le format requis : johndoe@gmail.com";
          return $this->error_messages;
        }
      } else {
        header('HTTP/1.1 400');
        $this->error_messages["email_message_error_empty"] = "Ce champ ne peut-être vide !";
        return $this->error_messages;
      }
    }
  }
  public function checkPasswordInput(): string|array
  {

    $password_regex =  "/^(?=.*[A-Z])(?=.*\d).{8,}$/";
    if (isset($_POST['submit'])) {

      if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        if (preg_match($password_regex, $password)) {
          $hash_password = password_hash($password, PASSWORD_DEFAULT);
          return $hash_password;
        } else {
          header('HTTP/1.1 400');
          $this->error_messages["password_message_error_wrong_format"] = "Oops ! Le format de votre mot de passe est incorrect. Merci de suivre le format ci-dessus";
          return $this->error_messages;
        }
      } else {
        header('HTTP/1.1 400');
        $this->error_messages["password_message_error_empty"] = "Ce champ ne peut être vide !";
        return $this->error_messages;
      }
    }
  }

  public function inputsValidation(): void
  {
    $dbConnect = $this->connector->connect();

    $username_value = $this->checkUsernameInput();
    $file_value = $this->checkFileInput();
    $email_value = $this->checkEmailInput();
    $password_value = $this->checkPasswordInput();



    if (is_string($username_value) && array_key_first($file_value) === "file" &&  is_string($email_value) && is_string($password_value)) {
      $this->username = $username_value;
      $this->profileImage = "http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/src/admin/assets/images/" . $file_value["file"]["name"];
      $this->email = $email_value;
      $this->password = $password_value;
      $this->type = UserType::USER ?? UserType::ADMIN;
      if (str_contains($this->type->value, "user") || str_contains($this->type->value, "admin")) {

        $statement = $dbConnect->prepare("INSERT INTO users (username,profile_image,email,password,type) VALUES(?,?,?,?,?)");
        $data_values = [
          $this->username,
          $this->profileImage,
          $this->email,
          $this->password,
          $this->type->value
        ];
        header('HTTP/1.1 302');
        header("Location: ?selection=sign_in");
        $statement->execute($data_values);
        move_uploaded_file($file_value["file"]["tmp"], $file_value["file"]["directory"] . "/" . $file_value["file"]["name"]);
      } else {
        header('HTTP/1.1 500');
      }
    }
  }

  public function login($email, $password): array
  {

    $dbConnect = $this->connector->connect();
    if (isset($_POST['submit'])) {

      if (empty($email)) {
        header('HTTP/1.1 400');
        $this->error_messages["email_message_error_empty"] = "Ce champ ne peut-être vide !";
      }
      if (empty($password)) {
        header('HTTP/1.1 400');
        $this->error_messages["password_message_error_empty"] = "Ce champ ne peut-être vide !";
      } else {
        $statement = $dbConnect->prepare("SELECT email,password FROM users WHERE email = :email");
        $this->email = $email;
        $this->password = $password;
        $statement->bindParam(":email", $this->email);
        $statement->execute();
        $user = $statement->fetch();
        if ($user) {
          if (password_verify($this->password, $user['password'])) {
          } else {
            header('HTTP/1.1 400');
            $this->error_messages["password_message_error_incorrect"] = "Oups ! Le mot de passe saisi est incorrect !";
          }
        } else {
          header('HTTP/1.1 400');
          $this->error_messages["email_message_error_doesnt_exist"] = "Oups ! Nous n'avons trouvé aucun compte associé à cette adresse e-mail. Assurez-vous que vous avez saisi correctement votre adresse e-mail et réessayez";
        }
      }
      return $this->error_messages;
    }
  }
}
