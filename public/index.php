<?php
$root = dirname(__DIR__);

require_once $root."/vendor/autoload.php";

$action = "";
$selection = "";

$paths = [
  $root."/templates",
  $root."/src/inc",
  $root."/src/admin/templates"
];
$loader = new \Twig\Loader\FilesystemLoader($paths);
$twig = new \Twig\Environment($loader,
[
  'cache' => false
]);

if(isset($_GET['action']) && !empty($_GET['action'])) $action = $_GET['action'];
elseif(isset($_GET['selection']) && !empty($_GET['selection'])) $selection = $_GET['selection'];


//! Lorsque l'intégration sera complètement terminé voir ce qu'il en est de la forme des url

switch($selection):
  
  case "sign_in":
    echo $twig->render("sign_in.twig");
    break;

    case "sign_up":
      echo $twig->render("sign_up.twig");
      break;
    case "blog":
      echo $twig->render("blog.twig");
    break;

    case "admin_panel":

      echo $twig->render("admin_homepage.twig");
    break;

    
    case "view_article":

      echo $twig->render("admin_view_article_and_commentary.twig");
    break;
  default:
    echo $twig->render("homepage.twig");
endswitch;
