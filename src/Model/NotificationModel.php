<?php

namespace Model;

class NotificationModel
{

  public function __construct(
    public int $idArticle,
    public int $idUser,
    public ?bool $status,
    public string $feedbackAdministrator
  ) {
  }

  public function getIdArticle(): int
  {
    return $this->idArticle;
  }
  public function getIdUser(): int
  {
    return $this->idUser;
  }
  public function getStatus():?bool
  {
    return $this->status;
  }
 
  public function getFeedbackAdministrator(): string
  {
    return $this->feedbackAdministrator;
  }
}
