<?php

namespace Controller;

use Exceptions\ValidationException;

use Model\HomepageFormModel;
use Repository\HomepageFormRepository;
use Enumeration\Regex;


class HomepageFormController
{

  public function __construct(private readonly HomepageFormRepository $homepageFormRepository)
  {
  }

  public function handleFirstNameField(string $firstname): array|string
  {
    $validationException = new ValidationException();
    switch (true) {
      case empty($firstname):
        throw $validationException->setTypeAndValueOfException("firstname_exception", $validationException::ERROR_EMPTY);
      case !preg_match(REGEX::FIRSTNAME, $firstname):
        throw $validationException->setTypeAndValueOfException("firstname_exception", $validationException::FIRSTNAME_MESSAGE_ERROR_WRONG_FORMAT);
      default:
        return ["firstname" => $firstname];
    }
  }
  public function handleLastNameField(string $lastname): array|string
  {
    $validationException = new ValidationException();
    switch (true) {
      case empty($lastname):
        throw $validationException->setTypeAndValueOfException("lastname_exception", $validationException::ERROR_EMPTY);
      case !preg_match(REGEX::LASTNAME, $lastname):
        throw $validationException->setTypeAndValueOfException("lastname_exception", $validationException::LASTNAME_MESSAGE_ERROR_WRONG_FORMAT);
      default:
        return ["lastname" => $lastname];
    }
  }
  public function handleEmailField(string $email): array|string
  {
    $validationException = new ValidationException();
    switch (true) {
      case empty($email):
        throw $validationException->setTypeAndValueOfException("email_exception", $validationException::ERROR_EMPTY);
      case !preg_match(REGEX::EMAIL, $email):
        throw $validationException->setTypeAndValueOfException("email_exception", $validationException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT);
      default:
        return ["email" => $email];
    }
  }

  public function handleSubjectField(string $subject): array|string
  {
    $validationException = new ValidationException();
    switch (true) {
      case empty($subject):
        throw $validationException->setTypeAndValueOfException("subject_exception", $validationException::ERROR_EMPTY);
      case !preg_match(REGEX::SUBJECT, $subject):
        throw $validationException->setTypeAndValueOfException("subject_exception", $validationException::SUBJECT_MESSAGE_ERROR_MIN_20_CHARS_MAX_100_CHARS);
      default:
        return ["subject" => $subject];
    }
  }
  public function handleMessageField(string $message): array|string
  {
    $validationException = new ValidationException();
    switch (true) {
      case empty($message):
        throw $validationException->setTypeAndValueOfException("content_message_exception", $validationException::ERROR_EMPTY);
      case !preg_match(REGEX::FORM_MESSAGE, $message):
        throw $validationException->setTypeAndValueOfException("content_message_exception", $validationException::CONTENT_MESSAGE_ERROR_MIN_20_CHARS_MAX_500_CHARS);
      default:
        return ["message" => $message];
    }
  }


  public function homepageFormValidator(string $firstname, string $lastname, string $email, string $subject, string $message): ?array
  {

    $firstnameResult = $this->handleFirstNameField($firstname)["firstname"];
    $lastnameResult = $this->handleLastNameField($lastname)["lastname"];
    $emailResult = $this->handleEmailField($email)["email"];
    $subjectResult = $this->handleSubjectField($subject)["subject"];
    $messageResult = $this->handleMessageField($message)["message"];

    $homepageFormModel = new HomepageFormModel($firstnameResult, $lastnameResult, $emailResult, $subjectResult, $messageResult, null);
    
    $insertDataDb = $this->homepageFormRepository->insertDataInDatabase($homepageFormModel);
    if ($insertDataDb->getFormDataSaved()) {
      $getDataFromDb = $this->homepageFormRepository->getDataFromDatabase($insertDataDb);
      return array_key_exists("data_retrieved", $getDataFromDb) && $getDataFromDb["data_retrieved"] == 1 ? $this->homepageFormRepository->sendMailAdmin($getDataFromDb) : null;
    }
  }
}
