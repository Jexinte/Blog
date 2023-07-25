<?php

namespace Controller;

use Repository\CommentRepository;

class CommentController
{

  public function __construct(private readonly CommentRepository $comment)
  {
  }

  public function handleGetAllComments(int $idArticle): ?array
  {
    $commentRepository = $this->comment;
    $comments = $commentRepository->getAllComments([],$idArticle);
   return $comments;
  }
}
