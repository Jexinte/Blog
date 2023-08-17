<?php

/**
 * Handle Actions
 * 
 * PHP version 8
 *
 * @category Enumeration
 * @package  Action
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */

namespace Enumeration;

/**
 * Handle Actions
 * 
 * PHP version 8
 *
 * @category Enumeration
 * @package  Action
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
enum Action: string
{
    const SIGN_UP = "sign_up";
    const SIGN_IN = "sign_in";
    const DOWNLOAD_FILE = "download_file";
    const CONTACT = "contact";
    const ERROR = "error";
    const ADD_ARTICLE = "add_article";
    const UPDATE_ARTICLE = "update_article";
    const DELETE_ARTICLE = "delete_article";
    const LOGOUT = "logout";
    const ADD_COMMENT = "add_comment";
    const VALIDATION = "validation";
    const DELETE_NOTIFICATION = "delete_notification";
}
