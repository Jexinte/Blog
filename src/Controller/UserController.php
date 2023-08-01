<?php

namespace Controller;

use Enumeration\UserType;


use Exceptions\ValidationException;
use Repository\UserRepository;
use Model\UserModel;
use Enumeration\Regex;





class UserController
{


  public function __construct(private readonly UserRepository $UserRepository)
  {
  }


  public function handleUsernameField(string $username): array|string
  {
    $validationException = new ValidationException();
    switch (true) {
      case empty($username):
        throw $validationException->setTypeAndValueOfException("username_exception", $validationException::ERROR_EMPTY);
      case !preg_match(REGEX::USERNAME, $username):

        throw $validationException->setTypeAndValueOfException("username_exception", $validationException::USERNAME_MESSAGE_ERROR_WRONG_FORMAT);
      default:
        return ["username" => $username];
    }
  }
  public function handleFileField(array $file): array|string
  {
    $validationException = new ValidationException();
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
          throw $validationException->setTypeAndValueOfException("file_exception", $validationException::FILE_MESSAGE_ERROR_TYPE_FILE);
        }

      default:
        throw $validationException->setTypeAndValueOfException("file_exception", $validationException::FILE_MESSAGE_ERROR_NO_FILE_SELECTED);
    }
  }


  public function handleEmailField(string $email): array|string
  {
    $validationException = new ValidationException();
    switch (true) {
      case empty($email):
        throw $validationException->setTypeAndValueOfException("email_exception", $validationException::ERROR_EMPTY);
      case !preg_match(REGEX::EMAIL, $email):
        throw $validationException->setTypeAndValueOfException("email_exception", $validationException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT);
      default:
        return ["email" => $email];
    }
  }
  public function handlePasswordField(string $password): array|string
  {
    $validationException = new ValidationException();
    switch (true) {
      case empty($password):
        throw $validationException->setTypeAndValueOfException('password_exception', $validationException::ERROR_EMPTY);
      case !preg_match(REGEX::PASSWORD, $password):
        throw $validationException->setTypeAndValueOfException("password_exception", $validationException::PASSWORD_MESSAGE_ERROR_WRONG_FORMAT);
      default:
        $hashPassword = password_hash($password, PASSWORD_DEFAULT);
        return ["password" => $hashPassword];
    }
  }


  public function signUpValidator(string $username, array $file, string $email, string $password): ?UserModel
  {

    $validationException = new ValidationException();
    $usernameResult = $this->handleUsernameField($username)["username"];
    $emailResult = $this->handleEmailField($email)["email"];
    $passwordResult = $this->handlePasswordField($password)["password"];
    $fileResult = $this->handleFileField($file)["file"];
    $userType = UserType::USER;



    $userModel = new UserModel($usernameResult, $fileResult, $emailResult, $passwordResult, $userType, null, null, null);
    $usernameInModel = $userModel->getUsername();
    $profileImageInModel = $userModel->getProfileImage();
    $emailInModel = $userModel->getEmail();
    $passwordInModel = $userModel->getPassword();
    $userTypeInModel = $userModel->getUserType();


    $userRepository = $this->UserRepository;
    $userDb = $userRepository->createUser($usernameInModel, $profileImageInModel, $emailInModel, $passwordInModel, $userTypeInModel);

    if ($userDb->getSuccessSignUp()) {
      return $userDb;
    }
    switch (true) {
      case !is_null($userDb->isUsernameAvailable()):
        throw $validationException->setTypeAndValueOfException("username_exception", $validationException::USERNAME_UNAVAILABLE_MESSAGE_ERROR . $username . " n'est pas disponible !");
      case !is_null($userDb->isEmailAvailable()):
        throw $validationException->setTypeAndValueOfException("email_exception", $validationException::EMAIL_UNAVAILABLE_MESSAGE_ERROR . $email . " n'est pas disponible !");
    }
  }


  public function verifyEmailOnLogin(string $email): array|string
  {
    $validationException = new ValidationException();
    $emailRegex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";
    switch (true) {
      case empty($email):
        throw $validationException->setTypeAndValueOfException("email_exception", $validationException::ERROR_EMPTY);
      case !preg_match($emailRegex, $email):
        throw $validationException->setTypeAndValueOfException("email_exception", $validationException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT);
      default:
        return ["email" => $email];
    }
  }

  public function verifyPasswordOnLogin(string $password): array|string
  {
    $validationException = new ValidationException();

    if (empty($password)) {
      throw $validationException->setTypeAndValueOfException('password_exception', $validationException::ERROR_EMPTY);
    }
    return ["password" => $password];
  }


  public function loginValidator(string $email, string $password): array|string|null
  {
    $validationException = new ValidationException();
    $userRepository = $this->UserRepository;
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
      throw $validationException->setTypeAndValueOfException("password_exception", $validationException::PASSWORD_INCORRECT_MESSAGE_ERROR);
    } elseif (array_key_exists('email_error', $login)) {
      throw $validationException->setTypeAndValueOfException("email_exception", $validationException::EMAIL_UNEXIST_MESSAGE_ERROR);
    }
    return $login ?? null;
  }



  public function handleLogout(array $sessionData): ?array
  {
    $userRepository = $this->UserRepository;

    if (is_array($userRepository->logout($sessionData))) {
      return $userRepository->logout($sessionData);
    }
  }

  public function handleGetAllUserNotifications(array $sessionData): ?array
  {
    $userRepository = $this->UserRepository;
    return $userRepository->getAllUserNotifications($sessionData);
  }

  public function handleDeleteNotification(int $idNotification): ?array
  {
    $userRepository = $this->UserRepository;
    return $userRepository->deleteNotification($idNotification);
  }
}
