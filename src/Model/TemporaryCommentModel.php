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

  public function getData(): array
  {
    return ["id_article" => $this->idArticle, "id_user" => $this->idUser, "content" => $this->content, "date_creation" => $this->dateCreation,"approved" => $this->approved,"rejected" => $this->rejected,"feedback_admin" => $this->feedbackAdministrator];
  }
}

