<?php


require_once __DIR__ . "../../vendor/autoload.php";

use Model\Article;
use Model\TemporaryComment;
use Model\Comment;
use Model\User;
use Model\HomepageForm;
use Model\SessionManager;

$sessionRepository = new SessionManager();
$sessionRepository->startSession();

use Config\DatabaseConnection;

use Exceptions\UsernameErrorEmptyException;
use Exceptions\UsernameWrongFormatException;
use Exceptions\FileErrorEmptyException;
use Exceptions\FileTypeException;
use Exceptions\EmailErrorEmptyException;
use Exceptions\EmailWrongFormatException;
use Exceptions\PasswordErrorEmptyException;
use Exceptions\PasswordWrongFormatException;
use Exceptions\TitleErrorEmptyException;
use Exceptions\TitleWrongFormatException;
use Exceptions\ContentArticleErrorEmptyException;
use Exceptions\ContentArticleWrongFormatException;
use Exceptions\ShortPhraseErrorEmptyException;
use Exceptions\ShortPhraseWrongFormatException;
use Exceptions\TagsErrorEmptyException;
use Exceptions\TagsWrongFormatException;
use Exceptions\ContentMessageWrongFormatException;
use Exceptions\ContentMessageErrorEmptyException;
use Exceptions\SubjectErrorEmptyException;
use Exceptions\SubjectWrongFormatException;
use Exceptions\LastnameErrorEmptyException;
use Exceptions\LastnameWrongFormatException;
use Exceptions\FirstNameErrorEmptyException;
use Exceptions\FirstNameWrongFormatException;
use Exceptions\CommentEmptyException;
use Exceptions\CommentWrongFormatException;
use Exceptions\EmailUnavailableException;
use Exceptions\EmailUnexistException;
use Exceptions\FormMessageNotSentException;
use Exceptions\PasswordIncorrectException;
use Exceptions\UsernameUnavailableException;
use Exceptions\ValidationErrorWrongFormatException;

use Controller\ArticleController;
use Controller\UserController;
use Controller\DownloadController;
use Controller\HomepageFormController;
use Controller\TemporaryCommentController;
use Controller\CommentController;

use Enumeration\UserType;


$action = "";
$selection = "";

$paths = [
    __DIR__ . "/../templates",
    __DIR__ . '/../templates/admin'
];
$loader = new \Twig\Loader\FilesystemLoader($paths);
$twig = new \Twig\Environment(
    $loader,
    [
        'cache' => false
    ]
);
$db = new DatabaseConnection("professional_blog", "root", "");


$userRepository = new User($db);
$userController = new UserController($userRepository);

$articleRepository = new Article($db);
$articleController = new ArticleController($articleRepository);

$downloadController = new DownloadController();

$formRepository = new HomepageForm($db);
$formController = new HomepageFormController($formRepository);

$temporaryCommentRepository = new TemporaryComment($db);
$temporaryCommentController = new TemporaryCommentController($temporaryCommentRepository);

$commentRepository = new Comment($db);
$commentController = new CommentController($commentRepository);

$template = "homepage.twig";
$paramaters = [];


if (isset($_GET['action'])) {

    $action = $_GET['action'];
    $defaultValues = [];
    $defaultValuesTemporaryComments = [];
    $labels = [
        "title", "id",
        "short_phrase",
        "content",
        "image",
        "author_image",
        "author",
        "tags",
        "date_of_publication"
    ];

    $labelsTemporaryComments = [
        "username", "date_of_publication", "content"
    ];

    switch ($action) {
        case "sign_up":
            try {
                $template = "sign_up.twig";
                $signUp = $userController->signUpValidator(
                    $_POST['username'],
                    $_FILES['profile_image'],
                    $_POST["mail"],
                    $_POST["password"]
                );

                $paramaters["message"] = $signUp;

                if (is_array($signUp)) {
                    header("HTTP/1.1 302");
                    header("Location: index.php?selection=blog");
                }
            } catch (UsernameErrorEmptyException $e) {
                header("HTTP/1.1 400");
                $paramaters["username_exception"] =
                    UsernameErrorEmptyException::USERNAME_MESSAGE_ERROR_EMPTY;
            } catch (UsernameWrongFormatException $e) {
                header("HTTP/1.1 400");
                $paramaters["username_exception"] = UsernameWrongFormatException::USERNAME_MESSAGE_ERROR_WRONG_FORMAT;
            } catch (FileErrorEmptyException $e) {
                header("HTTP/1.1 400");
                $paramaters["file_exception"] = FileErrorEmptyException::FILE_MESSAGE_ERROR_NO_FILE_SELECTED;
            } catch (FileTypeException $e) {
                header("HTTP/1.1 400");
                $paramaters["file_exception"] = FileTypeException::FILE_MESSAGE_ERROR_TYPE_FILE;
            } catch (EmailErrorEmptyException $e) {
                header("HTTP/1.1 400");
                $paramaters["email_exception"] = EmailErrorEmptyException::EMAIL_MESSAGE_ERROR_EMPTY;
            } catch (EmailWrongFormatException $e) {
                header("HTTP/1.1 400");
                $paramaters["email_exception"] = EmailWrongFormatException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT;
            } catch (PasswordErrorEmptyException $e) {
                header("HTTP/1.1 400");
                $paramaters["password_exception"] = PasswordErrorEmptyException::PASSWORD_MESSAGE_ERROR_EMPTY;
            } catch (PasswordWrongFormatException $e) {
                header("HTTP/1.1 400");
                $paramaters["password_exception"] = PasswordWrongFormatException::PASSWORD_MESSAGE_ERROR_WRONG_FORMAT;
            } catch (UsernameUnavailableException $e) {
                header("HTTP/1.1 400");
                $paramaters["username_exception"] = UsernameUnavailableException::USERNAME_UNAVAILABLE_MESSAGE_ERROR . ' ' . $_POST["username"] . " n'est pas disponible ";
            } catch (EmailUnavailableException $e) {
                header("HTTP/1.1 400");
                $paramaters["email_exception"] = EmailUnavailableException::EMAIL_UNAVAILABLE_MESSAGE_ERROR . ' ' . $_POST["mail"] . " n'est pas disponible";
            }

            break;
        case "sign_in":
            try {
                $template = "sign_in.twig";

                $paramaters["message"] = $userController->loginValidator($_POST['mail'], $_POST["password"]);
                $login = $userController->loginValidator($_POST['mail'], $_POST["password"]);

                if (is_array($login) && array_key_exists("username", $login) && array_key_exists("type_user", $login)) {
                    $_SESSION["username"] = $login["username"];
                    $_SESSION["type_user"] = $login["type_user"];
                    $_SESSION["id_user"] = $login["id_user"];
                    $userController->handleInsertSessionData($_SESSION);
                    header("HTTP/1.1 302");
                    header("Location: index.php?selection=blog");
                }
            } catch (EmailErrorEmptyException $e) {
                header("HTTP/1.1 400");
                $paramaters["email_exception"] = EmailErrorEmptyException::EMAIL_MESSAGE_ERROR_EMPTY;
            } catch (EmailWrongFormatException $e) {
                header("HTTP/1.1 400");
                $paramaters["email_exception"] = EmailWrongFormatException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT;
            } catch (PasswordErrorEmptyException $e) {
                header("HTTP/1.1 400");
                $paramaters["password_exception"] = PasswordErrorEmptyException::PASSWORD_MESSAGE_ERROR_EMPTY;
            } catch (PasswordWrongFormatException $e) {
                header("HTTP/1.1 400");
                $paramaters["password_exception"] = PasswordWrongFormatException::PASSWORD_MESSAGE_ERROR_WRONG_FORMAT;
            } catch (EmailUnexistException $e) {
                header("HTTP/1.1 400");
                $paramaters["email_exception"] = EmailUnexistException::EMAIL_UNEXIST_MESSAGE_ERROR;
            } catch (PasswordIncorrectException $e) {
                header("HTTP/1.1 403");
                $paramaters["password_exception"] = PasswordIncorrectException::PASSWORD_INCORRECT_MESSAGE_ERROR;
            }

            break;

        case "download_file":
            $template = "homepage.twig";
            $downloadController->handleDownloadFile();
            if (is_array($downloadController->handleDownloadFile()) && array_key_exists("file_logs", $downloadController->handleDownloadFile())) {
                header("Content-Length: " . $downloadController->handleDownloadFile()["file_logs"]);
                header('Content-Description: File Transfer');
                header("Content-Type: application/pdf");
                header("Pragma: public");
                header("Content-Disposition:attachment;filename=cv.pdf");
                header("HTTP/1.1 200");
                readfile($downloadController->handleDownloadFile()["file_logs"]);
            } elseif (is_array($downloadController->handleDownloadFile()) && array_key_exists("code_error", $downloadController->handleDownloadFile())) {
                header("Location: index.php?action=error&code=" . $downloadController->handleDownloadFile()["code_error"]);
            }
            break;

        case "contact":
            try {
                $template = "homepage.twig";
                $paramaters["message"] = $formController->homepageFormValidator($_POST["firstname"], $_POST["lastname"], $_POST["mail"], $_POST["subject"], $_POST["message"]);
            } catch (FirstNameErrorEmptyException $e) {
                header('HTTP/1.1 400');
                $paramaters["firstname_exception"] = FirstNameErrorEmptyException::FIRSTNAME_MESSAGE_ERROR_EMPTY;
            } catch (FirstNameWrongFormatException $e) {
                header('HTTP/1.1 400');
                $paramaters["firstname_exception"] = FirstNameWrongFormatException::FIRSTNAME_MESSAGE_ERROR_WRONG_FORMAT;
            } catch (LastnameErrorEmptyException $e) {
                header('HTTP/1.1 400');
                $paramaters["lastname_exception"] = LastnameErrorEmptyException::LASTNAME_MESSAGE_ERROR_EMPTY;
            } catch (LastnameWrongFormatException $e) {
                header('HTTP/1.1 400');
                $paramaters["lastname_exception"] = LastnameWrongFormatException::LASTNAME_MESSAGE_ERROR_WRONG_FORMAT;
            } catch (EmailErrorEmptyException $e) {
                header('HTTP/1.1 400');
                $paramaters["email_exception"] = EmailErrorEmptyException::EMAIL_MESSAGE_ERROR_EMPTY;
            } catch (EmailWrongFormatException $e) {
                header('HTTP/1.1 400');
                $paramaters["email_exception"] = EmailWrongFormatException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT;
            } catch (SubjectErrorEmptyException $e) {
                header('HTTP/1.1 400');
                $paramaters["subject_exception"] = SubjectErrorEmptyException::SUBJECT_MESSAGE_ERROR_EMPTY;
            } catch (SubjectWrongFormatException $e) {
                header('HTTP/1.1 400');
                $paramaters["subject_exception"] = SubjectWrongFormatException::SUBJECT_MESSAGE_ERROR_MIN_20_CHARS_MAX_100_CHARS;
            } catch (ContentMessageErrorEmptyException $e) {
                header('HTTP/1.1 400');
                $paramaters["content_message_exception"] = ContentMessageErrorEmptyException::CONTENT_MESSAGE_ERROR_EMPTY;
            } catch (ContentMessageWrongFormatException $e) {
                header('HTTP/1.1 400');
                $paramaters["content_message_exception"] = ContentMessageWrongFormatException::CONTENT_MESSAGE_ERROR_MIN_20_CHARS_MAX_500_CHARS;
            } catch (FormMessageNotSentException $e) {
                header("HTTP/1.1 500");
                header("Location: index.php?action=error&code=500");
                $paramaters["message_not_sent_exception"] = FormMessageNotSentException::MESSAGE_SENT_FAILED;
            }
            break;

        case "error":
            if (isset($_GET["code"])) {
                $template = "error.twig";
                $paramaters["code"] = $_GET["code"];
            }
            break;

        case "add_article":
            if ($_SESSION["type_user"] != UserType::ADMIN->value) {
                header("Location: index.php?action=error&code=403");
            }
            $template = "admin_add_article.twig";
            try {
                $paramaters = [
                    "session" => $_SESSION,
                ];

                if (is_array($articleController->handleCreateArticleValidator($_POST["title"], $_FILES["image_file"], $_POST["short-phrase"], $_POST["content"], $_POST["tags"], $_SESSION))) {
                    header("HTTP/1.1 302");
                    header("Location: index.php?selection=admin_panel");
                }
            } catch (TitleErrorEmptyException $e) {
                header("HTTP/1.1 400");
                $paramaters["title_exception"] = TitleErrorEmptyException::TITLE_MESSAGE_ERROR_EMPTY;
            } catch (TitleWrongFormatException $e) {
                header("HTTP/1.1 400");
                $paramaters["title_exception"] = TitleWrongFormatException::TITLE_MESSAGE_ERROR_MAX_500_CHARS;
            } catch (FileErrorEmptyException $e) {
                header("HTTP/1.1 400");
                $paramaters["file_exception"] = FileErrorEmptyException::FILE_MESSAGE_ERROR_NO_FILE_SELECTED;
            } catch (FileTypeException $e) {
                header("HTTP/1.1 400");
                $paramaters["file_exception"] = FileTypeException::FILE_MESSAGE_ERROR_TYPE_FILE;
            } catch (ShortPhraseErrorEmptyException $e) {
                header("HTTP/1.1 400");
                $paramaters["short_phrase_exception"] = ShortPhraseErrorEmptyException::SHORT_PHRASE_MESSAGE_ERROR_EMPTY;
            } catch (ShortPhraseWrongFormatException $e) {
                header("HTTP/1.1 400");
                $paramaters["short_phrase_exception"] = ShortPhraseWrongFormatException::SHORT_PHRASE_MESSAGE_ERROR_MAX_500_CHARS;
            } catch (ContentArticleErrorEmptyException $e) {
                header("HTTP/1.1 400");
                $paramaters["content_article_exception"] = ContentArticleErrorEmptyException::CONTENT_ARTICLE_MESSAGE_ERROR_EMPTY;
            } catch (ContentArticleWrongFormatException $e) {
                header("HTTP/1.1 400");
                $paramaters["content_article_exception"] = ContentArticleWrongFormatException::CONTENT_ARTICLE_MESSAGE_ERROR_MAX_5000_CHARS;
            } catch (TagsErrorEmptyException $e) {
                header("HTTP/1.1 400");
                $paramaters["tags_exception"] = TagsErrorEmptyException::TAGS_ERROR_EMPTY;
            } catch (TagsWrongFormatException $e) {
                header("HTTP/1.1 400");
                $paramaters["tags_exception"] = TagsWrongFormatException::TAGS_MESSAGE_ERROR_MAX_3_TAGS;
            }
            break;

        case "update_article":
            if ($_SESSION["type_user"] != UserType::ADMIN->value) {
                header("Location: index.php?action=error&code=403");
            }
            //TODO Appliquer le même processus que pour la partie "add_comment" ainsi les exceptions pourront être utilisées !
            $defautValuesInEachField =
                [
                    "title" => $_POST["title"],
                    "short_phrase" => $_POST["short_phrase"],
                    "content" => $_POST["content"],
                    "tags" => $_POST["tags"],
                    "file_image" => $_POST["original_file_path"],
                    "id_article" => $_POST['id_article']
                ];

            $template = "admin_update_article.twig";
            $paramaters = [
                "message" => $articleController->handleUpdateArticleValidator($_POST["title"], $_FILES["image_file"], $_POST["original_file_path"], $_POST["short_phrase"], $_POST["content"], $_POST["tags"], $_SESSION, $_POST["id_article"]),
                "default_value" => $defautValuesInEachField,
            ];

            if (is_array($articleController->handleUpdateArticleValidator($_POST["title"], $_FILES["image_file"], $_POST["original_file_path"], $_POST["short_phrase"], $_POST["content"], $_POST["tags"], $_SESSION, $_POST["id_article"]))) {
                header("HTTP/1.1 302");
                header("Location: index.php?selection=admin_panel");
            }


            break;
        case "delete_article":
            if ($_SESSION["type_user"] != UserType::ADMIN->value) {
                header("Location: index.php?action=error&code=403");
            }
            if (is_array($articleController->handleDeleteArticle($_GET["id"], $_SESSION))) {
                header("HTTP/1.1 302");
                header("Location: index.php?selection=admin_panel");
            }
            break;
        case "logout":

            if (is_array($userController->handleLogout($_SESSION))) {
                header("HTTP/1.1 302");
                header("Location: index.php?selection=blog");
                $sessionRepository->destroySession();
            }
            break;

        case "add_comment":
            $article = current($articleController->handleOneArticle($_GET["idArticle"]));
            foreach ($labels as $k => $v) {
                $defaultValues[$v] = $article[$v];
            }


            try {
                $template = "article.twig";

                $paramaters["default"] = $defaultValues;
                if (is_array($temporaryCommentController->handleInsertTemporaryCommentValidator($_POST["comment"], $_POST["id_article"], $_SESSION))) {
                    header("HTTP/1.1 302");
                    header("Location: index.php?selection=article&id=" . $defaultValues["id"]);
                    $temporaryCommentController->handleMailToAdmin($_SESSION, $defaultValues["title"]);
                }
            } catch (CommentEmptyException $e) {
                $paramaters = [
                    "comment_exception" => CommentEmptyException::COMMENT_EMPTY_EXCEPTION,
                    "default" => $defaultValues
                ];
            } catch (CommentWrongFormatException $e) {
                $paramaters = [
                    "comment_exception" => CommentWrongFormatException::COMMENT_WRONG_FORMAT_EXCEPTION,
                    "default" => $defaultValues
                ];
            }
            break;
        case "validation":

            try {

                $template = "admin_validation_commentary.twig";

                $defaultValues = [];
                $defaultValues["idComment"] = $_GET["idComment"];
                $temporaryComment = $temporaryCommentController->handleGetOneTemporaryComment($defaultValues["idComment"]);
                foreach ($labelsTemporaryComments as $k => $v) {
                    $defaultValuesTemporaryComments[$v] = $temporaryComment[$v];
                }
                $paramaters["comment"] = $defaultValues;
                $paramaters["com"] = $temporaryCommentController->handleGetOneTemporaryComment($defaultValues["idComment"]);


                $finalPost = isset($_POST["accepted"]) ? $_POST["accepted"] : $_POST["rejected"];


                if (array_key_exists("approved", $temporaryCommentController->handleValidationTemporaryComment($finalPost, $_GET["idComment"], $_POST["feedback"])) || array_key_exists("rejected", $temporaryCommentController->handleValidationTemporaryComment($finalPost, $_GET["idComment"], $_POST["feedback"]))) {
                    header("HTTP/1.1 302");
                    header("Location:index.php?selection=admin_panel");
                    $_SESSION[array_key_first($temporaryCommentController->handleinsertNotificationUserOfTemporaryComment($temporaryCommentController->handleValidationTemporaryComment($finalPost, $_GET["idComment"], $_POST["feedback"])))] = 1;
                    $_SESSION["id_comment"] = $defaultValues["idComment"];
                    if (array_key_exists("temporary_comment_approved", $temporaryCommentController->handleFinalValidationOfTemporaryComment($_SESSION))) {
                        unset($_SESSION["temporary_comment_approved"]);
                    } else {
                        unset($_SESSION["temporary_comment_rejected"]);
                    }
                }
            } catch (ValidationErrorWrongFormatException $e) {
                $paramaters = [
                    "validation_exception" => ValidationErrorWrongFormatException::VALIDATION_MESSAGE_ERROR_WRONG_FORMAT,
                    "comment" => $temporaryComment
                ];
            }
            break;

            case "delete_notification":
                $template ="notification.twig";
                $paramaters["notifications"] = $userController->handleGetAllUserNotifications($_SESSION);
                if(array_key_exists("notification_delete",$userController->handleDeleteNotification($_GET["id_notification"]))){
                    header("HTTP/1.1 302");
                    header("Location:index.php?selection=notifications");
                }
    }
} elseif ((isset($_GET['selection']))) {

    $selection = $_GET['selection'];

    switch ($selection) {

        case "homepage":
            $template = "homepage.twig";
            $paramaters["session"] =  $_SESSION;
            break;
        case "sign_in":
            $template = "sign_in.twig";
            break;
        case "sign_up":
            $template = "sign_up.twig";
            break;
        case "blog":
            $template = "blog.twig";

            if (is_array($userController->handleGetIdSessionData($_SESSION)) && array_key_exists("session_id", $userController->handleGetIdSessionData($_SESSION))) {
                $_SESSION["session_id"] = $userController->handleGetIdSessionData($_SESSION)["session_id"];
            }

            $paramaters = [
                "articles" => $articleController->listOfAllArticles(),
                "session" => $_SESSION,
                "total_notifications" => count($userController->handleGetAllUserNotifications($_SESSION))
            ];
            break;
        case "admin_panel":
            if ($_SESSION["type_user"] != UserType::ADMIN->value) {
                header("Location: index.php?action=error&code=403");
            }
            $template = "admin_homepage.twig";
            $paramaters = [
                "articles" => $articleController->listOfAllArticles(),
                "session" => $_SESSION,
                "comments" => $temporaryCommentController->handlegetTemporaryCommentsForAdministrators($_SESSION),
                "total_comments" => !is_null($temporaryCommentController->handlegetTemporaryCommentsForAdministrators($_SESSION)) ?? count($temporaryCommentController->handlegetTemporaryCommentsForAdministrators($_SESSION))
            ];


            break;
        case "comment_details":
            if ($_SESSION["type_user"] != UserType::ADMIN->value) {
                header("Location: index.php?action=error&code=403");
            }
            $template = "admin_validation_commentary.twig";
            if (is_array($temporaryCommentController->handleGetOneTemporaryComment($_GET["idComment"]))) {
                $paramaters["comment"] = $temporaryCommentController->handleGetOneTemporaryComment($_GET["idComment"]);
            }
            break;
        case "article":


            $defaultValue = [
                "data" => current($articleController->handleOneArticle($_GET['id']))

            ];
            $template = "article.twig";

            $_SESSION["id_article"] = $defaultValue["data"]["id"];
            $paramaters["article"] = current($articleController->handleOneArticle($_GET['id']));

            $paramaters["comments"] = $commentController->handleGetAllComments($_GET['id']);
            if (isset($_SESSION["username"])) {

                if (is_array($temporaryCommentController->handlecheckCommentAlreadySentByUser($_SESSION)) && array_key_exists("user_already_commented", $temporaryCommentController->handlecheckCommentAlreadySentByUser($_SESSION))) {
                    $paramaters["count_of_comments"] = $temporaryCommentController->handlecheckCommentAlreadySentByUser($_SESSION)["user_already_commented"];
                }
            } else {
                $paramaters["no_user_connected"] = 1;
            }
            break;
        case "add_article":
            if ($_SESSION["type_user"] != UserType::ADMIN->value) {
                header("Location: index.php?action=error&code=403");
            }
            $template = "admin_add_article.twig";
            $paramaters["session"] =  $_SESSION;
            break;
        case "view_update_article":
            if ($_SESSION["type_user"] != UserType::ADMIN->value) {
                header("Location: index.php?action=error&code=403");
            }
            $template = "admin_update_article.twig";
            $paramaters = [
                "article" => current($articleController->handleOneArticle($_GET["id"])),
            ];
            break;

        case "notifications":
            $template = "notification.twig";
            $paramaters["notifications"] = $userController->handleGetAllUserNotifications($_SESSION);
    }
}

echo $twig->render($template, $paramaters);
