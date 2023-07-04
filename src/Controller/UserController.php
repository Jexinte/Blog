<?php

namespace Controller;


use Model\User;
use Exceptions\UserException;


class UserController
{


  public function __construct(private readonly User $user)
  {

  }
  public function handleUsernameField(): ?string
  {

    try {
      $this->user->checkUsernameInput();
    } catch (UserException $e) {
      return $e->getMessage();
    }

    return null;
  }
  public function handleFileField(): ?string
  {
  
    try {
      $this->user->checkFileInput();
    } catch (UserException $e) {
      return $e->getMessage();
    }

    return null;
  }


  public function handleEmailField(): ?string
  {

    try {
      $this->user->checkEmailInput();
    } catch (UserException $e) {
      return $e->getMessage();
    }

    return null;
  }

  public function handlePasswordField(): ?string
  {

    try {
      $this->user->checkPasswordInput();
    } catch (UserException $e) {
      return $e->getMessage();
    }
    return null;
  }


  public function handleInputsValidation(): ?string
  {

    try {
      $this->user->inputsValidation();
    } catch (UserException $e) {
      return $e->getMessage();
    }
    return null;
  }

  public function handleLoginField(): array
  {
//TODO Ne pas se servir des superglobales ?
    return $this->user->login($_POST['mail'], $_POST['password']);
  }
}
