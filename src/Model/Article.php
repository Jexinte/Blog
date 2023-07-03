<?php

namespace Model;

use Config\DatabaseConnection;
use DateTime;
use IntlDateFormatter;

class Article
{

  public function __construct(private DatabaseConnection $connector)
  {
  }
  public function getArticles(): array
  {

    $dbConnect = $this->connector->connect();
    $statement = $dbConnect->prepare("SELECT image,title,chapô,content,tags,author,DATE_FORMAT(date_creation,'%d %M %Y') AS date_article FROM articles");
    $statement->execute();
    $articles = [];

    while ($row = $statement->fetch()) {
      $french_date_format = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
      $date = $french_date_format->format(new DateTime($row["date_article"]));


      $data = [
        "image" => $row['image'],
        "title" => $row['title'],
        "short_phrase" => $row['chapô'],
        "content" => $row['content'],
        "tags" => $row['tags'],
        "author" => $row['author'],
        "date_of_publication" => ucfirst($date)
      ];
      $articles[] = $data;
    }

    return $articles;
  }
}
