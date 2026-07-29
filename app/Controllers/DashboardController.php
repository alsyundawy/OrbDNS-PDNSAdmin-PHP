<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ActivityLog;
use App\Services\PowerDNSClient;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $pdns = new PowerDNSClient();
        $zones = $pdns->getZones();
        $stats = $pdns->getStatistics();
        $statsMap = [];
        foreach ($stats as $stat) {
            if (isset($stat['name'])) {
                $statsMap[(string) $stat['name']] = $stat['value'] ?? 0;
            }
        }
        $recentLogs = ActivityLog::all(1, 10);
        $title = 'Dashboard — PDNS Admin';
        $viewFile = APP_PATH . '/Views/dashboard/index.php';
        require APP_PATH . '/Views/layouts/app.php';
    }
}
