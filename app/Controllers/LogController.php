<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ActivityLog;

final class LogController extends Controller
{
    public function index(): void
    {
        $logs = ActivityLog::all(1, 100);
        $title = 'Activity Logs — PDNS Admin';
        $viewFile = APP_PATH . '/Views/logs/index.php';
        require_once APP_PATH . '/Views/layouts/app.php';
        unset($logs, $title, $viewFile);
    }

    public function exportCsv(): never
    {
        $logs = ActivityLog::all(1, 5000);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="activity-logs.csv"');
        $out = fopen('php://output', 'wb');
        if ($out !== false) {
            fputcsv($out, ['id', 'username', 'action', 'target', 'detail', 'ip_address', 'created_at']);
            foreach ($logs as $log) {
                fputcsv($out, [
                    $log['id'] ?? '',
                    $log['username'] ?? '',
                    $log['action'] ?? '',
                    $log['target'] ?? '',
                    $log['detail'] ?? '',
                    $log['ip_address'] ?? '',
                    $log['created_at'] ?? '',
                ]);
            }
            fclose($out);
        }
        exit;
    }
}
