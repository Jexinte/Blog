<?php

/**
 * Handle homepageform property
 * 
 * PHP version 8
 *
 * @category Model
 * @package  HomepageFormModel
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */

namespace Model;

/**
 * HomepageFormModel class
 * 
 * PHP version 8
 *
 * @category Model
 * @package  HomepageFormModel
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class HomepageFormModel
{

    /**
     * Summary of __construct
     *
     * @param string $firstname 
     * @param string $lastname 
     * @param string $email 
     * @param string $subject 
     * @param string $message 
     * @param mixed  $formDataSaved 
     */
    public function __construct(
        public string $firstname,
        public string $lastname,
        public string $email,
        public string $subject,
        public string $message,
        public ?bool $formDataSaved
    ) {
    }




    /**
     * Summary of getFirstname
     * 
     * @return string
     */
    public function getFirstname():string
    {
        return $this->firstname;
    }
    /**
     * Summary of getLastname
     * 
     * @return string
     */
    public function getLastname():string
    {
        return $this->lastname;
    }
    /**
     * Summary of getEmail
     * 
     * @return string
     */
    public function getEmail():string
    {
        return $this->email;
    }
    /**
     * Summary of getSubject
     * 
     * @return string
     */
    public function getSubject():string
    {
        return $this->subject;
    }
    /**
     * Summary of getMessage
     * 
     * @return string
     */
    public function getMessage():string
    {
        return $this->message;
    }

    /**
     * Summary of getFormDataSaved
     * 
     * @return bool
     */
    public function getFormDataSaved():?bool
    {
        return $this->formDataSaved;
    }

    /**
     * Summary of isFormDataSaved
     *
     * @param mixed $formDataSaved 
     * 
     * @return void
     */
    public function isFormDataSaved(?bool $formDataSaved):void
    {
        $this->formDataSaved = $formDataSaved;
    }
}
