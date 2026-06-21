<?php
declare(strict_types=1);

// app/queries/query_buses.php

require_once __DIR__ . '/../core/core_db.php';

function get_all_buses(): array
{
    return db()->query('SELECT * FROM buses ORDER BY bus_num')->fetchAll();
}

function get_bus_by_id(int $id): array|false
{
    $stmt = db()->prepare('SELECT * FROM buses WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    return $stmt->fetch();
}
