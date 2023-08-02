<?php

namespace Controller;


use Model\ArticleModel;

use Exceptions\ValidationException;
use Repository\ArticleRepository;

use Enumeration\Regex;

class ArticleController
{

  public function __construct(private readonly ArticleRepository $articleRepository)
  {
  }
  public function listOfAllArticles(): array
  {

    return $this->articleRepository->getArticles();
  }

  public function handleOneArticle(int $id): ?array
  {
    $result = !empty($id);

    return $result ? $this->articleRepository->getArticle($id) : null;
  }



  public function handleTitleField(string $title): array|string
  {
    $validationException = new ValidationException();

    switch (true) {
      case empty($title):
        throw $validationException->setTypeAndValueOfException("title_exception", $validationException::ERROR_EMPTY);
      case !preg_match(Regex::TITLE, $title):
        throw $validationException->setTypeAndValueOfException("title_exception", $validationException::TITLE_MESSAGE_ERROR_MAX_255_CHARS);
      default:
        return ["title" => $title];
    }
  }

  public function handleFileField(array $fileArticle): array|string
  {
    $validationException = new ValidationException();

    switch (true) {
      case !empty($fileArticle["name"]) && $fileArticle["error"] == UPLOAD_ERR_OK:
        $filenameArticle = $fileArticle["name"];
        $dirImagesUpdateArticle = "../public/assets/images/";
        $filenameTmpArticle = $fileArticle['tmp_name'];
        $extensionOfTheUploadedFile = explode('.', $filenameArticle);
        $authorizedExtensionsArticle = array("jpg", "jpeg", "png", "webp");

        if (in_array($extensionOfTheUploadedFile[1], $authorizedExtensionsArticle)) {
          $bytesToStr = str_replace("/", "", base64_encode(random_bytes(9)));
          $filenameAndExtensionArticle = explode('.', $filenameArticle);
          $filenameGeneratedArticle = $bytesToStr . "." . $filenameAndExtensionArticle[1];

          return ["file" => "$filenameGeneratedArticle;$filenameTmpArticle;$dirImagesUpdateArticle"];
        } else {
          throw $validationException->setTypeAndValueOfException("file_exception", $validationException::FILE_MESSAGE_ERROR_TYPE_FILE);
        }

      default:
        throw $validationException->setTypeAndValueOfException("file_exception", $validationException::FILE_MESSAGE_ERROR_NO_FILE_SELECTED);
    }
  }
  public function handleShortPhraseField(string $shortPhrase): array|string
  {
    $validationException = new ValidationException();
    switch (true) {
      case empty($shortPhrase):
        throw $validationException->setTypeAndValueOfException("short_phrase_exception", $validationException::ERROR_EMPTY);
      case !preg_match(Regex::SHORT_PHRASE, $shortPhrase):
        throw $validationException->setTypeAndValueOfException("short_phrase_exception", $validationException::SHORT_PHRASE_MESSAGE_ERROR_MAX_255_CHARS);
      default:
        return ["short_phrase" => $shortPhrase];
    }
  }
  public function handleContentField(string $content): array|string
  {
    $validationException = new ValidationException();
    switch (true) {
      case empty($content):
        throw $validationException->setTypeAndValueOfException("content_article_exception", $validationException::ERROR_EMPTY);
      case !preg_match(REGEX::CONTENT_ARTICLE, $content):
        throw $validationException->setTypeAndValueOfException("content_article_exception", $validationException::CONTENT_ARTICLE_MESSAGE_ERROR_MAX_5000_CHARS);
      default:
        return ["content" => $content];
    }
  }
  public function handleTagsField(string $tags): array|string
  {
    $validationException = new ValidationException();
    switch (true) {
      case empty($tags):
        throw $validationException->setTypeAndValueOfException("tags_exception", $validationException::ERROR_EMPTY);
      case !preg_match(REGEX::TAGS, $tags):
        throw $validationException->setTypeAndValueOfException("tags_exception", $validationException::TAGS_MESSAGE_ERROR_MAX_3_TAGS);
      default:
        return ["tags" => $tags];
    }
  }


  public function handleCreateArticleValidator(string $title, array $fileArticle, string $shortPhrase, string $content, string $tags, array $sessionData,string $idCookie): ?ArticleModel
  {
    
    $titleField = $this->handleTitleField($title)["title"];
    $fileField = $this->handleFileField($fileArticle)["file"];
    $shortPhraseField = $this->handleShortPhraseField($shortPhrase)["short_phrase"];
    $contentField = $this->handleContentField($content)["content"];
    $tagsField = $this->handleTagsField($tags);


    $articleModel = new ArticleModel($fileField, $titleField, $shortPhraseField, $contentField, $tagsField, null);

    $articleResult = $this->articleRepository->createArticle($articleModel,$sessionData,$idCookie);

    if ($articleResult) {
      return $articleResult;
    }
  }



  public function handleUpdateTitleValidation(string $title): string|array
  {
    $expectedWord = ucfirst($title);
    $validationException = new ValidationException();


    switch (true) {
      case empty($title):
        throw $validationException->setTypeAndValueOfException("title_exception", $validationException::ERROR_EMPTY);
      case $title != $expectedWord || strlen($title) > 255:
        throw $validationException->setTypeAndValueOfException("title_exception", $validationException::TITLE_MESSAGE_ERROR_MAX_255_CHARS);
      default:
        return ["title" => $title];
    }
  }


  public function handleUpdateShortPhraseValidation(string $shortPhrase): string|array
  {

    $expectedWord = ucfirst($shortPhrase);
    $validationException = new ValidationException();


    switch (true) {
      case empty($shortPhrase):
        throw $validationException->setTypeAndValueOfException("short_phrase_exception", $validationException::ERROR_EMPTY);
      case $shortPhrase != $expectedWord:
        throw $validationException->setTypeAndValueOfException("short_phrase_exception", $validationException::SHORT_PHRASE_MESSAGE_ERROR_MAX_255_CHARS);
      case strlen($shortPhrase) > 255:
        throw $validationException->setTypeAndValueOfException("short_phrase_exception", $validationException::SHORT_PHRASE_MESSAGE_ERROR_MAX_255_CHARS);
      default:
        return ["short_phrase" => $shortPhrase];
    }
  }

  public function handleUpdateContentValidation(string $content): string|array
  {
    $expectedWord = ucfirst($content);
    $validationException = new ValidationException();

    switch (true) {
      case empty($content):
        throw $validationException->setTypeAndValueOfException("content_article_exception", $validationException::ERROR_EMPTY);
      case $content != $expectedWord:
        throw $validationException->setTypeAndValueOfException("content_article_exception", $validationException::CONTENT_ARTICLE_MESSAGE_ERROR_MAX_5000_CHARS);
      case strlen($content) > 5000:
        throw $validationException->setTypeAndValueOfException("content_article_exception", $validationException::CONTENT_ARTICLE_MESSAGE_ERROR_MAX_5000_CHARS);
      default:
        return ["content" => $content];
    }
  }



  public function handleUpdateValidationOnNumberOfTagsAuthorized(string $value, int $numberOfTagsAuthorized): string|array
  {
    $validationException = new ValidationException();

    $result = count(explode(" ", $value)) == 3 ? explode(' ', $value) : null;
    $counter = 0;
    if (!is_null($result)) {
      foreach ($result as $v) {
        if ($v[0] === "#" && isset($v[1]) && ctype_upper($v[1])) $counter++;
      }
    }

    return $counter === $numberOfTagsAuthorized ? ["tags" => $value] : throw $validationException->setTypeAndValueOfException("tags_exception", $validationException::TAGS_MESSAGE_ERROR_MAX_3_TAGS);
  }

  public function handleUpdateValidationOnFilePath(array $filePathFromForm, string $originalPathFromDatabase): string|array|null
  {
    $validationException = new ValidationException();

    switch (true) {
      case empty($filePathFromForm["name"]):
        return $originalPathFromDatabase;

      default:
        if ($filePathFromForm["error"] == UPLOAD_ERR_OK) {
          $filenameUpdateArticle = $filePathFromForm["name"];
          $dirImagesUpdateArticle = "../public/assets/images/";
          $filenameTmpUpdateArticle = $filePathFromForm['tmp_name'];
          $extensionOfTheUploadedFile = explode('.', $filenameUpdateArticle);
          $authorizedExtensionsArticle = array("jpg", "jpeg", "png", "webp");

          if (in_array($extensionOfTheUploadedFile[1], $authorizedExtensionsArticle)) {
            $bytesToStr = str_replace("/", "", base64_encode(random_bytes(9)));
            $filenameAndExtensionUpdateArticle = explode('.', $filenameUpdateArticle);
            $filenameGeneratedUpdateArticle = $bytesToStr . "." . $filenameAndExtensionUpdateArticle[1];

            return ["file" => "$filenameGeneratedUpdateArticle;$filenameTmpUpdateArticle;$dirImagesUpdateArticle"];
          } else {

            throw $validationException->setTypeAndValueOfException("file_exception", $validationException::FILE_MESSAGE_ERROR_TYPE_FILE);
          }
        }
        return null;
    }
  }
  public function handleUpdateArticleValidator(string $title, array $fileArticle, string $hiddenInputFileOriginalPath, string $shortPhrase, string $content, string $tags, array $sessionData, int $idArticle,string $idCookie): ?array
  {

    $numberOfTagsAuthorized = 3;
    $titleResult = $this->handleUpdateTitleValidation($title)["title"];
    $shortPhraseResult = $this->handleUpdateShortPhraseValidation($shortPhrase)["short_phrase"];
    $contentResult = $this->handleUpdateContentValidation($content)["content"];
    $fileResult =  $this->handleUpdateValidationOnFilePath($fileArticle, $hiddenInputFileOriginalPath);
    $tagsResult = $this->handleUpdateValidationOnNumberOfTagsAuthorized($tags, $numberOfTagsAuthorized)["tags"];

    $fields = [
      "title" => $titleResult,
      "short_phrase" => $shortPhraseResult,
      "content" => $contentResult,
      "tags" => $tagsResult,
      "file" => $fileResult,
      "id_article" => $idArticle
    ];

    return $this->articleRepository->updateArticle($fields, $sessionData,$idCookie);
  }


  public function handleDeleteArticle(int $idArticle, array $sessionData,string $idCookie): ?array
  {
    return $this->articleRepository->deleteArticle($idArticle, $sessionData,$idCookie);
  }
}
