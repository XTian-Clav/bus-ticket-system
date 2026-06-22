<?php
declare(strict_types=1);

// app/core/core_view.php
// Page-level guards + small helpers for server-rendered views.
// core_auth.php guards (require_login/require_admin) reply with JSON —
// correct for API endpoints, wrong for pages. These redirect instead.

require_once __DIR__ . '/core_auth.php';

function require_login_page(): void
{
    if (!is_logged_in()) {
        redirect(url('/login.php'));
    }
}

function require_user_page(): void
{
    require_login_page();

    if (is_admin()) {
        redirect(url('/views/admin/admin_dashboard.php'));
    }
}

function require_admin_page(): void
{
    require_login_page();

    if (!is_admin()) {
        redirect(url('/views/user/dashboard.php'));
    }
}

function require_guest_page(): void
{
    if (!is_logged_in()) {
        return;
    }

    redirect(url(is_admin() ? '/views/admin/admin_dashboard.php' : '/views/user/dashboard.php'));
}

function home_path(): string
{
    return url(is_admin() ? '/views/admin/admin_dashboard.php' : '/views/user/dashboard.php');
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function is_active(string $path): string
{
    $current = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    return $current === $path ? 'true' : 'false';
}
