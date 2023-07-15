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
    $titleRegex = "/^(?=.{1,51}$)[A-ZÀ-ÿ][A-Za-zÀ-ÿ -']*$/";
    switch (true) {
      case empty($title):
        header("HTTP/1.1 400");
        throw new TitleErrorEmptyException();
      case !preg_match($titleRegex, $title):
        header("HTTP/1.1 400");
        throw new TitleWrongFormatException();
      default:
        return ["title" => $title];
    }
  }

  public function handleFileField(array $file): array|string
  {
    switch (true) {
      case !empty($file["name"]) && $file["error"] == UPLOAD_ERR_OK:
        $filename = $file["name"];
        $dirImages = "../public/assets/images/";
        $filenameTmp = $file['tmp_name'];
        $extensionOfTheUploaded_file = explode('.', $filename);
        $authorizedExtensions = array("jpg", "jpeg", "png", "webp");

        if (in_array($extensionOfTheUploaded_file[1], $authorizedExtensions)) {
          $bytesToStr = str_replace("/", "", base64_encode(random_bytes(9)));
          $filenameAndExtension = explode('.', $filename);
          $filenameGenerated = $bytesToStr . "." . $filenameAndExtension[1];

          return ["file" => "$filenameGenerated;$filenameTmp;$dirImages"];
        } else {
          header("HTTP/1.1 400");
          throw new FileTypeException();
        }

      default:
        header("HTTP/1.1 400");
        throw new FileErrorEmptyException(FileErrorEmptyException::FILE_MESSAGE_ERROR_NO_FILE_SELECTED);
    }
  }
  public function handleShortPhraseField(string $shortPhrase): array|string
  {
    $shortPhraseRegex = "/^(?=.{1,100}$)[A-ZÀ-ÿ][A-Za-zÀ-ÿ -']*$/";
    switch (true) {
      case empty($shortPhrase):
        header("HTTP/1.1 400");
        throw new ShortPhraseErrorEmptyException();
      case !preg_match($shortPhraseRegex, $shortPhrase):
        header("HTTP/1.1 400");
        throw new ShortPhraseWrongFormatException();
      default:
        return ["short_phrase" => $shortPhrase];
    }
  }
  public function handleContentField(string $content): array|string
  {
    $contentRegex = "/^(?=.{1,5000}$)[A-ZÀ-ÿ][A-Za-zÀ-ÿ -']*$/";
    switch (true) {
      case empty($content):
        header("HTTP/1.1 400");
        throw new ContentArticleErrorEmptyException();
      case !preg_match($contentRegex, $content):
        header("HTTP/1.1 400");
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
        header("HTTP/1.1 400");
        throw new TagsErrorEmptyException();
      case !preg_match($tagsRegex, $tags):
        header("HTTP/1.1 400");
        throw new TagsWrongFormatException();
      default:
        return ["tags" => $tags];
    }
  }


  public function handleCreateArticleValidator(string $title, array $file, string $shortPhrase, string $content, string $tags, array $sessionData): void
  {
    $articleRepository = $this->article;
    $titleField = $this->handleTitleField($title);
    $fileField = $this->handleFileField($file);
    $shortPhraseField = $this->handleShortPhraseField($shortPhrase);
    $contentField = $this->handleContentField($content);
    $tagsField = $this->handleTagsField($tags);
    $counter = 0;

    $fields = [
      "title" =>  $titleField,
      "file" =>  $fileField,
      "short_phrase" =>  $shortPhraseField,
      "content" =>  $contentField,
      "tags" =>  $tagsField,
    ];

    foreach ($fields as $v) {
      if (is_array($v)) $counter++;
    }

    if ($counter == 5) {
      $articleRepository->createArticle($fields, $sessionData);
    }
  }
}
