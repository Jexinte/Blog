<?php

namespace Controller;

use Model\NotificationModel;
use Repository\NotificationRepository;

class NotificationController
{

  public function __construct(private readonly NotificationRepository $notificationRepository)
  {
  }

  public function handleCreateNotification(array $validation): void
  {

    switch (true) {
      case $validation["status"] == 1:
        $notificationModel = new NotificationModel($validation["id_article"], $validation["id_user"], $validation["status"], $validation["feedback"]);

        $this->notificationRepository->createNotification($notificationModel);
        break;
      default:
        $notificationModel = new NotificationModel($validation["id_article"], $validation["id_user"], $validation["status"], $validation["feedback"]);

        $this->notificationRepository->createNotification($notificationModel);
        break;
    }
  }

  public function handleGetAllUserNotifications(array $validation): ?array
  {
    return $this->notificationRepository->getAllUserNotifications($validation);
  }

  public function handleDeleteNotification(int $idNotification): null
  {
    return $this->notificationRepository->deleteNotification($idNotification);
  }
}
