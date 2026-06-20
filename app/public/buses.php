<?php

// app/public/buses.php — admin only
//
// GET    /buses.php        → list all buses
// GET    /buses.php?id=1   → get one bus
// POST   /buses.php        → add bus
// PUT    /buses.php?id=1   → update bus
// DELETE /buses.php?id=1   → delete bus

require_once __DIR__ . '/../core/core_auth.php';
require_once __DIR__ . '/../core/core_response.php';
require_once __DIR__ . '/../actions/action_buses.php';
require_once __DIR__ . '/../queries/query_buses.php';

start_session();
require_admin();

$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$input  = json_decode(file_get_contents('php://input'), true) ?? [];

if ($method === 'GET') {
    if ($id > 0) {
        $bus = get_bus_by_id($id);
        if (!$bus) json_error('Bus not found.', 404);
        json_ok(['bus' => $bus]);
    }
    json_ok(['buses' => get_all_buses()]);
}

if ($method === 'POST') {
    $bus_id = add_bus($input);
    json_ok(['bus_id' => $bus_id], 'Bus added successfully.');
}

if ($method === 'PUT') {
    if ($id < 1) json_error('Bus ID is required.');
    update_bus($id, $input);
    json_ok([], 'Bus updated successfully.');
}

if ($method === 'DELETE') {
    if ($id < 1) json_error('Bus ID is required.');
    delete_bus($id);
    json_ok([], 'Bus deleted successfully.');
}

json_error('Method not allowed.', 405);
