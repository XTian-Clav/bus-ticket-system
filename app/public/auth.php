<?php
declare(strict_types=1);

// app/public/auth.php
// POST /app/public/auth.php          → login
// POST /app/public/auth.php?logout=1 → logout

require_once __DIR__ . '/../core/core_auth.php';
require_once __DIR__ . '/../core/core_response.php';
require_once __DIR__ . '/../actions/action_users.php';

start_session();

$method = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['logout'])) {
    clear_auth_session();
    json_ok([], 'Logged out successfully.');
}

if ($method !== 'POST') {
    json_error('Method not allowed.', 405);
}

$input = json_decode(file_get_contents('php://input'), true) ?? [];

$user = login_user(
    $input['username_or_email']    ?? '',
    $input['password'] ?? ''
);

set_auth_session($user);

json_ok([
    'user_id'  => $user['id'],
    'username' => $user['username'],
    'role'     => $user['role'],
], 'Login successful.');
