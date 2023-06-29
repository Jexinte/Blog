<?php

namespace Model;

use Model\UserModel;
use Class\User as UserType;
use Config\DatabaseConnection;


//* Regex ^[A-Z][A-Za-z0-9]{0,9}$ User
class User extends UserModel
{
  public $messages = [
    // USERNAME MESSAGES
    'username_message_error_empty' => "",
    'username_message_success_available' => "",
    'username_message_error_unavailable' => "",
    'username_message_error_wrong_format' => "",

    // FILE MESSAGES
    'file_message_error_type' => "",
    'file_message_error_empty' => "",
    'file_message_success' => "",

    // EMAIL MESSAGES
    'email_message_error_already_exist' => "",
    'email_message_error_empty' => "",
    'email_message_error_wrong_format' => "",
    // PASSWORD MESSAGES
    'password_message_error_empty' => "",
    'password_message_error_wrong_format' => "",
  ];


  public function __construct(private DatabaseConnection $connector)
  {
  }

  public function checkUsernameInput()
  {
    $dbConnect = $this->connector->connect();
    $user_regex =  "/^[A-Z][A-Za-z0-9]{0,9}$/";
    if (isset($_POST['submit'])) {

      if (!empty($_POST['username'])) {
        $username = $_POST['username'];
        if (preg_match($user_regex, $username)) {
          $statement = $dbConnect->prepare("SELECT username FROM users WHERE username = :username");
          $statement->bindValue(":username", $username);
          $statement->execute();
          $result = $statement->fetch();
          if (!$result) {
            return $username;
          } else {
            $this->messages['username_message_error_unavailable'] = "Le nom d'utilisateur $username n'est pas disponible";
            header('Location?action=sign_up', true, 404);
            return $this->messages;
          }
        } else {
          $this->messages['username_message_error_wrong_format'] = "Merci de suivre les règles ci-dessus pour la définition de votre nom d'utilisateur !";
          header('Location?action=sign_up', true, 404);
          return $this->messages;
        }
      } else {
        $this->messages['username_message_error_empty'] = "Ce champ ne peut-être vide !";
        header('Location?action=sign_up', true, 404);
        return $this->messages;
      }
    }
  }

  public function checkPasswordInput()
  {

    $password_regex =  "/^(?=.*[A-Z])(?=.*\d).{8,}$/";
    if (isset($_POST['submit'])) {
      if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        if (preg_match($password_regex, $password)) {
          $hash_password = password_hash($password, PASSWORD_DEFAULT);
          return $hash_password;
        } else {
          $this->messages["password_message_error_wrong_format"] = "Merci de définir un mot de passe suivant les règles ci-dessus!";
          header('Location?action=sign_up', true, 404);
          return $this->messages;
        }
      } else {
        $this->messages["password_message_error_empty"] = "Ce champ ne peut-être vide!";
        header('Location?action=sign_up', true, 404);
        return $this->messages;
      }
    }
  }



  public function checkFileInput()
  {

    if (isset($_POST['submit'])) {
      if (!empty($_FILES['profile_image']["name"])) {
        $dir_images = "../src/admin/assets/images";
        $filename = $_FILES['profile_image']['name'];
        $filename_tmp = $_FILES['profile_image']['tmp_name'];
        $filename_code_error = $_FILES['profile_image']['error'];
        $authorized_extensions = array("jpg", "jpeg", "png", "webp");

        if ($filename_code_error == UPLOAD_ERR_OK) {

          //* Get the extension of the downloaded file
          $extension_of_the_uploaded_file = explode('.', $filename);
          if (in_array($extension_of_the_uploaded_file[1], $authorized_extensions)) {
            $generate_bytes = random_bytes(9);
            $bytes_to_str  = base64_encode($generate_bytes);

            $filename_and_extension = explode('.', $filename);
            $filename_generated = $bytes_to_str . "." . $filename_and_extension[1];
            $file_settings = [
              "file" => [
                "name" => $filename_generated,
                "tmp" => $filename_tmp,
                "directory" => $dir_images
              ]
            ];
            //! Thist line will be moved in a function that will check if every properties of the class is filled and check then all of them will be insert in the database
            //move_uploaded_file($filename_tmp, "$dir_images/$filename_generated");
            return $file_settings;
          } else {
            $this->messages["file_message_error_type"] = "Seuls les fichiers de type : jpg, jpeg , png et webp sont acceptés";
            header('Location?action=sign_up', true, 404);
            return $this->messages;
          }
        }
      } else {
        $this->messages["file_message_error_empty"] = "Merci de sélectionner un fichier sans quoi l'inscription ne peut se poursuivre!";
        header('Location?action=sign_up', true, 404);
        return $this->messages;
      }
    }
  }

  public function checkEmailInput()
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
            $this->messages["email_message_error_already_exist"] = "L'adresse email " . $_POST['mail'] . " est déjà prise";
            header('Location?action=sign_up', true, 404);
            return $this->messages;
          }
        } else {
          $this->messages["email_message_error_wrong_format"] = "Merci d'insérer une adresse email dans le format suivant johndoe@gmail.com";
          header('Location?action=sign_up', true, 404);
          return $this->messages;
        }
      } else {
        $this->messages["email_message_error_empty"] = "Ce champ ne peut-être vide !";
        header('Location?action=sign_up', true, 404);
        return $this->messages;
      }
    }
  }

  public function validateProperty()
  {
    $dbConnect = $this->connector->connect();
    $username_value = $this->checkUsernameInput();
    $file_value = $this->checkFileInput();
    $email_value = $this->checkEmailInput();
    $password_value = $this->checkPasswordInput();
    if (is_string($username_value) && array_key_first($file_value) === "file" && is_string($email_value) && is_string($password_value)) {
      $this->username = $username_value;
      $this->profileImage = "http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/src/admin/assets/images/" . $file_value["file"]["name"];
      $this->email = $email_value;
      $this->password = $password_value;
      $this->type = UserType::USER ?? UserType::ADMIN;
      if (!str_contains($this->type->value, "user") && !str_contains($this->type->value, "admin")) {
        header("Location ?action=sign_up", true, 400);
      } else {
        $statement = $dbConnect->prepare("INSERT INTO users (username,profile_image,email,password,type) VALUES(?,?,?,?,?)");
        $values = [
          $this->username,
          $this->profileImage,
          $this->email,
          $this->password,
          $this->type->value
        ];
        $statement->execute($values);
        move_uploaded_file($file_value["file"]["tmp"], $file_value["file"]["directory"] . "/" . $file_value["file"]["name"]);
        header('Location?action=sign_up', true, 201);
      }
    }
  }
}
