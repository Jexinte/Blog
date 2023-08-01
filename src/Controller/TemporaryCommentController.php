<?php

namespace Controller;

use Exceptions\ValidationException;
use Repository\TemporaryCommentRepository;
use Model\TemporaryCommentModel;
use Enumeration\Regex;

class TemporaryCommentController
{

  public function __construct(private readonly TemporaryCommentRepository $temporaryComment)
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
  public function handleInsertTemporaryCommentValidator(string $comment, int $idArticle, array $sessionData): ?TemporaryCommentModel
  {

    $temporaryCommentRepository = $this->temporaryComment;
    $dateOfCreation =  date('Y-m-d');
    $temporaryCommentResult = $this->handleCommentField($comment)["comment"];

    $temporaryCommentModel = new TemporaryCommentModel($idArticle, $sessionData["id_user"], $temporaryCommentResult, $dateOfCreation, null, null, null, null);

    $idArticleInModel = $temporaryCommentModel->getIdArticle();
    $idUserInModel = $temporaryCommentModel->getIdUser();
    $temporaryCommentInModel = $temporaryCommentModel->getContent();
    $dateInModel = $temporaryCommentModel->getDateCreation();
    $approvedInModel = $temporaryCommentModel->getApproved();
    $rejectedInModel = $temporaryCommentModel->getRejected();
    $feedbackAdmin = $temporaryCommentModel->getFeedbackAdministrator();
    $temporaryCommentResult = $temporaryCommentRepository->insertTemporaryComment(
      $idArticleInModel,
      $idUserInModel,
      $temporaryCommentInModel,
      $dateInModel,
      $approvedInModel,
      $rejectedInModel,
      $feedbackAdmin,
      $sessionData
    );
    if ($temporaryCommentResult->getTemporaryCommentCreated())
      return $temporaryCommentResult;
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

  public function handleValidationTemporaryComment(string $valueOfValidation, int $idComment, string $feedback): ?array
  {

    $feedbackResult = $this->handleFeedbackField($feedback)["feedback"];
    $temporaryCommentRepository = $this->temporaryComment;
    return $temporaryCommentRepository->validationTemporaryComment($valueOfValidation, $idComment, $feedbackResult);
  }

  public function handleDeleteTemporaryComment(array $rejectedValidation): void
  {
    if (array_key_exists("rejected", $rejectedValidation)) {
      $temporaryCommentRepository = $this->temporaryComment;
      $temporaryCommentRepository->deleteTemporaryComment($rejectedValidation);
    }
  }
}
