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
    $sessionManagerRepository = $this->sessionManagerRepository;
    
    $sessionModel = new SessionModel(null,$sessionData["username"],$sessionData["type_user"]);
    $idSessionInModel = $sessionModel->getIdSession();
    $usernameInModel = $sessionModel->getUsername();
    $userTypeInModel = $sessionModel->getUserType();

    $sessionManagerRepository->insertSessionData($idSessionInModel,$usernameInModel,$userTypeInModel);
  }

  public function handleGetIdSessionData(array $arr): ?array
  {
    $sessionManagerRepository = $this->sessionManagerRepository;

    return $sessionManagerRepository->getIdSessionData($arr);
  }
}
