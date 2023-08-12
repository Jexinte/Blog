<?php

/**
 * Handle notification property
 * 
 * PHP version 8
 *
 * @category Model
 * @package  NotificationModel
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
namespace Model;

/**
 * Notificationmodel class
 * 
 * PHP version 8
 *
 * @category Model
 * @package  NotificationModel
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class NotificationModel
{

    /**
     * Summary of __construct
     *
     * @param int   $idArticle 
     * @param int   $idUser 
     * @param mixed $status 
     * @param mixed $feedbackAdministrator 
     */
    public function __construct(
        public int $idArticle,
        public int $idUser,
        public ?bool $status,
        public ?string $feedbackAdministrator
    ) {
    }

    /**
     * Summary of getIdArticle
     * 
     * @return int
     */
    public function getIdArticle(): int
    {
        return $this->idArticle;
    }
    /**
     * Summary of getIdUser
     * 
     * @return int
     */
    public function getIdUser(): int
    {
        return $this->idUser;
    }
    /**
     * Summary of getStatus
     * 
     * @return mixed
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * Summary of getFeedbackAdministrator
     * 
     * @return mixed
     */
    public function getFeedbackAdministrator(): ?string
    {
        return $this->feedbackAdministrator;
    }
}
