<?php

namespace Model;

class HomepageFormModel
{

  public function __construct(
    public string $firstname,
    public string $lastname,
    public string $email,
    public string $subject,
    public string $message,
  ) {
  }




  public function getFirstname()
  {
    return $this->firstname;
  }
  public function getLastname()
  {
    return $this->lastname;
  }
  public function getEmail()
  {
    return $this->email;
  }
  public function getSubject()
  {
    return $this->subject;
  }
  public function getMessage()
  {
    return $this->message;
  }
}
