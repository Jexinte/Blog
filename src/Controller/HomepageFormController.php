<?php

namespace Controller;

use Model\HomepageFormModel;
use Model\HomepageForm;
use Exceptions\UserException;

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
          case preg_match($firstname_regex, $firstname) == 1:
            $this->data_form["firstname"] = $firstname;
            return null;
          case !preg_match($firstname_regex, $firstname):
            throw new UserException(UserException::FIRSTNAME_MESSAGE_ERROR_WRONG_FORMAT);
        }
      } else {
        throw new UserException(UserException::FIRSTNAME_MESSAGE_ERROR_EMPTY);
      }
    } catch (UserException $e) {
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
          case !preg_match($lastname_regex, $lastname):
            throw new UserException(UserException::LASTNAME_MESSAGE_ERROR_WRONG_FORMAT);
        }
      } else {
        throw new UserException(UserException::LASTNAME_MESSAGE_ERROR_EMPTY);
      }
    } catch (UserException $e) {
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
          case !preg_match($email_regex, $email):
            throw new UserException(UserException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT);
        }
      } else {
        throw new UserException(UserException::EMAIL_MESSAGE_ERROR_EMPTY);
      }
    } catch (UserException $e) {
      return $e->getMessage();
    }
  }
  public function handleSubjectField(string $subject): ?string
  {
    try {
      $subject_regex = "/^.{20,100}$/";

      if (!empty($subject)) {
        switch (true) {
          case preg_match($subject_regex, $subject) == 1 && strlen($subject) >= 20 && strlen($subject) <= 100:
            $this->data_form["subject"] = $subject;
            return null;
          case !preg_match($subject_regex, $subject) && strlen($subject) < 50:
            throw new UserException(UserException::SUBJECT_MESSAGE_ERROR_LESS_THAN_20);

          case !preg_match($subject_regex, $subject) && strlen($subject) > 100:
            throw new UserException(UserException::SUBJECT_MESSAGE_ERROR_ABOVE_THAN_100);
        }
      } else {
        throw new UserException(UserException::SUBJECT_MESSAGE_ERROR_EMPTY);
      }
    } catch (UserException $e) {
      return $e->getMessage();
    }
  }
  public function handleMessageField(string $message): ?string
  {
    try {
      $message_regex = "/^.{50,500}$/";

      if (!empty($message)) {
        switch (true) {
          case preg_match($message_regex, $message) == 1 && strlen($message) >= 50 && strlen($message) <= 500:
            $this->data_form["message"] = $message;
            return null;
          case !preg_match($message_regex, $message) && strlen($message) < 50:
            throw new UserException(UserException::CONTENT_MESSAGE_ERROR_LESS_THAN_50);

          case !preg_match($message_regex, $message) && strlen($message) > 500:
            throw new UserException(UserException::CONTENT_MESSAGE_ERROR_ABOVE_THAN_500);
        }
      } else {
        throw new UserException(UserException::CONTENT_MESSAGE_ERROR_EMPTY);
      }
    } catch (UserException $e) {
      return $e->getMessage();
    }
  }




  public function homepageFormHandler()
  {


    $formRepository = $this->homepageForm;
    //TODO Find a way to get rid off the cyclomaticity
    if (isset($this->data_form)) {
      if (count($this->data_form) == 5) {
        $user_data_from_form = new HomepageFormModel(null, $this->data_form["firstname"], $this->data_form["lastname"], $this->data_form["email"], $this->data_form["subject"], $this->data_form["message"]);
        $insert_data_db = $formRepository->insertDataInDatabase($user_data_from_form);
        $get_data_from_db = $formRepository->getDataFromDatabase($insert_data_db);

        switch (true) {
          case array_key_exists("data_retrieved", $get_data_from_db):
            if ($get_data_from_db["data_retrieved"] == 1) {
              $send_mail_to_admin = $formRepository->sendMailAdmin($get_data_from_db);
              if (array_key_exists("message_sent", $send_mail_to_admin) && in_array(1, $send_mail_to_admin)) return   ["message_sent" => "Votre message a bien été envoyé !"];
              else
                return ["message_failed" => "Votre message n'a pu être envoyé ! Merci de réessayez plus tard !"];
            }
        }
      }
    }
  }
  // Message form "Merci pour votre intérêt et votre soutien continu !"

}
