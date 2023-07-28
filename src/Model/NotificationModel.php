<?php

namespace Model;

class NotificationModel
{

  public function __construct(
    public int $idArticle,
    public int $idUser,
    public ?bool $approved,
    public ?bool $rejected,
    public string $feedbackAdministrator
  ) {
  }

  public function getIdArticle():int
  {
    return $this->idArticle;
  }
  public function getIdUser():int
  {
    return $this->idUser;
  }
  public function getApproved():null|bool
  {
    return $this->approved;
  }
  public function getRejected():null|bool
  {
    return $this->rejected;
  }
  public function getFeedbackAdministrator():string
  {
    return $this->feedbackAdministrator;
  }
}
