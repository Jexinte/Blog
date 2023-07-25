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
  ) {
  }

  public function getUsername():string
  {
    return $this->username;
  }
  public function getProfileImage():string
  {
    return $this->profileImage;
  }
  public function getEmail():string
  {
    return $this->email;
  }
  public function getPassword():string
  {
    return $this->password;
  }
  public function getUserType():BackedEnum
  {
    return $this->type;
  }
}
