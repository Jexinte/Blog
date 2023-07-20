<?php

namespace Model;

use Config\DatabaseConnection;
use DateTime;
use IntlDateFormatter;
use Enumeration\UserType;
class TemporaryComment
{

  public function __construct(
    private readonly DatabaseConnection  $connector
  ) {
  }

  public function insertTemporaryComment(object $data, array $sessionData): array
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
    if ($result) {
      $statementComment = $dbConnect->prepare("INSERT INTO temporary_comment (idArticle,idUser,content,date_creation,approved,rejected,feedback_administrator) VALUES(:idArticle,:idUser,:content,:date_creation,:approved,:rejected,:feedback_administrator)");

      $statementComment->bindParam("idArticle", $commentsData["id_article"]);
      $statementComment->bindParam("idUser", $commentsData["id_user"]);
      $statementComment->bindParam("content", $commentsData["content"]);
      $statementComment->bindParam("date_creation", $commentsData["date_creation"]);
      $statementComment->bindParam("approved", $commentsData["approved"]);
      $statementComment->bindParam("rejected", $commentsData["rejected"]);
      $statementComment->bindParam("feedback_administrator", $commentsData["feedback_administrator"]);
      $statementComment->execute();
    }
    return ["temporary_comment_saved" => 1];
  }

  public function checkCommentAlreadySentByUser(array $sessionData): ?array
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
    if ($resultSession) {
      $statementTemporaryComments = $dbConnect->prepare("SELECT idArticle,idUser FROM temporary_comment WHERE idUser = :idUser AND idArticle = :idArticle");
      $statementTemporaryComments->bindParam("idUser", $idUserSession);
      $statementTemporaryComments->bindParam("idArticle", $idArticleSession);
      $statementTemporaryComments->execute();


      $userComments = [];
      while ($row = $statementTemporaryComments->fetch()) {
        $userComments[] = $row;
      }



      return count($userComments) == 1 ? ["user_already_commented" => 1] : null;
    }
  }

  public function getTemporaryCommentsForAdministrators(array $sessionData):?array
  {
    $dbConnect = $this->connector->connect();
    $statement = $dbConnect->prepare("SELECT username,user_type FROM session WHERE username = :username AND user_type = :user_type");

    $statement->bindParam("username", $sessionData["username"]);
    $statement->bindParam("user_type", $sessionData["type_user"]);
    $statement->execute();
    $resultSession = $statement->fetch();

    if($resultSession["user_type"] === UserType::ADMIN->value){
    
   
      $temporaryComments = [];
      $statementTemporaryComment = $dbConnect->prepare("SELECT tc.idArticle, tc.idUser, tc.content, DATE_FORMAT(tc.date_creation, '%d %M %Y') AS date_of_publication, a.title FROM temporary_comment tc JOIN article a ON tc.idArticle = a.id ");
      $statementTemporaryComment->execute();

      
      while ($row = $statementTemporaryComment->fetch()) {
        $frenchDateFormat = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        $date = $frenchDateFormat->format(new DateTime($row["date_of_publication"]));
        $statementUser = $dbConnect->prepare("SELECT id,username FROM user WHERE id = :idUser");
        $statementUser->bindParam("idUser",$row["idUser"]);
        $statementUser->execute();
        while($row2 = $statementUser->fetch()){
          $data = [
            "id_article" => $row["idArticle"],
            "id_user" => $row["idUser"],
            "content" => $row["content"],
            "date_creation" => $date,
            "title" => $row["title"],
            "username" => $row2["username"]
          ];
        }
      
          $temporaryComments[] = $data;
      }
      return !empty($temporaryComments) ? $temporaryComments : null;
    }
  }
}
