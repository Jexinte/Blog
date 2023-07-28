<?php

namespace Model;


class SessionModel
{

  public function __construct(private ?string $idSession, public string $username, protected string $userType)
  {
  }

  public function getIdSession():?string
  {
    return $this->idSession;
  }
  public function getUsername():string
  {
    return $this->username;
  }
  public function getUserType():string
  {
    return $this->userType;
  }

  public function setIdSession(string $sessionId):void
  {
    $this->idSession = $sessionId;
  }
}
