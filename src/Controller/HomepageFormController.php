<?php

namespace Controller;

use Model\HomepageFormModel;
use Model\HomepageForm;
use Exceptions\EmptyFieldException;
use Exceptions\InvalidFieldException;


readonly class HomepageFormController
{

  public function __construct(private HomepageForm $homepageForm)
  {
  }

  public function handleFirstNameField(string $firstname): array|string
  {
    try {
      $firstnameRegex = "/^[A-Z][a-zA-ZÀ-ÖØ-öø-ſ\s'-]*$/";
      if (!empty($firstname)) {
          if (preg_match($firstnameRegex, $firstname)) {
              return ["firstname" => $firstname];
          }
        header('HTTP/1.1 400');
        throw new InvalidFieldException(InvalidFieldException::FIRSTNAME_MESSAGE_ERROR_WRONG_FORMAT);
      }
      header('HTTP/1.1 400');
      throw new EmptyFieldException(EmptyFieldException::FIRSTNAME_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException|EmptyFieldException $e) {
      return $e->getMessage();
    }
  }
  public function handleLastNameField(string $lastname): array|string
  {

    try {
      $lastnameRegex = "/^[A-Z][a-zA-ZÀ-ÖØ-öø-ſ\s'-]*$/";
      if (!empty($lastname)) {
          if (preg_match($lastnameRegex, $lastname)) {
              return ["lastname" => $lastname];
          }
        header('HTTP/1.1 400');
        throw new InvalidFieldException(InvalidFieldException::LASTNAME_MESSAGE_ERROR_WRONG_FORMAT);
      }
      header('HTTP/1.1 400');
      throw new EmptyFieldException(EmptyFieldException::LASTNAME_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException|EmptyFieldException $e) {
      return $e->getMessage();
    }
  }
  public function handleEmailField(string $email): array|string
  {
    try {
      $emailRegex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";

      if (!empty($email)) {
          if (preg_match($emailRegex, $email)) {
              return ["email" => $email];
          }
        header('HTTP/1.1 400');
        throw new InvalidFieldException(InvalidFieldException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT);
      }
      header('HTTP/1.1 400');
      throw new EmptyFieldException(EmptyFieldException::EMAIL_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException|EmptyFieldException $e) {
      return $e->getMessage();
    }
  }

  public function handleSubjectField(string $subject): array|string
  {
    try {
      $subjectRegex = "/^.{20,100}$/";

      if (!empty($subject)) {
          if (preg_match($subjectRegex, $subject)) {
              return ["subject" => $subject];
          }
        header('HTTP/1.1 400');
        throw new InvalidFieldException(InvalidFieldException::SUBJECT_MESSAGE_ERROR_MIN_20_CHARS_MAX_100_CHARS);
      }
      header('HTTP/1.1 400');
      throw new EmptyFieldException(EmptyFieldException::SUBJECT_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException|EmptyFieldException $e) {
      return $e->getMessage();
    }
  }
  public function handleMessageField(string $message): array|string
  {

    try {
      $messageRegex = "/^.{20,500}$/";

      if (!empty($message)) {
          if (preg_match($messageRegex, $message)) {
              return ["message" => $message];
          }
        header('HTTP/1.1 400');
        throw new InvalidFieldException(InvalidFieldException::CONTENT_MESSAGE_ERROR_MIN_20_CHARS_MAX_500_CHARS);
      }
      header('HTTP/1.1 400');
      throw new EmptyFieldException(EmptyFieldException::CONTENT_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException|EmptyFieldException $e) {
      return $e->getMessage();
    }
  }


  public function homepageFormValidator(string $firstname, string $lastname, string $email, string $subject, string $message): ?array
  {

    $firstnameResult = $this->handleFirstNameField($firstname);
    $lastnameResult = $this->handleLastNameField($lastname);
    $emailResult = $this->handleEmailField($email);
    $subjectResult = $this->handleSubjectField($subject);
    $messageResult = $this->handleMessageField($message);
    $counter = 0;

    $fields = [
      "firstname" => $firstnameResult,
      "lastname" => $lastnameResult,
      "email" => $emailResult,
      "subject" => $subjectResult,
      "message" => $messageResult
    ];

    $errors = [];


    foreach ($fields as $key => $v) {
      if (gettype($v) == "string") $errors[$key . "_error"] = $v;
    }

    $formRepository = $this->homepageForm;

    foreach ($fields as $v) {
      if (is_array($v)) {
        $counter++;
      }
    }

    
    if ($counter == 5) {
      $userDataFromForm = new HomepageFormModel(null, $firstnameResult["firstname"], $lastnameResult["lastname"], $emailResult["email"], $subjectResult["subject"], $messageResult["message"]);

      $insertDataDb = $formRepository->insertDataInDatabase($userDataFromForm);
      $getDataFromDb = $formRepository->getDataFromDatabase($insertDataDb);
      return array_key_exists("data_retrieved", $getDataFromDb) && $getDataFromDb["data_retrieved"] == 1 ? $formRepository->sendMailAdmin($getDataFromDb) : null;
    }

    return $errors;
  }
}
