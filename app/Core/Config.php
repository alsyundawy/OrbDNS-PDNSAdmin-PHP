<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Config singleton.
 *
 * Seeded once from public/index.php via Config::init() so that all
 * application classes can call Config::all() without re-including the
 * config file, avoiding the require_once "returns true" pitfall.
 */
final class Config
{
    /** @var array<string, mixed>|null */
    private static ?array $data = null;

    /**
     * Called once by the entry-point after loading config.php.
     *
     * @param array<string, mixed> $data
     */
    public static function init(array $data): void
    {
        self::$data = $data;
    }

    /**
     * Returns the full configuration array.
     *
     * Falls back to loading config.php directly when Config::init() was
     * not called (e.g. in unit-test bootstraps or CLI scripts).
     *
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        if (self::$data === null) {
            /** @var mixed $loaded */
            $loaded = require_once APP_PATH . '/Config/config.php';
            self::$data = is_array($loaded) ? $loaded : [];
        }
        return self::$data;
    }
}
