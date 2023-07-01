<?php

namespace Controller;

use Config\DatabaseConnection;
use Model\User;
use Model\UserSignUpException;


class UserController
{
  private $db;

  public function __construct()
  {
    $this->db = new DatabaseConnection("professional_blog", "root", "");
  }
  public function handleUsernameField(): ?string
  {

    $user = new User($this->db);

    try {
      $user->checkUsernameInput();
    } catch (UserSignUpException $e) {
      return $e->getMessage();
    }

    return null;
  }
  public function handleFileField(): ?string
  {
    $user = new User($this->db);
    try {
      $user->checkFileInput();
    } catch (UserSignUpException $e) {
      return $e->getMessage();
    }

    return null;
  }


  public function handleEmailField(): ?string
  {
    $user = new User($this->db);
    try {
      $user->checkEmailInput();
    } catch (UserSignUpException $e) {
      return $e->getMessage();
    }

    return null;
  }

  public function handlePasswordField(): ?string
  {
    $user = new User($this->db);
    try {
      $user->checkPasswordInput();
    } catch (UserSignUpException $e) {
      return $e->getMessage();
    }
    return null;
  }


  public function handleInputsValidation(): ?string
  {
    $user = new User($this->db);
    try {
      $user->inputsValidation();
    } catch (UserSignUpException $e) {
      return $e->getMessage();
    }
    return null;
  }

  public function handleLoginField(): array
  {
    $user = new User($this->db);
    return $user->login($_POST['mail'], $_POST['password']);
  }
}
