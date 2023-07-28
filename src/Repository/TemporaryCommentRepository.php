<?php

namespace Repository;

use Config\DatabaseConnection;
use DateTime;
use IntlDateFormatter;
use Enumeration\UserType;
use PHPMailer\PHPMailer\PHPMailer;

class TemporaryCommentRepository
{

  public array $validation_details;
  public function __construct(
    private readonly DatabaseConnection  $connector,
    private readonly string $username,
    private readonly string $password,
    private readonly string $smtp_address
  ) {
  }

  public function insertTemporaryComment(int $idArticle, int $idUser, string $temporaryComment, string $date, null $approved, null $rejected, null $feedback, array $sessionData): null
  {

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

      $statementComment->bindParam("idArticle", $idArticle);
      $statementComment->bindParam("idUser", $idUser);
      $statementComment->bindParam("content", $temporaryComment);
      $statementComment->bindParam("date_creation", $date);
      $statementComment->bindParam("approved", $approved);
      $statementComment->bindParam("rejected", $rejected);
      $statementComment->bindParam("feedback_administrator", $feedback);
      $statementComment->execute();
    }
    return null;
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



    $username = json_decode($this->username, true);
    $password = json_decode($this->password, true);
    $gmail = json_decode($this->smtp_address, true);
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
      Pour modérer ou répondre à ce commentaire, veuillez vous connecter à l'interface d'administration du site : http://localhost/P5_Créez%20votre%20premier%20blog%20en%20PHP%20-%20Dembele%20Mamadou/public/index.php?selection=sign_in. <br><br>
      Cordialement,<br><br>
      L'équipe du site";
    $mail->send();
  }

  public function getOneTemporaryComment(int $idComment): ?array
  {
    $dbConnect = $this->connector->connect();
    $statement = $dbConnect->prepare("SELECT tc.id, tc.idUser ,idArticle,content,DATE_FORMAT(date_creation, '%d %M %Y') AS date_of_publication, u.username FROM temporary_comment AS tc JOIN user u ON tc.idUser = u.id WHERE tc.id = :idComment");
    $statement->bindParam("idComment", $idComment);
    $statement->execute();
    $thereIsComment = $statement->fetch();
    if ($thereIsComment) {
      return $thereIsComment;
    }
    return null;
  }

  public function validationTemporaryComment(string $valueOfValidation, int $idComment, string $feedback): ?array
  {

    $dbConnect = $this->connector->connect();
    switch (true) {
      case str_contains($valueOfValidation, "Accepter"):
        $statement = $dbConnect->prepare("SELECT id,idUser,idArticle,content,DATE_FORMAT(date_creation, '%d %M %Y') AS date_of_publication FROM temporary_comment WHERE id = :idComment");
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
          return ["approved" => 1, "id_comment" => $resultIsAccepted["id"], "id_user" => $resultIsAccepted["idUser"], "id_article" => $resultIsAccepted["idArticle"], "content" => $resultIsAccepted["content"], "date_creation" => $resultIsAccepted["date_of_publication"]];
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
          return ["rejected" => 1, "id_comment" => $resultIsRejected["id"]];
        }
        break;
    }
  }

  public function deleteTemporaryComment(array $rejectedValidation): void
  {
    $dbConnect = $this->connector->connect();
    $statementDeleteTemporaryComment = $dbConnect->prepare("DELETE FROM temporary_comment WHERE id = :idComment");
    $statementDeleteTemporaryComment->bindParam("idComment", $rejectedValidation["id_comment"]);
    $statementDeleteTemporaryComment->execute();
  }
}
