<?php

namespace Model;

require_once "../vendor/autoload.php";

use Config\DatabaseConnection;
use DateTime;
use IntlDateFormatter;

class ArticleModel
{

  public function getArticles(): array
  {
    $database = DatabaseConnection::getInstance();
    $config_db = json_decode(file_get_contents("../config/config.json"), true);
    $dbConnect = $database->connect($config_db['db_name'], $config_db['user'], $config_db['password']);
    $statement = $dbConnect->prepare("SELECT image,title,chapô,content,tags,author,DATE_FORMAT(date_creation,'%d %M %Y') AS date_article FROM articles");
    $statement->execute();
    $articles = [];
    while ($row = $statement->fetch()) :
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
    endwhile;


    return $articles;
  }
}
