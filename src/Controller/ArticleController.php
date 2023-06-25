<?php

namespace Controller;

use Model\ArticleModel;

class ArticleController
{
  public function listOfAllArticles()
  {
    $articles = new ArticleModel();
    return $articles->getArticles();
  }
}
