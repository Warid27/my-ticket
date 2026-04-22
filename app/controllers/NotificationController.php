<?php
require_once 'app/core/BaseController.php';

class NotificationController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->guard($this->userRoles);
    }

    public function markRead(): void
    {
        $notificationId = (int) $_GET['id'];
        $userId = $_SESSION['user_id'];

        require_once 'app/models/NotificationModel.php';
        $notificationModel = new NotificationModel();

        if ($notificationModel->markAsRead($notificationId, $userId)) {
            $_SESSION['success'] = 'Notification marked as read';
        } else {
            $_SESSION['error'] = 'Failed to mark notification as read';
        }

        header('Location: index.php?page=dashboard&action=user');
        exit;
    }

    public function markAllRead(): void
    {
        $userId = $_SESSION['user_id'];

        require_once 'app/models/NotificationModel.php';
        $notificationModel = new NotificationModel();

        if ($notificationModel->markAllAsRead($userId)) {
            $_SESSION['success'] = 'All notifications marked as read';
        } else {
            $_SESSION['error'] = 'Failed to mark notifications as read';
        }

        header('Location: index.php?page=dashboard&action=user');
        exit;
    }
}
