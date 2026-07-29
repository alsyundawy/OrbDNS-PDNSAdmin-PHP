<?php
declare(strict_types=1);

$envFile = BASE_PATH . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
    }
}

return [
    'app' => [
        'name' => $_ENV['APP_NAME'] ?? 'PDNS Admin',
        'env' => $_ENV['APP_ENV'] ?? 'production',
        'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
        'key' => $_ENV['APP_KEY'] ?? '',
        'url' => $_ENV['APP_URL'] ?? 'http://localhost',
        'timezone' => $_ENV['APP_TIMEZONE'] ?? 'UTC',
        'force_https' => filter_var($_ENV['APP_FORCE_HTTPS'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'enable_gzip' => filter_var($_ENV['APP_ENABLE_GZIP'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'cache_driver' => $_ENV['APP_CACHE_DRIVER'] ?? 'apcu',
    ],
    'db' => [
        'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'port' => (int) ($_ENV['DB_PORT'] ?? 3306),
        'name' => $_ENV['DB_NAME'] ?? 'pdns_admin',
        'user' => $_ENV['DB_USER'] ?? 'pdns_admin',
        'pass' => $_ENV['DB_PASS'] ?? '',
        'charset' => 'utf8mb4',
    ],
    'pdns' => [
        'api_url' => rtrim($_ENV['PDNS_API_URL'] ?? 'http://127.0.0.1:8081', '/'),
        'api_key' => $_ENV['PDNS_API_KEY'] ?? '',
        'server' => $_ENV['PDNS_SERVER'] ?? 'localhost',
        'timeout' => (int) ($_ENV['PDNS_TIMEOUT'] ?? 10),
        'cache_ttl' => (int) ($_ENV['PDNS_CACHE_TTL'] ?? 60),
    ],
    'session' => [
        'lifetime' => (int) ($_ENV['SESSION_LIFETIME'] ?? 7200),
        'secure' => filter_var($_ENV['SESSION_SECURE'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'samesite' => $_ENV['SESSION_SAMESITE'] ?? 'Strict',
    ],
    'security' => [
        'bcrypt_cost' => (int) ($_ENV['BCRYPT_COST'] ?? 12),
        'max_login_attempts' => (int) ($_ENV['MAX_LOGIN_ATTEMPTS'] ?? 5),
        'lockout_minutes' => (int) ($_ENV['LOCKOUT_MINUTES'] ?? 15),
        'totp_enabled' => filter_var($_ENV['TOTP_ENABLED'] ?? true, FILTER_VALIDATE_BOOLEAN),
    ],
    'rate_limit' => [
        'driver' => $_ENV['RATE_LIMIT_DRIVER'] ?? 'apcu',
        'login_max' => (int) ($_ENV['RATE_LIMIT_LOGIN_MAX'] ?? 5),
        'login_window' => (int) ($_ENV['RATE_LIMIT_LOGIN_WINDOW'] ?? 900),
        'api_max' => (int) ($_ENV['RATE_LIMIT_API_MAX'] ?? 120),
        'api_window' => (int) ($_ENV['RATE_LIMIT_API_WINDOW'] ?? 60),
    ],
];
