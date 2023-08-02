<?php

namespace Model;


class CommentModel
{

  public function __construct(
    public int $idArticle,
    private int $idUser,
    public string $content,
    public string $dateCreation,
    public ?bool $created,
    public string $status
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

  public function getCreated():?bool 
  {
    return $this->created;
  }


  public function isCreated(?bool $created):void
  {
    $this->created = $created;
  }


}
