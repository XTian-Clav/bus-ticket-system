<?php
declare(strict_types=1);

// app/public/avatar.php
//
// POST   /avatar.php?id=1   → admin or own account: upload/replace profile photo
//
// multipart/form-data only — field name "avatar".

require_once __DIR__ . '/../core/core_auth.php';
require_once __DIR__ . '/../core/core_response.php';
require_once __DIR__ . '/../actions/action_users.php';

start_session();
require_login();

$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($method !== 'POST') {
    json_error('Method not allowed.', 405);
}

if ($id < 1) {
    json_error('User ID is required.');
}

if (!is_admin() && auth_user_id() !== $id) {
    json_error('Forbidden.', 403);
}

$filename = update_user_avatar($id, $_FILES['avatar'] ?? []);

if (auth_user_id() === $id) {
    update_session_avatar($filename);
}

$cfg = require __DIR__ . '/../config/config_upload.php';

json_ok(['avatar' => $filename, 'avatar_url' => $cfg['avatar_url'] . '/' . $filename], 'Profile photo updated.');
