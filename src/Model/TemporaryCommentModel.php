<?php

namespace Model;


class TemporaryCommentModel
{

  public function __construct(
    private int $idArticle,
    private int $idUser,
    public string $content,
    public string $dateCreation,
    public ?bool $approved,
    public ?bool $rejected,
    private ?string $feedbackAdministrator,
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
  public function getContent()
  {
    return $this->content;
  }
  public function getDateCreation()
  {
    return $this->dateCreation;
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
