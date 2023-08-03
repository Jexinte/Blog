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


  public function handleTextField(string $keyArray, string $value, string $keyException, object $exception, string $regex, string $emptyException, string $wrongFormatException): string|array
  {
    switch (true) {
      case empty($value):
        throw $exception->setTypeAndValueOfException($keyException, $emptyException);
      case !preg_match($regex, $value):
        throw $exception->setTypeAndValueOfException($keyException, $wrongFormatException);
      default:
        return [$keyArray => $value];
    }
  }


  public function homepageFormValidator(string $firstname, string $lastname, string $email, string $subject, string $message): ?array
  {
    $validationException = new ValidationException();

    $exceptionKeyArray =
      [
        "firstname_field" => "firstname_exception",
        "lastname_field" => "lastname_exception",
        "email_field" => "email_exception",
        "subject_field" => "subject_exception",
        "message_field" => "content_message_exception"
      ];
    $keyArrayWhenAFieldIsTreated =
      [
        "firstname_field" => "firstname",
        "lastname_field" => "lastname",
        "email_field" => "email",
        "subject_field" => "subject",
        "message_field" => "message"
      ];

    $exceptionByField = [
      "error_empty" => $validationException::ERROR_EMPTY,
      "firstname_exception" => $validationException::FIRSTNAME_MESSAGE_ERROR_WRONG_FORMAT,
      "lastname_exception" => $validationException::LASTNAME_MESSAGE_ERROR_WRONG_FORMAT,
      "email_exception" => $validationException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT,
      "subject_exception" => $validationException::SUBJECT_MESSAGE_ERROR_MIN_20_CHARS_MAX_100_CHARS,
      "content_message_exception" => $validationException::CONTENT_MESSAGE_ERROR_MIN_20_CHARS_MAX_500_CHARS,
    ];

    $regexByField = [
      "firstname_regex" => REGEX::FIRSTNAME,
      "lastname_regex" => REGEX::LASTNAME,
      "email_regex" => REGEX::EMAIL,
      "subject_regex" => REGEX::SUBJECT,
      "message_regex" => REGEX::FORM_MESSAGE
    ];


    $firstnameResult = $this->handleTextField($keyArrayWhenAFieldIsTreated["firstname_field"], $firstname, $exceptionKeyArray["firstname_field"], $validationException, $regexByField["firstname_regex"], $exceptionByField["error_empty"], $exceptionByField["firstname_exception"])["firstname"];

    $lastnameResult = $this->handleTextField($keyArrayWhenAFieldIsTreated["lastname_field"], $lastname, $exceptionKeyArray["lastname_field"], $validationException, $regexByField["lastname_regex"], $exceptionByField["error_empty"], $exceptionByField["lastname_exception"])["lastname"];

    $emailResult = $this->handleTextField($keyArrayWhenAFieldIsTreated["email_field"], $email, $exceptionKeyArray["email_field"], $validationException, $regexByField["email_regex"], $exceptionByField["error_empty"], $exceptionByField["email_exception"])["email"];

    $subjectResult = $this->handleTextField($keyArrayWhenAFieldIsTreated["subject_field"], $subject, $exceptionKeyArray["subject_field"], $validationException, $regexByField["subject_regex"], $exceptionByField["error_empty"], $exceptionByField["subject_exception"])["subject"];

    $messageResult = $this->handleTextField($keyArrayWhenAFieldIsTreated["message_field"], $message, $exceptionKeyArray["message_field"], $validationException, $regexByField["message_regex"], $exceptionByField["error_empty"], $exceptionByField["content_message_exception"])["message"];

    $homepageFormModel = new HomepageFormModel($firstnameResult, $lastnameResult, $emailResult, $subjectResult, $messageResult, null);

    $insertDataDb = $this->homepageFormRepository->insertDataInDatabase($homepageFormModel);
    if ($insertDataDb->getFormDataSaved()) {
      $getDataFromDb = $this->homepageFormRepository->getDataFromDatabase($insertDataDb);
      return array_key_exists("data_retrieved", $getDataFromDb) && $getDataFromDb["data_retrieved"] == 1 ? $this->homepageFormRepository->sendMailAdmin($getDataFromDb) : null;
    }
  }
}
