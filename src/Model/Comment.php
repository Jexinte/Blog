<?php

namespace Model;

use Config\DatabaseConnection;
use DateTime;
use IntlDateFormatter;
class Comment
{

  public function __construct(
    private readonly DatabaseConnection  $connector
  ) {
  }

  public function getAllComments(int $idArticle):?array
  {
    $dbConnect = $this->connector->connect();

    $statementGetComments = $dbConnect->prepare("SELECT id,idArticle,idUser,content,DATE_FORMAT(date_creation,'%d %M %Y') AS date_publication  FROM comment WHERE idArticle = :idArticle ORDER BY date_publication DESC ");
    $statementGetComments->bindParam("idArticle",$idArticle);
    $statementGetComments->execute();

    $comments = [];


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
