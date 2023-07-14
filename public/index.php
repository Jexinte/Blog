<?php


require_once __DIR__ . "../../vendor/autoload.php";

use Model\Article;
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

use Controller\ArticleController;
use Controller\UserController;
use Controller\DownloadController;
use Controller\HomepageFormController;





//TODO  URGENT  : Mettre en place la création d'article pour les administrateurs avec les sessions ainsi il faudra avoir une class SessionManager qui sera la seule à créer des session_start() , session_destroy etc...

//* IMPORTANT : Les credentials de mail étant une dépendance extérieure il faut faire quelquechose avec mais je ne sais plus donc je regardais ça plus tard
//TODO  BONUS : Si la partie avec l'administrateur est terminé alors s'occuper de la partie commentaire !

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


$template = "homepage.twig";
$paramaters = [];
if (isset($_GET['action'])) {

    $action = $_GET['action'];


    switch ($action) {
        case "sign_up":
            try {
                $template = "sign_up.twig";

                $paramaters["message"] = $userController->signUpValidator(
                    $_POST['username'],
                    $_FILES['profile_image'],
                    $_POST["mail"],
                    $_POST["password"]
                );
            } catch (UsernameErrorEmptyException $e) {
                $paramaters["username_exception"] =
                    UsernameErrorEmptyException::USERNAME_MESSAGE_ERROR_EMPTY;
            } catch (UsernameWrongFormatException $e) {
                $paramaters["username_exception"] = UsernameWrongFormatException::USERNAME_MESSAGE_ERROR_WRONG_FORMAT;
            } catch (FileErrorEmptyException $e) {
                $paramaters["file_exception"] = FileErrorEmptyException::FILE_MESSAGE_ERROR_NO_FILE_SELECTED;
            } catch (FileTypeException $e) {
                $paramaters["file_exception"] = FileTypeException::FILE_MESSAGE_ERROR_TYPE_FILE;
            } catch (EmailErrorEmptyException $e) {
                $paramaters["email_exception"] = EmailErrorEmptyException::EMAIL_MESSAGE_ERROR_EMPTY;
            } catch (EmailWrongFormatException $e) {
                $paramaters["email_exception"] = EmailWrongFormatException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT;
            } catch (PasswordErrorEmptyException $e) {
                $paramaters["password_exception"] = PasswordErrorEmptyException::PASSWORD_MESSAGE_ERROR_EMPTY;
            } catch (PasswordWrongFormatException $e) {
                $paramaters["password_exception"] = PasswordWrongFormatException::PASSWORD_MESSAGE_ERROR_WRONG_FORMAT;
            }

            break;

        case "sign_in":
            try {
                $template = "sign_in.twig";

                $paramaters["message"] = $userController->loginValidator($_POST['mail'], $_POST["password"]);
                $login = $userController->loginValidator($_POST['mail'], $_POST["password"]);
                $dataLogs = is_array($login) && array_key_exists("username", $login) && array_key_exists("type_user", $login) ? $login : null;


                $_SESSION["username"] = $dataLogs["username"];
                $_SESSION["type_user"] = $dataLogs["type_user"];

                $userController->handleInsertSessionData($_SESSION);
            } catch (EmailErrorEmptyException $e) {
                $paramaters["email_exception"] = EmailErrorEmptyException::EMAIL_MESSAGE_ERROR_EMPTY;
            } catch (EmailWrongFormatException $e) {
                $paramaters["email_exception"] = EmailWrongFormatException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT;
            } catch (PasswordErrorEmptyException $e) {
                $paramaters["password_exception"] = PasswordErrorEmptyException::PASSWORD_MESSAGE_ERROR_EMPTY;
            } catch (PasswordWrongFormatException $e) {
                $paramaters["password_exception"] = PasswordWrongFormatException::PASSWORD_MESSAGE_ERROR_WRONG_FORMAT;
            }

            break;

        case "download_file":
            $template = "homepage.twig";
            $downloadController->handleDownloadFile();
            break;

        case "contact":
            $template = "homepage.twig";
            $paramaters["message"] = $formController->homepageFormValidator($_POST["firstname"], $_POST["lastname"], $_POST["mail"], $_POST["subject"], $_POST["message"]);
            break;

        case "error":
            if (isset($_GET["code"])) {
                $template = "error.twig";
                $paramaters["code"] = $_GET["code"];
            }
            break;
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
                "session" => $_SESSION
            ];
            break;
        case "admin_panel":
            $template = "admin_homepage.twig";
            $paramaters = [
                "session" => $_SESSION
            ];
            break;
        case "view_article":
            $template = "admin_article_and_commentary.twig";
            break;
        case "article":


            $template = "article.twig";
            $paramaters["article"] = $articleController->handleOneArticle($_GET['id']);
            break;
        case "add_article":
            $template = "admin_add_article.twig";
            $paramaters["session"] =  $_SESSION;
            break;
        case "update_article":
            $template = "admin_update_article.twig";
            break;
    }
}

echo $twig->render($template, $paramaters);
