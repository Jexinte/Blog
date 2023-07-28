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
use Repository\HomepageFormRepository;
use Enumeration\Regex;


class HomepageFormController
{

  public function __construct(private readonly HomepageFormRepository $homepageForm)
  {
  }

  public function handleFirstNameField(string $firstname): array|string
  {

    switch (true) {
      case empty($firstname):
        throw new FirstNameErrorEmptyException();
      case !preg_match(REGEX::FIRSTNAME, $firstname):
        throw new FirstNameWrongFormatException();
      default:
        return ["firstname" => $firstname];
    }
  }
  public function handleLastNameField(string $lastname): array|string
  {
    switch (true) {
      case empty($lastname):
        throw new LastnameErrorEmptyException();
      case !preg_match(REGEX::LASTNAME, $lastname):
        throw new LastnameWrongFormatException();
      default:
        return ["lastname" => $lastname];
    }
  }
  public function handleEmailField(string $email): array|string
  {
    switch (true) {
      case empty($email):
        throw new EmailErrorEmptyException();
      case !preg_match(REGEX::EMAIL, $email):
        throw new EmailWrongFormatException();
      default:
        return ["email" => $email];
    }
  }

  public function handleSubjectField(string $subject): array|string
  {
    switch (true) {
      case empty($subject):
        throw new SubjectErrorEmptyException();
      case !preg_match(REGEX::SUBJECT, $subject):

        throw new SubjectWrongFormatException();
      default:
        return ["subject" => $subject];
    }
  }
  public function handleMessageField(string $message): array|string
  {
    switch (true) {
      case empty($message):
        throw new ContentMessageErrorEmptyException();
      case !preg_match(REGEX::FORM_MESSAGE, $message):
        throw new ContentMessageWrongFormatException();
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



    $formRepository = $this->homepageForm;

    $userDataFromForm = new HomepageFormModel($firstnameResult, $lastnameResult, $emailResult, $subjectResult, $messageResult);
    $firstnameInModel = $userDataFromForm->getFirstname();
    $lastnameInModel = $userDataFromForm->getLastname();
    $emailInModel = $userDataFromForm->getEmail();
    $subjectInModel = $userDataFromForm->getSubject();
    $messageInModel = $userDataFromForm->getMessage();


    $insertDataDb = $formRepository->insertDataInDatabase(
      $firstnameInModel,
      $lastnameInModel,
      $emailInModel,
      $subjectInModel,
      $messageInModel
    );
    $getDataFromDb = $formRepository->getDataFromDatabase($insertDataDb);
    return array_key_exists("data_retrieved", $getDataFromDb) && $getDataFromDb["data_retrieved"] == 1 ? $formRepository->sendMailAdmin($getDataFromDb) : null;
  }
}
