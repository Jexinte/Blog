<?php

namespace Controller;

use Exceptions\CommentEmptyException;
use Exceptions\CommentWrongFormatException;
use Exceptions\ValidationErrorWrongFormatException;
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


    switch (true) {
      case empty($comment):
        throw new CommentEmptyException();
      case !preg_match(REGEX::COMMENT, $comment):
        throw new CommentWrongFormatException();
      default:
        return ["comment" => $comment];
    }
  }

  public function handleFeedbackField(string $feedback): ?array
  {
    switch (true) {

      case !preg_match(REGEX::FEEDBACK, $feedback):
        throw new ValidationErrorWrongFormatException();
      default:
        return ["feedback" => $feedback];
    }
  }
  public function handleInsertTemporaryCommentValidator(string $comment, int $idArticle, array $sessionData): ?TemporaryCommentModel
  {

    $temporaryCommentRepository = $this->temporaryComment;
    $dateDay =  date('Y-m-d');
    $temporaryCommentResult = $this->handleCommentField($comment)["comment"];

    $temporaryCommentData = new TemporaryCommentModel($idArticle, $sessionData["id_user"], $temporaryCommentResult, $dateDay, null, null, null, null);

    $idArticleInModel = $temporaryCommentData->getIdArticle();
    $idUserInModel = $temporaryCommentData->getIdUser();
    $temporaryCommentInModel = $temporaryCommentData->getContent();
    $dateInModel = $temporaryCommentData->getDateCreation();
    $approvedInModel = $temporaryCommentData->getApproved();
    $rejectedInModel = $temporaryCommentData->getRejected();
    $feedbackAdmin = $temporaryCommentData->getFeedbackAdministrator();
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
