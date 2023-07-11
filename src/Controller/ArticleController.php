<?php

namespace Controller;

use Model\Article;


readonly class ArticleController
{

  public function __construct(private  Article $article)
  {
  }
  public function listOfAllArticles(): array
  {

    return $this->article->getArticles();
  }

  public function handleOneArticle(int $id):?array
  {
    $result = !empty($id);

     return $result ? $this->article->getArticle($id) : null;
  }
}
