<?php
declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Helper;

final class RoleMiddleware
{
    public static function handle(string $role): void
    {
        if (!Auth::is($role)) {
            Helper::flashSet('danger', 'Tidak memiliki izin.');
            Helper::redirect('/');
        }
    }
}
