<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Helper;

final class CsrfMiddleware
{
    public static function handle(): void
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if (!in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH'], true)) {
            return;
        }
        $token = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        if (!hash_equals((string) ($_SESSION['csrf_token'] ?? ''), (string) $token)) {
            if (Helper::isAjax()) {
                Helper::json(['success' => false, 'error' => 'Invalid CSRF token'], 403);
            }
            http_response_code(403);
            exit('Invalid CSRF token');
        }
    }
}
