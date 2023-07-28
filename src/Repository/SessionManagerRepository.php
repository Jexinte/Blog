<?php

namespace Repository;

use Config\DatabaseConnection;
use Model\SessionModel;

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



  public function insertSessionData(?string $idSession, string $username, string $userType): ?array
  {

    $dbConnect = $this->connector->connect();

    $statement = $dbConnect->prepare("SELECT username FROM session WHERE username = :username");
    $statement->bindParam("username", $username);
    $statement->execute();
    $result = $statement->fetch();

    if (!$result) {
      $sessionModel = new SessionModel($idSession, $username, $userType);
      $sessionModel->setIdSession(str_replace("/", "", base64_encode(random_bytes(50))));
      $idSessionInModel = $sessionModel->getIdSession();
      $insertData = $dbConnect->prepare("INSERT INTO session (id_session,username,user_type) VALUES(?,?,?)");

      $values = [$idSessionInModel, $username, $userType];
      $insertData->execute($values);
    }

    return null;
  }


  public function getIdSessionData($sessionData): ?array
  {
    $dbConnect = $this->connector->connect();
    $statement = $dbConnect->prepare("SELECT username,type FROM user WHERE username = :username AND type = :type_user");

    $statement->bindParam("username", $sessionData["username"]);
    $statement->bindParam("type_user", $sessionData["type_user"]);
    $statement->execute();
    $result = $statement->fetch();

    if ($result) {
      $statementSession = $dbConnect->prepare("SELECT id_session,username,user_type FROM session WHERE username = :username AND user_type = :type_user");
      $statementSession->bindParam("username", $sessionData["username"]);
      $statementSession->bindParam("type_user", $sessionData["type_user"]);

      $statementSession->execute();
      $resultStatementSession = $statementSession->fetch();
      $idSession = !empty($resultStatementSession) ?  $resultStatementSession["id_session"] : null;
      if (!empty($idSession)) return ["session_id" => $idSession];
    }
    return  null;
  }
}
