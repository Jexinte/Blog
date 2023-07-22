<?php

namespace Model;

use Config\DatabaseConnection;
use DateTime;
use IntlDateFormatter;
use Enumeration\UserType;
use Exceptions\FormMessageNotSentException;
use PHPMailer\PHPMailer\PHPMailer;

class TemporaryComment
{

  public array $validation_details;
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

  public function getTemporaryCommentsForAdministrators(array $sessionData): ?array
  {
    $dbConnect = $this->connector->connect();
    $statement = $dbConnect->prepare("SELECT username,user_type FROM session WHERE username = :username AND user_type = :user_type");

    $statement->bindParam("username", $sessionData["username"]);
    $statement->bindParam("user_type", $sessionData["type_user"]);
    $statement->execute();
    $resultSession = $statement->fetch();

    if ($resultSession["user_type"] === UserType::ADMIN->value) {


      $temporaryComments = [];
      $statementTemporaryComment = $dbConnect->prepare("SELECT tc.id,tc.idArticle, tc.idUser, tc.content, DATE_FORMAT(tc.date_creation, '%d %M %Y') AS date_of_publication, a.title FROM temporary_comment tc JOIN article a ON tc.idArticle = a.id ");
      $statementTemporaryComment->execute();


      while ($row = $statementTemporaryComment->fetch()) {
        $frenchDateFormat = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        $date = $frenchDateFormat->format(new DateTime($row["date_of_publication"]));
        $statementUser = $dbConnect->prepare("SELECT id,username FROM user WHERE id = :idUser");
        $statementUser->bindParam("idUser", $row["idUser"]);
        $statementUser->execute();
        while ($row2 = $statementUser->fetch()) {
          $data = [
            "id_article" => $row["idArticle"],
            "id" => $row["id"],
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

  public function mailToAdmin(array $sessionData, string $titleOfArticle): void
  {

    $key = file_get_contents("../config/stmp_credentials.json");
    $key_2 = file_get_contents("../config/stmp_credentials.json");
    $key_3 = file_get_contents("../config/stmp_credentials.json");
    $username = json_decode($key, true);
    $password = json_decode($key_2, true);
    $gmail = json_decode($key_3, true);
    $usernameSession = $sessionData["username"];
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $gmail["smtp_address"];
    $mail->SMTPAuth = true;
    $mail->Username = $username["username"]; // Name of the owner application password
    $mail->Password = $password["password"]; // Gmail Password Application
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->SMTPOptions = array(
      'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
      )
    );

    $mail->setFrom($username["username"],);
    $mail->addAddress($username["username"]);
    $mail->isHTML();

    $mail->Subject = "Nouveau commentaire du sujet $titleOfArticle";

    $mail->Body = "Cher administrateur, <br><br>
      Un nouveau commentaire a été publié sur le site par l'utilisateur <strong>$usernameSession</strong>. <br><br>
      Pour modérer ou répondre à ce commentaire, veuillez vous connecter à l'interface d'administration du site. <br><br>
      Cordialement,<br><br>
      L'équipe du site";
    $mail->send();
  }

  public function getOneTemporaryComment(int $idComment): ?array
  {
    $dbConnect = $this->connector->connect();
    $statement = $dbConnect->prepare("SELECT id,idArticle,idUser,content,DATE_FORMAT(date_creation, '%d %M %Y') AS date_of_publication FROM temporary_comment WHERE id=:idComment");
    $statement->bindParam("idComment", $idComment);
    $statement->execute();
    $result = $statement->fetch();
    if ($result) {
      $statementUser = $dbConnect->prepare("SELECT id,username FROM user WHERE id = :idUser");
      $statementUser->bindParam("idUser", $result["idUser"]);
      $statementUser->execute();
      $resultUser = $statementUser->fetch();
      if ($resultUser)
        $frenchDateFormat = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
      $date = $frenchDateFormat->format(new DateTime($result["date_of_publication"]));
      $result["date_of_publication"] = $date;
      $result["username"] = $resultUser["username"];
      return $result;
    }
  }

  public function validationTemporaryComment(string $valueOfValidation, int $idComment, string $feedback): ?array
  {
    
    $dbConnect = $this->connector->connect();
    switch (true) {
      case str_contains($valueOfValidation, "Accepter"):
        $statement = $dbConnect->prepare("SELECT id,idUser,idArticle FROM temporary_comment WHERE id = :idComment");
        $statement->bindParam("idComment", $idComment);
        $statement->execute();
        $resultIsAccepted = $statement->fetch();
        if ($resultIsAccepted) {
          $feedbackResult = !empty($feedback) ? $feedback : null;
          $statementInsertAcceptedChoice = $dbConnect->prepare("UPDATE temporary_comment SET approved =  :approved , feedback_administrator = :feedback WHERE id = :idComment");
          $statementInsertAcceptedChoice->bindValue("approved", true);
          $statementInsertAcceptedChoice->bindValue("idComment", $idComment);
          $statementInsertAcceptedChoice->bindValue("feedback", $feedbackResult);
          $statementInsertAcceptedChoice->execute();
          return ["approved" => 1, "feedback" => $feedbackResult, "id_user" => $resultIsAccepted["idUser"], "id_comment" => $idComment,"id_article" => $resultIsAccepted["idArticle"]];
        }
        break;

      case str_contains($valueOfValidation, "Rejeter"):
        $statementRejected = $dbConnect->prepare("SELECT id,idUser,idArticle FROM temporary_comment WHERE id = :idComment");
        $statementRejected->bindParam("idComment", $idComment);
        $statementRejected->execute();
        $resultIsRejected = $statementRejected->fetch();
        if ($resultIsRejected) {
          $feedbackResult = !empty($feedback) ? $feedback : null;
          $statementInsertRejectedChoice = $dbConnect->prepare("UPDATE temporary_comment SET rejected =  :rejected , feedback_administrator = :feedback WHERE id = :idComment");
          $statementInsertRejectedChoice->bindValue("rejected", true);
          $statementInsertRejectedChoice->bindValue("idComment", $idComment);
          $statementInsertRejectedChoice->bindValue("feedback", $feedbackResult);
          $statementInsertRejectedChoice->execute();
          return ["rejected" => 1, "feedback" => $feedbackResult, "id_user" => $resultIsRejected["idUser"], "id_comment" => $idComment,"id_article" => $resultIsRejected["idArticle"]];
        }
        break;
    }
  }


  public function insertNotificationUserOfTemporaryComment(array $data): ?array
  {

    $dbConnect = $this->connector->connect();


    switch (true) {
      case array_key_exists("approved", $data):

        $statementAccepted = $dbConnect->prepare("INSERT INTO user_notification (idArticle,idUser,approved,feedback_administrator) VALUES(:idArticle,:idUser,:approved,:feedbackAdministrator)");
        $statementAccepted->bindValue("idArticle", $data["id_article"]);
        $statementAccepted->bindValue("idUser", $data["id_user"]);
        $statementAccepted->bindValue("approved", $data["approved"]);
        $statementAccepted->bindValue("feedbackAdministrator", $data["feedback"]);
        $statementAccepted->execute();
        return ["temporary_comment_approved" => 1, "id_comment" => $data["id_comment"]];

      case array_key_exists("rejected", $data):
        $statementRejected = $dbConnect->prepare("INSERT INTO user_notification (idArticle,idUser,rejected,feedback_administrator) VALUES(:idArticle,:idUser,:rejected,:feedbackAdministrator)");
        $statementRejected->bindValue("idArticle", $data["id_article"]);
        $statementRejected->bindValue("idUser", $data["id_user"]);
        $statementRejected->bindValue("rejected", $data["rejected"]);
        $statementRejected->bindValue("feedbackAdministrator", $data["feedback"]);
        $statementRejected->execute();
        return ["temporary_comment_rejected" => 1, "id_comment" => $data["id_comment"]];
    }
  }
  public function finalValidationOfTemporaryComment(array $sessionData): ?array
  {

    $dbConnect = $this->connector->connect();
    switch (true) {
      case array_key_exists("temporary_comment_approved", $sessionData):
        $statementGetTemporaryComment = $dbConnect->prepare("SELECT id,idArticle,idUser,content,date_creation FROM temporary_comment WHERE id = :idComment");
        $statementGetTemporaryComment->bindParam("idComment", $sessionData["id_comment"]);
        $statementGetTemporaryComment->execute();
        $resultGetTemporaryComment = $statementGetTemporaryComment->fetch();
        if ($resultGetTemporaryComment) {
          $statementInsertFinalComment = $dbConnect->prepare("INSERT INTO comment (idArticle,idUser,content,date_creation) VALUES(:idArticle,:idUser,:content,:date_creation)");
          $statementInsertFinalComment->bindParam("idArticle", $resultGetTemporaryComment["idArticle"]);
          $statementInsertFinalComment->bindParam("idUser", $resultGetTemporaryComment["idUser"]);
          $statementInsertFinalComment->bindParam("content", $resultGetTemporaryComment["content"]);
          $statementInsertFinalComment->bindParam("date_creation", $resultGetTemporaryComment["date_creation"]);
          $statementInsertFinalComment->execute();

          $statementDeleteTemporaryComment = $dbConnect->prepare("DELETE FROM temporary_comment WHERE id = :idComment");
          $statementDeleteTemporaryComment->bindParam("idComment", $sessionData["id_comment"]);
          $statementDeleteTemporaryComment->execute();
          return ["temporary_comment_approved" => 1];
        }
        break;

      case array_key_exists("temporary_comment_rejected", $sessionData):
        $statementGetTemporaryComment = $dbConnect->prepare("SELECT id,idArticle,idUser,content,date_creation FROM temporary_comment WHERE id = :idComment");
        $statementGetTemporaryComment->bindParam("idComment", $sessionData["id_comment"]);
        $statementGetTemporaryComment->execute();
        $resultGetTemporaryComment = $statementGetTemporaryComment->fetch();
        if ($resultGetTemporaryComment) {
          $statementDeleteTemporaryComment = $dbConnect->prepare("DELETE FROM temporary_comment WHERE id = :idComment");
          $statementDeleteTemporaryComment->bindParam("idComment", $sessionData["id_comment"]);
          $statementDeleteTemporaryComment->execute();
          return ["temporary_comment_approved" => 1];
        }
        break;
    }
  }
}
