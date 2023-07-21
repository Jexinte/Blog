<?php

namespace Controller;

use Exceptions\CommentEmptyException;
use Exceptions\CommentWrongFormatException;
use Exceptions\ValidationErrorEmptyException;
use Exceptions\ValidationErrorWrongFormatException;
use Model\TemporaryComment;
use Model\TemporaryCommentModel;

class TemporaryCommentController
{

  public function __construct(private readonly TemporaryComment $temporaryComment)
  {
  }


  public function handleCommentField(string $comment): array|string
  {
    $commentRegex = "/^[A-ZÀ-ÿ][A-ZÀ-ÿa-zÀ-ÿ0-9\s\-\_\!\@\#\$\%\&\'\(\)\*\+\,\.\:\/\;\=\?\[\]\^\`\{\|\}\~]{0,498}[A-ZÀ-ÿa-zÀ-ÿ0-9\s\-\_\!\@\#\$\%\&\'\(\)\*\+\,\.\:\/\;\=\?\[\]\^\`\{\|\}\~]$/";

    switch (true) {
      case empty($comment):
        throw new CommentEmptyException();
      case !preg_match($commentRegex, $comment):
        throw new CommentWrongFormatException();
      default:
        return ["comment" => $comment];
    }
  }

  public function handleFeedbackField($feedback)
  {
    $feedbackRegex = "/^[A-ZÀ-ÿa-zÀ-ÿ0-9\s\-_\!\@\#\$\%\&\'\(\)\*\+\,\.\:\/\;\=\?\[\]\^\`\{\|\}\~]{0,500}$/";
    switch (true) {

      case !preg_match($feedbackRegex, $feedback):
        throw new ValidationErrorWrongFormatException();
      default:
        return ["feedback" => $feedback];
    }
  }
  public function handleInsertTemporaryCommentValidator(string $comment, int $idArticle, array $sessionData): array
  {

    $temporaryCommentRepository = $this->temporaryComment;
    $dateDay =  date('Y-m-d');
    $commentResult = $this->handleCommentField($comment)["comment"];
    $commentsData = new TemporaryCommentModel($idArticle, $sessionData["id_user"], $commentResult, $dateDay, null, null, null);
    return $temporaryCommentRepository->insertTemporaryComment($commentsData, $sessionData);
  }

  public function handlecheckCommentAlreadySentByUser(array $sessionData): ?array
  {
    $temporaryCommentRepository = $this->temporaryComment;
    return $temporaryCommentRepository->checkCommentAlreadySentByUser($sessionData);
  }

  public function handlegetTemporaryCommentsForAdministrators(array $sessionData): ?array
  {
    $temporaryCommentRepository = $this->temporaryComment;
    return $temporaryCommentRepository->getTemporaryCommentsForAdministrators($sessionData);
  }

  public function handleMailToAdmin(array $sessionData, string $titleOfArticle): void
  {
    $temporaryCommentRepository = $this->temporaryComment;
    $temporaryCommentRepository->mailToAdmin($sessionData, $titleOfArticle);
  }

  public function handleGetOneTemporaryComment(int $idComment): ?array
  {
    $temporaryCommentRepository = $this->temporaryComment;
    return $temporaryCommentRepository->getOneTemporaryComment($idComment);
  }

  public function handleValidationTemporaryComment(string $typeValidation, int $idComment, string $feedback): ?array
  {

    $feedbackResult = $this->handleFeedbackField($feedback)["feedback"];
    $temporaryCommentRepository = $this->temporaryComment;
    return $temporaryCommentRepository->ValidationTemporaryComment($typeValidation, $idComment, $feedbackResult);
  }

  public function handleinsertNotificationUserOfTemporaryComment(array $data): ?array
  {
    $temporaryCommentRepository = $this->temporaryComment;
    return $temporaryCommentRepository->insertNotificationUserOfTemporaryComment($data);
  }

  public function handleFinalValidationOfTemporaryComment(array $data): ?array
  {
    $temporaryCommentRepository = $this->temporaryComment;
    return $temporaryCommentRepository->finalValidationOfTemporaryComment($data);
  }
}
