<?php

namespace Model;

use Config\DatabaseConnection;

class TemporaryComment
{

  public function __construct(
    private readonly DatabaseConnection  $connector
  ) {
  }

  public function insertTemporaryComment(object $data , array $sessionData):array
  {
    $commentsData = $data->getData();
    $dbConnect = $this->connector->connect();
    $idSession = $sessionData["session_id"];
    $usernameSession = $sessionData["username"];
    $typeUserSession = $sessionData["type_user"];
    $statementSession = $dbConnect->prepare("SELECT id_session,username,user_type FROM session WHERE id_session = :id_from_session_variable AND username = :username_from_session_variable AND user_type = :type_user_from_session_variable");

    $statementSession->bindParam("id_from_session_variable", $idSession);
    $statementSession->bindParam("username_from_session_variable", $usernameSession);
    $statementSession->bindParam("type_user_from_session_variable", $typeUserSession);

    $statementSession->execute();
    $result = $statementSession->fetch();
    if($result){
      $statementComment = $dbConnect->prepare("INSERT INTO temporary_comment (idArticle,idUser,content,date_creation,approved,rejected,feedback_administrator) VALUES(:idArticle,:idUser,:content,:date_creation,:approved,:rejected,:feedback_administrator)");

      $statementComment->bindParam("idArticle",$commentsData["id_article"]);
      $statementComment->bindParam("idUser",$commentsData["id_user"]);
      $statementComment->bindParam("content",$commentsData["content"]);
      $statementComment->bindParam("date_creation",$commentsData["date_creation"]);
      $statementComment->bindParam("approved",$commentsData["approved"]);
      $statementComment->bindParam("rejected",$commentsData["rejected"]);
      $statementComment->bindParam("feedback_administrator",$commentsData["feedback_administrator"]);
      $statementComment->execute();
    }
  return ["temporary_comment_saved" => 1];   
}

public function checkCommentAlreadySentByUser(array $sessionData):?array
{

  $dbConnect = $this->connector->connect();
  $idSession = $sessionData["session_id"];
  $usernameSession = $sessionData["username"];
  $typeUserSession = $sessionData["type_user"];
  $idUserSession = $sessionData["id_user"];
  $idArticleSession = $sessionData["id_article"];
  $statementSession = $dbConnect->prepare("SELECT id_session,username,user_type FROM session WHERE id_session = :id_from_session_variable AND username = :username_from_session_variable AND user_type = :type_user_from_session_variable");

  $statementSession->bindParam("id_from_session_variable", $idSession);
  $statementSession->bindParam("username_from_session_variable", $usernameSession);
  $statementSession->bindParam("type_user_from_session_variable", $typeUserSession);
$resultSession = $statementSession->execute();
  if($resultSession){
    $statementTemporaryComments = $dbConnect->prepare("SELECT idArticle,idUser FROM temporary_comment WHERE idUser = :idUser AND idArticle = :idArticle");
    $statementTemporaryComments->bindParam("idUser",$idUserSession);
    $statementTemporaryComments->bindParam("idArticle",$idArticleSession);
    $statementTemporaryComments->execute();


      $userComments = [];
      while($row = $statementTemporaryComments->fetch()){
        $userComments[] = $row;
      }

//TODO Objectif : Faire en sorte de savoir si un utilisateur a au moins un commentaire en cours de validation si c'est le cas alors il faut cacher le formulaire

    return count($userComments) == 1 ? ["user_already_commented" => 1] : null;
  }
}
}