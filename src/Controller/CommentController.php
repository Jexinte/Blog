<?php

/**
 * Handle Comment Validation
 * 
 * PHP version 8
 *
 * @category Controller
 * @package  CommentController
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */

namespace Controller;


use Enumeration\Regex;
use Exceptions\ValidationException;
use Model\CommentModel;
use PHPMailer\PHPMailer\PHPMailer;
use Repository\CommentRepository;

/**
 * Handle Comment Validation
 * 
 * PHP version 8
 *
 * @category Controller
 * @package  CommentController
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class CommentController
{

    /**
     * Summary of __construct
     *
     * @param \Repository\CommentRepository $commentRepository commentRepository
     * @param string                        $username          credential STMP
     * @param string                        $password          credential STMP
     * @param string                        $smtp_address      credential STMP
     */
    public function __construct(
        private readonly CommentRepository $commentRepository,
        private readonly string $username,
        private readonly string $password,
        private readonly string $smtpAddress
    ) {
    }


    /**
     * Summary of handleTextField
     *
     * @param string                          $keyArray             
     * @param mixed                           $value                
     * @param string                          $keyException         
     * @param \Exceptions\ValidationException $exception            
     * @param string                          $regex                
     * @param string                          $emptyException       
     * @param string                          $wrongFormatException 
     * 
     * @return string|array|null
     */
    public function handleTextField(
        string $keyArray, 
        ?string $value, 
        string $keyException, 
        ValidationException $exception, 
        string $regex, 
        string $emptyException, 
        string $wrongFormatException
    ): string|array|null {


        switch (true) {
        case is_null($value):
            return [$keyArray => $value];
        case empty($value):
            throw $exception->setTypeAndValueOfException(
                $keyException, 
                $emptyException
            );
        case !preg_match($regex, $value):
            throw $exception->setTypeAndValueOfException(
                $keyException, 
                $wrongFormatException
            );
        default:
            return [$keyArray => $value];
        }
    }

    /**
     * Summary of handleGetAllComments
     *
     * @param int $idArticle 
     * 
     * @return array|null
     */
    public function handleGetAllComments(int $idArticle): ?array
    {
        return $this->commentRepository->getAllComments([], $idArticle);;
    }

    /**
     * Summary of handleInsertComment
     *
     * @param string $comment     
     * @param array  $sessionData 
     * @param string $idInCookie  
     * 
     * @return bool|null
     */
    public function handleInsertComment(
        string $comment, 
        array $sessionData, 
        string $idInCookie
    ): ?bool {
        $validationException = new ValidationException();
        $exceptionKeyArray = ["comment_field" => "comment_exception"];
        $keyArrayWhenAFieldIsTreated = ["comment_field" => "comment"];
        $exceptionByField = [
        "error_empty" => $validationException::ERROR_EMPTY,
        "comment_exception" => $validationException::COMMENT_WRONG_FORMAT_EXCEPTION
        ];
        $regexByField = ["comment_regex" => REGEX::COMMENT];

        $commentResult = $this->handleTextField(
            $keyArrayWhenAFieldIsTreated["comment_field"], 
            $comment, 
            $exceptionKeyArray["comment_field"], 
            $validationException, 
            $regexByField["comment_regex"], 
            $exceptionByField["error_empty"], 
            $exceptionByField["comment_exception"]
        )["comment"];

        $dateOfCreation =  date('Y-m-d');
        $commentModel = new CommentModel(
            $sessionData["id_article"],
            $sessionData["id_user"], 
            $commentResult, 
            $dateOfCreation, 
            null
        );

        $commentCreation = $this->commentRepository->insertComment(
            $commentModel, 
            $sessionData,
            $idInCookie
        );
        if ($commentCreation->getCreated()) {
            return $commentCreation->getCreated();
        }
    }


     /**
      * Summary of handleMailToAdmin
      *
      * @param array  $sessionData    
      * @param string $titleOfArticle 
      * @param string $idInCookie     

      * @return void
      */
    public function handleMailToAdmin(
        array $sessionData,
        string $titleOfArticle, 
        string $idInCookie
    ): void {
   
        if ($sessionData["session_id"] == $idInCookie) {
  
            $username = json_decode($this->username, true);
            $password = json_decode($this->password, true);
            $gmail = json_decode($this->smtpAddress, true);
            $usernameSession = $sessionData["username"];
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $gmail["smtp_address"];
            $mail->SMTPAuth = true;
            $mail->Username = $username["username"]; 
            $mail->Password = $password["password"]; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->SMTPOptions = array(
            'ssl' => array(
              'verify_peer' => false,
              'verify_peer_name' => false,
              'allow_self_signed' => true
            )
            );
  
            $mail->setFrom($username["username"]);
            $mail->addAddress($username["username"]);
            $mail->isHTML();
   
            $mail->Subject = "Nouveau commentaire du sujet $titleOfArticle";
   
            $mail->Body = "Cher administrateur,
             <br><br> 
             Un nouveau commentaire a été publié sur le site
              par l'utilisateur <strong>$usernameSession</strong>.
              <br><br> Pour modérer ou répondre à ce commentaire,
               veuillez vous 
               <a href=
               'http://localhost/P5_Créez%20votre%20premier%20blog%20en%20PHP%20-
               %20Dembele%20Mamadou/public/?selection=sign_in'>connecter</a>
                à l'interface d'administration du site : 
                . <br><br>Cordialement,<br><br>L'équipe du site";
            $mail->send();
        }
   
    }

    /**
     * Summary of handleCommentSent
     *
     * @param array  $sessionData 
     * @param string $idInCookie  
     * 
     * @return array|null
     */
    public function handleCommentSent(
        array $sessionData, 
        string $idInCookie
    ): ?array {
        return $this->commentRepository->checkCommentAlreadySentByUser(
            $sessionData, 
            $idInCookie
        );
    }

    /**
     * Summary of handleCommentsValidation
     *
     * @param array  $sessionData 
     * @param string $idInCookie  
     * 
     * @return array|null
     */
    public function handleCommentsValidation(
        array $sessionData, 
        string $idInCookie
    ): ?array {
        return $this->commentRepository->getCommentsNotValidateByAdministrators(
            $sessionData, 
            $idInCookie
        );
    }

    /**
     * Summary of handleGetOneComment
     *
     * @param int    $idComment  
     * @param array  $session    
     * @param string $idInCookie 
     * 
     * @return array|null
     */
    public function handleGetOneComment(
        int $idComment, 
        array $session, 
        string $idInCookie
    ): ?array {
        return $this->commentRepository->getOneComment(
            $idComment, 
            $session, 
            $idInCookie
        );
    }

    /**
     * Summary of handleValidationComment
     *
     * @param string $valueOfValidation 
     * @param int    $idComment         
     * @param string $feedback          
     * @param array  $session           
     * @param string $idInCookie        
     * 
     * @return array|null
     */
    public function handleValidationComment(
        string $valueOfValidation, 
        int $idComment, 
        string $feedback, 
        array $session, 
        string $idInCookie
    ): ?array {
        $feedbackFinalValue = empty($feedback) ? null : $feedback;


        $validationException = new ValidationException();
        $exceptionKeyArray = ["feedback_field" => "validation_exception"];
        $keyArrayWhenAFieldIsTreated = ["feedback_field" => "feedback"];
        $exceptionByField = [
        "error_empty" =>
         $validationException::ERROR_EMPTY,
        "feedback_exception" =>
         $validationException::COMMENT_WRONG_FORMAT_EXCEPTION
        ];
        $regexByField = ["comment_regex" => REGEX::COMMENT];

        $feedbackResult = $this->handleTextField(
            $keyArrayWhenAFieldIsTreated["feedback_field"], 
            $feedbackFinalValue, 
            $exceptionKeyArray["feedback_field"], 
            $validationException, 
            $regexByField["comment_regex"], 
            $exceptionByField["error_empty"], 
            $exceptionByField["feedback_exception"]
        )["feedback"];
        return $this->commentRepository->validationComment(
            $valueOfValidation, 
            $idComment, 
            $feedbackResult, 
            $session, 
            $idInCookie
        );
    }

    /**
     * Summary of handleDeleteComment
     *
     * @param array  $validation 
     * @param array  $session    
     * @param string $idInCookie 
     * 
     * @return void
     */
    public function handleDeleteComment(
        array $validation, 
        array $session, 
        string $idInCookie
    ): void {
        if (!$validation["status"]) {
            $this->commentRepository->deleteComment(
                $validation, 
                $session, 
                $idInCookie
            );
        }
    }
}
