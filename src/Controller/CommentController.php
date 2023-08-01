<?php

namespace Controller;

use DateTime;
use Model\CommentModel;
use Repository\CommentRepository;

class CommentController
{

  public function __construct(private readonly CommentRepository $commentRepository)
  {
  }

  public function handleGetAllComments(int $idArticle): ?array
  {
    return $this->commentRepository->getAllComments([], $idArticle);
  }

  public function handleCreateComment(array $commentsDetails, array $sessionData): void
  {
    if (array_key_exists("approved", $commentsDetails)) {
      $new_date_format = DateTime::createFromFormat("d F Y", $commentsDetails["date_creation"]);
      $dateOfCreation = $new_date_format->format("Y-m-d");
      $commentModel = new CommentModel($commentsDetails["id_article"], $commentsDetails["id_user"], $commentsDetails["content"], $dateOfCreation);
      $idArticleInModel = $commentModel->getIdArticle();
      $idUserInModel = $commentModel->getIdUser();
      $contentInModel = $commentModel->getContent();
      $dateCreationInModel = $commentModel->getDateCreation();
      $this->commentRepository->createComment($idArticleInModel, $idUserInModel, $contentInModel, $dateCreationInModel, $sessionData);
    }
  }
}
