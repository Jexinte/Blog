<?php

namespace Controller;

use Model\HomepageFormModel;
use Model\HomepageForm;
use Exceptions\EmptyFieldException;
use Exceptions\InvalidFieldException;


class HomepageFormController
{

  public array $data_form;

  public function __construct(private readonly HomepageForm $homepageForm)
  {
  }

  public function handleFirstNameField(string $firstname): ?string
  {
    try {
      $firstname_regex = "/^[A-Z][a-zA-ZÀ-ÖØ-öø-ſ\s'-]*$/";
      if (!empty($firstname)) {
        switch (true) {
          case preg_match($firstname_regex, $firstname):
            $this->data_form["firstname"] = $firstname;
            return null;
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
  public function handleLastNameField(string $lastname): ?string
  {

    try {
      $lastname_regex = "/^[A-Z][a-zA-ZÀ-ÖØ-öø-ſ\s'-]*$/";
      if (!empty($lastname)) {
        switch (true) {
          case preg_match($lastname_regex, $lastname) == 1:
            $this->data_form["lastname"] = $lastname;
            return null;
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
  public function handleEmailField(string $email): ?string
  {
    try {
      $email_regex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";

      if (!empty($email)) {
        switch (true) {
          case preg_match($email_regex, $email) == 1:
            $this->data_form["email"] = $email;
            return null;
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

  public function handleSubjectField(string $subject): ?string
  {
    try {
      $subject_regex = "/^.{20,100}$/";

      if (!empty($subject)) {
        switch (true) {
          case preg_match($subject_regex, $subject):
            $this->data_form["subject"] = $subject;
            return null;
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
  public function handleMessageField(string $message): ?string
  {
    try {
      $message_regex = "/^.{50,500}$/";

      if (!empty($message)) {
        switch (true) {
          case preg_match($message_regex, $message):
            $this->data_form["message"] = $message;
            return null;
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


  public function homepageFormHandler(): ?array
  {
    $formRepository = $this->homepageForm;
    if (isset($this->data_form)) {
      if (count($this->data_form) == 5) {
        $user_data_from_form = new HomepageFormModel(null, $this->data_form["firstname"], $this->data_form["lastname"], $this->data_form["email"], $this->data_form["subject"], $this->data_form["message"]);
        $insert_data_db = $formRepository->insertDataInDatabase($user_data_from_form);
        $get_data_from_db = $formRepository->getDataFromDatabase($insert_data_db);
        return array_key_exists("data_retrieved", $get_data_from_db) && $get_data_from_db["data_retrieved"] == 1 ? $formRepository->sendMailAdmin($get_data_from_db) : null;
      }
    }
  }
}
