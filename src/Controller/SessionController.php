<?php

namespace Controller;

use Repository\SessionManagerRepository;
use Model\SessionModel;

class SessionController
{

  public function __construct(private readonly SessionManagerRepository $sessionManagerRepository)
  {
  }


  public function handleInsertSessionData(array $sessionData): void
  {
    $sessionModel = new SessionModel(null, $sessionData["username"], $sessionData["type_user"]);
  

    $this->sessionManagerRepository->insertSessionData($sessionModel);
  }

  public function handleGetIdSessionData(array $arr): ?array
  {

    return $this->sessionManagerRepository->getIdSessionData($arr);
  }
}
