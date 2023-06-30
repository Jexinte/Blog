<?php

require_once __DIR__ . "/../vendor/autoload.php";

use Controller\ArticleController;
use Controller\UserController;

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

if (isset($_GET['action'])) {

  $action = $_GET['action'];

  switch ($action) {
    case "sign_up":
      $user = new UserController();

      echo $twig->render("sign_up.twig", [
        "username_field" => $user->handleUsernameField(),
        "file_field" => $user->handleFileField(),
        "email_field" => $user->handleEmailField(),
        "password_field" => $user->handlePasswordField(),
        "validation" => $user->handleInputsValidation()
      ]);

      break;

      case "sign_in":
        $user = new UserController();
        echo $twig->render("sign_in.twig",["message" => $user->handleLoginField()]);
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
      $articles = new ArticleController();
      echo $twig->render("blog.twig", ["articles" => $articles->listOfAllArticles()]);
      break;
    case "admin_panel":
      echo $twig->render("admin_homepage.twig");
      break;
    case "view_article":
      echo $twig->render("admin_article_and_commentary.twig");
      break;
    case "article":
      echo $twig->render("article.twig");
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
