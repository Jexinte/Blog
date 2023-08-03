<?php

namespace Controller;

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

  public function handleGetAllComments(int $idArticle): ?array
  {
    return $this->commentRepository->getAllComments([], $idArticle);;
  }

  public function handleInsertComment(string $comment, array $sessionData, string $idInCookie): ?bool
  {
    $commentResult = $this->handleCommentField($comment);
    $dateOfCreation =  date('Y-m-d');
    $commentModel = new CommentModel($sessionData["id_article"], $sessionData["id_user"], $commentResult["comment"], $dateOfCreation, null);

    $commentCreation = $this->commentRepository->insertComment($commentModel, $sessionData, $idInCookie);
    if ($commentCreation->getCreated()) {
      return $commentCreation->getCreated();
    }
  }

  public function handleMailToAdmin(array $sessionData, string $titleOfArticle, string $idInCookie): void
  {
    $this->commentRepository->mailToAdmin($sessionData, $titleOfArticle, $idInCookie);
  }

  public function handleCheckCommentAlreadySentByUser(array $sessionData, string $idInCookie): ?array
  {
    return $this->commentRepository->checkCommentAlreadySentByUser($sessionData, $idInCookie);
  }

  public function handleGetCommentsNotValidateByAdministrators(array $sessionData, string $idInCookie): ?array
  {
    return $this->commentRepository->getCommentsNotValidateByAdministrators($sessionData, $idInCookie);
  }

  public function handleGetOneComment(int $idComment, array $session, string $idInCookie): ?array
  {
    return $this->commentRepository->getOneComment($idComment, $session, $idInCookie);
  }

  public function handleValidationComment(string $valueOfValidation, int $idComment, string $feedback, array $session, string $idInCookie): ?array
  {
    $feedbackResult = $this->handleFeedbackField($feedback)["feedback"];
    return $this->commentRepository->validationComment($valueOfValidation, $idComment, $feedbackResult, $session, $idInCookie);
  }

  public function handleDeleteComment(array $validation, array $session, string $idInCookie): void
  {
    if (!$validation["status"]) {
      $this->commentRepository->deleteTemporaryComment($validation, $session, $idInCookie);
    }
  }
}
