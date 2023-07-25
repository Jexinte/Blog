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




  public function getFirstname():string
  {
    return $this->firstname;
  }
  public function getLastname():string
  {
    return $this->lastname;
  }
  public function getEmail():string
  {
    return $this->email;
  }
  public function getSubject():string
  {
    return $this->subject;
  }
  public function getMessage():string
  {
    return $this->message;
  }
}
