<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Helper;

final class AuthMiddleware
{
    public static function handle(): void
    {
        if (!Auth::check()) {
            Helper::flashSet('warning', 'Sesi berakhir.');
            Helper::redirect('/login');
        }
    }
}
