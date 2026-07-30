<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class ZonePermission
{
    public static function canRead(int $userId, string $zoneId): bool
    {
        $stmt = Database::query('SELECT can_read FROM zone_permissions WHERE user_id = ? AND zone_id = ? LIMIT 1', [$userId, $zoneId]);
        $row = $stmt->fetch();
        return $row ? (int) $row['can_read'] === 1 : false;
    }

    public static function canWrite(int $userId, string $zoneId): bool
    {
        $stmt = Database::query('SELECT can_write FROM zone_permissions WHERE user_id = ? AND zone_id = ? LIMIT 1', [$userId, $zoneId]);
        $row = $stmt->fetch();
        return $row ? (int) $row['can_write'] === 1 : false;
    }
}
