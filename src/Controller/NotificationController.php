<?php

namespace Controller;

use Model\NotificationModel;
use Repository\NotificationRepository;

class NotificationController
{

  public function __construct(private readonly NotificationRepository $notificationRepository)
  {
  }

  public function handleCreateNotification(array $sessionData): void
  {
    $notificationRepository = $this->notificationRepository;
    var_dump($sessionData);
    switch (true) {
      case array_key_exists("approved", $sessionData):
        $notificationModel = new NotificationModel($sessionData["id_article"], $sessionData["id_user"], $sessionData["approved"], null, $sessionData["feedback"]);
        $idArticleInModel = $notificationModel->getIdArticle();
        $idUserInModel = $notificationModel->getIdUser();
        $approvedInModel = $notificationModel->getApproved();
        $rejectedInModel = $notificationModel->getRejected();
        $feedbackInModel = $notificationModel->getFeedbackAdministrator();
        $notificationRepository->createNotification($idArticleInModel, $idUserInModel, $approvedInModel, $rejectedInModel, $feedbackInModel);
        break;
      case array_key_exists("rejected", $sessionData):
        $notificationModel = new NotificationModel($sessionData["id_article"], $sessionData["id_user"], null, $sessionData["rejected"], $sessionData["feedback"]);
        $idArticleInModel = $notificationModel->getIdArticle();
        $idUserInModel = $notificationModel->getIdUser();
        $approvedInModel = $notificationModel->getApproved();
        $rejectedInModel = $notificationModel->getRejected();
        $feedbackInModel = $notificationModel->getFeedbackAdministrator();
        $notificationRepository->createNotification($idArticleInModel, $idUserInModel, $approvedInModel, $rejectedInModel, $feedbackInModel);
        break;
    }
  }

  public function handleGetAllUserNotifications(array $sessionData): ?array
  {
    $notificationRepository = $this->notificationRepository;
    return $notificationRepository->getAllUserNotifications($sessionData);
  }

  public function handleDeleteNotification(int $idNotification): null
  {
    $notificationRepository = $this->notificationRepository;
    return $notificationRepository->deleteNotification($idNotification);
  }
}
