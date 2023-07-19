<?php

namespace Controller;

use Exceptions\ContentMessageWrongFormatException;
use Exceptions\ContentMessageErrorEmptyException;
use Exceptions\SubjectErrorEmptyException;
use Exceptions\SubjectWrongFormatException;
use Exceptions\EmailErrorEmptyException;
use Exceptions\EmailWrongFormatException;
use Exceptions\LastnameErrorEmptyException;
use Exceptions\LastnameWrongFormatException;
use Exceptions\FirstNameErrorEmptyException;
use Exceptions\FirstNameWrongFormatException;


use Model\HomepageFormModel;
use Model\HomepageForm;



readonly class HomepageFormController
{

  public function __construct(private HomepageForm $homepageForm)
  {
  }

  public function handleFirstNameField(string $firstname): array|string
  {
    $firstnameRegex = "/^[A-Z][a-zA-ZÀ-ÖØ-öø-ſ\s'-]*$/";
    switch (true) {
      case empty($firstname):
        throw new FirstNameErrorEmptyException();
      case !preg_match($firstnameRegex, $firstname):
        throw new FirstNameWrongFormatException();
      default:
        return ["firstname" => $firstname];
    }
  }
  public function handleLastNameField(string $lastname): array|string
  {
    $lastnameRegex = "/^[A-Z][a-zA-ZÀ-ÖØ-öø-ſ\s'-]*$/";
    switch (true) {
      case empty($lastname):
        throw new LastnameErrorEmptyException();
      case !preg_match($lastnameRegex, $lastname):
        throw new LastnameWrongFormatException();
      default:
        return ["lastname" => $lastname];
    }
  }
  public function handleEmailField(string $email): array|string
  {
    $emailRegex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";
    switch (true) {
      case empty($email):
        throw new EmailErrorEmptyException();
      case !preg_match($emailRegex, $email):
        throw new EmailWrongFormatException();
      default:
        return ["email" => $email];
    }
  }

  public function handleSubjectField(string $subject): array|string
  {
    $subjectRegex = "/^.{20,100}$/";
    switch (true) {
      case empty($subject):
        throw new SubjectErrorEmptyException();
      case !preg_match($subjectRegex, $subject):

        throw new SubjectWrongFormatException();
      default:
        return ["subject" => $subject];
    }
  }
  public function handleMessageField(string $message): array|string
  {
    $messageRegex = "/^.{20,500}$/";
    switch (true) {
      case empty($message):
        throw new ContentMessageErrorEmptyException();
      case !preg_match($messageRegex, $message):
        throw new ContentMessageWrongFormatException();
      default:
        return ["message" => $message];
    }
  }


  public function homepageFormValidator(string $firstname, string $lastname, string $email, string $subject, string $message): ?array
  {

    $firstnameResult = $this->handleFirstNameField($firstname);
    $lastnameResult = $this->handleLastNameField($lastname);
    $emailResult = $this->handleEmailField($email);
    $subjectResult = $this->handleSubjectField($subject);
    $messageResult = $this->handleMessageField($message);



    $formRepository = $this->homepageForm;

    $userDataFromForm = new HomepageFormModel(null, $firstnameResult["firstname"], $lastnameResult["lastname"], $emailResult["email"], $subjectResult["subject"], $messageResult["message"]);

    $insertDataDb = $formRepository->insertDataInDatabase($userDataFromForm);
    $getDataFromDb = $formRepository->getDataFromDatabase($insertDataDb);
    return array_key_exists("data_retrieved", $getDataFromDb) && $getDataFromDb["data_retrieved"] == 1 ? $formRepository->sendMailAdmin($getDataFromDb) : null;
  }
}
