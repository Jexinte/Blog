<?php

namespace Controller;

use Config\DatabaseConnection;
use Model\User;


class SignUpController
{
  private $db;

  public function __construct()
  {
    $this->db = new DatabaseConnection("professional_blog", "root", "");
  }
  public function handleUsernameField()
  {

    $user = new User($this->db);
    return $user->checkUsernameInput();
  }

  public function handlePasswordField()
  {
    $user = new User($this->db);
    return $user->checkPasswordInput();
  }

  public function handleFileInput()
  {
    $user = new User($this->db);
    return $user->checkFileInput();
  }

  public function handleEmailFileInput()
  {
    $user = new User($this->db);
    return $user->checkEmailInput();
  }

  public function handleValidationProperty()
  {
    $user = new User($this->db);
    return $user->validateProperty();
  }
}
