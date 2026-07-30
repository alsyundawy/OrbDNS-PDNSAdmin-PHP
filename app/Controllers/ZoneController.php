<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Helper;
use App\Core\Validator;
use App\Models\ActivityLog;
use App\Services\PowerDNSClient;

final class ZoneController extends Controller
{
    public function index(): void
    {
        $title = 'Zones — PDNS Admin';
        $viewFile = APP_PATH . '/Views/zones/index.php';
        require_once APP_PATH . '/Views/layouts/app.php';
        unset($title, $viewFile);
    }

    public function create(): void
    {
        $title = 'Create Zone — PDNS Admin';
        $viewFile = APP_PATH . '/Views/zones/create.php';
        require_once APP_PATH . '/Views/layouts/app.php';
        unset($title, $viewFile);
    }

    public function store(): void
    {
        $name = trim((string) ($_POST['name'] ?? ''));
        $kind = trim((string) ($_POST['kind'] ?? 'Native'));
        $masters = array_filter(array_map('trim', explode(',', (string) ($_POST['masters'] ?? ''))));
        $nameservers = array_filter(array_map('trim', explode(',', (string) ($_POST['nameservers'] ?? ''))));
        $validator = (new Validator())
            ->required('name', $name, 'Nama zone wajib diisi.')
            ->domain('name', $name, 'Nama zone tidak valid.')
            ->in('kind', $kind, ['Native', 'Master', 'Slave'], 'Kind zone tidak valid.');

        if ($kind === 'Slave') {
            if ($masters === []) {
                $validator->required('masters', '', 'Masters wajib diisi untuk zone Slave.');
            } else {
                $validator->ipList('masters', $masters, 'IP master tidak valid');
            }
        }

        if (!$validator->passes()) {
            Helper::flashSet('danger', $validator->first());
            Helper::redirect('/zones/create');
        }

        (new PowerDNSClient())->createZone([
            'name' => rtrim($name, '.') . '.',
            'kind' => $kind,
            'nameservers' => array_values($nameservers),
            'masters' => $kind === 'Slave' ? array_values($masters) : [],
        ]);

        $user = Auth::user();
        ActivityLog::log($user['id'] ?? null, 'ZONE_CREATE', $name, 'Zone created');
        Helper::flashSet('success', 'Zone berhasil dibuat.');
        Helper::redirect('/zones');
    }
}
