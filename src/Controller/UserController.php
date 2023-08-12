<?php

/**
 * Handle Users Validation
 * 
 * PHP version 8
 *
 * @category Controller
 * @package  UserController
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */

namespace Controller;

use Enumeration\Regex;
use Enumeration\UserType;
use Exceptions\ValidationException;
use Model\UserModel;
use Repository\userRepository;




/**
 * Handle Users Validation
 * 
 * PHP version 8
 *
 * @category Controller
 * @package  UserController
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */

class UserController
{


    /**
     * Summary of __construct
     *
     * @param \Repository\userRepository $userRepository userRepository
     */
    public function __construct(private readonly userRepository $userRepository)
    {
    }



    /**
     * Summary of handleFileField
     *
     * @param array $file imgofuser
     * 
     * @return array|string
     */
    public function handleFileField(array $file): array|string
    {
        $validationException = new ValidationException();
        switch (true) {
        case !empty($file["name"]) && $file["error"] == UPLOAD_ERR_OK:
            $filename = $file["name"];
            $dirImages = "../public/assets/images/";
            $filenameTmp = $file['tmp_name'];
            $extensionOfTheUploaded_file = explode('.', $filename);
            $authorizedExtensions = array("jpg", "jpeg", "png", "webp");

            if (in_array($extensionOfTheUploaded_file[1], $authorizedExtensions)) {
                $bytesToStr = str_replace("/", "", base64_encode(random_bytes(9)));
                $filenameAndExtension = explode('.', $filename);
                $filenameGenerated = $bytesToStr . "." . $filenameAndExtension[1];

                return ["file" => "$filenameGenerated;$filenameTmp;$dirImages"];
            } else {
                throw $validationException->setTypeAndValueOfException(
                    "file_exception", 
                    $validationException::FILE_MESSAGE_ERROR_TYPE_FILE
                );
            }

        default:
            throw $validationException->setTypeAndValueOfException(
                "file_exception", 
                $validationException::FILE_MESSAGE_ERROR_NO_FILE_SELECTED
            );
        }
    }



    /**
     * Summary of handleTextField
     *
     * @param string                          $keyArray              
     * @param mixed                           $value                 
     * @param string                          $keyException          
     * @param \Exceptions\ValidationException $exception             
     * @param string                          $regex                 
     * @param string                          $emptyException        
     * @param string                          $wrongFormatException  
     * 
     * @return string|array|null
     */
    public function handleTextField(
        string $keyArray, 
        string $value, 
        string $keyException, 
        ValidationException $exception, 
        string $regex, 
        string $emptyException, 
        string $wrongFormatException
    ): string|array {
        switch (true) {
        case empty($value):
            throw $exception->setTypeAndValueOfException(
                $keyException, 
                $emptyException
            );
        case !preg_match($regex, $value):
            throw $exception->setTypeAndValueOfException(
                $keyException, 
                $wrongFormatException
            );
        default:
            return [$keyArray => $value];
        }
    }

    /**
     * Summary of signUpValidator
     *
     * @param string $username of user
     * @param array  $file     of user
     * @param string $email    of user
     * @param string $password of user
     * 
     * @return UserModel|null
     */
    public function signUpValidator(
        string $username, 
        array $file, 
        string $email, 
        string $password
    ): ?UserModel {

        $validationException = new ValidationException();
        $exceptionKeyArray = [
        "username_field" => "username_exception",
        "email_field" => "email_exception",
        "password_field" => "password_exception",
        ];
        $keyArrayWhenAFieldIsTreated = [
        "username_field" => "username",
        "email_field" => "email",
        "password_field" => "password",
        ];

        $exceptionByField = [
        "error_empty" => 
        $validationException::ERROR_EMPTY,
        "username_exception" => 
        $validationException::USERNAME_MESSAGE_ERROR_WRONG_FORMAT,
        "email_exception" => 
        $validationException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT,
        "password_exception" => 
        $validationException::PASSWORD_MESSAGE_ERROR_WRONG_FORMAT,
        ];

        $regexByField = [
        "username_regex" => REGEX::USERNAME,
        "email_regex" => REGEX::EMAIL,
        "password_regex" => REGEX::PASSWORD,
        ];


        $usernameResult = $this->handleTextField(
            $keyArrayWhenAFieldIsTreated["username_field"], 
            $username, 
            $exceptionKeyArray["username_field"], 
            $validationException, 
            $regexByField["username_regex"], 
            $exceptionByField["error_empty"], 
            $exceptionByField["username_exception"]
        )["username"];

        $emailResult = $this->handleTextField(
            $keyArrayWhenAFieldIsTreated["email_field"], 
            $email, 
            $exceptionKeyArray["email_field"], 
            $validationException, 
            $regexByField["email_regex"], 
            $exceptionByField["error_empty"], 
            $exceptionByField["email_exception"]
        )["email"];

        $passwordResult = password_hash(
            $this->handleTextField(
                $keyArrayWhenAFieldIsTreated["password_field"], 
                $password, 
                $exceptionKeyArray["password_field"], 
                $validationException, 
                $regexByField["password_regex"], 
                $exceptionByField["error_empty"], 
                $exceptionByField["password_exception"]
            )["password"], PASSWORD_DEFAULT
        );

        $fileResult = $this->handleFileField($file)["file"];

        $userType = UserType::USER;


        $userModel = new UserModel(
            $usernameResult, 
            $fileResult, 
            $emailResult, 
            $passwordResult, 
            $userType, 
            null,
            null, 
            null
        );

        $userDb = $this->userRepository->createUser($userModel);

        if ($userDb->getSuccessSignUp()) {
            return $userDb;
        }
        switch (true) {
        case !is_null($userDb->isUsernameAvailable()):
            throw $validationException->setTypeAndValueOfException(
                "username_exception", 
                $validationException::USERNAME_UNAVAILABLE_MESSAGE_ERROR
                 . $username . " n'est pas disponible !"
            );
        case !is_null($userDb->isEmailAvailable()):
            throw $validationException->setTypeAndValueOfException(
                "email_exception", 
                $validationException::EMAIL_UNAVAILABLE_MESSAGE_ERROR
                 . $email . " n'est pas disponible !"
            );
        }
    }


    /**
     * Summary of verifyEmailOnLogin
     *
     * @param string $email of user
     * 
     * @return array|string
     */
    public function verifyEmailOnLogin(string $email): array|string
    {
        $validationException = new ValidationException();
        $emailRegex = "/^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/";
        switch (true) {
        case empty($email):
            throw $validationException->setTypeAndValueOfException(
                "email_exception", 
                $validationException::ERROR_EMPTY
            );
        case !preg_match($emailRegex, $email):
            throw $validationException->setTypeAndValueOfException(
                "email_exception", 
                $validationException::EMAIL_MESSAGE_ERROR_WRONG_FORMAT
            );
        default:
            return ["email" => $email];
        }
    }

    /**
     * Summary of verifyPasswordOnLogin
     *
     * @param string $password of user
     * 
     * @return array|string
     */
    public function verifyPasswordOnLogin(string $password): array|string
    {
        $validationException = new ValidationException();

        if (empty($password)) {
            throw $validationException->setTypeAndValueOfException(
                'password_exception', 
                $validationException::ERROR_EMPTY
            );
        }
        return ["password" => $password];
    }


    /**
     * Summary of loginValidator
     *
     * @param string $email    of user
     * @param string $password of user
     * 
     * @return array|string|null
     */
    public function loginValidator(
        string $email, 
        string $password
    ): array|string|null {
        $validationException = new ValidationException();
        $emailResult = $this->verifyEmailOnLogin($email);
        $passwordResult = $this->verifyPasswordOnLogin($password);


        $fields = [
        "email" => $emailResult,
        "password" => $passwordResult
        ];

        $emailField = $fields["email"]["email"];
        $passwordField = $fields["password"]["password"];



        $login = $this->userRepository->loginUser($emailField, $passwordField);
        if (array_key_exists("password_error", $login)) {
            throw $validationException->setTypeAndValueOfException(
                "password_exception", 
                $validationException::PASSWORD_INCORRECT_MESSAGE_ERROR
            );
        } elseif (array_key_exists('email_error', $login)) {
            throw $validationException->setTypeAndValueOfException(
                "email_exception", 
                $validationException::EMAIL_UNEXIST_MESSAGE_ERROR
            );
        }
        return $login ?? null;
    }



    /**
     * Summary of handleLogout
     *
     * @param array $sessionData sessiondata
     * 
     * @return array|null
     */
    public function handleLogout(array $sessionData): ?array
    {
        if (is_array($this->userRepository->logout($sessionData))) {
            return $this->userRepository->logout($sessionData);
        }
    }

    /**
     * Summary of handleNotifications
     *
     * @param array $sessionData sessiondata
     * 
     * @return array|null
     */
    public function handleNotifications(array $sessionData): ?array
    {
        return $this->userRepository->getAllUserNotifications($sessionData);
    }

    /**
     * Summary of handleDeleteNotification
     *
     * @param int $idNotification of notification
     * 
     * @return array|null
     */
    public function handleDeleteNotification(int $idNotification): ?array
    {
        return $this->userRepository->deleteNotification($idNotification);
    }
}
