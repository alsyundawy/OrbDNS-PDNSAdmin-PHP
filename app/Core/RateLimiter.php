<?php

declare(strict_types=1);

namespace App\Core;

final class RateLimiter
{
    private Cache $cache;

    public function __construct()
    {
        $this->cache = new Cache();
    }

    public function hit(string $key, int $maxAttempts, int $windowSeconds): array
    {
        $now = time();
        $bucket = $this->cache->get($key, ['count' => 0, 'reset_at' => $now + $windowSeconds]);
        if (!is_array($bucket) || ($bucket['reset_at'] ?? 0) <= $now) {
            $bucket = ['count' => 0, 'reset_at' => $now + $windowSeconds];
        }
        $bucket['count']++;
        $this->cache->set($key, $bucket, max(1, (int) $bucket['reset_at'] - $now));
        return [
            'allowed' => (int) $bucket['count'] <= $maxAttempts,
            'remaining' => max(0, $maxAttempts - (int) $bucket['count']),
            'reset_at' => (int) $bucket['reset_at'],
            'count' => (int) $bucket['count'],
        ];
    }

    public function clear(string $key): void
    {
        $this->cache->delete($key);
    }
}
