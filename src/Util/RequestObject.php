<?php

/**
 * Handle some superglobals
 * 
 * PHP version 8
 *
 * @category Util
 * @package  RequestObject
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
namespace Util;

/**
 * RequestObject class
 * 
 * PHP version 8
 *
 * @category Util
 * @package  RequestObject
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class RequestObject
{

    /**
     * Summary of actionSet
     *
     * @return bool
     */
    public function actionSet():?bool
    {
        if (isset($_GET["action"])) {
            return true;
        }
    }
    /**
     * Summary of selectionSet
     * 
     * @return bool
     */
    public function selectionSet():?bool
    {
        if (isset($_GET["selection"])) {
            return true;
        }
    }
    /**
     * Summary of post
     * 
     * @return array
     */
    public function post() :?array
    {
        if (!empty($_POST)) {
            return $_POST;
        }
    }
    /**
     * Summary of get
     * 
     * @return array
     */
    public function get():?array
    {
        if (!empty($_GET)) {
            return $_GET;
        }
    }


    /**
     * Summary of files
     * 
     * @return array
     */
    public function files():?array
    {
        if (!empty($_FILES)) {
            return $_FILES;
        }
    }
}
