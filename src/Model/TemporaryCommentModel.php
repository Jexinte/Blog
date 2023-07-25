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



  public function getIdArticle():int
  {
    return $this->idArticle;
  }
  public function getIdUser():int
  {
    return $this->idUser;
  }
  public function getContent():string
  {
    return $this->content;
  }
  public function getDateCreation():string
  {
    return $this->dateCreation;
  }
  public function getApproved() : bool|null
  {
    return $this->approved;
  }
  public function getRejected() : bool|null
  {
    return $this->rejected;
  }
  public function getFeedbackAdministrator():string|null
  {
    return $this->feedbackAdministrator;
  }
}
