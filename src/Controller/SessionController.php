<?php

/**
 * Handle Sessions for Users 
 * 
 * PHP version 8
 *
 * @category Controller
 * @package  SessionController
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */

namespace Controller;


use Manager\Session;

/**
 * Handle Sessions for Users 
 * 
 * PHP version 8
 *
 * @category Controller
 * @package  SessionController
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */

class SessionController
{

    /**
     * Summary of __construct
     *
     * @param \Manager\Session $session sessionmanager
     */
    public function __construct(private Session $session)
    {
    }

    /**
     * Summary of initializeLoginDataAndSessionId
     *
     * @param array $loginData from user
     * 
     * @return void
     */
    public function initializeLoginDataAndSessionId(array $loginData):void
    {
        $this->session->initializeKeyAndValue("username", $loginData["username"]);
        $this->session->initializeKeyAndValue("id_user", $loginData["id_user"]);
        $this->session->initializeKeyAndValue("type_user", $loginData["type_user"]);
        $this->session->initializeKeyAndValue("session_id", session_id());
  
    }

    /**
     * Summary of initializeIdArticle
     *
     * @param int $articleId of article
     * 
     * @return void
     */
    public function initializeIdArticle(int $articleId):void
    {
        $this->session->initializeKeyAndValue("id_article", $articleId);
    }



    /**
     * Summary of handleGetSessionData
     *
     * @return array of sessiondata
     */
    public function handleGetSessionData():array
    {
  
        return $this->session->getSessionData();
    }

}
