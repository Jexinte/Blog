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


  public function handleTextField(string $keyArray, ?string $value, string $keyException, object $exception, string $regex, string $emptyException, string $wrongFormatException): string|array|null
  {


    switch (true) {
      case is_null($value):
        return [$keyArray => $value];
      case empty($value):
        throw $exception->setTypeAndValueOfException($keyException, $emptyException);
      case !preg_match($regex, $value):
        throw $exception->setTypeAndValueOfException($keyException, $wrongFormatException);
      default:
        return [$keyArray => $value];
    }
  }

  public function handleGetAllComments(int $idArticle): ?array
  {
    return $this->commentRepository->getAllComments([], $idArticle);;
  }

  public function handleInsertComment(string $comment, array $sessionData, string $idInCookie): ?bool
  {
    $validationException = new ValidationException();
    $exceptionKeyArray = ["comment_field" => "comment_exception"];
    $keyArrayWhenAFieldIsTreated = ["comment_field" => "comment"];
    $exceptionByField = [
      "error_empty" => $validationException::ERROR_EMPTY,
      "comment_exception" => $validationException::COMMENT_WRONG_FORMAT_EXCEPTION
    ];
    $regexByField = ["comment_regex" => REGEX::COMMENT];

    $commentResult = $this->handleTextField($keyArrayWhenAFieldIsTreated["comment_field"], $comment, $exceptionKeyArray["comment_field"], $validationException, $regexByField["comment_regex"], $exceptionByField["error_empty"], $exceptionByField["comment_exception"])["comment"];

    $dateOfCreation =  date('Y-m-d');
    $commentModel = new CommentModel($sessionData["id_article"], $sessionData["id_user"], $commentResult, $dateOfCreation, null);

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
    $feedbackFinalValue = empty($feedback) ? null : $feedback;


    $validationException = new ValidationException();
    $exceptionKeyArray = ["feedback_field" => "validation_exception"];
    $keyArrayWhenAFieldIsTreated = ["feedback_field" => "feedback"];
    $exceptionByField = [
      "error_empty" => $validationException::ERROR_EMPTY,
      "feedback_exception" => $validationException::COMMENT_WRONG_FORMAT_EXCEPTION
    ];
    $regexByField = ["comment_regex" => REGEX::COMMENT];

    $feedbackResult = $this->handleTextField($keyArrayWhenAFieldIsTreated["feedback_field"], $feedbackFinalValue, $exceptionKeyArray["feedback_field"], $validationException, $regexByField["comment_regex"], $exceptionByField["error_empty"], $exceptionByField["feedback_exception"])["feedback"];
    return $this->commentRepository->validationComment($valueOfValidation, $idComment, $feedbackResult, $session, $idInCookie);
  }

  public function handleDeleteComment(array $validation, array $session, string $idInCookie): void
  {
    if (!$validation["status"]) {
      $this->commentRepository->deleteTemporaryComment($validation, $session, $idInCookie);
    }
  }
}
