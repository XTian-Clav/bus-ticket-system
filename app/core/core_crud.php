<?php
declare(strict_types=1);

// app/core/core_crud.php

require_once __DIR__ . '/core_db.php';

function db_find_all(string $table, array $where = [], string $order = 'id ASC'): array
{
    if (empty($where)) {
        return db()->query("SELECT * FROM `{$table}` ORDER BY {$order}")->fetchAll();
    }

    [$clause, $bindings] = build_where($where);
    $stmt = db()->prepare("SELECT * FROM `{$table}` WHERE {$clause} ORDER BY {$order}");
    $stmt->execute($bindings);
    return $stmt->fetchAll();
}

function db_find_one(string $table, array $where): array|false
{
    [$clause, $bindings] = build_where($where);
    $stmt = db()->prepare("SELECT * FROM `{$table}` WHERE {$clause} LIMIT 1");
    $stmt->execute($bindings);
    return $stmt->fetch();
}

function db_find_by_id(string $table, int $id): array|false
{
    return db_find_one($table, ['id' => $id]);
}

function db_insert(string $table, array $data): int
{
    $cols   = implode(', ', array_map(fn($c) => "`{$c}`", array_keys($data)));
    $places = implode(', ', array_fill(0, count($data), '?'));

    $stmt = db()->prepare("INSERT INTO `{$table}` ({$cols}) VALUES ({$places})");
    $stmt->execute(array_values($data));
    return (int) db()->lastInsertId();
}

function db_update(string $table, int $id, array $data): bool
{
    $set  = implode(', ', array_map(fn($c) => "`{$c}` = ?", array_keys($data)));
    $stmt = db()->prepare("UPDATE `{$table}` SET {$set} WHERE id = ?");
    return $stmt->execute([...array_values($data), $id]);
}

function db_delete(string $table, int $id): bool
{
    $stmt = db()->prepare("DELETE FROM `{$table}` WHERE id = ?");
    return $stmt->execute([$id]);
}

function build_where(array $where): array
{
    $clause   = implode(' AND ', array_map(fn($c) => "`{$c}` = ?", array_keys($where)));
    $bindings = array_values($where);
    return [$clause, $bindings];
}
