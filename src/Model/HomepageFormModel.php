<?php

namespace Model;

class HomepageFormModel
{

  public function __construct(
    public null $idUser,
    public string $firstname,
    public string $lastname,
    public string $email,
    public string $subject,
    public string $message,
  ) {
  }

  public function getData()
  {
    return [
      "idUser" => $this->idUser,
      "firstname" => $this->firstname,
      "lastname" => $this->lastname,
      "email" => $this->email,
      "subject" => $this->subject,
      "message" => $this->message
    ];
  }
}
