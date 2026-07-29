<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\User;

final class Auth
{
    public static function attempt(string $username, string $password): bool
    {
        $user = User::findByUsername($username);
        if ($user === null || !password_verify($password, (string) $user['password']) || (int) $user['is_active'] !== 1) {
            return false;
        }
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = (string) $user['username'];
        $_SESSION['role'] = (string) $user['role'];
        $_SESSION['2fa_verified'] = (int) ($user['totp_enabled'] ?? 0) !== 1;
        $_SESSION['login_at'] = time();
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
        $_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return true;
    }

    public static function check(): bool
    {
        if (!isset($_SESSION['user_id'], $_SESSION['ip'], $_SESSION['ua'])) {
            return false;
        }
        if (($_SESSION['ip'] ?? '') !== ($_SERVER['REMOTE_ADDR'] ?? '')) {
            self::logout();
            return false;
        }
        $config = require APP_PATH . '/Config/config.php';
        if (time() - (int) ($_SESSION['login_at'] ?? 0) > $config['session']['lifetime']) {
            self::logout();
            return false;
        }
        return true;
    }

    public static function user(): ?array
    {
        return isset($_SESSION['user_id']) ? [
            'id' => (int) $_SESSION['user_id'],
            'username' => (string) $_SESSION['username'],
            'role' => (string) $_SESSION['role'],
        ] : null;
    }

    public static function is(string $role): bool
    {
        return (string) ($_SESSION['role'] ?? '') === $role;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
        }
        session_destroy();
    }
}
