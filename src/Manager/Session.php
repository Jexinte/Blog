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
    private array $_session;
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
            $this->_session = &$_SESSION;
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
            $this->_session = [];
        }
    }

    /**
     * Summary of getIdInCookie
     * 
     * @return string
     */
    public function getIdInCookie():string
    {
        $this->_cookieId = $_COOKIE["PHPSESSID"];
        return $this->_cookieId;
    }


    /**
     * Summary of initializeKeyAndValue
     *
     * @param string          $key   of data
     * @param string|null|int $value 
     * 
     * @return void
     */
    public function initializeKeyAndValue(string $key, string|null|int $value): void
    {
        $this->_session[$key] = $value;
    }

    /**
     * Summary of getSessionData
     * 
     * @return array
     */
    public function getSessionData(): array
    {
        return $this->_session;
    }
}
