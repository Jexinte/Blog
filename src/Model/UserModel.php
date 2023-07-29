<?php

namespace Model;

use BackedEnum;
use Enumeration\UserType;

class UserModel
{

  public function __construct(
    protected string $username,
    protected string $profileImage,
    protected string $email,
    protected string $password,
    protected UserType $type,
    protected ?bool $usernameStatus,
    protected ?bool $emailStatus,
    protected ?bool $successSignUp
  ) {
  }

  public function getUsername(): string
  {
    return $this->username;
  }
  public function getProfileImage(): string
  {
    return $this->profileImage;
  }
  public function getEmail(): string
  {
    return $this->email;
  }
  public function getPassword(): string
  {
    return $this->password;
  }
  public function getUserType(): BackedEnum
  {
    return $this->type;
  }
  public function isUsernameAvailable():?bool
  {
    return $this->usernameStatus;
  }
  public function isEmailAvailable():?bool
  {
    return $this->emailStatus;
  }

  public function getSuccessSignUp():?bool
  {
    return $this->successSignUp;
  }

  public function setUsernameAvailability(bool $status):void
  {
    $this->usernameStatus = $status;
  }
  public function setEmailAvailability(bool $status):void
  {
    $this->emailStatus = $status;
  }

  public function isSignUpSuccessful($successSignUp):void
  {
    $this->successSignUp = $successSignUp;
  }
}
