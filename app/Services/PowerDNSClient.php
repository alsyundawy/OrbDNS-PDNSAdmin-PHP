<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Cache;
use RuntimeException;

final class PowerDNSClient
{
    private string $baseUrl;
    private string $apiKey;
    private string $server;
    private int $timeout;
    private int $cacheTtl;
    private Cache $cache;

    public function __construct()
    {
        $config = require APP_PATH . '/Config/config.php';
        $this->baseUrl = $config['pdns']['api_url'];
        $this->apiKey = $config['pdns']['api_key'];
        $this->server = $config['pdns']['server'];
        $this->timeout = $config['pdns']['timeout'];
        $this->cacheTtl = $config['pdns']['cache_ttl'];
        $this->cache = new Cache();
    }

    private function request(string $method, string $endpoint, array $data = []): mixed
    {
        $url = $this->baseUrl . '/api/v1/servers/' . rawurlencode($this->server) . $endpoint;
        $curl = curl_init($url);
        if ($curl === false) {
            throw new RuntimeException('Unable to initialize cURL.');
        }
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['X-API-Key: ' . $this->apiKey, 'Accept: application/json', 'Content-Type: application/json'],
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
        ]);
        if ($data !== []) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        }
        $response = curl_exec($curl);
        $httpCode = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        if ($response === false) {
            throw new RuntimeException('PowerDNS API request failed: ' . $error);
        }
        $decoded = json_decode($response, true);
        if ($httpCode >= 400) {
            throw new RuntimeException('PowerDNS API error.');
        }
        return $decoded;
    }

    public function getZones(bool $forceRefresh = false): array
    {
        $cacheKey = 'pdns.zones';
        if (!$forceRefresh) {
            $cached = $this->cache->get($cacheKey);
            if (is_array($cached)) {
                return $cached;
            }
        }
        $zones = $this->request('GET', '/zones');
        $this->cache->set($cacheKey, $zones, $this->cacheTtl);
        return is_array($zones) ? $zones : [];
    }

    public function getServerInfo(): array
    {
        $info = $this->request('GET', '');
        return is_array($info) ? $info : [];
    }

    public function getStatistics(): array
    {
        $stats = $this->request('GET', '/statistics');
        return is_array($stats) ? $stats : [];
    }

    public function createZone(array $payload): array
    {
        $this->cache->delete('pdns.zones');
        $result = $this->request('POST', '/zones', $payload);
        return is_array($result) ? $result : [];
    }
}
