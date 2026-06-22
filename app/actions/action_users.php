<?php
declare(strict_types=1);

// app/actions/action_users.php

require_once __DIR__ . '/../core/core_crud.php';
require_once __DIR__ . '/../core/core_response.php';
require_once __DIR__ . '/../queries/query_users.php';

// ── VALIDATION ────────────────────────────────────────────────────────────────

function validate_new_user_fields(array $data): void
{
    $required = ['username', 'fullname', 'email', 'contact', 'password', 'confirm_password'];
    foreach ($required as $field) {
        if (empty(trim($data[$field] ?? ''))) {
            json_error("The {$field} field is required.");
        }
    }

    $username = trim($data['username']);
    if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
        json_error('Username must be 3–30 characters: letters, numbers, or underscores only.');
    }

    $email = strtolower(trim($data['email']));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        json_error('Invalid email address.');
    }

    if (!is_valid_ph_contact($data['contact'])) {
        json_error('Contact number must be 11 digits and start with 09 (e.g. 09123456789).');
    }

    if (!is_strong_password($data['password'])) {
        json_error('Password must be at least 8 characters and include one uppercase letter, one number, and one special character.');
    }

    if (($data['confirm_password'] ?? '') !== $data['password']) {
        json_error('Passwords do not match.');
    }

    if (get_user_by_email($email)) {
        json_error('That email address is already registered.');
    }

    if (get_user_by_username($username)) {
        json_error('That username is already taken.');
    }
}

// ── REGISTER ──────────────────────────────────────────────────────────────────

function register_user(array $data): int
{
    validate_new_user_fields($data);

    return db_insert('users', [
        'username' => trim($data['username']),
        'fullname' => trim($data['fullname']),
        'email'    => strtolower(trim($data['email'])),
        'contact'  => trim($data['contact']),
        'password' => password_hash($data['password'], PASSWORD_BCRYPT),
        'role'     => 'user',
    ]);
}

// ── LOGIN ─────────────────────────────────────────────────────────────────────

function login_user(string $usernameOrEmail, string $password): array|false
{
    $usernameOrEmail = trim($usernameOrEmail);
    if ($usernameOrEmail === '' || $password === '') {
        json_error('Username/email and password are required.');
    }

    $user = filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)
        ? get_user_by_email(strtolower($usernameOrEmail))
        : get_user_by_username($usernameOrEmail);

    if (!$user || !password_verify($password, $user['password'])) {
        json_error('Invalid credentials.', 401);
    }

    unset($user['password']);
    return $user;
}

// ── UPDATE ────────────────────────────────────────────────────────────────────

function update_user(int $id, array $data, bool $is_admin = false): bool
{
    $user = get_user_by_id($id);
    if (!$user) {
        json_error('User not found.', 404);
    }

    $update = [];

    foreach (['fullname', 'contact', 'username', 'email'] as $field) {
        if (isset($data[$field]) && trim((string) ($data[$field] ?? '')) !== '') {
            $update[$field] = trim($data[$field]);
        }
    }

    // Admin-only
    if ($is_admin && isset($data['role'])) {
        $role = trim($data['role']);
        if (!in_array($role, ['user', 'admin'], true)) {
            json_error('Role must be either "user" or "admin".');
        }
        $update['role'] = $role;
    }

    if (isset($update['username'])) {
        if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $update['username'])) {
            json_error('Username must be 3–30 characters: letters, numbers, or underscores only.');
        }
        $existing = get_user_by_username($update['username']);
        if ($existing && (int) $existing['id'] !== $id) {
            json_error('That username is already taken.');
        }
    }

    if (isset($update['email'])) {
        $update['email'] = strtolower($update['email']);
        if (!filter_var($update['email'], FILTER_VALIDATE_EMAIL)) {
            json_error('Invalid email address.');
        }
        $existing = get_user_by_email($update['email']);
        if ($existing && (int) $existing['id'] !== $id) {
            json_error('That email address is already registered.');
        }
    }

    if (isset($update['contact']) && !is_valid_ph_contact($update['contact'])) {
        json_error('Contact number must be 11 digits and start with 09 (e.g. 09123456789).');
    }

    if (empty($update)) {
        json_error('No valid fields provided to update.');
    }

    return db_update('users', $id, $update);
}

// ── DELETE ────────────────────────────────────────────────────────────────────

function delete_user(int $id): bool
{
    $user = get_user_by_id($id);
    if (!$user) {
        json_error('User not found.', 404);
    }

    return db_delete('users', $id);
}

// ── ADMIN CREATE USER ──────────────────────────────────────────────────────────

function admin_create_user(array $data): int
{
    $required = ['username', 'fullname', 'email', 'contact', 'password'];
    foreach ($required as $field) {
        if (empty(trim($data[$field] ?? ''))) {
            json_error("The {$field} field is required.");
        }
    }

    $username = trim($data['username']);
    if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
        json_error('Username must be 3–30 characters: letters, numbers, or underscores only.');
    }

    $email = strtolower(trim($data['email']));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        json_error('Invalid email address.');
    }

    if (!is_valid_ph_contact($data['contact'])) {
        json_error('Contact number must be 11 digits and start with 09 (e.g. 09123456789).');
    }

    if (!is_strong_password($data['password'])) {
        json_error('Password must be at least 8 characters and include one uppercase letter, one number, and one special character.');
    }

    if (get_user_by_email($email)) {
        json_error('That email address is already registered.');
    }

    if (get_user_by_username($username)) {
        json_error('That username is already taken.');
    }

    $role = $data['role'] ?? 'user';
    if (!in_array($role, ['user', 'admin'], true)) {
        json_error('Role must be either "user" or "admin".');
    }

    return db_insert('users', [
        'username' => $username,
        'fullname' => trim($data['fullname']),
        'email'    => $email,
        'contact'  => trim($data['contact']),
        'password' => password_hash($data['password'], PASSWORD_BCRYPT),
        'role'     => $role,
    ]);
}

// ── PRIVATE HELPERS ───────────────────────────────────────────────────────────

function is_strong_password(string $password): bool
{
    if (strlen($password) < 8)           return false;
    if (!preg_match('/[A-Z]/', $password)) return false;
    if (!preg_match('/[0-9]/', $password)) return false;
    if (!preg_match('/[\W_]/', $password)) return false;
    return true;
}

function is_valid_ph_contact(string $contact): bool
{
    // Philippine mobile number (e.g. 09123456789).
    return (bool) preg_match('/^09\d{9}$/', trim($contact));
}