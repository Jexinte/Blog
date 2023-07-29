<?php

namespace Repository;

use Config\DatabaseConnection;
use DateTime;
use IntlDateFormatter;
use Model\CommentModel;

class CommentRepository
{

  public function __construct(
    private readonly DatabaseConnection  $connector
  ) {
  }
  public function createComment(int $idArticle, int $idUser, string $content, string $dateCreation, array $sessionData): void
  {

    $dbConnect = $this->connector->connect();
    $commentModel = new CommentModel($idArticle, $idUser, $content, $dateCreation);
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


    while ($row = $statementGetComments->fetch()) {
      $frenchDateFormat = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

      $date = $frenchDateFormat->format(new DateTime($row["date_publication"]));
      $statement2 = $dbConnect->prepare("SELECT id,username,profile_image FROM user WHERE id = :idUserComment");
      $statement2->bindParam("idUserComment", $row["idUser"]);
      $statement2->execute();
      while ($row2 = $statement2->fetch()) {
        $data = [
          "username" => $row2["username"],
          "profile_image" => $row2["profile_image"],
          "content" => $row['content'],
          "date_of_publication" => ucfirst($date)
        ];
      }
      $comments[] = $data;
    }



    return $comments;
  }
}
