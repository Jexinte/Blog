<?php

namespace Repository;

use Config\DatabaseConnection;
use Enumeration\UserType;
use Model\ArticleModel;
use DateTime;
use IntlDateFormatter;

class ArticleRepository
{

  public function __construct(private readonly DatabaseConnection   $connector)
  {
  }


  public function getArticles(): array
  {

    $dbConnect = $this->connector->connect();

    $statementToGetArticles = $dbConnect->prepare("SELECT id,title,chapô,content,tags,author,DATE_FORMAT(date_creation,'%d %M %Y') AS date_article  FROM article ORDER BY id DESC");
    $statementToGetArticles->execute();

    $articles = [];


    while ($rowOfArticle = $statementToGetArticles->fetch()) {
      $frenchDateFormat = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

      $dateOfCreation = $frenchDateFormat->format(new DateTime($rowOfArticle["date_article"]));
      $statementToGetUserProfileImageRegardlessToAuthorNameOfArticle = $dbConnect->prepare("SELECT profile_image AS image, username FROM user WHERE username = :author");
      $statementToGetUserProfileImageRegardlessToAuthorNameOfArticle->bindParam("author", $rowOfArticle["author"]);
      $statementToGetUserProfileImageRegardlessToAuthorNameOfArticle->execute();
      $data = [];
      while ($rowOfUserData = $statementToGetUserProfileImageRegardlessToAuthorNameOfArticle->fetch()) {
        $data = [
          "id" => $rowOfArticle["id"],
          "image_user" => $rowOfUserData['image'],
          "title" => $rowOfArticle['title'],
          "short_phrase" => $rowOfArticle['chapô'],
          "content" => substr($rowOfArticle['content'], 0, 250) . '...',
          "tags" => $rowOfArticle['tags'],
          "author" => $rowOfArticle['author'],
          "date_of_publication" => ucfirst($dateOfCreation)
        ];
      }
      $articles[] = $data;
    }


    return $articles;
  }


  public function getArticle(int $id): array
  {
    $dbConnect = $this->connector->connect();
    $statementToGetArticle = $dbConnect->prepare("SELECT id, image,title,chapô,content,tags,author,DATE_FORMAT(date_creation,'%d %M %Y') AS date_article FROM article WHERE id = :id");
    $statementToGetArticle->bindParam("id", $id);
    $statementToGetArticle->execute();
    $article = [];
    while ($rowOfArticle = $statementToGetArticle->fetch()) {
      $french_date_format = new IntlDateFormatter('fr_FR', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

      $dateOfArticleCreation = $french_date_format->format(new DateTime($rowOfArticle["date_article"]));
      $statementToGetUserDataRegardlessToAuthorNameOfArticle = $dbConnect->prepare("SELECT profile_image, username FROM user WHERE username = :author");
      $statementToGetUserDataRegardlessToAuthorNameOfArticle->bindParam("author", $rowOfArticle["author"]);
      $statementToGetUserDataRegardlessToAuthorNameOfArticle->execute();
      while ($rowOfUserData = $statementToGetUserDataRegardlessToAuthorNameOfArticle->fetch()) {

        $data = [
          "id" => intval($rowOfArticle["id"]),
          "image" => $rowOfArticle['image'],
          "author_image" => $rowOfUserData["profile_image"],
          "title" => $rowOfArticle['title'],
          "short_phrase" => $rowOfArticle['chapô'],
          "content" => $rowOfArticle["content"],
          "tags" => $rowOfArticle['tags'],
          "author" => $rowOfArticle['author'],
          "date_of_publication" => ucfirst($dateOfArticleCreation)
        ];
      }
      $article[] = $data;
    }
    return $article;
  }

  public function createArticle(string $title, string $file, string $shortPhrase, string $content, array $tags, array $sessionData): ?ArticleModel
  {

    $dbConnect = $this->connector->connect();
    $articleModel = new ArticleModel($file, $title, $shortPhrase, $content, $tags, null);
    $idSession = $sessionData["session_id"];
    $usernameSession = $sessionData["username"];
    $typeUserSession = $sessionData["type_user"];
    $statementToCheckIfAdminIsCreatingArticle = $dbConnect->prepare("SELECT id_session,username,user_type FROM session WHERE id_session = :id_from_session_variable AND username = :username_from_session_variable AND user_type = :type_user_from_session_variable");

    $statementToCheckIfAdminIsCreatingArticle->bindParam("id_from_session_variable", $idSession);
    $statementToCheckIfAdminIsCreatingArticle->bindParam("username_from_session_variable", $usernameSession);
    $statementToCheckIfAdminIsCreatingArticle->bindParam("type_user_from_session_variable", $typeUserSession);

    $statementToCheckIfAdminIsCreatingArticle->execute();
    $admin = $statementToCheckIfAdminIsCreatingArticle->fetch();
    if ($admin["user_type"] === UserType::ADMIN->value && $admin["username"] == $sessionData["username"]) {

      $titleArticle = $articleModel->getTitle();
      $fileArticle = $articleModel->getImage();
      $shortPhraseArticle = $articleModel->getChapo();
      $contentArticle = $articleModel->getContent();
      $tagsArticle = $articleModel->getTags()["tags"];

      $fileRequirements = explode(';', $fileArticle);
      $fileSettings["file_name"] = $fileRequirements[0];
      $fileSettings["tmp_name"] = $fileRequirements[1];
      $fileSettings["directory"] = $fileRequirements[2];
      $filePath = "http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/" . $fileSettings["file_name"];

      $statementToCreateArticle = $dbConnect->prepare("INSERT INTO article (image,title,chapô,content,tags,author,date_creation) VALUES(:fileArticle,:titleArticle,:shortPhraseArticle,:contentArticle,:tagsArticle,:authorArticle,:dateArticle)");

      $statementToCreateArticle->bindParam(':fileArticle', $filePath);
      $statementToCreateArticle->bindParam(':titleArticle', $titleArticle);
      $statementToCreateArticle->bindParam(':shortPhraseArticle', $shortPhraseArticle);
      $statementToCreateArticle->bindParam(':contentArticle', $contentArticle);
      $statementToCreateArticle->bindParam(':tagsArticle', $tagsArticle);
      $statementToCreateArticle->bindParam(':authorArticle', $usernameSession);
      $statementToCreateArticle->bindValue(':dateArticle', date('Y-m-d'));
      $statementToCreateArticle->execute();
      move_uploaded_file($fileSettings["tmp_name"], $fileSettings["directory"] . "/" . $fileSettings["file_name"]);
      $articleModel->isArticleCreated(true);
      return $articleModel;
    }
  }

  public function updateArticle(array $updateArticleData, array $sessionData): ?array
  {
    $dbConnect = $this->connector->connect();
    $idSession = $sessionData["session_id"];
    $usernameSession = $sessionData["username"];
    $typeUserSession = $sessionData["type_user"];
    $statementToCheckIfAdminIsUpdatingArticle = $dbConnect->prepare("SELECT id_session,username,user_type FROM session WHERE id_session = :id_from_session_variable AND username = :username_from_session_variable AND user_type = :type_user_from_session_variable");

    $statementToCheckIfAdminIsUpdatingArticle->bindParam("id_from_session_variable", $idSession);
    $statementToCheckIfAdminIsUpdatingArticle->bindParam("username_from_session_variable", $usernameSession);
    $statementToCheckIfAdminIsUpdatingArticle->bindParam("type_user_from_session_variable", $typeUserSession);

    $statementToCheckIfAdminIsUpdatingArticle->execute();
    $admin = $statementToCheckIfAdminIsUpdatingArticle->fetch();

    if ($admin["user_type"] === UserType::ADMIN->value) {
      $titleUpdateArticle = $updateArticleData["title"];
      $fileUpdateArticle = $updateArticleData["file"];
      $shortPhraseUpdateArticle = $updateArticleData["short_phrase"];
      $contentUpdateArticle = $updateArticleData["content"];
      $tagsUpdateArticle = $updateArticleData["tags"];
      $idUpdateArticle  = $updateArticleData["id_article"];
      $dateOfTheDay = date('Y-m-d');
      switch (true) {
        case is_array($fileUpdateArticle):
          $fileRequirements = explode(';', implode(';', $fileUpdateArticle));
          $fileSettings["file_name"] = $fileRequirements[0];
          $fileSettings["tmp_name"] = $fileRequirements[1];
          $fileSettings["directory"] = $fileRequirements[2];
          $filePath = "http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/" . $fileSettings["file_name"];
          $statementWithUploadedFile = $dbConnect->prepare("UPDATE article SET image = :filePath,title = :titleUpdateArticle,chapô  = :shortPhraseUpdateArticle,content = :contentUpdateArticle,tags = :tagsUpdateArticle,author = :authorArticle,date_creation = :dateUpdateArticle WHERE id = :idUpdateArticle");
          $statementWithUploadedFile->bindParam(':filePath', $filePath);
          $statementWithUploadedFile->bindParam(':titleUpdateArticle', $titleUpdateArticle);
          $statementWithUploadedFile->bindParam(':shortPhraseUpdateArticle', $shortPhraseUpdateArticle);
          $statementWithUploadedFile->bindParam(':contentUpdateArticle', $contentUpdateArticle);
          $statementWithUploadedFile->bindParam(':tagsUpdateArticle', $tagsUpdateArticle);
          $statementWithUploadedFile->bindParam(':authorArticle', $usernameSession);
          $statementWithUploadedFile->bindParam(':dateUpdateArticle', $dateOfTheDay);
          $statementWithUploadedFile->bindParam(':idUpdateArticle', $idUpdateArticle);
          $statementWithUploadedFile->execute();
          move_uploaded_file($fileSettings["tmp_name"], $fileSettings["directory"] . "/" . $fileSettings["file_name"]);
          return ["article_updated" => 1];

        case !is_array($fileUpdateArticle):

          $statementWithoutUploadedFile = $dbConnect->prepare("UPDATE article SET image = :filePath,title = :titleUpdateArticle,chapô  = :shortPhraseUpdateArticle,content = :contentUpdateArticle,tags = :tagsUpdateArticle,author = :authorArticle,date_creation = :dateUpdateArticle WHERE id = :idUpdateArticle");
          $statementWithoutUploadedFile->bindParam(':filePath', $fileUpdateArticle);
          $statementWithoutUploadedFile->bindParam(':titleUpdateArticle', $titleUpdateArticle);
          $statementWithoutUploadedFile->bindParam(':shortPhraseUpdateArticle', $shortPhraseUpdateArticle);
          $statementWithoutUploadedFile->bindParam(':contentUpdateArticle', $contentUpdateArticle);
          $statementWithoutUploadedFile->bindParam(':tagsUpdateArticle', $tagsUpdateArticle);
          $statementWithoutUploadedFile->bindParam(':authorArticle', $usernameSession);
          $statementWithoutUploadedFile->bindParam(':dateUpdateArticle', $dateOfTheDay);
          $statementWithoutUploadedFile->bindParam(':idUpdateArticle', $idUpdateArticle);
          $statementWithoutUploadedFile->execute();
          return ["article_updated" => 1];
      }
    }
  }
  public function deleteArticle(int $idArticle, array $sessionData): ?array
  {

    $dbConnect = $this->connector->connect();
    $idSession = $sessionData["session_id"];
    $usernameSession = $sessionData["username"];
    $typeUserSession = $sessionData["type_user"];
    $statementToCheckIfAdminIsDeletingArticle = $dbConnect->prepare("SELECT id_session,username,user_type FROM session WHERE id_session = :id_from_session_variable AND username = :username_from_session_variable AND user_type = :type_user_from_session_variable");

    $statementToCheckIfAdminIsDeletingArticle->bindParam("id_from_session_variable", $idSession);
    $statementToCheckIfAdminIsDeletingArticle->bindParam("username_from_session_variable", $usernameSession);
    $statementToCheckIfAdminIsDeletingArticle->bindParam("type_user_from_session_variable", $typeUserSession);

    $statementToCheckIfAdminIsDeletingArticle->execute();
    $admin = $statementToCheckIfAdminIsDeletingArticle->fetch();

    if ($admin["user_type"] === UserType::ADMIN->value) {

      $statement = $dbConnect->prepare("DELETE FROM article WHERE id = :id");
      $statement->bindParam("id", $idArticle);
      $statement->execute();
      return ["article_deleted" => 1];
    }
  }
}
