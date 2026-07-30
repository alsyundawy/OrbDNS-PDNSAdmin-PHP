<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Config;
use App\Core\Controller;
use App\Core\Helper;
use App\Core\RateLimiter;
use App\Services\PowerDNSClient;

final class ApiController extends Controller
{
    private function guardApiRateLimit(): void
    {
        $config = Config::all();
        $key = 'api:' . hash('sha256', (string) ($_SESSION['user_id'] ?? 'guest') . '|' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
        $rate = (new RateLimiter())->hit($key, $config['rate_limit']['api_max'], $config['rate_limit']['api_window']);
        if (!$rate['allowed']) {
            Helper::json(['success' => false, 'error' => 'Rate limit exceeded'], 429);
        }
    }

    public function zones(): never
    {
        $this->guardApiRateLimit();
        Helper::json(['success' => true, 'data' => (new PowerDNSClient())->getZones()]);
    }

    public function status(): never
    {
        $this->guardApiRateLimit();
        try {
            $info = (new PowerDNSClient())->getServerInfo();
            Helper::json(['success' => true, 'status' => 'ok', 'server_id' => $info['id'] ?? 'unknown', 'version' => $info['version'] ?? 'unknown']);
        } catch (\Throwable) {
            Helper::json(['success' => false, 'status' => 'down'], 503);
        }
    }
}
