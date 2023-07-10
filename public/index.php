<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Config\DatabaseConnection;
use Controller\ArticleController;
use Controller\UserController;
use Controller\DownloadController;
use Controller\HomepageFormController;
use Model\Article;
use Model\User;
use Model\HomepageForm;

// TODO URGENT  : Faire en sorte qu'il n'y ait qu'une instance de twig avecrender de manière à ensuite l'appeler pour chaque case puis faire un echo de l'instance un peu plus bas dans le if
//TODO URGENT : Renommer l'ensemble des variable en camelCase et non snake_case avec PHPSTORM
//TODO URGENT : Configurer les namespace pour qu'il soient en rapport avec la nouvelle structure du projet
//TODO URGENT  : Mettre en place la création d'article pour les administrateurs avec les sessions ainsi il faudra avoir une class SessionManager qui sera la seule à créer des session_start() , session_destroy etc...
//TODO URGENT : Centraliser la levée des exceptions et des en-têtes de requêtes 400 dans index.php , faire des recherches
//TODO URGENT : Maintenant que l'utilisation des sessions est confirmée il faudra faire en sorte de cacher l'accès au panel d'administration lorsque nécessaire
//TODO IMPORTANT : Les credentials de mail étant une dépendance extérieure il faut faire quelquechose avec mais je ne sais plus donc je regardais ça plus tard
//TODO BONUS : Si la partie avec l'administrateur est terminé alors s'occuper de la partie commentaire !
$action = "";
$selection = "";

$paths = [
  __DIR__ . "/../templates",
  __DIR__ . "/../src/inc",
  __DIR__ . "/../src/admin/templates"
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

if (isset($_GET['action'])) {

  $action = $_GET['action'];

  switch ($action) {
    case "sign_up":

      echo $twig->render("sign_up.twig", [
        "message" => $userController->signUpValidator(
          $_POST['username'],
          $_FILES['profile_image'],
          $_POST["mail"],
          $_POST["password"]
        )
      ]);

      break;

    case "sign_in":
      echo $twig->render("sign_in.twig", [
        "message" => $userController->loginValidator(
          $_POST['mail'],
          $_POST["password"]
        )
      ]);
      break;

    case "download_file":
      echo $twig->render("homepage.twig", ["file" => $downloadController->handleDownloadFile()]);
      break;

    case "contact":
      echo $twig->render("homepage.twig", [
        "message" => $formController->homepageFormValidator(
          $_POST["firstname"],
          $_POST["lastname"],
          $_POST["mail"],
          $_POST["subject"],
          $_POST["message"]
        )
      ]);

    case "error":
      if (isset($_GET["code"]) && !empty($_GET["code"])) echo $twig->render("error.twig", ["code" => $_GET["code"]]);
      break;
  }
} elseif (isset($_GET['selection'])) {

  $selection = $_GET['selection'];
  switch ($selection) {

    case "homepage":
      echo $twig->render("homepage.twig");
      break;
    case "sign_in":
      echo $twig->render("sign_in.twig");
      break;
    case "sign_up":
      echo $twig->render("sign_up.twig");
      break;
    case "blog":

      echo $twig->render("blog.twig", ["articles" => $articleController->listOfAllArticles()]);
      break;
    case "admin_panel":
      echo $twig->render("admin_homepage.twig");
      break;
    case "view_article":
      echo $twig->render("admin_article_and_commentary.twig");
      break;
    case "article":
      echo $twig->render("article.twig", ["article" => $articleController->handleOneArticle($_GET['id'])]);
      break;
    case "add_article":
      echo $twig->render("admin_add_article.twig");
      break;
    case "update_article":
      echo $twig->render("admin_update_article.twig");
      break;
  }
} else {
  echo $twig->render("homepage.twig");
}
