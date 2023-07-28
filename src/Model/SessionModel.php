<?php

namespace Model;


class SessionModel
{

  public function __construct(private ?string $idSession, public string $username, protected string $userType)
  {
  }

  public function getIdSession()
  {
    return $this->idSession;
  }
  public function getUsername()
  {
    return $this->username;
  }
  public function getUserType()
  {
    return $this->userType;
  }

  public function setIdSession($sessionId)
  {
    $this->idSession = $sessionId;
  }
}
