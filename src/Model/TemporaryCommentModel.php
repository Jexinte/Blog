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
    public ?bool $temporaryCommentCreated
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
  public function getApproved() :?bool
  {
    return $this->approved;
  }
  public function getRejected() :?bool
  {
    return $this->rejected;
  }
  public function getFeedbackAdministrator():?string
  {
    return $this->feedbackAdministrator;
  }

  public function getTemporaryCommentCreated():?bool
  {
    return $this->temporaryCommentCreated;
  }
  public function isTemporaryCommentCreated(?bool $temporaryCommentCreated):void
  {
  $this->temporaryCommentCreated = $temporaryCommentCreated;
  }
}
