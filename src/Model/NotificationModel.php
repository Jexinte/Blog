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

  public function getIdArticle()
  {
    return $this->idArticle;
  }
  public function getIdUser()
  {
    return $this->idUser;
  }
  public function getApproved()
  {
    return $this->approved;
  }
  public function getRejected()
  {
    return $this->rejected;
  }
  public function getFeedbackAdministrator()
  {
    return $this->feedbackAdministrator;
  }
}
