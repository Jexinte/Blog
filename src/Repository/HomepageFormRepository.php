<?php

/**
 * Handle homepageform functions
 * 
 * PHP version 8
 *
 * @category Repository
 * @package  HomepageFormRepository
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
namespace Repository;

use Config\DatabaseConnection;
use Exceptions\ValidationException;
use Model\HomepageFormModel;
use PHPMailer\PHPMailer\PHPMailer;



/**
 * HomepageFormRepository class
 * 
 * PHP version 8
 *
 * @category Repository
 * @package  HomepageFormRepository
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class HomepageFormRepository
{

    /**
     * Summary of __construct
     *
     * @param \Config\DatabaseConnection $connector 
     * @param string                     $username 
     * @param string                     $password 
     * @param string                     $smtp_address 
     */
    public function __construct(private readonly DatabaseConnection $connector, private readonly string $username, private readonly string $password, private readonly string $smtp_address)
    {
    }

    /**
     * Summary of insertDataInDatabase
     *
     * @param \Model\HomepageFormModel $homepageFormModel 
     * 
     * @return HomepageFormModel
     */
    public function insertDataInDatabase(HomepageFormModel $homepageFormModel): ?HomepageFormModel
    {

        $dbConnect = $this->connector->connect();
        $firstnameFromForm = $homepageFormModel->getFirstname();
        $lastnameFromForm = $homepageFormModel->getLastname();
        $emailFromForm = $homepageFormModel->getEmail();
        $subjectFromForm = $homepageFormModel->getSubject();
        $messageFromForm = $homepageFormModel->getMessage();
        $statementToSaveMessageFromFrom = $dbConnect->prepare("INSERT INTO form_message(firstname,lastname,email,subject,message) VALUES(?,?,?,?,?)");
        $values = [
        $firstnameFromForm,
        $lastnameFromForm,
        $emailFromForm,
        $subjectFromForm,
        $messageFromForm
        ];
        $statementToSaveMessageFromFrom->execute($values);
        $homepageFormModel->isFormDataSaved(true);
        return $homepageFormModel;
    }

    /**
     * Summary of getDataFromDatabase
     *
     * @param object $dataFromModel 
     * 
     * @return array
     */
    public function getDataFromDatabase(object $dataFromModel): ?array
    {

        $dbConnect = $this->connector->connect();

        if ($dataFromModel->getFormDataSaved()) {
            $statementToFormMessageAndDataAssociatedWith = $dbConnect->prepare("SELECT * FROM form_message ORDER BY id DESC LIMIT 1");
            $statementToFormMessageAndDataAssociatedWith->execute();
            $resReq = $statementToFormMessageAndDataAssociatedWith->fetch();
            return [
            "data_retrieved" => 1,
            "user" =>  $resReq
            ];
        }
    }


    /**
     * Summary of sendMailAdmin
     *
     * @param array $data 
     * 
     * @return array<string>
     */
    public function sendMailAdmin(array $data): ?array
    {

        $validationException = new ValidationException();
        $username = json_decode($this->username, true);
        $password = json_decode($this->password, true);
        $gmail = json_decode($this->smtp_address, true);

        $mail = new PHPMailer(true);
        $thereIsContentFromForm = !empty($data);

        if ($thereIsContentFromForm) {
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

            $mail->setFrom($username["username"], 'Message du formulaire de contact');
            $mail->addAddress($username["username"]);
            $mail->addReplyTo($data["user"]["email"], $data["user"]["firstname"]);
            $mail->isHTML();

            $mail->Subject = $data["user"]["subject"];


            $mail->Body = "Cher administrateur, <br><br>
      Un message a été envoyé depuis le formulaire de contact de la part de <strong>{$data["user"]["firstname"]} {$data["user"]["lastname"]}</strong></strong> :  <br><br>
      {$data["user"]["message"]}
      Cordialement,<br><br>
      L'équipe du site";

            $mail->send();
            return ["message_sent" => "Votre message a bien été envoyé !"];
        }

        throw $validationException->setTypeAndValueOfException("message_not_sent_exception", $validationException::MESSAGE_SENT_FAILED);
    }
}
