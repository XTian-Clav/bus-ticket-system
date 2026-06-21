<?php
declare(strict_types=1);

// app/queries/query_routes.php

require_once __DIR__ . '/../core/core_db.php';

function get_all_routes(): array
{
    return db()->query('SELECT * FROM routes ORDER BY source, destination')->fetchAll();
}

function get_route_by_id(int $id): array|false
{
    $stmt = db()->prepare('SELECT * FROM routes WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    return $stmt->fetch();
}
