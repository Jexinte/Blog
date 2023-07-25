<?php

namespace Controller;


use Model\Article;

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

class ArticleController
{

  public function __construct(private readonly Article $article)
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
    $titleRegex = "/^(?=.{1,500}$)[A-ZÀ-ÿ][A-Za-zÀ-ÿ -']*$/";
    switch (true) {
      case empty($title):
        throw new TitleErrorEmptyException();
      case !preg_match($titleRegex, $title):
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
    $shortPhraseRegex = "/^(?=.{1,500}$)[A-ZÀ-ÿ][A-Za-zÀ-ÿ -']*$/";
    switch (true) {
      case empty($shortPhrase):
        throw new ShortPhraseErrorEmptyException();
      case !preg_match($shortPhraseRegex, $shortPhrase):
        throw new ShortPhraseWrongFormatException();
      default:
        return ["short_phrase" => $shortPhrase];
    }
  }
  public function handleContentField(string $content): array|string
  {
    $contentRegex = "/^(?=.{1,5000}$)[A-ZÀ-ÿ][A-Za-zÀ-ÿ, .'-]*$/u";
    switch (true) {
      case empty($content):
        throw new ContentArticleErrorEmptyException();
      case !preg_match($contentRegex, $content):
        throw new ContentArticleWrongFormatException();
      default:
        return ["content" => $content];
    }
  }
  public function handleTagsField(string $tags): array|string
  {
    $tagsRegex = "/^(#([\p{L} '-]{1,20})(?:\s|$)){1,3}$/";
    switch (true) {
      case empty($tags):
        throw new TagsErrorEmptyException();
      case !preg_match($tagsRegex, $tags):
        throw new TagsWrongFormatException();
      default:
        return ["tags" => $tags];
    }
  }


  public function handleCreateArticleValidator(string $title, array $fileArticle, string $shortPhrase, string $content, string $tags, array $sessionData): ?array
  {
    $articleRepository = $this->article;
    $titleField = $this->handleTitleField($title);
    $fileField = $this->handleFileField($fileArticle);
    $shortPhraseField = $this->handleShortPhraseField($shortPhrase);
    $contentField = $this->handleContentField($content);
    $tagsField = $this->handleTagsField($tags);


    $fields = [
      "title" =>  $titleField["title"],
      "file" =>  $fileField["file"],
      "short_phrase" =>  $shortPhraseField["short_phrase"],
      "content" =>  $contentField["content"],
      "tags" =>  $tagsField["tags"],
    ];


    return $articleRepository->createArticle($fields, $sessionData);
  }



  public function handleUpdateValidationOnCharacterLength(string $value, int $minimumLength, int $maximumLength): bool
  {
    return strlen($value) >= $minimumLength && strlen($value) <= $maximumLength;
  }

  public function handleUpdateValidationOnNumberOfTagsAuthorized(string $value, int $numberOfTagsAuthorized): bool
  {
    $result = count(explode(" ", $value)) == 3 ? explode(' ', $value) : null;
    $counter = 0;
    if (!is_null($result)) {
      foreach ($result as $v) {
        if ($v[0] === "#" && isset($v[1]) && ctype_upper($v[1])) $counter++;
      }
    }

    return $counter === $numberOfTagsAuthorized;
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
          }

          return ["failed_type" => "Seuls les fichiers de type : jpg, jpeg , png et webp sont acceptés !"];
        }
        return null;
    }
  }
  public function handleUpdateArticleValidator(string $title, array $fileArticle, string $hiddenInputFileOriginalPath, string $shortPhrase, string $content, string $tags, array $sessionData, int $idArticle): ?array
  {
    $articleRepository = $this->article;

    $numberOfTagsAuthorized = 3;
    $counterOfFieldsWithoutError = 0;


    $errors = [];


    $titleMinimumLength = 1;
    $titleMaximumLength = 500;
    $shortPhraseMinimumLength = 1;
    $shortPhraseMaximumLength = 500;
    $contentMinimumLength = 1;
    $contentMaximumLength = 5000;

    $this->handleUpdateValidationOnCharacterLength($title, $titleMinimumLength, $titleMaximumLength) ? $counterOfFieldsWithoutError++ : $errors["title_error"] = "Le titre doit minimum posséder 20 caractères et ne peut en excéder 50";

    $this->handleUpdateValidationOnCharacterLength($shortPhrase, $shortPhraseMinimumLength, $shortPhraseMaximumLength) ?  $counterOfFieldsWithoutError++ : $errors["short_phrase_error"] = "Le chapô e doit minimum posséder 20 caractères et ne peut en excéder 100";

    $this->handleUpdateValidationOnCharacterLength($content, $contentMinimumLength, $contentMaximumLength) ? $counterOfFieldsWithoutError++ :  $errors["content_error"] = "Le contenu doit minimum posséder 2000 caractères et ne peut en excéder 5000";

    $this->handleUpdateValidationOnNumberOfTagsAuthorized($tags, $numberOfTagsAuthorized) ? $counterOfFieldsWithoutError++ : $errors["tags_error"] = "Le nombre de tags doit être au nombre de 3 et doit suivre le format suivant #Nomdutag #Nomdutag #Nomdutag";



    $fileResult =  $this->handleUpdateValidationOnFilePath($fileArticle, $hiddenInputFileOriginalPath);

    switch (true) {
      case $counterOfFieldsWithoutError == 4 && is_string($fileResult):
      case $counterOfFieldsWithoutError == 4 && is_array($fileResult) && array_key_exists("file", $fileResult):
        $counterOfFieldsWithoutError++;
        break;
      case $counterOfFieldsWithoutError == 4 && is_array($fileResult) && array_key_exists("failed_type", $fileResult):
        $errors["file_error"] = "Seuls les fichiers de type : jpg, jpeg , png et webp sont acceptés !";
        break;
    }


    if ($counterOfFieldsWithoutError === 5) {
      $fields = [
        "title" => $title,
        "short_phrase" => $shortPhrase,
        "content" => $content,
        "tags" => $tags,
        "file" => $fileResult,
        "id_article" => $idArticle
      ];

      return $articleRepository->updateArticle($fields, $sessionData);
    }
    return $errors;
  }


  public function handleDeleteArticle(int $idArticle, array $sessionData): ?array
  {
    $articleRepository = $this->article;
    return $articleRepository->deleteArticle($idArticle, $sessionData);
  }
}
