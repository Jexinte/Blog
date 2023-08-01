<?php

namespace Repository;

use Config\DatabaseConnection;

class SessionManagerRepository
{
  public function __construct(private readonly DatabaseConnection $connector)
  {
  }

  public function startSession(): void
  {
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }
  }


  public function destroySession(): void
  {

    if (session_status() === PHP_SESSION_ACTIVE) {
      session_destroy();
    }
  }



  public function insertSessionData(object $sessionModel): ?array
  {

    $dbConnect = $this->connector->connect();

    $username = $sessionModel->getUsername();
    $userType = $sessionModel->getUserType();
    
    $statementToCreateSession = $dbConnect->prepare("SELECT username FROM session WHERE username = :username");
    $statementToCreateSession->bindParam("username", $username);
    $statementToCreateSession->execute();
    $result = $statementToCreateSession->fetch();

    if (!$result) {
      $sessionModel->setIdSession(str_replace("/", "", base64_encode(random_bytes(50))));
      $idSessionInModel = $sessionModel->getIdSession();
      $insertData = $dbConnect->prepare("INSERT INTO session (id_session,username,user_type) VALUES(?,?,?)");

      $values = [$idSessionInModel, $username, $userType];
      $insertData->execute($values);
    }

    return null;
  }


  public function getIdSessionData(array $sessionData): ?array
  {
    $dbConnect = $this->connector->connect();
    $statementToGetIdSession = $dbConnect->prepare("SELECT username,type FROM user WHERE username = :username AND type = :type_user");

    $statementToGetIdSession->bindParam("username", $sessionData["username"]);
    $statementToGetIdSession->bindParam("type_user", $sessionData["type_user"]);
    $statementToGetIdSession->execute();
    $thereIsId = $statementToGetIdSession->fetch();

    if ($thereIsId) {
      $statementToGetUserDataRegardlessToSessionData = $dbConnect->prepare("SELECT id_session,username,user_type FROM session WHERE username = :username AND user_type = :type_user");
      $statementToGetUserDataRegardlessToSessionData->bindParam("username", $sessionData["username"]);
      $statementToGetUserDataRegardlessToSessionData->bindParam("type_user", $sessionData["type_user"]);

      $statementToGetUserDataRegardlessToSessionData->execute();
      $sessionData = $statementToGetUserDataRegardlessToSessionData->fetch();
      $idSession = !empty($sessionData) ?  $sessionData["id_session"] : null;
      if (!empty($idSession)) return ["session_id" => $idSession];
    }
    return  null;
  }
}
