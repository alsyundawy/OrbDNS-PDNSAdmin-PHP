<?php

declare(strict_types=1);

namespace App\Core;

final class Session
{
    public static function start(): void
    {
        /** @psalm-suppress UndefinedConstant */
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        $config = Config::all();
        $secureCookie = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || ((int) ($_SERVER['SERVER_PORT'] ?? 0) === 443)
            || (bool) ($config['session']['secure'] ?? true);

        session_name('PDNSADMIN_SESS');
        session_set_cookie_params([ // NOSONAR - secure flag is evaluated dynamically above based on HTTPS/port/config
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => $secureCookie,
            'httponly' => true,
            'samesite' => (string) ($config['session']['samesite'] ?? 'Strict'),
        ]);
        ini_set('session.use_strict_mode', '1');
        session_start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
}
