<?php

namespace Controller;

use Enumeration\UserType;

use Exceptions\EmailErrorEmptyException;
use Exceptions\EmailUnavailableException;
use Exceptions\EmailUnexistException;
use Exceptions\EmailWrongFormatException;
use Exceptions\PasswordErrorEmptyException;
use Exceptions\PasswordIncorrectException;
use Exceptions\PasswordWrongFormatException;
use Exceptions\UsernameUnavailableException;
use Exceptions\UsernameWrongFormatException;
use Exceptions\UsernameErrorEmptyException;
use Exceptions\FileTypeException;
use Exceptions\FileErrorEmptyException;

use Model\User;
use Model\UserModel;






readonly class UserController
{


  public function __construct(private User $user)
  {
  }


  public function handleUsernameField(string $username): array|string
  {

    $userRegex =  "/^[A-Z][A-Za-z\d]{2,10}$/";
    switch (true) {
      case empty($username):

        throw new UsernameErrorEmptyException();
      case !preg_match($userRegex, $username):

        throw new UsernameWrongFormatException();
      default:
        return ["username" => $username];
    }
  }
  public function handleFileField(array $file): array|string
  {
    switch (true) {
      case !empty($file["name"]) && $file["error"] == UPLOAD_ERR_OK:
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
        } else {

          throw new FileTypeException();
        }

      default:

        throw new FileErrorEmptyException(FileErrorEmptyException::FILE_MESSAGE_ERROR_NO_FILE_SELECTED);
    }
  }


  public function handleEmailField(string $email): array|string
  {

    $emailRegex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";

    switch (true) {
      case empty($email):

        throw new EmailErrorEmptyException();
      case !preg_match($emailRegex, $email):

        throw new EmailWrongFormatException();
      default:
        return ["email" => $email];
    }
  }
  public function handlePasswordField(string $password): array|string
  {

    $passwordRegex = "/^(?=.*[A-Z])(?=.*\d).{8,}$/";
    switch (true) {
      case empty($password):

        throw new PasswordErrorEmptyException();
      case !preg_match($passwordRegex, $password):

        throw new PasswordWrongFormatException();
      default:
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        return ["password" => $hashPassword];
    }
  }


  public function signUpValidator(string $username, array $file, string $email, string $password): array|string
  {

    $usernameResult = $this->handleUsernameField($username);

    $emailResult = $this->handleEmailField($email);
    $passwordResult = $this->handlePasswordField($password);
    $fileResult = $this->handleFileField($file);


    $fields = [
      "username" => $usernameResult,
      "email" => $emailResult,
      "password" => $passwordResult,
      "file" => $fileResult
    ];


    $userRepository = $this->user;
    $username = $fields["username"]["username"];
    $fileSettings = $fields["file"];
    $email = $fields["email"]["email"];
    $password = $fields["password"]["password"];
    $userType = UserType::USER;
    $userData = new UserModel($username, $fileSettings["file"], $email, $password, $userType);

    $userDb = $userRepository->createUser($userData);




    switch (true) {
      case isset($userDb["username"])  && $userDb["username"]  === $username:
        throw new UsernameUnavailableException();
      case isset($userDb["email"]) && $userDb["email"] === $email:
        throw new EmailUnavailableException();
      default:
        return $userDb;
    }
  }


  public function verifyEmailOnLogin(string $email): array|string
  {

    $emailRegex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";
    switch (true) {
      case empty($email):

        throw new EmailErrorEmptyException();
      case !preg_match($emailRegex, $email):

        throw new EmailWrongFormatException();
      default:
        return ["email" => $email];
    }
  }

  public function verifyPasswordOnLogin(string $password): array|string
  {


    if (empty($password)) {
      throw new PasswordErrorEmptyException();
    }
    return ["password" => $password];
  }


  public function loginValidator(string $email, string $password): array|string|null
  {
    $userRepository = $this->user;
    $emailResult = $this->verifyEmailOnLogin($email);
    $passwordResult = $this->verifyPasswordOnLogin($password);


    $fields = [
      "email" => $emailResult,
      "password" => $passwordResult
    ];

    $emailField = $fields["email"]["email"];
    $passwordField = $fields["password"]["password"];



    $login = $userRepository->loginUser($emailField, $passwordField);

    if (array_key_exists("password_error", $login)) {
      throw new PasswordIncorrectException();
    } elseif (array_key_exists('email_error', $login)) {
      throw new EmailUnexistException();
    }
    return $login ?? null;
  }

  public function handleInsertSessionData(array $arr): void
  {
    $userRepository = $this->user;

    $userRepository->insertSessionData($arr);
  }

  public function handleGetIdSessionData($arr): ?array
  {
    $userRepository = $this->user;

    return $userRepository->getIdSessionData($arr);
  }

  public function handleLogout(array $sessionData): ?array
  {
    $userRepository = $this->user;

    if (is_array($userRepository->logout($sessionData))) {
      return $userRepository->logout($sessionData);
    }
  }
}
