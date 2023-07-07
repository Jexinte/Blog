<?php

namespace Model;

use Config\DatabaseConnection;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require "../vendor/phpmailer/phpmailer/src/Exception.php";
require "../vendor/phpmailer/phpmailer/src/PHPMailer.php";
require "../vendor/phpmailer/phpmailer/src/SMTP.php";

class HomepageForm
{

  public function __construct(private DatabaseConnection $connector)
  {
  }

  public function insertDataInDatabase(object $form_data): array
  {

    $dbConnect = $this->connector->connect();
    $data = $form_data->getData();
    $firstname = $data["firstname"];
    $lastname = $data["lastname"];
    $email = $data["email"];
    $subject = $data["subject"];
    $message = $data["message"];

    $statement = $dbConnect->prepare("INSERT INTO form_messages(idUser,firstname,lastname,email,subject,message) VALUES(?,?,?,?,?,?)");
    $values = [
      null,
      $firstname,
      $lastname,
      $email,
      $subject,
      $message
    ];
    $statement->execute($values);
    header("HTTP/1.1 201");
    return ["data_saved" => 1];
  }

  public function getDataFromDatabase(array $arr): ?array
  {

    $dbConnect = $this->connector->connect();
    $result = in_array(1, $arr);

    if ($result) {
      $statement = $dbConnect->prepare("SELECT * FROM form_messages ORDER BY id DESC LIMIT 1");
      $statement->execute();
      $res_req = $statement->fetch();
      header("HTTP/1.1 200");
      return [
        "data_retrieved" => 1,
        "db_data" =>  $res_req
      ];
    }
  }

  public function sendMailAdmin(array $data):?array
  {

    try {
      $mail = new PHPMailer(true);

      if (!empty($data)) {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = ''; // Name of the owner application password
        $mail->Password = ""; // Gmail Password Application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;;
        $mail->Port = 465;
        $mail->SMTPOptions = array(
          'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
          )
        );
        $mail->setFrom("mdembelepro@gmail.com");
        $mail->addAddress("mdembelepro@gmail.com");
        $mail->isHTML(true);

        $mail->Subject = $data["db_data"]["subject"]; 
        $mail->Body = "Le message suivant a été envoyé par <strong>" . $data["db_data"]["firstname"] . " " . $data["db_data"]["lastname"] . "</strong> via le formulaire de contact  : <br><br><br>" . $data["db_data"]["message"];

        $mail->send();
        header("HTTP/1.1 200");
        return ["message_sent" => "Votre message a bien été envoyé !"];
      }

    } catch (Exception $e) {
      header("HTTP/1.1 500");
      return  ["message_sent_failed" => "Votre message n'a pu être envoyé , veuillez réessayez plus tard !"];
    }
  }
}
