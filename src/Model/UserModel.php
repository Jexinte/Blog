<?php

namespace Model;


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

  public function getUsername()
  {
    return $this->username;
  }
  public function getProfileImage()
  {
    return $this->profileImage;
  }
  public function getEmail()
  {
    return $this->email;
  }
  public function getPassword()
  {
    return $this->password;
  }
  public function getUserType()
  {
    return $this->type;
  }
}
