<?php
declare(strict_types=1);

// app/public/routes.php — admin only
//
// GET    /routes.php        → list all routes
// GET    /routes.php?id=1   → get one route
// POST   /routes.php        → add route
// PUT    /routes.php?id=1   → update route
// DELETE /routes.php?id=1   → delete route

require_once __DIR__ . '/../core/core_auth.php';
require_once __DIR__ . '/../core/core_response.php';
require_once __DIR__ . '/../actions/action_routes.php';
require_once __DIR__ . '/../queries/query_routes.php';

start_session();
require_admin();

$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$input  = json_decode(file_get_contents('php://input'), true) ?? [];

if ($method === 'GET') {
    if ($id > 0) {
        $route = get_route_by_id($id);
        if (!$route) json_error('Route not found.', 404);
        json_ok(['route' => $route]);
    }
    json_ok(['routes' => get_all_routes()]);
}

if ($method === 'POST') {
    $route_id = add_route($input);
    json_ok(['route_id' => $route_id], 'Route added successfully.');
}

if ($method === 'PUT') {
    if ($id < 1) json_error('Route ID is required.');
    update_route($id, $input);
    json_ok([], 'Route updated successfully.');
}

if ($method === 'DELETE') {
    if ($id < 1) json_error('Route ID is required.');
    delete_route($id);
    json_ok([], 'Route deleted successfully.');
}

json_error('Method not allowed.', 405);
