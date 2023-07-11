<?php

namespace Model;

use Config\DatabaseConnection;
use DateTime;
use Exception;
use IntlDateFormatter;

 class Article
{

  public function __construct(private readonly DatabaseConnection   $connector)
  {
  }

    /**
     * @throws Exception
     */
    public function getArticles(): array
  {

    $dbConnect = $this->connector->connect();

    $statement = $dbConnect->prepare("SELECT id,title,chapô,content,tags,author,DATE_FORMAT(date_creation,'%d %M %Y') AS date_article  FROM articles ORDER BY date_article DESC");
    $statement->execute();

    $articles = [];


    while ($row = $statement->fetch()) {
      $frenchDateFormat = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        /** @var string $date */
        $date = $frenchDateFormat->format(new DateTime($row["date_article"]));
      $statement2 = $dbConnect->prepare("SELECT profile_image AS image, username FROM users WHERE username = :author");
      $statement2->bindParam("author", $row["author"]);
      $statement2->execute();
      while ($row2 = $statement2->fetch()) {
        $data = [
          "id" => $row["id"],
          "image" => $row2['image'],
          "title" => $row['title'],
          "short_phrase" => $row['chapô'],
          "content" => substr($row['content'], 0, 250) . '...',
          "tags" => $row['tags'],
          "author" => $row['author'],
          "date_of_publication" => ucfirst($date)
        ];
      }
      $articles[] = $data;
    }



    return $articles;
  }

    /**
     * @throws Exception
     */
    public function getArticle(int $id):array
  {
    $dbConnect = $this->connector->connect();
    $statement = $dbConnect->prepare("SELECT id, image,title,chapô,content,tags,author,DATE_FORMAT(date_creation,'%d %M %Y') AS date_article FROM articles WHERE id = :id");
    $statement->bindParam("id", $id);
    $statement->execute();
    $article = [];
    while ($row = $statement->fetch()) {
      $french_date_format = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
        /** @var string $date */
      $date = $french_date_format->format(new DateTime($row["date_article"]));
      $statement2 = $dbConnect->prepare("SELECT profile_image, username FROM users WHERE username = :author");
      $statement2->bindParam("author", $row["author"]);
      $statement2->execute();
      while ($row2 = $statement2->fetch()) {

        $data = [
          "id" => $row["id"],
          "image" => $row['image'],
          "author_image" => $row2["profile_image"],
          "title" => $row['title'],
          "short_phrase" => $row['chapô'],
          "content" => $row["content"],
          "tags" => $row['tags'],
          "author" => $row['author'],
          "date_of_publication" => ucfirst($date)
        ];
      }
      $article[] = $data;
    }
    header("HTTP/1.1 200");
    return $article;
  }
}
