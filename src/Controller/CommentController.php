<?php

namespace Controller;

use Model\Comment;

class CommentController
{

  public function __construct(private readonly Comment $comment)
  {
  }

  public function handleGetAllComments(int $idArticle): ?array
  {
    $commentRepository = $this->comment;
    return $commentRepository->getAllComments($idArticle);
  }
}
