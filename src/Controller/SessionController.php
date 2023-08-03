<?php

namespace Controller;


use Manager\SessionManager;

class SessionController
{

  public function __construct(private SessionManager $sessionManager){}

  public function initializeLoginDataAndSessionId(array $loginData):void
  {
    $this->sessionManager->initializeKeyAndValue("username",$loginData["username"]);
    $this->sessionManager->initializeKeyAndValue("id_user",$loginData["id_user"]);
    $this->sessionManager->initializeKeyAndValue("type_user",$loginData["type_user"]);
    $this->sessionManager->initializeKeyAndValue("session_id",session_id());
  
  }

  public function initializeIdArticle(int $articleId):void
  {
    $this->sessionManager->initializeKeyAndValue("id_article",$articleId);
  }



public function handleGetSessionData():array
{
  
  return $this->sessionManager->getSessionData();
}

}
