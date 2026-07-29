<?php
declare(strict_types=1);

namespace App\Core;

use Redis;

final class Cache
{
    private string $driver;
    private ?Redis $redis = null;

    public function __construct()
    {
        $config = require APP_PATH . '/Config/config.php';
        $this->driver = $config['app']['cache_driver'] ?? 'apcu';
        if ($this->driver === 'redis' && extension_loaded('redis')) {
            $this->redis = new Redis();
            try {
                $this->redis->connect($_ENV['REDIS_HOST'] ?? '127.0.0.1', (int) ($_ENV['REDIS_PORT'] ?? 6379), 1.5);
            } catch (\Throwable) {
                $this->driver = 'array';
            }
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->driver === 'apcu' && function_exists('apcu_fetch')) {
            $success = false;
            $value = apcu_fetch($key, $success);
            return $success ? $value : $default;
        }
        if ($this->driver === 'redis' && $this->redis) {
            $value = $this->redis->get($key);
            return $value !== false ? unserialize((string) $value) : $default;
        }
        return $default;
    }

    public function set(string $key, mixed $value, int $ttl = 60): bool
    {
        if ($this->driver === 'apcu' && function_exists('apcu_store')) {
            return apcu_store($key, $value, $ttl);
        }
        if ($this->driver === 'redis' && $this->redis) {
            return (bool) $this->redis->setex($key, $ttl, serialize($value));
        }
        return false;
    }

    public function delete(string $key): bool
    {
        if ($this->driver === 'apcu' && function_exists('apcu_delete')) {
            return (bool) apcu_delete($key);
        }
        if ($this->driver === 'redis' && $this->redis) {
            return (bool) $this->redis->del($key);
        }
        return false;
    }
}
