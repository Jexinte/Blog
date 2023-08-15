<?php

/**
 * Handle Database
 * 
 * PHP version 8
 *
 * @category Config
 * @package  DatabaseConnection
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */

namespace Config;

use PDO;
use PDOException;

/**
 * DatabaseConnection class
 * 
 * PHP version 8
 *
 * @category Config
 * @package  DatabaseConnection
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class DatabaseConnection
{


    /**
     * Summary of __construct
     *
     * @param string $dbName 
     * @param string $user 
     * @param string $password 
     */
    public function __construct(
        private readonly string $dbName,
        private readonly string $user,
        private readonly string $password
    ) {
    }




    /**
     * Summary of connect
     * 
     * @return string|object
     */
    public function connect() : string|object
    {


        try {
            $connect = new PDO(
                "mysql:host=localhost;dbname=$this->dbName",
                "$this->user", 
                "$this->password"
            );
        } catch (PDOException $e) {
            return "Database Error :" . $e->getMessage();
        }

        return $connect;
    }
}
