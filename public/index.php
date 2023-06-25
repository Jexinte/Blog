<?php
require_once __DIR__ . "/../vendor/autoload.php";

use Controller\ArticleController;




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

if (isset($_GET['action'])) $action = $_GET['action'];
elseif (isset($_GET['selection'])) $selection = $_GET['selection'];



//* This part will be change sooner
switch ($selection):

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
  default:
    echo $twig->render("homepage.twig");
endswitch;
