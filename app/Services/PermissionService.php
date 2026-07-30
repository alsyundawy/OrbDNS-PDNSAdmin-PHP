<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Auth;
use App\Models\ZonePermission;

final class PermissionService
{
    public function canReadZone(string $zoneId): bool
    {
        if (Auth::is('admin')) {
            return true;
        }
        $user = Auth::user();
        return $user ? ZonePermission::canRead($user['id'], $zoneId) : false;
    }

    public function canWriteZone(string $zoneId): bool
    {
        if (Auth::is('admin')) {
            return true;
        }
        $user = Auth::user();
        return $user ? ZonePermission::canWrite($user['id'], $zoneId) : false;
    }
}
