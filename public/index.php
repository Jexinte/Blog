<?php

require_once __DIR__ . "../../vendor/autoload.php";

use Config\DatabaseConnection;
use Manager\Session;

$sessionManager = new Session();
$sessionManager->startSession();

use Controller\ArticleController;
use Controller\UserController;
use Controller\DownloadController;
use Controller\HomepageFormController;
use Controller\CommentController;
use Controller\NotificationController;
use Controller\SessionController;

use Enumeration\UserType;
use Repository\ArticleRepository;
use Repository\CommentRepository;
use Repository\HomepageFormRepository;
use Repository\NotificationRepository;
use Repository\UserRepository;

use Exceptions\ValidationException;

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
$formCredentialUsername = file_get_contents("../config/stmp_credentials.json");
$formCredentialsPassword = file_get_contents("../config/stmp_credentials.json");
$formCredentialsSmtpAddress = file_get_contents("../config/stmp_credentials.json");

$db = new DatabaseConnection("professional_blog", "root", "");

$userRepository = new UserRepository($db);
$userController = new UserController($userRepository);



$articleRepository = new ArticleRepository($db);
$articleController = new ArticleController($articleRepository);

$downloadController = new DownloadController();

$formRepository = new HomepageFormRepository(
    $db,
    $formCredentialUsername,
    $formCredentialsPassword,
    $formCredentialsSmtpAddress
);
$formController = new HomepageFormController($formRepository);
$sessionController = new SessionController($sessionManager);




$commentRepository = new CommentRepository($db);
$commentController = new CommentController($commentRepository, $formCredentialUsername, $formCredentialsPassword, $formCredentialsSmtpAddress);

$notificationRepository = new NotificationRepository($db);
$notificationController = new NotificationController($notificationRepository);

$template = "homepage.twig";
$paramaters = [];

$session = $sessionController->handleGetSessionData();
$idInCookie = $sessionManager->getIdInCookie();
if (isset($_GET['action'])) {

    $action = $_GET['action'];
    $defaultValues = [];

    $defaultValuesComments = [];
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
    $labelsUpdateArticle = [
        "title",
        "short_phrase",
        "content",
        "tags",
        "file_image",
        "id_article"
    ];

    $labelsTemporaryComments = [
        "username", "date_of_publication", "content"
    ];

    switch ($action) {
        case "sign_up":
            try {
                $template = "sign_up.twig";
                $signUpSucceed = $userController->signUpValidator(
                    $_POST['username'],
                    $_FILES['profile_image'],
                    $_POST["mail"],
                    $_POST["password"]
                );

                $paramaters["message"] = $signUpSucceed;

                if ($signUpSucceed) {
                    header("HTTP/1.1 302");
                    header("Location:?selection=sign_in");
                }
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
                header("HTTP/1.1 400");
                foreach ($errors as $key => $v) {
                    $paramaters[$key] = $v;
                }
            }
            break;
        case "sign_in":
            try {
                $template = "sign_in.twig";

                $paramaters["message"] = $userController->loginValidator($_POST['mail'], $_POST["password"]);
                $loginSucceed = $userController->loginValidator($_POST['mail'], $_POST["password"]);

                if (is_array($loginSucceed) 
                && array_key_exists("username", $loginSucceed) 
                && array_key_exists("type_user", $loginSucceed)
                ) {
                    header("HTTP/1.1 302");
                    header("Location:?selection=blog");
                    $sessionController->initializeLoginDataAndSessionId($loginSucceed);
                }
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
                header("HTTP/1.1 400");
                foreach ($errors as $key => $v) {
                    $paramaters[$key] = $v;
                }
            }

            break;

        case "download_file":
            $template = "homepage.twig";
            $fileIsDownload = $downloadController->downloadPdfFile();
            if (is_array($fileIsDownload) && array_key_exists("file_logs", $fileIsDownload)) {
                header("Content-Length: " . $fileIsDownload["file_logs"]);
                header('Content-Description: File Transfer');
                header("Content-Type: application/pdf");
                header("Pragma: public");
                header("Content-Disposition:attachment;filename=cv.pdf");
                header("HTTP/1.1 200");
                readfile($fileIsDownload["file_logs"]);
            } else {
                header("Location:?action=error&code=" . $fileIsDownload["code_error"]);
            }
            break;

        case "contact":
            try {
                $template = "homepage.twig";            
                $paramaters["message"] = $formController->homepageFormValidator($_POST["firstname"], $_POST["lastname"], $_POST["mail"], $_POST["subject"], $_POST["message"]);
                $paramaters["session"] = $session;
               
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
                foreach ($errors as $key => $v) {
                    $paramaters[$key] = $v;
                }
            }
            break;

        case "error":
            if (isset($_GET["code"])) {
                $template = "error.twig";
                $paramaters["code"] = $_GET["code"];
            }
            break;

        case "add_article":
            try {


                if ($session["type_user"] == UserType::ADMIN->value) {
                    $template = "admin_add_article.twig";
                    $paramaters = [
                        "session" => $session,
                    ];

                    $articleIsCreated = $articleController->handleCreateArticleValidator($_POST["title"], $_FILES["image_file"], $_POST["short-phrase"], $_POST["content"], $_POST["tags"], $session, $idInCookie);
                    if ($articleIsCreated) {
                        header("HTTP/1.1 302");
                        header("Location:?selection=admin_panel");
                    }
                } else {
                    header("Location:?action=error&code=403");
                }
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
                header("HTTP/1.1 400");
                foreach ($errors as $key => $v) {
                    $paramaters[$key] = $v;
                }
            }
            break;

        case "update_article":
            try {
                if ($session["type_user"] == UserType::ADMIN->value) {
                    $originalData = [];
                    $postData = [
                        $_POST["title"], $_POST["short_phrase"], $_POST["content"], $_POST["tags"], $_POST["original_file_path"],
                        $_POST['id_article']
                    ];

                    foreach ($labelsUpdateArticle as $k => $v) {
                        $originalData[$v] = $postData[$k];
                    }

                    $template = "admin_update_article.twig";
                    $updatedData = $articleController->handleUpdateArticleValidator($_POST["title"], $_FILES["image_file"], $_POST["original_file_path"], $_POST["short_phrase"], $_POST["content"], $_POST["tags"], $session, $_POST["id_article"], $idInCookie);
                    $paramaters = [
                        "message" => $updatedData,
                        "original_data" => $originalData,
                    ];

                    if (is_array($updatedData)) {
                        header("HTTP/1.1 302");
                        header("Location:?selection=admin_panel");
                    }
                } else {
                    header("Location:?action=error&code=403");
                }
            } catch (ValidationException $e) {
                $paramaters["original_data"] = $originalData;
                $errors = $e->getErrors();
                header("HTTP/1.1 400");
                foreach ($errors as $key => $v) {
                    $paramaters[$key] = $v;
                }
            }
            break;
        case "delete_article":
            if ($session["type_user"] === UserType::ADMIN->value) {
                $article = $articleController->handleDeleteArticle($_GET["id"], $session, $idInCookie);
                if (is_array($article)) {
                    header("HTTP/1.1 302");
                    header("Location:?selection=admin_panel");
                }
            } else {
                header("Location:?action=error&code=403");
            }

            break;
        case "logout":
            header("HTTP/1.1 302");
            header("Location:?selection=blog");
            $sessionManager->destroySession();
            break;

        case "add_comment":
            try {
                if ($session["type_user"] === UserType::ADMIN->value || $session["type_user"] == UserType::USER->value) {
                    $article = current($articleController->handleOneArticle($_GET["id"]));
                    $sessionController->initializeIdArticle($article["id"]);
                    foreach ($labels as $k => $v) {
                        $defaultValues[$v] = $article[$v];
                    }
                    $template = "article.twig";
                    $paramaters = [
                        "default" => $defaultValues,
                    ];

                    $session = $sessionController->handleGetSessionData();
                    $commentCreated = $commentController->handleInsertComment($_POST["comment"], $session, $idInCookie);

                    if ($commentCreated) {
                        header("HTTP/1.1 302");
                        header("Location:?selection=article&id={$article["id"]}");
                        $commentController->handleMailToAdmin($session, $defaultValues["title"], $idInCookie);
                    }
                } else {
                    header("Location:?action=error&code=403");
                }
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
                header("HTTP/1.1 400");
                $paramaters["default"] =  $defaultValues;
                foreach ($errors as $key => $v) {
                    $paramaters[$key] = $v;
                }
            }
            break;
        case "validation":

            try {
                $session = $sessionController->handleGetSessionData();
                if ($session["type_user"] === UserType::ADMIN->value) {
                    $template = "admin_validation_commentary.twig";

                    $defaultValues = [];
                    $defaultValues["idComment"] = $_GET["idComment"];
                    $temporaryComment = $commentController->handleGetOneComment($defaultValues["idComment"], $session, $idInCookie);

                    foreach ($labelsTemporaryComments as $k => $v) {
                        $defaultValuesComments[$v] = $temporaryComment[$v];
                    }

                    $paramaters["comment"] = $defaultValues;
                    $paramaters["default"] = $commentController->handleGetOneComment($defaultValues["idComment"], $session, $idInCookie);


                    $finalPost = isset($_POST["accepted"]) ? $_POST["accepted"] : $_POST["rejected"];
                    $commentIsAcceptedOrRejected = $commentController->handleValidationComment($finalPost, $_GET['idComment'], $_POST["feedback"], $session, $idInCookie);
                    $notation = array_key_exists("approved", $commentIsAcceptedOrRejected)  ? "approved" : "rejected";

                    switch (true) {
                        case $commentIsAcceptedOrRejected["status"] == 1:
                            header("HTTP/1.1 302");
                            header("Location:?selection=admin_panel");
                            $notificationController->handleCreateNotification($commentIsAcceptedOrRejected);
                            break;

                        default:
                            header("HTTP/1.1 302");
                            header("Location:?selection=admin_panel");
                            $notificationController->handleCreateNotification($commentIsAcceptedOrRejected);
                            $commentController->handleDeleteComment($commentIsAcceptedOrRejected, $session, $idInCookie);
                            break;
                    }
                }
            } catch (ValidationException $e) {
                $errors = $e->getErrors();
                header("HTTP/1.1 400");
                foreach ($errors as $key => $v) {
                    $paramaters[$key] = $v;
                }
            }
            break;

        case "delete_notification":
            $session = $sessionController->handleGetSessionData();
            if ($session["type_user"] === UserType::USER->value) {
                $template = "notification.twig";
                $paramaters["notifications"] = $notificationController->handleGetAllUserNotifications($session);
                $article = $notificationController->handleDeleteNotification($_GET["id_notification"]);
                if (is_null($article)) {
                    header("HTTP/1.1 302");
                    header("Location:?selection=notifications");
                }
            } else {
                header("Location:?action=error&code=403");
            }
    }
} elseif ((isset($_GET['selection']))) {

    $selection = $_GET['selection'];

    switch ($selection) {

        case "homepage":
            $template = "homepage.twig";
            $paramaters["session"] = $sessionController->handleGetSessionData();
            break;
        case "sign_in":
            $template = "sign_in.twig";
            break;
        case "sign_up":
            $template = "sign_up.twig";
            break;
        case "blog":
            $session = $sessionController->handleGetSessionData();
            $template = "blog.twig";
            $totalNotifications = $userController->handleGetAllUserNotifications($session);
            $paramaters = [
                "articles" => $articleController->listOfAllArticles(),
                "session" => $session,
                "total_notifications" => count($totalNotifications)
            ];
            break;
        case "admin_panel":

            $session = $sessionController->handleGetSessionData();
            if ($session["type_user"] == UserType::ADMIN->value) {
                $template = "admin_homepage.twig";
                $totalCommentsNotValidate =  $commentController->handleGetCommentsNotValidateByAdministrators($session, $idInCookie);
                $paramaters = [
                    "articles" => $articleController->listOfAllArticles(),
                    "session" => $session,
                    "comments" => $totalCommentsNotValidate,
                    "total_comments" => is_array($totalCommentsNotValidate) ? count($totalCommentsNotValidate) : 0

                ];
            } else {
                header("Location:?action=error&code=403");
            }
            break;
        case "comment_details":
            $session = $sessionController->handleGetSessionData();
            if ($session["type_user"] == UserType::ADMIN->value) {
                $template = "admin_validation_commentary.twig";
                $theComment = $commentController->handleGetOneComment($_GET["idComment"], $session, $idInCookie);
                if (is_array($theComment)) {
                    $paramaters["comment"] = $theComment;
                }
            } else {
                header("Location:?action=error&code=403");
            }

            break;
        case "article":
            $article = current($articleController->handleOneArticle($_GET["id"]));
            $paramaters = ["article" => $article];
            if (!$article) {
                $template = "error.twig";
                header("Location:?action=error&code=404");
            }
            $defaultValue = ["data" => $article];

            $template = "article.twig";

            $sessionController->initializeIdArticle($defaultValue["data"]["id"]);
            $session = $sessionController->handleGetSessionData();
            $paramaters["session"] = $session;

            $comments = $commentController->handleGetAllComments($_GET['id']);
            $paramaters["comments"] = $comments;
            if (isset($session["session_id"])) {
                $commentAlreadySentByUser =  $commentController->handleCheckCommentAlreadySentByUser($session, $idInCookie);
                if (is_array($commentAlreadySentByUser) && $commentAlreadySentByUser["user_already_commented"] == 1) {
                    $paramaters["count_of_comments"] = $commentAlreadySentByUser["user_already_commented"];
                }
            } else {
                $paramaters["no_user_connected"] = 1;
            }
            break;
        case "add_article":
            $session = $sessionController->handleGetSessionData();
            if ($session["type_user"] == UserType::ADMIN->value) {
                $template = "admin_add_article.twig";
                $paramaters["session"] = $session;
            } else {
                header("Location:?action=error&code=403");
            }

            break;
        case "view_update_article":
            $session = $sessionController->handleGetSessionData();
            if ($session["type_user"] == UserType::ADMIN->value) {
                $template = "admin_update_article.twig";
                $article = current($articleController->handleOneArticle($_GET["id"]));
                $paramaters = [
                    "article" => $article,
                ];
            } else {
                header("Location:?action=error&code=403");
            }

            break;

        case "notifications":
            $session = $sessionController->handleGetSessionData();
            $notifications = $userController->handleGetAllUserNotifications($session);
            $template = "notification.twig";
            $paramaters["notifications"] = $notifications;
    }
}

echo $twig->render($template, $paramaters);
