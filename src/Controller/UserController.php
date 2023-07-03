<?php

namespace Controller;

use Config\DatabaseConnection;
use Model\User;
use Exceptions\UserException;


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
    } catch (UserException $e) {
      return $e->getMessage();
    }

    return null;
  }
  public function handleFileField(): ?string
  {
    $user = new User($this->db);
    try {
      $user->checkFileInput();
    } catch (UserException $e) {
      return $e->getMessage();
    }

    return null;
  }


  public function handleEmailField(): ?string
  {
    $user = new User($this->db);
    try {
      $user->checkEmailInput();
    } catch (UserException $e) {
      return $e->getMessage();
    }

    return null;
  }

  public function handlePasswordField(): ?string
  {
    $user = new User($this->db);
    try {
      $user->checkPasswordInput();
    } catch (UserException $e) {
      return $e->getMessage();
    }
    return null;
  }


  public function handleInputsValidation(): ?string
  {
    $user = new User($this->db);
    try {
      $user->inputsValidation();
    } catch (UserException $e) {
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
