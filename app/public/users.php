<?php
declare(strict_types=1);

// app/public/users.php

require_once __DIR__ . '/../core/core_auth.php';
require_once __DIR__ . '/../core/core_response.php';
require_once __DIR__ . '/../actions/action_users.php';
require_once __DIR__ . '/../queries/query_users.php';

start_session();

$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$input  = json_decode(file_get_contents('php://input'), true) ?? [];

if ($method === 'POST') {
    if (is_admin()) {
        $user_id = admin_create_user($input);
    } else {
        $user_id = register_user($input);
    }
    json_ok(['user_id' => $user_id], 'Registration successful.');
}

require_login();

if ($method === 'GET') {
    require_admin();

    if ($id > 0) {
        $user = get_user_by_id($id);
        if (!$user) json_error('User not found.', 404);
        json_ok(['user' => $user]);
    }

    $role  = $_GET['role'] ?? '';
    $users = get_all_users($role);
    json_ok(['users' => $users]);
}

if ($method === 'PUT') {
    if ($id < 1) json_error('User ID is required.');

    if (!is_admin() && auth_user_id() !== $id) {
        json_error('Forbidden.', 403);
    }

    update_user($id, $input, is_admin());

    // Keep session in sync when user edits their own profile
    if (auth_user_id() === $id) {
        $fresh = get_user_by_id($id);
        if ($fresh) {
            $_SESSION['username'] = $fresh['username'];
            $_SESSION['role']     = $fresh['role'];
        }
    }

    json_ok([], 'User updated successfully.');
}

if ($method === 'DELETE') {
    require_admin();

    if ($id < 1) json_error('User ID is required.');

    if ($id === auth_user_id()) {
        json_error('You cannot delete your own account.');
    }

    delete_user($id);
    json_ok([], 'User deleted successfully.');
}

json_error('Method not allowed.', 405);