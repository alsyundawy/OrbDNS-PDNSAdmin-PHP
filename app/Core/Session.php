<?php
declare(strict_types=1);

namespace App\Core;

final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        $config = require APP_PATH . '/Config/config.php';
        session_name('PDNSADMIN_SESS');
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => $config['session']['secure'],
            'httponly' => true,
            'samesite' => $config['session']['samesite'],
        ]);
        ini_set('session.use_strict_mode', '1');
        session_start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}
