<?php

namespace Model;

//use Config\DatabaseConnection;

class ArticleModel
{

  public function __construct(
    public string $image,
    public string $title,
    public string $chapô,
    public string $content,
    public array $tags,
    public string $author,
    public string $dateCreation
  ) {
  }
}
