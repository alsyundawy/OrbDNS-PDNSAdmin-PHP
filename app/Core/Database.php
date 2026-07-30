<?php

declare(strict_types=1);

namespace App\Core;

use App\Exceptions\DatabaseException;
use PDO;
use PDOException;

final class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require_once APP_PATH . '/Config/config.php';
            $db = $config['db'];
            $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $db['host'], $db['port'], $db['name'], $db['charset']);
            try {
                self::$instance = new PDO($dsn, $db['user'], $db['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_STRINGIFY_FETCHES => false,
                ]);
            } catch (PDOException $e) {
                throw new DatabaseException('Database connection failed: ' . $e->getMessage(), 0, $e);
            }
        }
        return self::$instance;
    }

    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::getInstance()->prepare($sql);
        foreach (array_values($params) as $index => $value) {
            $stmt->bindValue($index + 1, $value);
        }
        $stmt->execute();
        return $stmt;
    }
}
