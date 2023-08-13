<?php

/**
 * Handle users functions
 * 
 * PHP version 8
 *
 * @category Repository
 * @package  UserRepository
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
namespace Repository;

use Config\DatabaseConnection;
use Enumeration\UserType;
use Model\UserModel;


/**
 * UserRepository class
 * 
 * PHP version 8
 *
 * @category Repository
 * @package  UserRepository
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class UserRepository
{


    /**
     * Summary of __construct
     *
     * @param \Config\DatabaseConnection $connector 
     */
    public function __construct(private readonly DatabaseConnection $connector)
    {
    }


    /**
     * Summary of createUser
     *
     * @param \Model\UserModel $userModel 
     * 
     * @return UserModel
     */
    public function createUser(UserModel $userModel): ?UserModel
    {

        $dbConnect = $this->connector->connect();
        $username = $userModel->getUsername();
        $file = $userModel->getProfileImage();
        $email = $userModel->getEmail();
        $password = $userModel->getPassword();
        $userType = $userModel->getUserType();

        $statementToCheckIfUserAlreadyExist = $dbConnect->prepare(
            'SELECT username,email
             FROM user 
             WHERE username = :username OR  
             email = :email'
        );
        $statementToCheckIfUserAlreadyExist->bindParam("username", $username);
        $statementToCheckIfUserAlreadyExist->bindParam("email", $email);
        $statementToCheckIfUserAlreadyExist->execute();
        $result = $statementToCheckIfUserAlreadyExist->fetch();

        switch (true) {
        case !$result:
            $fileRequirements = explode(';', $file);
            $fileSettings["file_name"] = $fileRequirements[0];
            $fileSettings["tmp_name"] = $fileRequirements[1];
            $fileSettings["directory"] = $fileRequirements[2];
            $filePath = "http://localhost/P5_Créez votre premier blog en PHP - Dembele Mamadou/public/assets/images/user_profile/". $fileSettings["file_name"];
            $statementToCreateUser = $dbConnect->prepare(
                "INSERT INTO user 
                (username,profileImage,email,password,type) 
                VALUES(?,?,?,?,?)"
            );
            $values = [
            $username,
            $filePath,
            $email,
            $password,
            $userType->value
            ];
            $statementToCreateUser->execute($values);
            move_uploaded_file(
                $fileSettings["tmp_name"],
                $fileSettings["directory"] . "/" . 
                 $fileSettings["file_name"]
            );
            $userModel->isSignUpSuccessful(true);
            return $userModel;

        case $result["username"] == $userModel->getUsername():
            $userModel->setUsernameAvailability(false);
            return $userModel;

        case $result["email"] == $userModel->getEmail():
            $userModel->setEmailAvailability(false);
            return $userModel;
        }
    }

    /**
     * Summary of loginUser
     *
     * @param string $email 
     * @param string $password 
     * 
     * @return array
     */
    public function loginUser(
        string $email, 
        string $password
    ): ?array {
    

        $dbConnect = $this->connector->connect();
        $statementToCheckUserCredentials = $dbConnect->prepare(
            "SELECT id,username,email,type,password 
            FROM user 
            WHERE email = :email"
        );
        $statementToCheckUserCredentials->bindParam(
            ":email", $email
        );
        $statementToCheckUserCredentials->execute();
        $user = $statementToCheckUserCredentials->fetch();

        if ($user && $user["type"] == UserType::USER->value 
            || $user && $user["type"] == UserType::ADMIN->value
        ) {
            $checkPassword = password_verify($password, $user['password']);
            $username = $user["username"];
            $typeUser = $user["type"];
            $userId = $user["id"];
            if (!$checkPassword) {
                return ["password_error" => 1];
            }

            return [
                "username" => $username, 
                "type_user" => $typeUser, 
                "id_user" => $userId
            ];
        } else {
            return ["email_error" => 1];
        }
    }






    /**
     * Summary of logout
     *
     * @param array $sessionData 
     * 
     * @return array<int>
     */
    public function logout(array $sessionData): ?array
    {
        $dbConnect = $this->connector->connect();
        $statementSession = $dbConnect->prepare(
            "SELECT id,id_session,username,user_type 
            FROM session 
            WHERE id_session = :id_session AND username = :username 
            AND user_type = :type_user"
        );
        $statementSession->bindParam("username", $sessionData["username"]);
        $statementSession->bindParam("type_user", $sessionData["type_user"]);
        $statementSession->bindParam("id_session", $sessionData["session_id"]);

        $statementSession->execute();

        $result = $statementSession->fetch();

        if ($result) {
            $idSessionInDb = $result["id"];

            $deleteSession = $dbConnect->prepare(
                "DELETE FROM 
                session 
                WHERE id = :id"
            );
            $deleteSession->bindParam("id", $idSessionInDb);
            $deleteSession->execute();
        }
        return ["logout" => 1];
    }

    /**
     * Summary of getAllUserNotifications
     *
     * @param array $sessionData 
     * 
     * @return mixed
     */
    public function getAllUserNotifications(array $sessionData): ?array
    {
        $dbConnect = $this->connector->connect();



        $statementGetAllNotifications = $dbConnect->prepare(
            "SELECT * FROM user_notification 
            WHERE idUser = :idUserOfSession"
        );

        $statementGetAllNotifications->bindParam(
            "idUserOfSession", 
            $sessionData["id_user"]
        );
        $statementGetAllNotifications->execute();

        return $statementGetAllNotifications->fetchAll();
    }

    /**
     * Summary of deleteNotification
     *
     * @param int $idNotification 
     * 
     * @return array<int>
     */
    public function deleteNotification(
        int $idNotification
    ): ?array {
        $dbConnect = $this->connector->connect();
        $statementDeleteNotification = $dbConnect->prepare(
            "DELETE FROM user_notification 
            WHERE id = :idNotification"
        );
        $statementDeleteNotification->bindParam("idNotification", $idNotification);
        $statementDeleteNotification->execute();
        return ["notification_delete" => 1];
    }
}
