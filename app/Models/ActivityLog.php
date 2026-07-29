<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class ActivityLog
{
    public static function log(?int $userId, string $action, string $target = '', string $detail = '', string $ip = ''): void
    {
        Database::query('INSERT INTO activity_logs (user_id, action, target, detail, ip_address, created_at) VALUES (?, ?, ?, ?, ?, NOW())', [$userId, $action, $target, $detail, $ip !== '' ? $ip : ($_SERVER['REMOTE_ADDR'] ?? '')]);
    }

    public static function all(int $page = 1, int $perPage = 50): array
    {
        $offset = max(0, ($page - 1) * $perPage);
        $stmt = Database::query('SELECT al.*, u.username FROM activity_logs al LEFT JOIN users u ON u.id = al.user_id ORDER BY al.created_at DESC LIMIT ? OFFSET ?', [$perPage, $offset]);
        return $stmt->fetchAll();
    }
}
