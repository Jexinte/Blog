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
    protected ?bool $emailStatus
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
  public function isUsernameAvailable()
  {
    return $this->usernameStatus;
  }
  public function isEmailAvailable()
  {
    return $this->emailStatus;
  }

  public function setUsernameAvailability(bool $status)
  {
    $this->usernameStatus = $status;
  }
  public function setEmailAvailability(bool $status)
  {
    $this->emailStatus = $status;
  }
}
