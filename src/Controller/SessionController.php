<?php

namespace Controller;


use Manager\Session;

class SessionController
{

  public function __construct(private Session $session){}

  public function initializeLoginDataAndSessionId(array $loginData):void
  {
    $this->session->initializeKeyAndValue("username",$loginData["username"]);
    $this->session->initializeKeyAndValue("id_user",$loginData["id_user"]);
    $this->session->initializeKeyAndValue("type_user",$loginData["type_user"]);
    $this->session->initializeKeyAndValue("session_id",session_id());
  
  }

  public function initializeIdArticle(int $articleId):void
  {
    $this->session->initializeKeyAndValue("id_article",$articleId);
  }



public function handleGetSessionData():array
{
  
  return $this->session->getSessionData();
}

}
