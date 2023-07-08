<?php

namespace Controller;

use Model\HomepageFormModel;
use Model\HomepageForm;
use Exceptions\EmptyFieldException;
use Exceptions\InvalidFieldException;


class HomepageFormController
{

  public function __construct(private readonly HomepageForm $homepageForm)
  {
  }

  public function handleFirstNameField(string $firstname): array|string
  {
    try {
      $firstname_regex = "/^[A-Z][a-zA-ZÀ-ÖØ-öø-ſ\s'-]*$/";
      if (!empty($firstname)) {
        switch (true) {
          case preg_match($firstname_regex, $firstname):
            return ["firstname" => $firstname];
        }
        header('HTTP/1.1 400');
        throw new InvalidFieldException(InvalidFieldException::FIRSTNAME_MESSAGE_ERROR_WRONG_FORMAT);
      }
      header('HTTP/1.1 400');
      throw new EmptyFieldException(EmptyFieldException::FIRSTNAME_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException $e) {
      return $e->getMessage();
    } catch (EmptyFieldException $e) {
      return $e->getMessage();
    }
  }
  public function handleLastNameField(string $lastname): array|string
  {

    try {
      $lastname_regex = "/^[A-Z][a-zA-ZÀ-ÖØ-öø-ſ\s'-]*$/";
      if (!empty($lastname)) {
        switch (true) {
          case preg_match($lastname_regex, $lastname):
            return ["lastname" => $lastname];
        }
        header('HTTP/1.1 400');
        throw new InvalidFieldException(InvalidFieldException::LASTNAME_MESSAGE_ERROR_WRONG_FORMAT);
      }
      header('HTTP/1.1 400');
      throw new EmptyFieldException(EmptyFieldException::LASTNAME_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException $e) {
      return $e->getMessage();
    } catch (EmptyFieldException $e) {
      return $e->getMessage();
    }
  }
  public function handleEmailField(string $email): array|string
  {
    try {
      $email_regex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";

      if (!empty($email)) {
        switch (true) {
          case preg_match($email_regex, $email):
            return ["email" => $email];
        }
        header('HTTP/1.1 400');
        throw new InvalidFieldException(InvalidFieldException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT);
      }
      header('HTTP/1.1 400');
      throw new EmptyFieldException(EmptyFieldException::EMAIL_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException $e) {
      return $e->getMessage();
    } catch (EmptyFieldException $e) {
      return $e->getMessage();
    }
  }

  public function handleSubjectField(string $subject): array|string
  {
    try {
      $subject_regex = "/^.{20,100}$/";

      if (!empty($subject)) {
        switch (true) {
          case preg_match($subject_regex, $subject):
            return ["subject" => $subject];
        }
        header('HTTP/1.1 400');
        throw new InvalidFieldException(InvalidFieldException::SUBJECT_MESSAGE_ERROR_MIN_20_CHARS_MAX_100_CHARS);
      }
      header('HTTP/1.1 400');
      throw new EmptyFieldException(EmptyFieldException::SUBJECT_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException $e) {
      return $e->getMessage();
    } catch (EmptyFieldException $e) {
      return $e->getMessage();
    }
  }
  public function handleMessageField(string $message): array|string
  {
    try {
      $message_regex = "/^.{50,500}$/";

      if (!empty($message)) {
        switch (true) {
          case preg_match($message_regex, $message):
            return ["message" => $message];
        }
        header('HTTP/1.1 400');
        throw new InvalidFieldException(InvalidFieldException::CONTENT_MESSAGE_ERROR_MIN_50_CHARS_MAX_500_CHARS);
      }
      header('HTTP/1.1 400');
      throw new EmptyFieldException(EmptyFieldException::CONTENT_MESSAGE_ERROR_EMPTY);
    } catch (InvalidFieldException $e) {
      return $e->getMessage();
    } catch (EmptyFieldException $e) {
      return $e->getMessage();
    }
  }


  public function homepageFormValidator( int $id_user = null,string $firstname, string $lastname, string $email, string $subject, string $message): ?array
  {
    $id_user_result = !is_null($id_user);
    $firstname_result = $this->handleFirstNameField($firstname);
    $lastname_result = $this->handleLastNameField($lastname);
    $email_result = $this->handleEmailField($email);
    $subject_result = $this->handleSubjectField($subject);
    $message_result = $this->handleMessageField($message);
    $counter = 0;

    $fields = [
      "firstname" => $firstname_result,
      "lastname" => $lastname_result,
      "email" => $email_result,
      "subject" => $subject_result,
      "message" => $message_result
    ];

    $errors = [];


    foreach ($fields as $key => $v) {
      if (gettype($v) == "string") $errors[$key . "_error"] = $v;
    }

    $formRepository = $this->homepageForm;

    foreach ($fields as $key => $v) {
      if (is_array($v)) $counter++;
    }
    if ($counter == 5) {
      $user_data_from_form = new HomepageFormModel($id_user_result ?? null, $firstname_result["firstname"], $lastname_result["lastname"], $email_result["email"], $subject_result["subject"], $message_result["message"]);
      $insert_data_db = $formRepository->insertDataInDatabase($user_data_from_form);
      $get_data_from_db = $formRepository->getDataFromDatabase($insert_data_db);
      return array_key_exists("data_retrieved", $get_data_from_db) && $get_data_from_db["data_retrieved"] == 1 ? $formRepository->sendMailAdmin($get_data_from_db) : null;
    }

    return $errors;
  }
}
