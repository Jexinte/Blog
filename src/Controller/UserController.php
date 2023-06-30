<?php

namespace Controller;

use Config\DatabaseConnection;
use Model\User;


class UserController
{
  private $db;

  public function __construct()
  {
    $this->db = new DatabaseConnection("professional_blog", "root", "");
  }
  public function handleUsernameField(): ?array
  {

    $user = new User($this->db);
    if (is_array($user->checkUsernameInput())) return $user->checkUsernameInput();
    else return null;
  }
  public function handleFileField(): ?array
  {
    $user = new User($this->db);
    if (array_key_first($user->checkFileInput()) != "file") return $user->checkFileInput();
    else return null;
  }

  public function handleEmailField(): ?array
  {
    $user = new User($this->db);
    if (is_array($user->checkEmailInput())) return $user->checkEmailInput();
    else return null;
  }

  public function handlePasswordField(): ?array
  {
    $user = new User($this->db);
    if (is_array($user->checkPasswordInput())) return $user->checkPasswordInput();
    else return null;
  }


  public function handleInputsValidation(): void
  {
    $user = new User($this->db);
    $user->inputsValidation();
  }
}
