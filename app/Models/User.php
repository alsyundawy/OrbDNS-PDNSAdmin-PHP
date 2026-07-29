<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class User
{
    public static function findByUsername(string $username): ?array
    {
        $stmt = Database::query('SELECT * FROM users WHERE username = ? LIMIT 1', [$username]);
        return $stmt->fetch() ?: null;
    }

    public static function findById(int $id): ?array
    {
        $stmt = Database::query('SELECT * FROM users WHERE id = ? LIMIT 1', [$id]);
        return $stmt->fetch() ?: null;
    }

    public static function updateLastLogin(int $id): void
    {
        Database::query('UPDATE users SET last_login_at = NOW(), login_count = login_count + 1, failed_login_count = 0, last_failed_at = NULL WHERE id = ?', [$id]);
    }

    public static function incrementFailedLogin(string $username): void
    {
        Database::query('UPDATE users SET failed_login_count = failed_login_count + 1, last_failed_at = NOW() WHERE username = ?', [$username]);
    }
}
