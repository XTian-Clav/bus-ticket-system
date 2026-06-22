<?php
declare(strict_types=1);

// app/public/schedules.php

require_once __DIR__ . '/../core/core_auth.php';
require_once __DIR__ . '/../core/core_response.php';
require_once __DIR__ . '/../actions/action_schedules.php';
require_once __DIR__ . '/../queries/query_schedules.php';
require_once __DIR__ . '/../queries/query_bookings.php';

start_session();
require_login();

$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$input  = json_decode(file_get_contents('php://input'), true) ?? [];

if ($method === 'GET') {
    if ($id > 0) {
        $schedule = get_schedule_by_id($id);
        if (!$schedule) json_error('Schedule not found.', 404);

        $schedule['available_seats'] = get_available_seats($id);
        $schedule['taken_seats']     = array_column(
            array_filter(get_bookings_by_schedule($id), fn($b) => $b['status'] === 'confirmed'),
            'seat_num'
        );

        json_ok(['schedule' => $schedule]);
    }

    json_ok(['schedules' => get_all_schedules()]);
}

require_admin();

if ($method === 'POST') {
    $schedule_id = add_schedule($input);
    json_ok(['schedule_id' => $schedule_id], 'Schedule added successfully.');
}

if ($method === 'PUT') {
    if ($id < 1) json_error('Schedule ID is required.');
    update_schedule($id, $input);
    json_ok([], 'Schedule updated successfully.');
}

if ($method === 'DELETE') {
    if ($id < 1) json_error('Schedule ID is required.');
    delete_schedule($id);
    json_ok([], 'Schedule deleted successfully.');
}

json_error('Method not allowed.', 405);
