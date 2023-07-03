<?php

namespace Controller;

use Config\DatabaseConnection;
use Model\Article;


class ArticleController
{
  public function listOfAllArticles(): array
  {
    $db = new DatabaseConnection("professional_blog", "root", "");
    $articles = new Article($db);
    return $articles->getArticles();
  }
}
