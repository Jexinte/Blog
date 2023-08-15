<?php
/**
 * Handle user types
 * 
 * PHP version 8
 *
 * @category Enumeration
 * @package  Usertype
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */

namespace Enumeration;

/**
 * UserType Enumeration
 * 
 * PHP version 8
 *
 * @category Enumeration
 * @package  Usertype
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
enum UserType: string
{
    case ADMIN = "admin";
    case USER = "user";
}
