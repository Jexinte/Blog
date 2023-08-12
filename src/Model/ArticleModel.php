<?php

/**
 * Handle article property
 * 
 * PHP version 8
 *
 * @category Model
 * @package  ArticleModel
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
namespace Model;

/**
 * ArticleModel class
 * 
 * PHP version 8
 *
 * @category Model
 * @package  ArticleModel
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class ArticleModel
{

    /**
     * Summary of __construct
     *
     * @param string $image 
     * @param string $title 
     * @param string $chapo 
     * @param string $content 
     * @param array  $tags 
     * @param mixed  $articleCreated 
     */
    public function __construct(
        public string $image,
        public string $title,
        public string $chapo,
        public string $content,
        public array  $tags,
        public ?bool $articleCreated
    ) {
    }

    /**
     * Summary of getImage
     * 
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }
    /**
     * Summary of getTitle
     * 
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    /**
     * Summary of getChapo
     * 
     * @return string
     */
    public function getChapo(): string
    {
        return $this->chapo;
    }
    /**
     * Summary of getContent
     * 
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
    /**
     * Summary of getTags
     * 
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Summary of getArticleCreated
     * 
     * @return bool
     */
    public function getArticleCreated(): ?bool
    {
        return $this->articleCreated;
    }

    /**
     * Summary of isArticleCreated
     *
     * @param mixed $articleCreated 
     * 
     * @return void
     */
    public function isArticleCreated(?bool $articleCreated): void
    {
        $this->articleCreated = $articleCreated;
    }
}
