<?php
require_once 'app/core/BaseModel.php';

class NotificationModel extends BaseModel
{
    protected string $table = 'notifications';
    protected array $fillable = ['user_id', 'title', 'message', 'type', 'is_read'];

    public function createNotification(int $userId, string $title, string $message, string $type = 'system'): bool
    {
        return $this->insert([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'is_read' => false
        ]);
    }

    public function getUserNotifications(int $userId, int $limit = 10): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC LIMIT ?";
        return $this->query($sql, [$userId, $limit]);
    }

    public function getUnreadCount(int $userId): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = ? AND is_read = FALSE";
        $result = $this->queryOne($sql, [$userId]);
        return $result['count'] ?? 0;
    }

    public function markAsRead(int $notificationId, int $userId): bool
    {
        $sql = "UPDATE {$this->table} SET is_read = TRUE WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$notificationId, $userId]);
    }

    public function markAllAsRead(int $userId): bool
    {
        $sql = "UPDATE {$this->table} SET is_read = TRUE WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId]);
    }
}
