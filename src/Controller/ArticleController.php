<?php

namespace Controller;


use Model\ArticleModel;


use Exceptions\TitleErrorEmptyException;
use Exceptions\TitleWrongFormatException;
use Exceptions\FileErrorEmptyException;
use Exceptions\FileTypeException;
use Exceptions\ShortPhraseErrorEmptyException;
use Exceptions\ShortPhraseWrongFormatException;
use Exceptions\ContentArticleErrorEmptyException;
use Exceptions\ContentArticleWrongFormatException;
use Exceptions\TagsErrorEmptyException;
use Exceptions\TagsWrongFormatException;
use Repository\ArticleRepository;

use Enumeration\Regex;

class ArticleController
{

  public function __construct(private readonly ArticleRepository $article)
  {
  }
  public function listOfAllArticles(): array
  {

    return $this->article->getArticles();
  }

  public function handleOneArticle(int $id): ?array
  {
    $result = !empty($id);

    return $result ? $this->article->getArticle($id) : null;
  }



  public function handleTitleField(string $title): array|string
  {
    switch (true) {
      case empty($title):
        throw new TitleErrorEmptyException();
      case !preg_match(Regex::TITLE, $title):
        throw new TitleWrongFormatException();
      default:
        return ["title" => $title];
    }
  }

  public function handleFileField(array $fileArticle): array|string
  {
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
          throw new FileTypeException();
        }

      default:
        throw new FileErrorEmptyException(FileErrorEmptyException::FILE_MESSAGE_ERROR_NO_FILE_SELECTED);
    }
  }
  public function handleShortPhraseField(string $shortPhrase): array|string
  {
    switch (true) {
      case empty($shortPhrase):
        throw new ShortPhraseErrorEmptyException();
      case !preg_match(Regex::SHORT_PHRASE, $shortPhrase):
        throw new ShortPhraseWrongFormatException();
      default:
        return ["short_phrase" => $shortPhrase];
    }
  }
  public function handleContentField(string $content): array|string
  {

    switch (true) {
      case empty($content):
        throw new ContentArticleErrorEmptyException();
      case !preg_match(REGEX::CONTENT_ARTICLE, $content):
        throw new ContentArticleWrongFormatException();
      default:
        return ["content" => $content];
    }
  }
  public function handleTagsField(string $tags): array|string
  {

    switch (true) {
      case empty($tags):
        throw new TagsErrorEmptyException();
      case !preg_match(REGEX::TAGS, $tags):
        throw new TagsWrongFormatException();
      default:
        return ["tags" => $tags];
    }
  }


  public function handleCreateArticleValidator(string $title, array $fileArticle, string $shortPhrase, string $content, string $tags, array $sessionData): ?ArticleModel
  {
    $articleRepository = $this->article;
    $titleField = $this->handleTitleField($title)["title"];
    $fileField = $this->handleFileField($fileArticle)["file"];
    $shortPhraseField = $this->handleShortPhraseField($shortPhrase)["short_phrase"];
    $contentField = $this->handleContentField($content)["content"];
    $tagsField = $this->handleTagsField($tags);


    $articleModel = new ArticleModel($fileField, $titleField, $shortPhraseField, $contentField, $tagsField, null);

    $titleInModel = $articleModel->getTitle();
    $fileInModel = $articleModel->getImage();
    $shortPhraseInModel = $articleModel->getChapo();
    $contentInModel = $articleModel->getContent();
    $tagsInModel = $articleModel->getTags();
    $articleResult = $articleRepository->createArticle($titleInModel, $fileInModel, $shortPhraseInModel, $contentInModel, $tagsInModel, $sessionData);

    if ($articleResult) {
      return $articleResult;
    }
  }



  public function handleUpdateTitleValidation(string $title): string|array
  {
    $expectedWord = ucfirst($title);

    switch (true) {
      case empty($title):
        throw new TitleErrorEmptyException();
      case $title != $expectedWord || strlen($title) > 500:
        throw new TitleWrongFormatException();

      default:
        return ["title" => $title];
    }
  }


  public function handleUpdateShortPhraseValidation(string $shortPhrase): string|array
  {

    $expectedWord = ucfirst($shortPhrase);

    switch (true) {
      case empty($shortPhrase):
        throw new ShortPhraseErrorEmptyException();
      case $shortPhrase != $expectedWord:
        throw new ShortPhraseWrongFormatException();
      case strlen($shortPhrase) > 500:
        throw new ShortPhraseWrongFormatException();
      default:
        return ["short_phrase" => $shortPhrase];
    }
  }

  public function handleUpdateContentValidation(string $content): string|array
  {
    $expectedWord = ucfirst($content);

    switch (true) {
      case empty($content):
        throw new ContentArticleErrorEmptyException();
      case $content != $expectedWord:
        throw new ContentArticleWrongFormatException();
      case strlen($content) > 500:
        throw new ContentArticleWrongFormatException();
      default:
        return ["content" => $content];
    }
  }



  public function handleUpdateValidationOnNumberOfTagsAuthorized(string $value, int $numberOfTagsAuthorized): string|array
  {
    $result = count(explode(" ", $value)) == 3 ? explode(' ', $value) : null;
    $counter = 0;
    if (!is_null($result)) {
      foreach ($result as $v) {
        if ($v[0] === "#" && isset($v[1]) && ctype_upper($v[1])) $counter++;
      }
    }

    return $counter === $numberOfTagsAuthorized ? ["tags" => $value] : throw new TagsWrongFormatException();
  }

  public function handleUpdateValidationOnFilePath(array $filePathFromForm, string $originalPathFromDatabase): string|array|null
  {

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
          } else throw new FileTypeException();
        }
        return null;
    }
  }
  public function handleUpdateArticleValidator(string $title, array $fileArticle, string $hiddenInputFileOriginalPath, string $shortPhrase, string $content, string $tags, array $sessionData, int $idArticle): ?array
  {

    $articleRepository = $this->article;
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

    return $articleRepository->updateArticle($fields, $sessionData);
  }


  public function handleDeleteArticle(int $idArticle, array $sessionData): ?array
  {
    $articleRepository = $this->article;
    return $articleRepository->deleteArticle($idArticle, $sessionData);
  }
}
