<?php

/**
 * Handle Selections
 * 
 * PHP version 8
 *
 * @category Enumeration
 * @package  Selection
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */

namespace Enumeration;

/**
 * Handle Selections
 * 
 * PHP version 8
 *
 * @category Enumeration
 * @package  Selection
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
enum Selection: string
{
    const SIGN_UP = "sign_up";
    const SIGN_IN = "sign_in";
    const HOMEPAGE = "homepage";
    const CONTACT = "contact";
    const BLOG = "blog";
    const ADMIN_PANEL = "admin_panel";
    const COMMENT_DETAILS = "comment_details";
    const ARTICLE = "article";
    const ADD_ARTICLE = "add_article";
    const VIEW_UPDATE_ARTICLE = "view_update_article";
    const NOTIFICATIONS = "notifications";
    const LOGOUT = "logout";
    const ADD_COMMENT = "add_comment";
    const VALIDATION = "validation";
    const DELETE_NOTIFICATION = "delete_notification";
}
