<?php

/**
 * Handle sessions
 * 
 * PHP version 8
 *
 * @category Manager
 * @package  Session
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */

namespace Manager;

/**
 * Session class
 * 
 * PHP version 8
 *
 * @category Manger
 * @package  Session
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class Session
{
    private string $_cookieId;


 
    /**
     * Summary of startSession
     * 
     * @return void
     */
    public function startSession(): void
    {

        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        } 

    }

    /**
     * Summary of destroySession
     * 
     * @return void
     */
    public function destroySession(): void
    {

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }

    /**
     * Summary of getIdInCookie
     * 
     * @return string
     */
    public function getIdInCookie():string
    {
        $this->_cookieId = !empty($_COOKIE["PHPSESSID"]) 
        ? $_COOKIE["PHPSESSID"] : "";
        return $this->_cookieId;
    }


    /**
     * Summary of initializeKeyAndValue
     *
     * @param string          $key   
     * @param string|null|int $value 
     * 
     * @return void
     */
    public function initializeKeyAndValue(string $key, string|null|int $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Summary of getSessionData
     * 
     * @return array
     */
    public function getSessionData(): array
    {
        return $_SESSION;
    }
}
