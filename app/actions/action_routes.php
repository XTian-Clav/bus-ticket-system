<?php
declare(strict_types=1);

// app/actions/action_routes.php

require_once __DIR__ . '/../core/core_crud.php';
require_once __DIR__ . '/../core/core_response.php';
require_once __DIR__ . '/../queries/query_routes.php';

function add_route(array $data): int
{
    if (empty(trim($data['source'] ?? ''))) {
        json_error('Source is required.');
    }

    if (empty(trim($data['destination'] ?? ''))) {
        json_error('Destination is required.');
    }

    $source      = trim($data['source']);
    $destination = trim($data['destination']);

    if (strtolower($source) === strtolower($destination)) {
        json_error('Source and destination cannot be the same.');
    }

    $existing = db_find_one('routes', ['source' => $source, 'destination' => $destination]);
    if ($existing) {
        json_error('That route already exists.');
    }

    return db_insert('routes', [
        'source'      => $source,
        'destination' => $destination,
    ]);
}

function update_route(int $id, array $data): bool
{
    $current = get_route_by_id($id);
    if (!$current) {
        json_error('Route not found.', 404);
    }

    $allowed = ['source', 'destination'];
    $update  = [];

    foreach ($allowed as $field) {
        if (!empty(trim($data[$field] ?? ''))) {
            $update[$field] = trim($data[$field]);
        }
    }

    if (empty($update)) {
        json_error('No valid fields provided to update.');
    }

    $source      = $update['source']      ?? $current['source'];
    $destination = $update['destination'] ?? $current['destination'];

    if (strtolower($source) === strtolower($destination)) {
        json_error('Source and destination cannot be the same.');
    }

    $existing = db_find_one('routes', ['source' => $source, 'destination' => $destination]);
    if ($existing && (int) $existing['id'] !== $id) {
        json_error('That route already exists.');
    }

    return db_update('routes', $id, $update);
}

function delete_route(int $id): bool
{
    if (!get_route_by_id($id)) {
        json_error('Route not found.', 404);
    }

    return db_delete('routes', $id);
}