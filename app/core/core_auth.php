<?php
declare(strict_types=1);

// app/core/core_auth.php
// Session bootstrap + RBAC guard functions.

require_once __DIR__ . '/core_response.php';

// ── SESSION BOOTSTRAP ─────────────────────────────────────────────────────────

const SESSION_TIMEOUT = 60 * 15; // 60 seconds * 15 = 15 minutes

function start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params(['httponly' => true]);
        session_start();
    }

    if (!empty($_SESSION['user_id']) && !empty($_SESSION['last_activity'])
        && time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
        session_unset();
        session_destroy();
        return;
    }

    $_SESSION['last_activity'] = time();
}

// ── SESSION WRITERS ───────────────────────────────────────────────────────────

function set_auth_session(array $user): void
{
    start_session();
    session_regenerate_id(true);    // prevent session fixation

    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];
}

function clear_auth_session(): void
{
    start_session();
    session_unset();
    session_destroy();
}

// ── SESSION READERS ───────────────────────────────────────────────────────────

function auth_user_id(): int
{
    return (int) ($_SESSION['user_id'] ?? 0);
}

function auth_role(): string
{
    return $_SESSION['role'] ?? '';
}

function is_logged_in(): bool
{
    start_session();
    return !empty($_SESSION['user_id']);
}

function is_admin(): bool
{
    return is_logged_in() && auth_role() === 'admin';
}

// ── RBAC GUARDS ───────────────────────────────────────────────────────────────

function require_login(): void
{
    if (!is_logged_in()) {
        json_error('Unauthorized. Please log in.', 401);
    }
}

function require_admin(): void
{
    require_login();

    if (!is_admin()) {
        json_error('Forbidden. Admin access required.', 403);
    }
}
