<?php

namespace Controller;

use Model\ArticleModel;

class ArticleController
{
  public function listOfAllArticles() : array
  {
    $articles = new ArticleModel();
    return $articles->getArticles();
  }
}
