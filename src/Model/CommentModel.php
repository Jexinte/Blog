<?php

/**
 * Handle comment property
 * 
 * PHP version 8
 *
 * @category Model
 * @package  CommentModel
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
namespace Model;

/**
 * Handle comment property
 * 
 * PHP version 8
 *
 * @category Model
 * @package  CommentModel
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class CommentModel
{

    /**
     * Summary of __construct
     *
     * @param int    $idArticle 
     * @param int    $idUser 
     * @param string $comment 
     * @param string $dateCreation 
     * @param mixed  $created 
     */
    public function __construct(
        public int $idArticle,
        private int $idUser,
        public string $comment,
        public string $dateCreation,
        public ?bool $created,
    ) {
    }

    /**
     * Summary of getIdArticle
     * 
     * @return int
     */
    public function getIdArticle():int
    {
        return $this->idArticle;
    }
    /**
     * Summary of getIdUser
     * 
     * @return int
     */
    public function getIdUser():int
    {
        return $this->idUser;
    }
    /**
     * Summary of getComment
     * 
     * @return string
     */
    public function getComment():string
    {
        return $this->comment;
    }
    /**
     * Summary of getDateCreation
     * 
     * @return string
     */
    public function getDateCreation():string
    {
        return $this->dateCreation;
    }

    /**
     * Summary of getCreated
     * 
     * @return bool
     */
    public function getCreated():?bool 
    {
        return $this->created;
    }



    /**
     * Summary of isCreated
     *
     * @param mixed $created 
     * 
     * @return void
     */
    public function isCreated(?bool $created):void
    {
        $this->created = $created;
    }



}
