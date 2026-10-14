<?php

namespace App\Core;

/**
 * Simple PDO wrapper (singleton) used by every Model.
 * Keeps a single connection for the request lifecycle and exposes
 * prepared-statement helpers.
 */
class Database
{
    private static ?Database $instance = null;
    private \PDO $pdo;

    private function __construct(array $cfg)
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $cfg['host'],
            $cfg['port'],
            $cfg['database'],
            $cfg['charset']
        );

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $this->pdo = new \PDO($dsn, $cfg['username'], $cfg['password'], $options);
    }

    public static function getInstance(array $cfg = null): \PDO
    {
        if (self::$instance === null) {
            // Fall back to the central config when no explicit config is passed.
            $cfg = $cfg ?? Config::get('db');
            self::$instance = new self((array) $cfg);
        }
        return self::$instance->pdo;
    }

    /** Raw PDO handle. */
    public static function pdo(array $cfg = null): \PDO
    {
        return self::getInstance($cfg);
    }

    // ---------------------------------------------------------------------
    //  Tiny query helpers used across models.
    // ---------------------------------------------------------------------

    public static function run(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function all(string $sql, array $params = []): array
    {
        return self::run($sql, $params)->fetchAll();
    }

    public static function one(string $sql, array $params = []): ?array
    {
        $row = self::run($sql, $params)->fetch();
        return $row === false ? null : $row;
    }

    public static function scalar(string $sql, array $params = [])
    {
        $row = self::run($sql, $params)->fetch(\PDO::FETCH_NUM);
        return $row ? $row[0] : null;
    }

    public static function insert(string $sql, array $params = []): int|false
    {
        self::run($sql, $params);
        return (int) self::getInstance()->lastInsertId();
    }

    public static function execute(string $sql, array $params = []): int
    {
        return self::run($sql, $params)->rowCount();
    }
}
