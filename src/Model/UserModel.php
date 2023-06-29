<?php


namespace Model;


use Class\User as User;

class UserModel
{

  public function __construct(
    protected string $username,
    protected string $profileImage,
    protected string $email,
    protected string $password,
    protected User $type,
  )
   {

  }
}
