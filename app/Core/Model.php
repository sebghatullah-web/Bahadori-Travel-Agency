<?php

namespace App\Core;

/**
 * Base model. Subclasses set $table and get generic CRUD helpers backed by
 * the shared PDO connection. All queries use prepared statements.
 */
abstract class Model
{
    protected string $table = '';
    protected string $primaryKey = 'id';

    /** All rows ordered. */
    public function all(string $orderBy = null, string $dir = 'ASC'): array
    {
        $sql = "SELECT * FROM `{$this->table}`";
        if ($orderBy) {
            $sql .= " ORDER BY `{$orderBy}` " . (strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC');
        }
        return Database::all($sql);
    }

    public function find($id): ?array
    {
        return Database::one(
            "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ? LIMIT 1",
            [$id]
        );
    }

    public function where(string $col, $value, string $orderBy = null): array
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$col}` = ?";
        if ($orderBy) {
            $sql .= " ORDER BY `{$orderBy}` DESC";
        }
        return Database::all($sql, [$value]);
    }

    public function findWhere(string $col, $value): ?array
    {
        return Database::one(
            "SELECT * FROM `{$this->table}` WHERE `{$col}` = ? LIMIT 1",
            [$value]
        );
    }

    /** Count rows, optionally filtered. */
    public function count(string $where = '', array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        return (int) Database::scalar($sql, $params);
    }

    /**
     * Insert a row from an associative array.
     * @return int new id
     */
    public function create(array $data): int
    {
        $cols = array_keys($data);
        $placeholders = implode(', ', array_fill(0, count($cols), '?'));
        $colList = '`' . implode('`, `', $cols) . '`';
        $sql = "INSERT INTO `{$this->table}` ({$colList}) VALUES ({$placeholders})";
        return (int) Database::insert($sql, array_values($data));
    }

    /** Update a row by primary key. @return int affected rows */
    public function update($id, array $data): int
    {
        $sets = '`' . implode('` = ?, `', array_keys($data)) . '` = ?';
        $sql = "UPDATE `{$this->table}` SET {$sets} WHERE `{$this->primaryKey}` = ?";
        $params = array_merge(array_values($data), [$id]);
        return Database::execute($sql, $params);
    }

    public function delete($id): int
    {
        return Database::execute(
            "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?",
            [$id]
        );
    }
}
