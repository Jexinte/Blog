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

  public function handleFeedbackField(string $feedback):?array
  {
    switch (true) {

      case !preg_match(REGEX::FEEDBACK, $feedback):
        throw new ValidationErrorWrongFormatException();
      default:
        return ["feedback" => $feedback];
    }
  }
  public function handleInsertTemporaryCommentValidator(string $comment, int $idArticle, array $sessionData): array
  {

    $temporaryCommentRepository = $this->temporaryComment;
    $dateDay =  date('Y-m-d');
    $temporaryCommentResult = $this->handleCommentField($comment)["comment"];
    
    $temporaryCommentData = new TemporaryCommentModel($idArticle, $sessionData["id_user"], $temporaryCommentResult, $dateDay, null, null, null);
    
    $idArticleInModel = $temporaryCommentData->getIdArticle();
    $idUserInModel = $temporaryCommentData->getIdUser();
    $temporaryCommentInModel = $temporaryCommentData->getContent();
    $dateInModel = $temporaryCommentData->getDateCreation();
    $approvedInModel = $temporaryCommentData->getApproved();
    $rejectedInModel = $temporaryCommentData->getRejected();
    $feedbackAdmin = $temporaryCommentData->getFeedbackAdministrator();
     $dataFromTemporaryCommentModel = [
       "id_article" => $idArticleInModel,
       "id_user" => $idUserInModel,
       "content" => $temporaryCommentInModel,
       "date_creation" => $dateInModel,
       "approved" => $approvedInModel,
       "rejected" => $rejectedInModel,
       "feedback_administrator" => $feedbackAdmin
     ];
    return $temporaryCommentRepository->insertTemporaryComment($dataFromTemporaryCommentModel, $sessionData);
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
    return $temporaryCommentRepository->validationTemporaryComment($typeValidation, $idComment, $feedbackResult);
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
