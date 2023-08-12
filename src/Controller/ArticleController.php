<?php

/**
 * Handle Article Validation
 * 
 * PHP version 8
 *
 * @category Controller
 * @package  ArticleController
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */

namespace Controller;


use Enumeration\Regex;
use Exceptions\ValidationException;
use Model\ArticleModel;
use Repository\ArticleRepository;

/**
 * Summary of ArticleController
 * 
 * @category Controller
 * @package  ArticleController
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class ArticleController
{

    /**
     * Summary of __construct
     *
     * @param \Repository\ArticleRepository $articleRepository ArticleRepository
     */
    public function __construct(private readonly ArticleRepository $articleRepository
    ) {
    }
    
    /**
     * Summary of listOfAllArticles
     *
     * @return array
     */
    public function listOfAllArticles(): array
    {

        return $this->articleRepository->getArticles();
    }

    /**
     * Summary of handleOneArticle
     *
     * @param int $id idArticle
     * 
     * @return array|null
     */
    public function handleOneArticle(int $id): ?array
    {
        $result = !empty($id);

        return $result ? $this->articleRepository->getArticle($id) : null;
    }




    /**
     * Summary of handleFileField
     *
     * @param array $fileArticle img
     * 
     * @return array|string
     */
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

            if (in_array(
                $extensionOfTheUploadedFile[1], 
                $authorizedExtensionsArticle
            )
            ) {
                $bytesToStr = str_replace("/", "", base64_encode(random_bytes(9)));
                $fileExt = explode('.', $filenameArticle);
                $filenameGeneratedArticle = $bytesToStr . "." . $fileExt[1];

                return ["file" => 
                "$filenameGeneratedArticle
                ;$filenameTmpArticle
                ;$dirImagesUpdateArticle"
                ];
            } else {
                throw $validationException->setTypeAndValueOfException("file_exception", $validationException::FILE_MESSAGE_ERROR_TYPE_FILE);
            }

        default:
            throw $validationException->setTypeAndValueOfException("file_exception", $validationException::FILE_MESSAGE_ERROR_NO_FILE_SELECTED);
        }
    }


    /**
     * Summary of handleTextField
     *
     * @param string                          $keyArray             key of value being processed
     * @param string                          $value                value being processed
     * @param string                          $keyException         key of the exception thrown
     * @param \Exceptions\ValidationException $exception            exception thrown of vlaue being processed
     * @param string                          $regex                regex
     * @param string                          $emptyException       exception for empty field
     * @param string                          $wrongFormatException exception for wrong value
     * 
     * @return string|array
     */
    public function handleTextField(
        string $keyArray, 
        string $value, 
        string $keyException, 
        ValidationException $exception, 
        string $regex, 
        string $emptyException, 
        string $wrongFormatException
    ): string|array {
        switch (true) {
        case empty($value):
            throw $exception->setTypeAndValueOfException($keyException, $emptyException);
        case !preg_match($regex, $value):
            throw $exception->setTypeAndValueOfException($keyException, $wrongFormatException);
        default:
            return [$keyArray => $value];
        }
    }

    /**
     * Summary of handleCreateArticleValidator
     *
     * @param string $title       of article
     * @param array  $fileArticle article
     * @param string $shortPhrase of article
     * @param string $content     of article
     * @param string $tags        of article
     * @param array  $sessionData session data
     * @param string $idCookie    id of actual cookie
     * 
     * @return ArticleModel
     */
    public function handleCreateArticleValidator(
        string $title, 
        array $fileArticle, 
        string $shortPhrase, 
        string $content, 
        string $tags, 
        array $sessionData, 
        string $idCookie
    ): ?ArticleModel {

        $validationException = new ValidationException();

        $exceptionKeyArray =[
        "title_field" => 
        "title_exception",
        "short_phrase_field" => 
        "short_phrase_exception",
        "content_field" => 
        "content_article_exception",
        "tags_field" => 
        "tags_exception"
        ];
        $keyArrayWhenAFieldIsTreated =[
        "title_field" => "title",
        "short_phrase_field" => "short_phrase",
        "content_field" => "content",
        "tags_field" => "tags"
        ];

        $exceptionByField = [
        "error_empty" => 
        $validationException::ERROR_EMPTY,
        "title_exception" => 
        $validationException::TITLE_MESSAGE_ERROR_MAX_255_CHARS,
        "short_phrase_exception" =>
         $validationException::SHORT_PHRASE_MESSAGE_ERROR_MAX_255_CHARS,
        "content_exception" => 
        $validationException::CONTENT_ARTICLE_MESSAGE_ERROR_MAX_5000_CHARS,
        "tags_exception" => 
        $validationException::TAGS_MESSAGE_ERROR_MAX_3_TAGS
        ];

        $regexByField = [
        "title_regex" => REGEX::TITLE,
        "short_phrase_regex" => REGEX::SHORT_PHRASE,
        "content_regex" => REGEX::CONTENT_ARTICLE,
        "tags_regex" => REGEX::TAGS
        ];

        $titleField = $this->handleTextField(
            $keyArrayWhenAFieldIsTreated
            ["title_field"], 
            $title, 
            $exceptionKeyArray["title_field"], 
            $validationException, 
            $regexByField["title_regex"], 
            $exceptionByField["error_empty"], 
            $exceptionByField["title_exception"]
        )["title"];

        $fileField = $this->handleFileField($fileArticle)["file"];

        $shortPhraseField = $this->handleTextField(
            $keyArrayWhenAFieldIsTreated
            ["short_phrase_field"], 
            $shortPhrase, 
            $exceptionKeyArray["short_phrase_field"], 
            $validationException, 
            $regexByField["short_phrase_regex"], 
            $exceptionByField["error_empty"], 
            $exceptionByField["short_phrase_exception"]
        )["short_phrase"];

        $contentField = $this->handleTextField(
            $keyArrayWhenAFieldIsTreated
            ["content_field"], 
            $content, 
            $exceptionKeyArray["content_field"], 
            $validationException, 
            $regexByField["content_regex"], 
            $exceptionByField["error_empty"], 
            $exceptionByField["content_exception"]
        )["content"];

        $tagsField = $this->handleTextField(
            $keyArrayWhenAFieldIsTreated
            ["tags_field"], 
            $tags, 
            $exceptionKeyArray["tags_field"], 
            $validationException, 
            $regexByField["tags_regex"], 
            $exceptionByField["error_empty"], 
            $exceptionByField["tags_exception"]
        );



        $articleModel = new ArticleModel($fileField, $titleField, $shortPhraseField, $contentField, $tagsField, null);

        $articleResult = $this->articleRepository->createArticle($articleModel, $sessionData, $idCookie);

        if ($articleResult) {
            return $articleResult;
        }
    }



    /**
     * Summary of handleUpdateTitleValidation
     *
     * @param string $title of article
     * 
     * @return string|array
     */
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


    /**
     * Summary of handleUpdateShortPhraseValidation
     *
     * @param string $shortPhrase of article
     * 
     * @return string|array
     */
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

    /**
     * Summary of handleUpdateContentValidation
     *
     * @param string $content of article
     * 
     * @return string|array
     */
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



    /**
     * Summary of handleUpdateValidationOnNumberOfTagsAuthorized
     *
     * @param string $value                  of article
     * @param int    $numberOfTagsAuthorized of article
     * 
     * @return string|array
     */
    public function handleUpdateValidationOnNumberOfTagsAuthorized(string $value, int $numberOfTagsAuthorized): string|array
    {
        $validationException = new ValidationException();

        $arr = explode(" ", $value);
        if (in_array("", $arr)) {
            throw $validationException->setTypeAndValueOfException("tags_exception", $validationException::TAGS_MESSAGE_ERROR_MAX_3_TAGS);
        }

        $counter = 0;
        if (!is_null($arr)
            && count($arr) == 3
        ) {
            foreach ($arr as $v) {
                if ($v[0] === "#" 
                    && isset($v[1]) 
                    && ctype_upper($v[1])
                ) {
                    $counter++;
                }
            }
        }

        return $counter === $numberOfTagsAuthorized ? ["tags" => $value] : throw $validationException->setTypeAndValueOfException("tags_exception", $validationException::TAGS_MESSAGE_ERROR_MAX_3_TAGS);
    }

    /**
     * Summary of handleUpdateValidationOnFilePath
     *
     * @param array  $filePathFromForm         of article
     * @param string $originalPathFromDatabase of article
     * 
     * @return string|array|null
     */
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
    /**
     * Summary of handleUpdateArticleValidator
     *
     * @param string $title                       of article
     * @param array  $fileArticle                 of article
     * @param string $hiddenInputFileOriginalPath of article
     * @param string $shortPhrase                 of article
     * @param string $content                     of article
     * @param string $tags                        of article
     * @param array  $sessionData                 sessiondata
     * @param int    $idArticle                   of article
     * @param string $idCookie                    phpsessid
     * 
     * @return array|null
     */
    public function handleUpdateArticleValidator(string $title, array $fileArticle, string $hiddenInputFileOriginalPath, string $shortPhrase, string $content, string $tags, array $sessionData, int $idArticle, string $idCookie): ?array
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

        return $this->articleRepository->updateArticle($fields, $sessionData, $idCookie);
    }


    /**
     * Summary of handleDeleteArticle
     *
     * @param int    $idArticle   of article
     * @param array  $sessionData sessiondata
     * @param string $idCookie    phpsessid
     * 
     * @return array|null
     */
    public function handleDeleteArticle(int $idArticle, array $sessionData, string $idCookie): ?array
    {
        return $this->articleRepository->deleteArticle($idArticle, $sessionData, $idCookie);
    }
}
