<?php

namespace Model;


class CommentModel
{

  public function __construct(
    private int $idArticle,
    private int $idUser,
    public string $content,
    public string $dateCreation,
    
  ) {
  }

  public function getData(): array
  {
    return ["id_article" => $this->idArticle, "id_user" => $this->idUser, "content" => $this->content, "date_creation" => $this->dateCreation];
  }
}


