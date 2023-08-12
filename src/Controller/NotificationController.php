<?php

/**
 * Handle Notification for Users 
 * 
 * PHP version 8
 *
 * @category Controller
 * @package  NotificationController
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */

namespace Controller;

use Model\NotificationModel;
use Repository\NotificationRepository;

/**
 * Handle Notification for Users 
 * 
 * PHP version 8
 *
 * @category Controller
 * @package  NotificationController
 * @author   Yokke <mdembelepro@gmail.com>
 * @license  ISC License
 * @link     https://github.com/Jexinte/P5---Blog-Professionnel---Openclassrooms
 */
class NotificationController
{

    /**
     * Summary of __construct
     *
     * @param \Repository\NotificationRepository $notificationRepository 
     */
    public function __construct(
        private readonly NotificationRepository $notificationRepository
    ) {
    }

    /**
     * Summary of handleCreateNotification
     *
     * @param array $validation 
     * 
     * @return void
     */
    public function handleCreateNotification(array $validation): void
    {

        switch (true) {
        case $validation["status"] == 1:
            $notificationModel = new NotificationModel(
                $validation["id_article"], 
                $validation["id_user"], 
                $validation["status"], 
                $validation["feedback"]
            );

            $this->notificationRepository->createNotification($notificationModel);
            break;
        default:
            $notificationModel = new NotificationModel(
                $validation["id_article"], 
                $validation["id_user"], 
                $validation["status"], 
                $validation["feedback"]
            );

            $this->notificationRepository->createNotification($notificationModel);
            break;
        }
    }

    /**
     * Summary of handleGetAllUserNotifications
     *
     * @param array $validation 
     * 
     * @return array|null
     */
    public function handleGetAllUserNotifications(array $validation): ?array
    {
        return $this->notificationRepository->getAllUserNotifications($validation);
    }

    /**
     * Summary of handleDeleteNotification
     *
     * @param int $idNotification 
     * 
     * @return null
     */
    public function handleDeleteNotification(int $idNotification): null
    {
        return $this->notificationRepository->deleteNotification($idNotification);
    }
}
