<?php

namespace Model;


class CommentModel
{

  public function __construct(
    public int $idArticle,
    private int $idUser,
    public string $content,
    public string $dateCreation,
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
}
