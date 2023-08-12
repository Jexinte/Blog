<?php

/**
 * Handle notifications functions
 * 
 * PHP version 8
 *
 * @category Repository
 * @package  NotificationRepository
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
namespace Repository;

use Config\DatabaseConnection;
use Model\NotificationModel;

/**
 * NotificationRepository class
 * 
 * PHP version 8
 *
 * @category Repository
 * @package  NotificationRepository
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class NotificationRepository
{

    /**
     * Summary of __construct
     *
     * @param \Config\DatabaseConnection $connector 
     */
    public function __construct(private DatabaseConnection $connector)
    {
    }

    /**
     * Summary of createNotification
     *
     * @param \Model\NotificationModel $notificationModel 
     * 
     * @return null
     */
    public function createNotification(NotificationModel $notificationModel): null
    {
        $dbConnect = $this->connector->connect();
    
        $statementToCreateNotification = $dbConnect->prepare("INSERT INTO user_notification (idArticle,idUser,status,feedbackAdministrator) VALUES(?,?,?,?)");
        $values = [
        $notificationModel->getIdArticle(),
        $notificationModel->getIdUser(),
        $notificationModel->getStatus(),
        $notificationModel->getFeedbackAdministrator()
        ];
        $statementToCreateNotification->execute($values);
        return null;
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



        $statementGetAllNotifications = $dbConnect->prepare("SELECT * FROM user_notification WHERE idUser = :idUserOfSession");

        $statementGetAllNotifications->bindParam("idUserOfSession", $sessionData["id_user"]);
        $statementGetAllNotifications->execute();

        return $statementGetAllNotifications->fetchAll();
    }

    /**
     * Summary of deleteNotification
     *
     * @param int $idNotification 
     * 
     * @return null
     */
    public function deleteNotification(int $idNotification): null
    {
        $dbConnect = $this->connector->connect();
        $statementDeleteNotification = $dbConnect->prepare("DELETE FROM user_notification WHERE id = :idNotification");
        $statementDeleteNotification->bindParam("idNotification", $idNotification);
        $statementDeleteNotification->execute();
        return null;
    }
}
