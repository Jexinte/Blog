<?php

namespace Controller;

use DateTime;
use Model\CommentModel;
use Repository\CommentRepository;
use Exceptions\ValidationException;
use Enumeration\Regex;

class CommentController
{

  public function __construct(private readonly CommentRepository $commentRepository)
  {
  }

  public function handleCommentField(string $comment): array|string
  {

    $validationException = new ValidationException();
    switch (true) {
      case empty($comment):
        throw $validationException->setTypeAndValueOfException("comment_exception", $validationException::ERROR_EMPTY);
      case !preg_match(REGEX::COMMENT, $comment):
        throw $validationException->setTypeAndValueOfException("comment_exception", $validationException::COMMENT_WRONG_FORMAT_EXCEPTION);
      default:
        return ["comment" => $comment];
    }
  }

  public function handleFeedbackField(string $feedback): ?array
  {
    $validationException = new ValidationException();
    switch (true) {

      case !preg_match(REGEX::FEEDBACK, $feedback):
        throw $validationException->setTypeAndValueOfException("comment_exception", $validationException::EXPLANATION_MESSAGE_ERROR_WRONG_FORMAT);

      default:
        return ["feedback" => $feedback];
    }
  }

  public function handleInsertComment($comment,$sessionData)
  {
    $commentResult = $this->handleCommentField($comment);
    var_dump($sessionData);
  }

}
