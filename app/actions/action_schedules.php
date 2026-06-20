<?php

// app/actions/action_schedules.php

require_once __DIR__ . '/../core/core_crud.php';
require_once __DIR__ . '/../core/core_response.php';
require_once __DIR__ . '/../queries/query_schedules.php';

function add_schedule(array $data): int
{
    $required = ['bus_id', 'route_id', 'fare', 'depart_time'];
    foreach ($required as $field) {
        if (empty($data[$field] ?? '')) {
            json_error("The {$field} field is required.");
        }
    }

    if (!db_find_by_id('buses', (int) $data['bus_id'])) {
        json_error('Bus not found.', 404);
    }

    if (!db_find_by_id('routes', (int) $data['route_id'])) {
        json_error('Route not found.', 404);
    }

    if ((float) $data['fare'] <= 0) {
        json_error('Fare must be greater than zero.');
    }

    return db_insert('schedules', [
        'bus_id'      => (int) $data['bus_id'],
        'route_id'    => (int) $data['route_id'],
        'fare'        => (float) $data['fare'],
        'depart_time' => $data['depart_time'],
    ]);
}

function update_schedule(int $id, array $data): bool
{
    if (!get_schedule_by_id($id)) {
        json_error('Schedule not found.', 404);
    }

    $allowed = ['fare', 'depart_time'];
    $update  = [];

    foreach ($allowed as $field) {
        if (!empty($data[$field] ?? '')) {
            $update[$field] = $field === 'fare' ? (float) $data[$field] : $data[$field];
        }
    }

    if (empty($update)) {
        json_error('No valid fields provided to update.');
    }

    return db_update('schedules', $id, $update);
}

function delete_schedule(int $id): bool
{
    if (!get_schedule_by_id($id)) {
        json_error('Schedule not found.', 404);
    }

    return db_delete('schedules', $id);
}
