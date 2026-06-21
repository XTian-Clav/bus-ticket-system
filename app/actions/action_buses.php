<?php
declare(strict_types=1);

// app/actions/action_buses.php

require_once __DIR__ . '/../core/core_crud.php';
require_once __DIR__ . '/../core/core_response.php';
require_once __DIR__ . '/../queries/query_buses.php';

function add_bus(array $data): int
{
    if (empty(trim($data['bus_num'] ?? ''))) {
        json_error('Bus number is required.');
    }

    if (empty($data['seats']) || (int) $data['seats'] < 1) {
        json_error('A valid seat count is required.');
    }

    $existing = db_find_one('buses', ['bus_num' => trim($data['bus_num'])]);
    if ($existing) {
        json_error('A bus with that number already exists.');
    }

    return db_insert('buses', [
        'bus_num' => trim($data['bus_num']),
        'seats'   => (int) $data['seats'],
    ]);
}

function update_bus(int $id, array $data): bool
{
    if (!get_bus_by_id($id)) {
        json_error('Bus not found.', 404);
    }

    $allowed = ['bus_num', 'seats'];
    $update  = [];

    foreach ($allowed as $field) {
        if (!empty($data[$field] ?? '')) {
            $update[$field] = $field === 'seats' ? (int) $data[$field] : trim($data[$field]);
        }
    }

    if (empty($update)) {
        json_error('No valid fields provided to update.');
    }

    return db_update('buses', $id, $update);
}

function delete_bus(int $id): bool
{
    if (!get_bus_by_id($id)) {
        json_error('Bus not found.', 404);
    }

    return db_delete('buses', $id);
}
