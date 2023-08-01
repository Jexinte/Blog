<?php

namespace Repository;

use Config\DatabaseConnection;
use DateTime;
use IntlDateFormatter;

class CommentRepository
{

  public function __construct(
    private readonly DatabaseConnection  $connector
  ) {
  }
  public function createComment(object $commentModel, array $sessionData): void
  {

    $dbConnect = $this->connector->connect();
    $idOfArticle = $commentModel->getIdArticle();
    $idOfUser = $commentModel->getIdUser();
    $contentOfArticle = $commentModel->getContent();
    $dateOfArticle = $commentModel->getDateCreation();

    switch (true) {
      case array_key_exists("approved", $sessionData):
        $statementGetTemporaryComment = $dbConnect->prepare("SELECT id,idArticle,idUser,content,date_creation FROM temporary_comment WHERE id = :idComment");
        $statementGetTemporaryComment->bindParam("idComment", $sessionData["id_comment"]);
        $statementGetTemporaryComment->execute();
        $resultGetTemporaryComment = $statementGetTemporaryComment->fetch();
        if ($resultGetTemporaryComment) {
          $statementInsertFinalComment = $dbConnect->prepare("INSERT INTO comment (idArticle,idUser,content,date_creation) VALUES(:idArticle,:idUser,:content,:date_creation)");
          $statementInsertFinalComment->bindParam("idArticle", $idOfArticle);
          $statementInsertFinalComment->bindParam("idUser", $idOfUser);
          $statementInsertFinalComment->bindParam("content", $contentOfArticle);
          $statementInsertFinalComment->bindParam("date_creation", $dateOfArticle);
          $statementInsertFinalComment->execute();

          $statementDeleteTemporaryComment = $dbConnect->prepare("DELETE FROM temporary_comment WHERE id = :idComment");
          $statementDeleteTemporaryComment->bindParam("idComment", $sessionData["id_comment"]);
          $statementDeleteTemporaryComment->execute();
        }
    }
  }
  public function getAllComments(array $comments, int $idArticle): ?array
  {
    $dbConnect = $this->connector->connect();

    $statementGetComments = $dbConnect->prepare("SELECT id,idArticle,idUser,content,DATE_FORMAT(date_creation,'%d %M %Y') AS date_publication  FROM comment WHERE idArticle = :idArticle ORDER BY date_publication DESC ");
    $statementGetComments->bindParam("idArticle", $idArticle);
    $statementGetComments->execute();


    while ($rowOfComment = $statementGetComments->fetch()) {
      $frenchDateFormat = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

      $date = $frenchDateFormat->format(new DateTime($rowOfComment["date_publication"]));
      $statementToGetUserData = $dbConnect->prepare("SELECT id,username,profile_image FROM user WHERE id = :idUserComment");
      $statementToGetUserData->bindParam("idUserComment", $rowOfComment["idUser"]);
      $statementToGetUserData->execute();
      while ($rowOfUserData = $statementToGetUserData->fetch()) {
        $data = [
          "username" => $rowOfUserData["username"],
          "profile_image" => $rowOfUserData["profile_image"],
          "content" => $rowOfComment['content'],
          "date_of_publication" => ucfirst($date)
        ];
      }
      $comments[] = $data;
    }



    return $comments;
  }
}
