<?php

namespace Model;


class CommentModel
{

  public function __construct(
    public int $idArticle,
    private int $idUser,
    public string $comment,
    public string $dateCreation,
    public ?bool $created,

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
  public function getComment():string
  {
    return $this->comment;
  }
  public function getDateCreation():string
  {
    return $this->dateCreation;
  }

  public function getCreated():?bool 
  {
    return $this->created;
  }



  public function isCreated(?bool $created):void
  {
    $this->created = $created;
  }



}
