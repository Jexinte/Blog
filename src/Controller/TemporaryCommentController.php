<?php
namespace Controller;

use Exceptions\CommentEmptyException;
use Exceptions\CommentWrongFormatException;
use Model\TemporaryComment;
use Model\TemporaryCommentModel;
class TemporaryCommentController {

  public function __construct(private readonly TemporaryComment $temporaryComment){}


  public function handleCommentField($comment){
    $commentRegex = "/^[A-ZÀ-ÿ][A-ZÀ-ÿa-zÀ-ÿ0-9\s\-\_\!\@\#\$\%\&\'\(\)\*\+\,\.\:\/\;\=\?\[\]\^\`\{\|\}\~]{0,498}[A-ZÀ-ÿa-zÀ-ÿ0-9\s\-\_\!\@\#\$\%\&\'\(\)\*\+\,\.\:\/\;\=\?\[\]\^\`\{\|\}\~]$/";
    
    switch(true)
    {
      case empty($comment):
        throw new CommentEmptyException();
      case !preg_match($commentRegex,$comment):
        throw new CommentWrongFormatException();
      default:
      return ["comment" => $comment];
    }
      
  }
  public function handleInsertTemporaryCommentValidator(string $comment , int $idArticle, array $sessionData):void
  {

    $temporaryCommentRepository = $this->temporaryComment;
    $dateDay =  date('Y-m-d');
    $commentResult = $this->handleCommentField($comment)["comment"];
    $commentsData = new TemporaryCommentModel($idArticle,$sessionData["id_user"],$commentResult,$dateDay,null,null,null);
    $temporaryCommentRepository->insertTemporaryComment($commentsData,$sessionData);
  }
}