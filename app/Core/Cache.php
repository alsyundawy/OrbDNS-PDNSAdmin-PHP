<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Cache handler supporting APCu and Redis drivers.
 *
 * @psalm-suppress UndefinedClass
 */
final class Cache
{
    private string $driver;
    /** @var mixed */
    private mixed $redis = null;

    public function __construct()
    {
        $config = Config::all();
        $this->driver = $config['app']['cache_driver'] ?? 'apcu';
        if ($this->driver === 'redis' && extension_loaded('redis') && class_exists('\Redis')) {
            /** @psalm-suppress UndefinedClass */
            $redisClass = '\Redis';
            $this->redis = new $redisClass();
            try {
                $this->redis->connect($_ENV['REDIS_HOST'] ?? '127.0.0.1', (int) ($_ENV['REDIS_PORT'] ?? 6379), 1.5);
            } catch (\Throwable) {
                $this->driver = 'array';
            }
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->driver === 'apcu' && \function_exists('apcu_fetch')) {
            $success = false;
            $value = \apcu_fetch($key, $success);
            return $success ? $value : $default;
        }
        if ($this->driver === 'redis' && $this->redis !== null) {
            $value = $this->redis->get($key);
            return $value !== false ? json_decode((string) $value, true) : $default;
        }
        return $default;
    }

    public function set(string $key, mixed $value, int $ttl = 60): bool
    {
        if ($this->driver === 'apcu' && \function_exists('apcu_store')) {
            return \apcu_store($key, $value, $ttl);
        }
        if ($this->driver === 'redis' && $this->redis !== null) {
            $encoded = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            return $encoded !== false && (bool) $this->redis->setex($key, $ttl, $encoded);
        }
        return false;
    }

    public function delete(string $key): bool
    {
        if ($this->driver === 'apcu' && \function_exists('apcu_delete')) {
            return (bool) \apcu_delete($key);
        }
        if ($this->driver === 'redis' && $this->redis !== null) {
            return (bool) $this->redis->del($key);
        }
        return false;
    }
}
