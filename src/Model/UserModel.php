<?php

/**
 * Handle user property
 * 
 * PHP version 8
 *
 * @category Model
 * @package  UserModel
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
namespace Model;

use BackedEnum;
use Enumeration\UserType;

/**
 * UserModel class
 * 
 * PHP version 8
 *
 * @category Model
 * @package  UserModel
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class UserModel
{

    /**
     * Summary of __construct
     *
     * @param string                $username 
     * @param string                $profileImage 
     * @param string                $email 
     * @param string                $password 
     * @param \Enumeration\UserType $type 
     * @param mixed                 $usernameStatus 
     * @param mixed                 $emailStatus 
     * @param mixed                 $successSignUp 
     */
    public function __construct(
        protected string $username,
        protected string $profileImage,
        protected string $email,
        protected string $password,
        protected UserType $type,
        protected ?bool $usernameStatus,
        protected ?bool $emailStatus,
        protected ?bool $successSignUp
    ) {
    }

    /**
     * Summary of getUsername
     * 
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
    /**
     * Summary of getProfileImage
     * 
     * @return string
     */
    public function getProfileImage(): string
    {
        return $this->profileImage;
    }
    /**
     * Summary of getEmail
     * 
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    /**
     * Summary of getPassword
     * 
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    /**
     * Summary of getUserType
     * 
     * @return \BackedEnum
     */
    public function getUserType(): BackedEnum
    {
        return $this->type;
    }
    /**
     * Summary of isUsernameAvailable
     * 
     * @return bool
     */
    public function isUsernameAvailable():?bool
    {
        return $this->usernameStatus;
    }
    /**
     * Summary of isEmailAvailable
     * 
     * @return bool
     */
    public function isEmailAvailable():?bool
    {
        return $this->emailStatus;
    }

    /**
     * Summary of getSuccessSignUp
     * 
     * @return bool
     */
    public function getSuccessSignUp():?bool
    {
        return $this->successSignUp;
    }

    /**
     * Summary of setUsernameAvailability
     *
     * @param bool $status 
     * 
     * @return void
     */
    public function setUsernameAvailability(bool $status):void
    {
        $this->usernameStatus = $status;
    }
    /**
     * Summary of setEmailAvailability
     *
     * @param bool $status 
     * 
     * @return void
     */
    public function setEmailAvailability(bool $status):void
    {
        $this->emailStatus = $status;
    }

    /**
     * Summary of isSignUpSuccessful
     *
     * @param mixed $successSignUp 
     * 
     * @return void
     */
    public function isSignUpSuccessful(?bool $successSignUp):void
    {
        $this->successSignUp = $successSignUp;
    }
}
