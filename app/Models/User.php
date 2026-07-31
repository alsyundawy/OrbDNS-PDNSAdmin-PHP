<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class User
{
    public static function all(): array
    {
        $sql = 'SELECT id, username, email, role, is_active, totp_enabled, last_login_at, created_at '
            . 'FROM users ORDER BY id DESC';
        $stmt = Database::query($sql);
        return $stmt->fetchAll() ?: [];
    }

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

    public static function exists(string $username, string $email): bool
    {
        $stmt = Database::query('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1', [$username, $email]);
        return (bool) $stmt->fetch();
    }

    public static function create(string $username, string $email, string $passwordHash, string $role = 'user'): bool
    {
        $stmt = Database::query(
            'INSERT INTO users (username, email, password, role, is_active, created_at) VALUES (?, ?, ?, ?, 1, NOW())',
            [$username, $email, $passwordHash, $role]
        );
        return $stmt->rowCount() > 0;
    }

    public static function updateLastLogin(int $id): void
    {
        $sql = 'UPDATE users SET last_login_at = NOW(), login_count = login_count + 1, '
            . 'failed_login_count = 0, last_failed_at = NULL WHERE id = ?';
        Database::query($sql, [$id]);
    }

    public static function incrementFailedLogin(string $username): void
    {
        $sql = 'UPDATE users SET failed_login_count = failed_login_count + 1, '
            . 'last_failed_at = NOW() WHERE username = ?';
        Database::query($sql, [$username]);
    }
}
