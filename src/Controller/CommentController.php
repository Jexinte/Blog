<?php

namespace Controller;

use DateTime;
use Model\CommentModel;
use Repository\CommentRepository;

class CommentController
{

  public function __construct(private readonly CommentRepository $comment)
  {
  }

  public function handleGetAllComments(int $idArticle): ?array
  {
    $commentRepository = $this->comment;
    $comments = $commentRepository->getAllComments([], $idArticle);
    return $comments;
  }

  public function handleCreateComment(array $commentsDetails, array $sessionData): void
  {
    $commentRepository = $this->comment;

    if (array_key_exists("approved", $commentsDetails)) {
      $new_date_format = DateTime::createFromFormat("d F Y", $commentsDetails["date_creation"]);
      $dateOfCreation = $new_date_format->format("Y-m-d");
      $commentModel = new CommentModel($commentsDetails["id_article"], $commentsDetails["id_user"], $commentsDetails["content"], $dateOfCreation);
      $idArticleInModel = $commentModel->getIdArticle();
      $idUserInModel = $commentModel->getIdUser();
      $contentInModel = $commentModel->getContent();
      $dateCreationInModel = $commentModel->getDateCreation();
      $commentRepository->createComment($idArticleInModel, $idUserInModel, $contentInModel, $dateCreationInModel, $sessionData);
    }
  }
}
